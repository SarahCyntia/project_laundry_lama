<?php
// database/seeders/LaundrySeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\Service;
use App\Models\Order;

class LaundrySeeder extends Seeder
{
    public function run()
    {
        // Create sample services
        $services = [
            [
                'nama' => 'Cuci Kering',
                'deskripsi' => 'Layanan cuci dan kering pakaian',
                'harga_per_kg' => 5000,
                'durasi_jam' => 24
            ],
            [
                'nama' => 'Cuci Setrika',
                'deskripsi' => 'Layanan cuci, kering, dan setrika',
                'harga_per_kg' => 7000,
                'durasi_jam' => 48
            ],
            [
                'nama' => 'Dry Clean',
                'deskripsi' => 'Layanan dry cleaning untuk pakaian khusus',
                'harga_per_kg' => 15000,
                'durasi_jam' => 72
            ]
        ];

        foreach ($services as $service) {
            Service::create($service);
        }

        // Create sample customers
        $customers = [
            [
                'nama' => 'Budi Santoso',
                'no_hp' => '081234567890',
                'alamat' => 'Jl. Merdeka No. 123, Surabaya'
            ],
            [
                'nama' => 'Siti Aminah',
                'no_hp' => '081234567891',
                'alamat' => 'Jl. Pahlawan No. 456, Surabaya'
            ],
            [
                'nama' => 'Ahmad Rahman',
                'no_hp' => '081234567892',
                'alamat' => 'Jl. Diponegoro No. 789, Surabaya'
            ]
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
}