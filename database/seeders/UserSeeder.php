<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@tokokita.lab',
                'phone' => '081234567890',
                'address' => 'Jl. Merdeka No. 45, Jakarta Pusat',
            ],
            [
                'name' => 'Siti Rahmawati',
                'email' => 'siti.rahmawati@tokokita.lab',
                'phone' => '081298765432',
                'address' => 'Jl. Diponegoro No. 12, Bandung',
            ],
            [
                'name' => 'Agus Pratama',
                'email' => 'agus.pratama@tokokita.lab',
                'phone' => '081311223344',
                'address' => 'Jl. Sudirman No. 88, Surabaya',
            ],
            [
                'name' => 'Dewi Anggraini',
                'email' => 'dewi.anggraini@tokokita.lab',
                'phone' => '081377889900',
                'address' => 'Jl. Gajah Mada No. 15, Yogyakarta',
            ],
            [
                'name' => 'Rizky Kurniawan',
                'email' => 'rizky.kurniawan@tokokita.lab',
                'phone' => '081455667788',
                'address' => 'Jl. Pahlawan No. 23, Semarang',
            ],
            [
                'name' => 'Linda Wulandari',
                'email' => 'linda.wulandari@tokokita.lab',
                'phone' => '081522334455',
                'address' => 'Jl. Ahmad Yani No. 90, Medan',
            ],
            [
                'name' => 'Fajar Hidayat',
                'email' => 'fajar.hidayat@tokokita.lab',
                'phone' => '081699887766',
                'address' => 'Jl. Pemuda No. 7, Makassar',
            ],
            [
                'name' => 'Maya Sari',
                'email' => 'maya.sari@tokokita.lab',
                'phone' => '081733445566',
                'address' => 'Jl. Gatot Subroto No. 34, Denpasar',
            ],
            [
                'name' => 'Hendra Wijaya',
                'email' => 'hendra.wijaya@tokokita.lab',
                'phone' => '081877665544',
                'address' => 'Jl. R.E. Martadinata No. 56, Malang',
            ],
            [
                'name' => 'Eka Putri',
                'email' => 'eka.putri@tokokita.lab',
                'phone' => '081911224466',
                'address' => 'Jl. Imam Bonjol No. 18, Palembang',
            ],
            [
                'name' => 'Dimas Setiawan',
                'email' => 'dimas.setiawan@tokokita.lab',
                'phone' => '082133557799',
                'address' => 'Jl. Veteran No. 42, Surakarta',
            ],
            [
                'name' => 'Anisa Lestari',
                'email' => 'anisa.lestari@tokokita.lab',
                'phone' => '082266880011',
                'address' => 'Jl. Basuki Rahmat No. 63, Balikpapan',
            ],
            [
                'name' => 'Rian Saputra',
                'email' => 'rian.saputra@tokokita.lab',
                'phone' => '082344556677',
                'address' => 'Jl. Teuku Umar No. 29, Banda Aceh',
            ],
            [
                'name' => 'Nadia Permata',
                'email' => 'nadia.permata@tokokita.lab',
                'phone' => '082488991122',
                'address' => 'Jl. Sisingamangaraja No. 81, Padang',
            ],
            [
                'name' => 'Ilham Ramadhan',
                'email' => 'ilham.ramadhan@tokokita.lab',
                'phone' => '082512345678',
                'address' => 'Jl. Hayam Wuruk No. 10, Banjarmasin',
            ],
        ];

        $defaultPassword = Hash::make('password123');

        foreach ($users as $data) {
            User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'phone' => $data['phone'],
                    'address' => $data['address'],
                    'password' => $defaultPassword,
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}
