<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

class ExportBackupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tokokita:export-backup
                            {--filename= : Custom filename for the .sql export}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export database to storage/backup/ in .sql format for manual S3 synchronization';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $backupDir = storage_path('backup');

        if (! File::isDirectory($backupDir)) {
            File::makeDirectory($backupDir, 0755, true, true);
        }

        // Determine and sanitize filename (prevent path traversal)
        $rawFilename = $this->option('filename');
        if ($rawFilename) {
            $cleaned = preg_replace('/[^a-zA-Z0-9_\-\.]/', '', basename($rawFilename));
            if (! str_ends_with($cleaned, '.sql')) {
                $cleaned .= '.sql';
            }
            $filename = $cleaned;
        } else {
            $filename = 'tokokita_backup_' . date('Ymd_His') . '.sql';
        }

        $outputPath = $backupDir . DIRECTORY_SEPARATOR . $filename;
        $connection = config('database.default');

        $this->info("Memulai proses backup database TokoKita [driver: {$connection}]...");

        try {
            if ($connection === 'mysql') {
                $this->exportMysql($outputPath);
            } elseif ($connection === 'sqlite') {
                $this->exportSqlite($outputPath);
            } else {
                $this->exportGenericSql($outputPath);
            }

            if (File::exists($outputPath) && File::size($outputPath) >= 0) {
                $fileSize = number_format(File::size($outputPath) / 1024, 2) . ' KB';
                $this->components->info("Backup database berhasil dibuat: {$outputPath} ({$fileSize})");
                $this->line("File siap disinkronkan ke S3 bucket lab infrastruktur.");
                return Command::SUCCESS;
            }

            $this->components->error("Gagal membuat file backup: File tidak ditemukan atau kosong.");
            return Command::FAILURE;
        } catch (\Throwable $e) {
            $this->components->error("Terjadi kesalahan saat mengekspor database: " . $e->getMessage());
            return Command::FAILURE;
        }
    }

    /**
     * Export MySQL database using mysqldump safely.
     */
    protected function exportMysql(string $outputPath): void
    {
        $host = (string) config('database.connections.mysql.host', '127.0.0.1');
        $port = (string) config('database.connections.mysql.port', '3306');
        $database = (string) config('database.connections.mysql.database', 'tokokita');
        $username = (string) config('database.connections.mysql.username', 'root');
        $password = (string) config('database.connections.mysql.password', '');

        $dumpBinary = $this->resolveMysqldumpBinary();

        $this->comment("Menjalankan mysqldump binary: {$dumpBinary}");

        // Build command using Process with arguments array to avoid shell injection
        $command = [
            $dumpBinary,
            '--host=' . $host,
            '--port=' . $port,
            '--user=' . $username,
            '--routines',
            '--triggers',
            '--single-transaction',
            '--quick',
            $database,
        ];

        $env = [];
        if ($password !== '') {
            $env['MYSQL_PWD'] = $password;
        }

        $process = new Process($command, null, $env, null, 300);
        $process->run();

        if (! $process->isSuccessful()) {
            // If mysqldump failed (e.g. server unreachable or wrong credentials), fallback to PDO generic dump
            $this->warn("mysqldump menghasilkan pesan: " . trim($process->getErrorOutput()));
            $this->info("Beralih ke export SQL berbasis koneksi PDO...");
            $this->exportGenericSql($outputPath);
            return;
        }

        File::put($outputPath, $process->getOutput());
    }

    /**
     * Locate mysqldump binary on system.
     */
    protected function resolveMysqldumpBinary(): string
    {
        $configured = env('MYSQLDUMP_PATH');
        if ($configured && File::exists($configured)) {
            return $configured;
        }

        $commonPaths = [
            'C:\\Program Files\\MySQL\\MySQL Server 8.0\\bin\\mysqldump.exe',
            'C:\\Program Files\\MySQL\\MySQL Server 8.4\\bin\\mysqldump.exe',
            'C:\\Program Files\\MySQL\\MySQL Workbench 8.0\\mysqldump.exe',
            'C:\\xampp\\mysql\\bin\\mysqldump.exe',
            'C:\\laragon\\bin\\mysql\\mysql-8.0.30-winx64\\bin\\mysqldump.exe',
            '/usr/bin/mysqldump',
            '/usr/local/bin/mysqldump',
        ];

        foreach ($commonPaths as $path) {
            if (File::exists($path)) {
                return $path;
            }
        }

        return 'mysqldump';
    }

    /**
     * Export SQLite database to .sql format.
     */
    protected function exportSqlite(string $outputPath): void
    {
        $databasePath = config('database.connections.sqlite.database');

        if ($databasePath !== ':memory:' && ! File::exists($databasePath)) {
            throw new \RuntimeException("File SQLite database tidak ditemukan di {$databasePath}");
        }

        $tables = DB::connection('sqlite')
            ->select("SELECT name, sql FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");

        $sqlContent = "-- TokoKita SQLite Export\n";
        $sqlContent .= "-- Generated at: " . date('Y-m-d H:i:s') . "\n";
        $sqlContent .= "-- Target S3 Sync Lab Infrastruktur\n\n";
        $sqlContent .= "PRAGMA foreign_keys = OFF;\n\n";

        foreach ($tables as $table) {
            $tableName = $table->name;
            $tableSql = $table->sql;

            $sqlContent .= "DROP TABLE IF EXISTS \"{$tableName}\";\n";
            $sqlContent .= "{$tableSql};\n\n";

            $rows = DB::connection('sqlite')->table($tableName)->get();
            if ($rows->count() > 0) {
                foreach ($rows as $row) {
                    $rowArray = (array) $row;
                    $columns = array_keys($rowArray);
                    $escapedCols = array_map(fn ($c) => "\"{$c}\"", $columns);

                    $values = array_map(function ($val) {
                        if ($val === null) {
                            return 'NULL';
                        }
                        if (is_numeric($val) && ! is_string($val)) {
                            return (string) $val;
                        }
                        return "'" . str_replace("'", "''", (string) $val) . "'";
                    }, array_values($rowArray));

                    $sqlContent .= "INSERT INTO \"{$tableName}\" (" . implode(', ', $escapedCols) . ") VALUES (" . implode(', ', $values) . ");\n";
                }
                $sqlContent .= "\n";
            }
        }

        $sqlContent .= "PRAGMA foreign_keys = ON;\n";

        File::put($outputPath, $sqlContent);
    }

    /**
     * Fallback generic SQL exporter via PDO for any relational connection.
     */
    protected function exportGenericSql(string $outputPath): void
    {
        $tables = DB::connection()->getDoctrineSchemaManager()->listTableNames();

        $sqlContent = "-- TokoKita Database Export (Generic SQL)\n";
        $sqlContent .= "-- Generated at: " . date('Y-m-d H:i:s') . "\n\n";

        foreach ($tables as $table) {
            $rows = DB::table($table)->get();
            $sqlContent .= "-- Table: {$table}\n";

            foreach ($rows as $row) {
                $rowArray = (array) $row;
                $columns = array_keys($rowArray);
                $escapedCols = array_map(fn ($c) => "`{$c}`", $columns);

                $values = array_map(function ($val) {
                    if ($val === null) {
                        return 'NULL';
                    }
                    if (is_numeric($val) && ! is_string($val)) {
                        return (string) $val;
                    }
                    return "'" . addslashes((string) $val) . "'";
                }, array_values($rowArray));

                $sqlContent .= "INSERT INTO `{$table}` (" . implode(', ', $escapedCols) . ") VALUES (" . implode(', ', $values) . ");\n";
            }
            $sqlContent .= "\n";
        }

        File::put($outputPath, $sqlContent);
    }
}
