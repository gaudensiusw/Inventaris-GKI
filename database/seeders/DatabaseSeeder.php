<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Room;
use App\Models\Item;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Default User
        User::factory()->create([
            'name' => 'Admin GKI',
            'email' => 'admin@gkidelima.org',
            'password' => bcrypt('password'),
        ]);

        // Categories
        $catElektronik = Category::create(['name' => 'Elektronik', 'icon' => 'monitor']);
        $catMebel = Category::create(['name' => 'Mebel', 'icon' => 'armchair']);
        $catMusik = Category::create(['name' => 'Alat Musik', 'icon' => 'music']);
        $catKendaraan = Category::create(['name' => 'Kendaraan', 'icon' => 'car']);

        // Rooms
        $roomUtama = Room::create(['name' => 'Ruang Ibadah Utama']);
        $roomSerbaguna = Room::create(['name' => 'Aula Serbaguna']);
        $roomMultimedia = Room::create(['name' => 'Ruang Multimedia']);

        // Dummy Items matching screenshot
        Item::create([
            'item_id' => 'INV-001',
            'name' => 'Proyektor Epson EB-X41',
            'category_id' => $catElektronik->id,
            'room_id' => $roomUtama->id,
            'total_qty' => 2,
            'qty_baik' => 2,
            'qty_tersedia' => 2,
            'price' => 8500000,
        ]);

        Item::create([
            'item_id' => 'INV-002',
            'name' => 'Kursi Lipat Chitose',
            'category_id' => $catMebel->id,
            'room_id' => $roomSerbaguna->id,
            'total_qty' => 100,
            'qty_baik' => 85,
            'qty_rusak_ringan' => 5,
            'qty_rusak_berat' => 10,
            'qty_tersedia' => 85,
            'qty_dipinjam' => 5,
            'qty_hilang' => 10,
            'price' => 750000,
        ]);
    }
}
