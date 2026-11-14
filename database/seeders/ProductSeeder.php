<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Managed Switch 24-Port Gigabit L2+',
                'category' => 'Networking',
                'price' => 2850000,
                'stock' => 12,
                'description' => 'Switch managed 24 port gigabit dengan fitur VLAN tagging 802.1Q, QoS, LACP trunking, dan port mirroring untuk monitoring traffic lab.',
                'image' => 'managed-switch-24p.jpg',
            ],
            [
                'name' => 'Hardware Firewall Appliance 4x GbE',
                'category' => 'Networking',
                'price' => 5400000,
                'stock' => 8,
                'description' => 'Appliance firewall mini-PC hemat daya dengan 4 port Intel Gigabit NIC, siap untuk pfSense / OPNsense / Suricata IDS/IPS.',
                'image' => 'firewall-appliance-4gbe.jpg',
            ],
            [
                'name' => 'Dual-Band AC1750 Enterprise AP',
                'category' => 'Networking',
                'price' => 1250000,
                'stock' => 25,
                'description' => 'Access point nirkabel dual-band gigabit dengan dukungan PoE, multi-SSID, isolasi client, dan enkripsi WPA3 Enterprise.',
                'image' => 'ap-enterprise-ac1750.jpg',
            ],
            [
                'name' => 'USB Wi-Fi Adapter Dual-Band Monitor Mode',
                'category' => 'Security Tools',
                'price' => 380000,
                'stock' => 40,
                'description' => 'Adaptor WiFi USB high-gain dengan chipset kompatibel monitor mode dan packet injection untuk audit keamanan nirkabel.',
                'image' => 'wifi-adapter-monitor-mode.jpg',
            ],
            [
                'name' => 'Hardware Security Key U2F FIDO2 NFC',
                'category' => 'Security Tools',
                'price' => 750000,
                'stock' => 35,
                'description' => 'Kunci keamanan hardware FIDO2 / WebAuthn anti-phishing dengan antarmuka USB-C dan NFC untuk multi-factor authentication aman.',
                'image' => 'fido2-security-key-nfc.jpg',
            ],
            [
                'name' => 'Cat6 UTP Cable 305 Meter Pure Copper',
                'category' => 'Networking',
                'price' => 1450000,
                'stock' => 15,
                'description' => 'Kabel LAN Cat6 24AWG pure bare copper bandwidth 250MHz untuk pengkabelan terstruktur rack server dan workstation.',
                'image' => 'cat6-utp-cable-305m.jpg',
            ],
            [
                'name' => 'Mini PC Lab Server Core i7 32GB RAM',
                'category' => 'Hardware & Server',
                'price' => 8900000,
                'stock' => 6,
                'description' => 'Host virtualisasi lab praktikum dengan Intel Core i7 12-core, RAM 32GB DDR4, Dual 2.5GbE LAN, dan 1TB NVMe SSD.',
                'image' => 'mini-pc-server-i7.jpg',
            ],
            [
                'name' => 'Rackmount Server Chassis 2U 8-Bay Hot-Swap',
                'category' => 'Hardware & Server',
                'price' => 4200000,
                'stock' => 5,
                'description' => 'Chassis rackmount 2U dengan 8 drive bay hot-swap SATA/SAS, redundan fan, dan kompatibilitas motherboard ATX/E-ATX.',
                'image' => 'server-chassis-2u-8bay.jpg',
            ],
            [
                'name' => 'Network TAP 10/100/1000M Copper',
                'category' => 'Security Tools',
                'price' => 3100000,
                'stock' => 10,
                'description' => 'Hardware Network Test Access Point pasif non-intrusif untuk Wireshark capturing traffic in-line tanpa packet dropping.',
                'image' => 'network-tap-copper.jpg',
            ],
            [
                'name' => 'Smart Managed PDU 8-Outlet Remote Control',
                'category' => 'Hardware & Server',
                'price' => 2600000,
                'stock' => 9,
                'description' => 'Power Distribution Unit rack 19 inci dengan manajemen web GUI/SNMP dan pengukuran daya individual per outlet.',
                'image' => 'smart-pdu-8outlet.jpg',
            ],
            [
                'name' => 'RJ45 Crimping Tool Set + Cable Tester',
                'category' => 'Aksesoris Komputer',
                'price' => 275000,
                'stock' => 50,
                'description' => 'Kit tang crimping multifungsi RJ45/RJ11 dilengkapi tester kabel LAN, stripper, dan konektor RJ45 modular Cat6.',
                'image' => 'rj45-crimping-toolset.jpg',
            ],
            [
                'name' => 'USB to Serial RS232 Console Cable FTDI',
                'category' => 'Aksesoris Komputer',
                'price' => 165000,
                'stock' => 60,
                'description' => 'Kabel konsol Cisco/Mikrotik RJ45 ke USB dengan chip FTDI original untuk konfigurasi CLI router dan switch.',
                'image' => 'usb-serial-console-cable.jpg',
            ],
            [
                'name' => 'NVMe M.2 SSD 1TB PCIe 4.0 High Endurance',
                'category' => 'Hardware & Server',
                'price' => 1350000,
                'stock' => 30,
                'description' => 'Solid state drive NVMe transfer speed up to 7000MB/s dengan enkripsi AES 256-bit berbasis hardware.',
                'image' => 'nvme-m2-ssd-1tb.jpg',
            ],
            [
                'name' => 'Industrial Ethernet DIN-Rail Switch 8-Port',
                'category' => 'Networking',
                'price' => 1890000,
                'stock' => 14,
                'description' => 'Switch ethernet industri berstandar IP40 dengan proteksi surge 6KV dan rentang suhu operasional -40°C hingga 75°C.',
                'image' => 'industrial-switch-dinrail-8p.jpg',
            ],
            [
                'name' => 'Laptop Lab Workstation 15.6" Ryzen 7 16GB',
                'category' => 'Laptop & Komputer',
                'price' => 11200000,
                'stock' => 10,
                'description' => 'Laptop performa tinggi untuk analisis data jaringan dan lab simulasi, AMD Ryzen 7, 16GB DDR5, 512GB SSD, Wi-Fi 6E.',
                'image' => 'laptop-lab-workstation-15.jpg',
            ],
            [
                'name' => 'Monitor IPS 24" 100Hz sRGB 99%',
                'category' => 'Laptop & Komputer',
                'price' => 1450000,
                'stock' => 20,
                'description' => 'Monitor FHD bezel tipis dengan input HDMI & DisplayPort, mode Low Blue Light, dan flicker-free untuk kenyamanan praktikum.',
                'image' => 'monitor-ips-24inch.jpg',
            ],
            [
                'name' => 'Mechanical Keyboard TKL Hot-Swappable',
                'category' => 'Aksesoris Komputer',
                'price' => 620000,
                'stock' => 25,
                'description' => 'Keyboard mekanikal tata letak tenkeyless, switch tactile red/brown, kabel USB-C braided lepas pasang, tahan banting.',
                'image' => 'mechanical-keyboard-tkl.jpg',
            ],
            [
                'name' => 'Wireless Optical Mouse Ergonomic 2.4GHz',
                'category' => 'Aksesoris Komputer',
                'price' => 185000,
                'stock' => 45,
                'description' => 'Mouse nirkabel ergonomis sensor optik presisi 1600 DPI hemat daya dengan slot nano receiver internal.',
                'image' => 'wireless-mouse-ergonomic.jpg',
            ],
            [
                'name' => 'External Encrypted HDD 2TB Hardware Keypad',
                'category' => 'Security Tools',
                'price' => 2100000,
                'stock' => 11,
                'description' => 'Hard disk eksternal dengan papan tombol alfanumerik fisik dan enkripsi hardware AES-XTS 256-bit standar FIPS 140-2 Level 3.',
                'image' => 'external-encrypted-hdd-2tb.jpg',
            ],
            [
                'name' => 'SFP+ 10G Optical Transceiver 850nm 300m',
                'category' => 'Networking',
                'price' => 450000,
                'stock' => 30,
                'description' => 'Modul optik 10 Gigabit multi-mode fiber LC connector kompatibel dengan switch Cisco/MikroTik/Ubiquiti.',
                'image' => 'sfp-10g-transceiver-850nm.jpg',
            ],
        ];

        foreach ($products as $item) {
            Product::updateOrCreate(
                ['slug' => Str::slug($item['name'])],
                [
                    'name' => $item['name'],
                    'category' => $item['category'],
                    'price' => $item['price'],
                    'stock' => $item['stock'],
                    'description' => $item['description'],
                    'image' => $item['image'],
                    'is_active' => true,
                ]
            );
        }
    }
}
