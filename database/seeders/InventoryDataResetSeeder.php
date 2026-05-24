<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Room;
use App\Models\Item;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class InventoryDataResetSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Disable Foreign Key Constraints
        Schema::disableForeignKeyConstraints();

        // 2. Truncate all related tables
        DB::table('items')->truncate();
        DB::table('rooms')->truncate();
        DB::table('orders')->truncate();
        DB::table('rents')->truncate();
        DB::table('repairs')->truncate();
        DB::table('stock_opname_details')->truncate();
        DB::table('stock_opname_headers')->truncate();
        DB::table('t_mutasi')->truncate();
        DB::table('t_penghapusan')->truncate();
        DB::table('t_so_detail')->truncate();
        DB::table('t_so_header')->truncate();
        DB::table('t_stok_log')->truncate();

        // Re-enable Foreign Key Constraints
        Schema::enableForeignKeyConstraints();

        // 3. Ensure Categories Exist (Update / Add custom categories to avoid "Lain-lain")
        $categoriesMap = [
            'Peralatan Perjamuan'   => 'glass-water',
            'Alat Musik'            => 'music',
            'Elektronik & AC'       => 'cpu',
            'Furniture'             => 'armchair',
            'Peralatan Dapur'       => 'utensils',
            'Alat Multimedia'       => 'video',
            'Buku & Alkitab'        => 'book-open',
            'Peralatan Kebersihan & K3' => 'trash-2',
            'Dekorasi & Alat Kantor'=> 'printer',
            'Lain-lain'             => 'package',
        ];

        $categories = [];
        foreach ($categoriesMap as $name => $icon) {
            $categories[$name] = Category::updateOrCreate(
                ['name' => $name],
                ['description' => 'Kategori ' . $name]
            );
        }

        // 4. Raw Dataset of Rooms & Items
        $dataset = [
            'Ruang konsistori' => [
                ['name' => 'Nampan Stainless Bundar', 'qty' => 56, 'cat' => 'Peralatan Perjamuan'],
                ['name' => 'Cawan Stainless Kecil', 'qty' => 2, 'cat' => 'Peralatan Perjamuan'],
                ['name' => 'Cawan Stainless Besar', 'qty' => 1, 'cat' => 'Peralatan Perjamuan'],
                ['name' => 'Teko Stainless Kecil', 'qty' => 1, 'cat' => 'Peralatan Perjamuan'],
                ['name' => 'Teko Stainless Besar', 'qty' => 3, 'cat' => 'Peralatan Perjamuan'],
                ['name' => 'Nampan Stainless', 'qty' => 2, 'cat' => 'Peralatan Perjamuan'],
                ['name' => 'Teko Stainless Perjamuan', 'qty' => 3, 'cat' => 'Peralatan Perjamuan'],
                ['name' => 'Gelas Perjamuan', 'qty' => 0, 'cat' => 'Peralatan Perjamuan', 'notes' => 'Jumlah gelas perjamuan mengikuti data Excel'],
                ['name' => 'Piring Stainless Kecil', 'qty' => 3, 'cat' => 'Peralatan Perjamuan'],
                ['name' => 'Lemari Kaca Alat Perjamuan', 'qty' => 1, 'cat' => 'Furniture'],
                ['name' => 'Lemari Kayu Perjamuan 3 Pintu', 'qty' => 1, 'cat' => 'Furniture'],
                ['name' => 'Dispenser', 'qty' => 1, 'cat' => 'Elektronik & AC'],
                ['name' => 'Alkitab TB 2 Besar', 'qty' => 2, 'cat' => 'Buku & Alkitab'],
                ['name' => 'Alkitab TB 2 Sedang', 'qty' => 10, 'cat' => 'Buku & Alkitab'],
                ['name' => 'Alkitab TB 2 Kecil', 'qty' => 25, 'cat' => 'Buku & Alkitab'],
                ['name' => 'Kantong Persembahan Merah', 'qty' => 6, 'qty_rusak_ringan' => 2, 'cat' => 'Peralatan Perjamuan'],
                ['name' => 'Mangkok Abu Kecil', 'qty' => 4, 'cat' => 'Peralatan Dapur'],
                ['name' => 'Mangkok Abu Gede', 'qty' => 1, 'cat' => 'Peralatan Dapur'],
                ['name' => 'Tong Sampah', 'qty' => 2, 'cat' => 'Peralatan Kebersihan & K3'],
                ['name' => 'AC AUX', 'qty' => 1, 'cat' => 'Elektronik & AC'],
                ['name' => 'Foto Presiden', 'qty' => 1, 'cat' => 'Dekorasi & Alat Kantor'],
                ['name' => 'Foto Wakil Presiden', 'qty' => 1, 'cat' => 'Dekorasi & Alat Kantor'],
                ['name' => 'Bangku', 'qty' => 12, 'cat' => 'Furniture'],
                ['name' => 'Papan Tulis', 'qty' => 2, 'cat' => 'Dekorasi & Alat Kantor'],
                ['name' => 'Papan Mading', 'qty' => 1, 'cat' => 'Dekorasi & Alat Kantor'],
                ['name' => 'Tiang Bendera', 'qty' => 1, 'cat' => 'Dekorasi & Alat Kantor'],
                ['name' => 'APAR', 'qty' => 1, 'cat' => 'Peralatan Kebersihan & K3'],
                ['name' => 'Meja Kecil', 'qty' => 1, 'cat' => 'Furniture'],
                ['name' => 'Rak Dinding', 'qty' => 1, 'cat' => 'Furniture'],
                ['name' => 'Kaca Dinding', 'qty' => 2, 'cat' => 'Dekorasi & Alat Kantor'],
                ['name' => 'Tirai', 'qty' => 2, 'cat' => 'Dekorasi & Alat Kantor'],
                ['name' => 'CCTV', 'qty' => 1, 'cat' => 'Elektronik & AC'],
            ],
            'Ruang ibadah lantai 1' => [
                ['name' => 'Genset', 'qty' => 1, 'cat' => 'Elektronik & AC'],
                ['name' => 'Kursi Kayu Panjang', 'qty' => 23, 'cat' => 'Furniture'],
                ['name' => 'Kursi Kayu Sedang', 'qty' => 12, 'cat' => 'Furniture'],
                ['name' => 'Air Purifier Besar', 'qty' => 1, 'cat' => 'Elektronik & AC'],
                ['name' => 'Lonceng', 'qty' => 1, 'cat' => 'Dekorasi & Alat Kantor'],
                ['name' => 'TV', 'qty' => 3, 'cat' => 'Elektronik & AC'],
                ['name' => 'AC', 'qty' => 8, 'cat' => 'Elektronik & AC'],
                ['name' => 'AC Portable', 'qty' => 1, 'cat' => 'Elektronik & AC'],
                ['name' => 'Dispenser', 'qty' => 1, 'cat' => 'Elektronik & AC'],
                ['name' => 'Kantong Persembahan', 'qty' => 15, 'cat' => 'Peralatan Perjamuan'],
                ['name' => 'Mimbar', 'qty' => 1, 'cat' => 'Furniture'],
                ['name' => 'Mimbar Pendeta', 'qty' => 1, 'cat' => 'Furniture'],
                ['name' => 'Air Purifier', 'qty' => 1, 'cat' => 'Elektronik & AC'],
                ['name' => 'Cawan Besar', 'qty' => 2, 'cat' => 'Peralatan Perjamuan'],
                ['name' => 'Teko Stainless Ibadah', 'qty' => 1, 'cat' => 'Peralatan Perjamuan'],
                ['name' => 'Nampan Stainless Kecil', 'qty' => 1, 'cat' => 'Peralatan Perjamuan'],
                ['name' => 'Meja Perjamuan', 'qty' => 1, 'cat' => 'Furniture'],
                ['name' => 'Jam Dinding', 'qty' => 1, 'cat' => 'Dekorasi & Alat Kantor'],
                ['name' => 'Proyektor', 'qty' => 2, 'cat' => 'Elektronik & AC'],
                ['name' => 'Mic Mimbar', 'qty' => 2, 'cat' => 'Alat Musik'],
                ['name' => 'Standbook', 'qty' => 2, 'cat' => 'Furniture'],
                ['name' => 'Stand Mic', 'qty' => 5, 'cat' => 'Alat Musik'],
                ['name' => 'Speaker Active', 'qty' => 2, 'cat' => 'Alat Musik'],
                ['name' => 'Speaker Passive', 'qty' => 1, 'cat' => 'Alat Musik'],
                ['name' => 'Camera Gantung', 'qty' => 1, 'cat' => 'Alat Multimedia'],
                ['name' => 'Air Curtain', 'qty' => 2, 'cat' => 'Elektronik & AC'],
                ['name' => 'Piano', 'qty' => 1, 'cat' => 'Alat Musik'],
                ['name' => 'Organ', 'qty' => 1, 'cat' => 'Alat Musik'],
                ['name' => 'Clavinova', 'qty' => 1, 'cat' => 'Alat Musik'],
                ['name' => 'Ampli Roland', 'qty' => 1, 'cat' => 'Alat Musik'],
                ['name' => 'Snack Cable', 'qty' => 1, 'cat' => 'Elektronik & AC'],
                ['name' => 'Lampu TL', 'qty' => 21, 'cat' => 'Elektronik & AC'],
                ['name' => 'Rak Alkitab', 'qty' => 1, 'cat' => 'Furniture'],
                ['name' => 'Meja Counter', 'qty' => 1, 'cat' => 'Furniture'],
                ['name' => 'Alkitab TB 2 Kecil', 'qty' => 6, 'cat' => 'Buku & Alkitab'],
                ['name' => 'Alkitab TB 2 Sedang', 'qty' => 20, 'cat' => 'Buku & Alkitab'],
                ['name' => 'Buku NKB', 'qty' => 43, 'qty_rusak_ringan' => 8, 'cat' => 'Buku & Alkitab'],
                ['name' => 'Buku Kidung Jemaat', 'qty' => 105, 'qty_rusak_ringan' => 12, 'cat' => 'Buku & Alkitab'],
                ['name' => 'Buku PKJ', 'qty' => 54, 'qty_rusak_ringan' => 16, 'cat' => 'Buku & Alkitab'],
                ['name' => 'Rak Amplop Kecil', 'qty' => 4, 'cat' => 'Furniture'],
                ['name' => 'Rak Amplop Besar', 'qty' => 1, 'cat' => 'Furniture'],
                ['name' => 'Keranjang Remote AC', 'qty' => 1, 'cat' => 'Furniture'],
                ['name' => 'CCTV', 'qty' => 2, 'cat' => 'Elektronik & AC'],
                ['name' => 'Camera Mulmed', 'qty' => 1, 'cat' => 'Alat Multimedia'],
                ['name' => 'Lampu Kuning', 'qty' => 14, 'cat' => 'Elektronik & AC'],
                ['name' => 'Rak Kantong Persembahan', 'qty' => 1, 'cat' => 'Furniture'],
                ['name' => 'Kaca Dinding', 'qty' => 1, 'cat' => 'Dekorasi & Alat Kantor'],
                ['name' => 'Tong Sampah Kecil', 'qty' => 1, 'cat' => 'Peralatan Kebersihan & K3'],
                ['name' => 'Tong Sampah Sedang', 'qty' => 1, 'cat' => 'Peralatan Kebersihan & K3'],
                ['name' => 'Alkitab', 'qty' => 9, 'cat' => 'Buku & Alkitab'],
                ['name' => 'Checker', 'qty' => 6, 'cat' => 'Dekorasi & Alat Kantor'],
                ['name' => 'Kidung Muda Mudi', 'qty' => 95, 'cat' => 'Buku & Alkitab'],
                ['name' => 'Bangku Kayu Kecil', 'qty' => 2, 'cat' => 'Furniture'],
                ['name' => 'Meja Alkitab', 'qty' => 1, 'cat' => 'Furniture'],
                ['name' => 'Extension Meja Perjamuan', 'qty' => 1, 'cat' => 'Furniture'],
                ['name' => 'Lemari Buku Kecil', 'qty' => 1, 'qty_rusak_ringan' => 1, 'cat' => 'Furniture'],
                ['name' => 'Meja Lipat', 'qty' => 1, 'cat' => 'Furniture'],
                ['name' => 'Kursi Lipat', 'qty' => 2, 'cat' => 'Furniture'],
                ['name' => 'Kotak Tisu', 'qty' => 2, 'cat' => 'Peralatan Dapur'],
            ],
            'Teras' => [
                ['name' => 'Meja Lipat Teras', 'qty' => 3, 'cat' => 'Furniture'],
                ['name' => 'Rak Helm', 'qty' => 1, 'cat' => 'Furniture'],
                ['name' => 'Papan Mading', 'qty' => 1, 'cat' => 'Dekorasi & Alat Kantor'],
                ['name' => 'Stand Poster', 'qty' => 2, 'cat' => 'Dekorasi & Alat Kantor'],
                ['name' => 'Kursi Kayu Panjang', 'qty' => 1, 'cat' => 'Furniture'],
                ['name' => 'Tong Sampah', 'qty' => 3, 'cat' => 'Peralatan Kebersihan & K3'],
                ['name' => 'Meja Kayu Besar', 'qty' => 1, 'cat' => 'Furniture'],
                ['name' => 'Kotak Angket', 'qty' => 1, 'cat' => 'Dekorasi & Alat Kantor'],
                ['name' => 'Meja Kerja Teras', 'qty' => 1, 'cat' => 'Furniture'],
                ['name' => 'Meja Taman', 'qty' => 2, 'cat' => 'Furniture'],
                ['name' => 'Bangku Meja Taman', 'qty' => 4, 'cat' => 'Furniture'],
                ['name' => 'Wastafel', 'qty' => 1, 'cat' => 'Peralatan Dapur'],
                ['name' => 'Aquarium', 'qty' => 1, 'cat' => 'Peralatan Dapur'],
                ['name' => 'Gayung', 'qty' => 1, 'cat' => 'Peralatan Dapur'],
                ['name' => 'Ember', 'qty' => 1, 'cat' => 'Peralatan Dapur'],
                ['name' => 'Toren', 'qty' => 1, 'cat' => 'Elektronik & AC'],
                ['name' => 'Selang', 'qty' => 1, 'cat' => 'Peralatan Dapur'],
                ['name' => 'CCTV', 'qty' => 3, 'cat' => 'Elektronik & AC'],
                ['name' => 'Papan Nama', 'qty' => 1, 'cat' => 'Dekorasi & Alat Kantor'],
                ['name' => 'Lampu Downlight', 'qty' => 7, 'cat' => 'Elektronik & AC'],
                ['name' => 'Lampu TL', 'qty' => 5, 'cat' => 'Elektronik & AC'],
                ['name' => 'Rak Kartu', 'qty' => 1, 'cat' => 'Furniture'],
            ],
            'Ruang ibadah lantai 2' => [
                ['name' => 'Kursi Kayu Extra Panjang', 'qty' => 12, 'cat' => 'Furniture'],
                ['name' => 'Kursi Kayu Kecil', 'qty' => 8, 'cat' => 'Furniture'],
                ['name' => 'Kursi Kayu Sedang', 'qty' => 1, 'cat' => 'Furniture'],
                ['name' => 'Kursi Kayu Panjang', 'qty' => 4, 'cat' => 'Furniture'],
                ['name' => 'Kursi Lipat', 'qty' => 39, 'cat' => 'Furniture'],
                ['name' => 'Arumba', 'qty' => 1, 'cat' => 'Alat Musik'],
                ['name' => 'Kolintang', 'qty' => 9, 'cat' => 'Alat Musik'],
                ['name' => 'Standbook', 'qty' => 8, 'cat' => 'Furniture'],
                ['name' => 'Angklung Set', 'qty' => 2, 'cat' => 'Alat Musik'],
                ['name' => 'Angklung', 'qty' => 46, 'cat' => 'Alat Musik'],
                ['name' => 'Kursi Plastik', 'qty' => 6, 'cat' => 'Furniture'],
                ['name' => 'Tong Sampah', 'qty' => 2, 'cat' => 'Peralatan Kebersihan & K3'],
                ['name' => 'AC', 'qty' => 7, 'cat' => 'Elektronik & AC'],
                ['name' => 'Speaker Passive', 'qty' => 3, 'cat' => 'Alat Musik'],
                ['name' => 'Lampu TL', 'qty' => 38, 'qty_rusak_berat' => 2, 'cat' => 'Elektronik & AC'],
                ['name' => 'Lampu DL', 'qty' => 3, 'cat' => 'Elektronik & AC'],
                ['name' => 'Lampu Kuning', 'qty' => 7, 'qty_rusak_berat' => 5, 'cat' => 'Elektronik & AC'],
                ['name' => 'CCTV', 'qty' => 3, 'cat' => 'Elektronik & AC'],
                ['name' => 'TV', 'qty' => 1, 'cat' => 'Elektronik & AC'],
                ['name' => 'Jam Dinding', 'qty' => 2, 'cat' => 'Dekorasi & Alat Kantor'],
            ],
            'ruang kaca' => [
                ['name' => 'Angklung Goyang', 'qty' => 3, 'cat' => 'Alat Musik'],
                ['name' => 'Angklung Set', 'qty' => 1, 'cat' => 'Alat Musik'],
                ['name' => 'Lemari Besi', 'qty' => 1, 'cat' => 'Furniture'],
                ['name' => 'Meja Panjang', 'qty' => 3, 'cat' => 'Furniture'],
                ['name' => 'TV Rusak', 'qty' => 2, 'qty_rusak_berat' => 2, 'cat' => 'Elektronik & AC'],
                ['name' => 'Stand Salib', 'qty' => 1, 'cat' => 'Peralatan Perjamuan'],
                ['name' => 'Kursi Plastik Pendek', 'qty' => 7, 'cat' => 'Furniture'],
                ['name' => 'AC', 'qty' => 1, 'cat' => 'Elektronik & AC'],
                ['name' => 'Lampu TL', 'qty' => 1, 'cat' => 'Elektronik & AC'],
            ],
            'ruang gudang' => [
                ['name' => 'Stand Obor', 'qty' => 6, 'cat' => 'Peralatan Perjamuan'],
                ['name' => 'Salib', 'qty' => 1, 'cat' => 'Peralatan Perjamuan'],
                ['name' => 'Pohon Natal', 'qty' => 1, 'cat' => 'Dekorasi & Alat Kantor'],
                ['name' => 'Kursi Roda', 'qty' => 1, 'cat' => 'Lain-lain'],
            ],
            'ruang rapat' => [
                ['name' => 'Kursi Lipat', 'qty' => 22, 'cat' => 'Furniture'],
                ['name' => 'Kursi Meja Lipat', 'qty' => 10, 'cat' => 'Furniture'],
                ['name' => 'Kursi Kayu Panjang', 'qty' => 1, 'cat' => 'Furniture'],
                ['name' => 'Tabung Oksigen', 'qty' => 2, 'cat' => 'Peralatan Kebersihan & K3'],
                ['name' => 'Lemari Inventaris', 'qty' => 1, 'cat' => 'Furniture'],
                ['name' => 'Meja Lipat Panjang', 'qty' => 8, 'cat' => 'Furniture'],
                ['name' => 'AC', 'qty' => 2, 'cat' => 'Elektronik & AC'],
                ['name' => 'Dispenser', 'qty' => 1, 'cat' => 'Elektronik & AC'],
                ['name' => 'Meja Besar', 'qty' => 1, 'cat' => 'Furniture'],
                ['name' => 'Buku Kidung Jemaat', 'qty' => 23, 'cat' => 'Buku & Alkitab'],
                ['name' => 'Buku PKJ', 'qty' => 10, 'cat' => 'Buku & Alkitab'],
                ['name' => 'Buku NKB', 'qty' => 23, 'cat' => 'Buku & Alkitab'],
                ['name' => 'Kotak Tisu', 'qty' => 1, 'cat' => 'Peralatan Dapur'],
                ['name' => 'Tong Sampah', 'qty' => 2, 'cat' => 'Peralatan Kebersihan & K3'],
                ['name' => 'Kabel Roll', 'qty' => 1, 'cat' => 'Elektronik & AC'],
                ['name' => 'Jam Dinding', 'qty' => 1, 'cat' => 'Dekorasi & Alat Kantor'],
                ['name' => 'Router', 'qty' => 1, 'cat' => 'Elektronik & AC'],
            ],
            'GSG 1' => [
                ['name' => 'Kursi Lipat', 'qty' => 36, 'cat' => 'Furniture'],
                ['name' => 'Kursi Plastik Anak', 'qty' => 19, 'cat' => 'Furniture'],
                ['name' => 'Rak Sepatu', 'qty' => 3, 'cat' => 'Furniture'],
                ['name' => 'Meja Plastik Anak', 'qty' => 7, 'cat' => 'Furniture'],
                ['name' => 'Keyboard', 'qty' => 1, 'cat' => 'Alat Musik'],
                ['name' => 'Meja Kayu', 'qty' => 1, 'cat' => 'Furniture'],
                ['name' => 'Lemari Buku Anak', 'qty' => 1, 'cat' => 'Furniture'],
                ['name' => 'Rak Buku', 'qty' => 1, 'cat' => 'Furniture'],
                ['name' => 'Lemari Kecil', 'qty' => 2, 'cat' => 'Furniture'],
                ['name' => 'AC', 'qty' => 3, 'cat' => 'Elektronik & AC'],
                ['name' => 'Speaker Passive', 'qty' => 1, 'cat' => 'Alat Musik'],
                ['name' => 'Proyektor', 'qty' => 1, 'cat' => 'Elektronik & AC'],
                ['name' => 'APAR', 'qty' => 1, 'cat' => 'Peralatan Kebersihan & K3'],
                ['name' => 'Layar Proyektor Besar', 'qty' => 1, 'cat' => 'Elektronik & AC'],
                ['name' => 'Meja Lipat', 'qty' => 1, 'cat' => 'Furniture'],
                ['name' => 'Lemari GSG', 'qty' => 4, 'cat' => 'Furniture'],
                ['name' => 'Jam Dinding', 'qty' => 1, 'cat' => 'Dekorasi & Alat Kantor'],
                ['name' => 'Kolam Mandi Bola', 'qty' => 1, 'cat' => 'Lain-lain'],
                ['name' => 'Kabel Gulung', 'qty' => 1, 'cat' => 'Elektronik & AC'],
                ['name' => 'CCTV', 'qty' => 1, 'cat' => 'Elektronik & AC'],
            ],
            'Teras GSG' => [
                ['name' => 'Lemari 3 Pintu', 'qty' => 1, 'cat' => 'Furniture'],
                ['name' => 'Kursi Sedang', 'qty' => 1, 'cat' => 'Furniture'],
                ['name' => 'Sanyo', 'qty' => 1, 'cat' => 'Elektronik & AC'],
                ['name' => 'Tong Sampah Kecil', 'qty' => 1, 'cat' => 'Peralatan Kebersihan & K3'],
                ['name' => 'Sapu', 'qty' => 1, 'cat' => 'Peralatan Dapur'],
                ['name' => 'Sapu Lidi', 'qty' => 1, 'cat' => 'Peralatan Dapur'],
                ['name' => 'Serokan', 'qty' => 1, 'cat' => 'Peralatan Dapur'],
            ],
            'dapur GSG' => [
                ['name' => 'Sanyo', 'qty' => 1, 'cat' => 'Elektronik & AC'],
                ['name' => 'Lemari Gantung', 'qty' => 3, 'cat' => 'Furniture'],
                ['name' => 'Genset', 'qty' => 2, 'qty_rusak_berat' => 2, 'cat' => 'Elektronik & AC'],
                ['name' => 'Kaca', 'qty' => 1, 'cat' => 'Dekorasi & Alat Kantor'],
                ['name' => 'Dispenser Listrik', 'qty' => 1, 'cat' => 'Elektronik & AC'],
                ['name' => 'Router', 'qty' => 1, 'cat' => 'Elektronik & AC'],
                ['name' => 'Tong Sampah', 'qty' => 2, 'cat' => 'Peralatan Kebersihan & K3'],
                ['name' => 'Rak Besi Besar', 'qty' => 1, 'cat' => 'Furniture'],
                ['name' => 'Rak Besi Kecil', 'qty' => 1, 'cat' => 'Furniture'],
                ['name' => 'Tangga', 'qty' => 4, 'cat' => 'Lain-lain'],
            ],
            'Ruang Gideon' => [
                ['name' => 'Proyektor', 'qty' => 1, 'cat' => 'Elektronik & AC'],
                ['name' => 'Kursi Lipat Plastik', 'qty' => 21, 'cat' => 'Furniture'],
                ['name' => 'Kursi Lipat', 'qty' => 27, 'cat' => 'Furniture'],
                ['name' => 'Standbook', 'qty' => 4, 'cat' => 'Furniture'],
                ['name' => 'Ampli Roland', 'qty' => 1, 'cat' => 'Alat Musik'],
                ['name' => 'Gitar', 'qty' => 2, 'cat' => 'Alat Musik'],
                ['name' => 'Cahon', 'qty' => 2, 'cat' => 'Alat Musik'],
                ['name' => 'Bass', 'qty' => 1, 'cat' => 'Alat Musik'],
                ['name' => 'Keyboard', 'qty' => 1, 'cat' => 'Alat Musik'],
                ['name' => 'Drum Electric', 'qty' => 1, 'cat' => 'Alat Musik'],
                ['name' => 'Mimbar Kecil', 'qty' => 1, 'cat' => 'Furniture'],
                ['name' => 'Salib Kecil', 'qty' => 1, 'cat' => 'Peralatan Perjamuan'],
                ['name' => 'Layar Proyektor', 'qty' => 1, 'cat' => 'Elektronik & AC'],
                ['name' => 'Lemari Gideon', 'qty' => 1, 'cat' => 'Furniture'],
                ['name' => 'Lemari Kecil Gideon', 'qty' => 1, 'cat' => 'Furniture'],
                ['name' => 'Air Purifier', 'qty' => 1, 'cat' => 'Elektronik & AC'],
                ['name' => 'Kantong Persembahan', 'qty' => 2, 'cat' => 'Peralatan Perjamuan'],
                ['name' => 'Speaker Passive', 'qty' => 2, 'cat' => 'Alat Musik'],
                ['name' => 'AC', 'qty' => 3, 'cat' => 'Elektronik & AC'],
                ['name' => 'Jam Dinding', 'qty' => 1, 'cat' => 'Dekorasi & Alat Kantor'],
                ['name' => 'Mixer', 'qty' => 1, 'cat' => 'Alat Musik'],
                ['name' => 'Komputer Set', 'qty' => 1, 'cat' => 'Elektronik & AC'],
                ['name' => 'TV', 'qty' => 1, 'cat' => 'Elektronik & AC'],
                ['name' => 'Meja Plastik Anak', 'qty' => 3, 'cat' => 'Furniture'],
                ['name' => 'Alkitab Kecil', 'qty' => 9, 'cat' => 'Buku & Alkitab'],
                ['name' => 'Alkitab Sedang', 'qty' => 2, 'cat' => 'Buku & Alkitab'],
                ['name' => 'CCTV', 'qty' => 1, 'cat' => 'Elektronik & AC'],
                ['name' => 'Baju Biru', 'qty' => 8, 'cat' => 'Lain-lain'],
                ['name' => 'Blazer', 'qty' => 4, 'cat' => 'Lain-lain'],
                ['name' => 'Colar', 'qty' => 37, 'cat' => 'Lain-lain'],
            ],
            'dapur Atas' => [
                ['name' => 'Jam Dinding', 'qty' => 1, 'cat' => 'Dekorasi & Alat Kantor'],
                ['name' => 'Router', 'qty' => 1, 'cat' => 'Elektronik & AC'],
                ['name' => 'Kulkas', 'qty' => 1, 'cat' => 'Elektronik & AC'],
                ['name' => 'Dispenser', 'qty' => 1, 'cat' => 'Elektronik & AC'],
                ['name' => 'Lemari Gantung', 'qty' => 3, 'cat' => 'Furniture'],
                ['name' => 'Meja Marmer', 'qty' => 1, 'cat' => 'Furniture'],
                ['name' => 'Kursi Kayu Kecil', 'qty' => 1, 'cat' => 'Furniture'],
            ],
            'pastori lantai 2' => [
                ['name' => 'Kursi Sujud', 'qty' => 1, 'cat' => 'Furniture'],
            ],
            'GSG lantai 2' => [
                ['name' => 'Tong Sampah', 'qty' => 4, 'cat' => 'Peralatan Kebersihan & K3'],
                ['name' => 'Mimbar', 'qty' => 1, 'cat' => 'Furniture'],
                ['name' => 'Dispenser', 'qty' => 1, 'cat' => 'Elektronik & AC'],
                ['name' => 'Meja Lipat Kayu', 'qty' => 1, 'cat' => 'Furniture'],
                ['name' => 'Kursi Lipat', 'qty' => 3, 'cat' => 'Furniture'],
                ['name' => 'Kursi Meja Lipat', 'qty' => 15, 'cat' => 'Furniture'],
                ['name' => 'Meja Kecil Anak', 'qty' => 1, 'cat' => 'Furniture'],
                ['name' => 'Lemari GSG Lantai 2', 'qty' => 1, 'cat' => 'Furniture'],
                ['name' => 'Keyboard', 'qty' => 1, 'cat' => 'Alat Musik'],
                ['name' => 'AC', 'qty' => 1, 'cat' => 'Elektronik & AC'],
                ['name' => 'Jam Dinding', 'qty' => 1, 'cat' => 'Dekorasi & Alat Kantor'],
                ['name' => 'Lemari Kaca', 'qty' => 1, 'cat' => 'Furniture'],
                ['name' => 'Rak Kayu', 'qty' => 1, 'cat' => 'Furniture'],
                ['name' => 'Lampu DL', 'qty' => 5, 'cat' => 'Elektronik & AC'],
                ['name' => 'Lampu TL', 'qty' => 6, 'cat' => 'Elektronik & AC'],
            ],
            'GSG lantai 3' => [
                ['name' => 'Meja Kecil Anak', 'qty' => 26, 'cat' => 'Furniture'],
                ['name' => 'Kursi Plastik Anak', 'qty' => 26, 'cat' => 'Furniture'],
                ['name' => 'Lemari Kaca GSG', 'qty' => 1, 'cat' => 'Furniture'],
                ['name' => 'Lemari Kayu GSG Lantai 3', 'qty' => 1, 'cat' => 'Furniture'],
                ['name' => 'Papan Tulis', 'qty' => 3, 'cat' => 'Dekorasi & Alat Kantor'],
                ['name' => 'Lemari Plastik', 'qty' => 1, 'cat' => 'Furniture'],
                ['name' => 'AC', 'qty' => 2, 'cat' => 'Elektronik & AC'],
                ['name' => 'Meja Taman Anak', 'qty' => 1, 'cat' => 'Furniture'],
                ['name' => 'Lampu DL', 'qty' => 3, 'cat' => 'Elektronik & AC'],
                ['name' => 'Router', 'qty' => 1, 'cat' => 'Elektronik & AC'],
                ['name' => 'Tong Sampah', 'qty' => 3, 'cat' => 'Peralatan Kebersihan & K3'],
                ['name' => 'CCTV', 'qty' => 1, 'cat' => 'Elektronik & AC'],
            ],
            'ruang guest house' => [
                ['name' => 'AC', 'qty' => 1, 'cat' => 'Elektronik & AC'],
                ['name' => 'TV', 'qty' => 1, 'cat' => 'Elektronik & AC'],
                ['name' => 'Meja Kayu', 'qty' => 1, 'cat' => 'Furniture'],
                ['name' => 'Kasur', 'qty' => 1, 'cat' => 'Furniture'],
                ['name' => 'Lampu Tidur', 'qty' => 1, 'cat' => 'Elektronik & AC'],
                ['name' => 'Lampu Meja', 'qty' => 1, 'cat' => 'Elektronik & AC'],
                ['name' => 'Teko Electric', 'qty' => 1, 'cat' => 'Elektronik & AC'],
                ['name' => 'Tempat Sampah', 'qty' => 1, 'cat' => 'Peralatan Kebersihan & K3'],
                ['name' => 'Lampu DL', 'qty' => 3, 'cat' => 'Elektronik & AC'],
                ['name' => 'Jam Dinding', 'qty' => 1, 'cat' => 'Dekorasi & Alat Kantor'],
                ['name' => 'Lampu TL', 'qty' => 12, 'cat' => 'Elektronik & AC'],
            ],
            'ruang musik' => [
                ['name' => 'Gitar', 'qty' => 4, 'qty_rusak_berat' => 4, 'cat' => 'Alat Musik'],
                ['name' => 'Keyboard', 'qty' => 2, 'qty_rusak_berat' => 1, 'cat' => 'Alat Musik'],
                ['name' => 'Speaker Passive', 'qty' => 3, 'cat' => 'Alat Musik'],
                ['name' => 'Ampli', 'qty' => 6, 'cat' => 'Alat Musik'],
                ['name' => 'AC', 'qty' => 1, 'cat' => 'Elektronik & AC'],
                ['name' => 'Lampu TL', 'qty' => 8, 'qty_rusak_berat' => 2, 'cat' => 'Elektronik & AC'],
                ['name' => 'Contra Bass', 'qty' => 1, 'cat' => 'Alat Musik'],
                ['name' => 'Lighting', 'qty' => 1, 'cat' => 'Elektronik & AC'],
                ['name' => 'Stand Speaker', 'qty' => 2, 'cat' => 'Alat Musik'],
                ['name' => 'Cahon', 'qty' => 1, 'cat' => 'Alat Musik'],
                ['name' => 'Green Screen', 'qty' => 1, 'cat' => 'Alat Multimedia'],
                ['name' => 'TV Rusak', 'qty' => 3, 'qty_rusak_berat' => 3, 'cat' => 'Elektronik & AC'],
                ['name' => 'Extension Kolintang Bass', 'qty' => 1, 'cat' => 'Alat Musik'],
                ['name' => 'Mixer', 'qty' => 2, 'qty_rusak_ringan' => 2, 'cat' => 'Alat Musik'],
                ['name' => 'Meja Kecil Anak', 'qty' => 1, 'cat' => 'Furniture'],
                ['name' => 'Meja Komputer', 'qty' => 1, 'cat' => 'Furniture'],
                ['name' => 'Karpet', 'qty' => 1, 'cat' => 'Furniture'],
                ['name' => 'Kursi Kulit', 'qty' => 2, 'cat' => 'Furniture'],
                ['name' => 'Kursi Bundar', 'qty' => 1, 'cat' => 'Furniture'],
                ['name' => 'Stick Drum', 'qty' => 1, 'cat' => 'Alat Musik'],
                ['name' => 'Papan Tulis', 'qty' => 2, 'cat' => 'Dekorasi & Alat Kantor'],
                ['name' => 'Tas Kedukaan', 'qty' => 1, 'cat' => 'Lain-lain'],
                ['name' => 'Bongo', 'qty' => 1, 'cat' => 'Alat Musik'],
                ['name' => 'Standbook', 'qty' => 4, 'cat' => 'Furniture'],
                ['name' => 'Bass', 'qty' => 1, 'qty_rusak_berat' => 1, 'cat' => 'Alat Musik'],
                ['name' => 'Stand Gitar Single', 'qty' => 1, 'cat' => 'Alat Musik'],
                ['name' => 'Stand Gitar Kepala 3', 'qty' => 1, 'cat' => 'Alat Musik'],
                ['name' => 'Rebana', 'qty' => 2, 'cat' => 'Alat Musik'],
                ['name' => 'Bantal Lutut', 'qty' => 8, 'cat' => 'Furniture'],
                ['name' => 'Salib Gantung Kecil', 'qty' => 1, 'cat' => 'Peralatan Perjamuan'],
                ['name' => 'Kaki Keyboard', 'qty' => 1, 'cat' => 'Alat Musik'],
            ],
            'Ruang Mulmed' => [
                ['name' => 'Mixer', 'qty' => 1, 'cat' => 'Alat Musik'],
                ['name' => 'Power Speaker', 'qty' => 3, 'cat' => 'Alat Musik'],
                ['name' => 'Laptop Lenovo', 'qty' => 2, 'cat' => 'Elektronik & AC'],
                ['name' => 'Laptop HP', 'qty' => 1, 'cat' => 'Elektronik & AC'],
                ['name' => 'PC', 'qty' => 2, 'cat' => 'Elektronik & AC'],
                ['name' => 'Monitor', 'qty' => 5, 'cat' => 'Elektronik & AC'],
                ['name' => 'Router', 'qty' => 1, 'cat' => 'Elektronik & AC'],
                ['name' => 'AC', 'qty' => 2, 'cat' => 'Elektronik & AC'],
                ['name' => 'Blackmagic', 'qty' => 1, 'cat' => 'Alat Multimedia'],
                ['name' => 'POE CCTV', 'qty' => 1, 'cat' => 'Elektronik & AC'],
                ['name' => 'Soundcard', 'qty' => 1, 'cat' => 'Alat Multimedia'],
                ['name' => 'Handicam', 'qty' => 1, 'cat' => 'Alat Multimedia'],
                ['name' => 'Mic Wireless Set', 'qty' => 4, 'cat' => 'Alat Musik'],
                ['name' => 'Mic Clip On', 'qty' => 3, 'cat' => 'Alat Musik'],
                ['name' => 'Mic Wire', 'qty' => 5, 'cat' => 'Alat Musik'],
                ['name' => 'Mic Condensor Podcast', 'qty' => 2, 'cat' => 'Alat Musik'],
                ['name' => 'Mic Condensor Padus', 'qty' => 2, 'cat' => 'Alat Musik'],
                ['name' => 'Mic Headset Set', 'qty' => 2, 'cat' => 'Alat Musik'],
                ['name' => 'Headphone', 'qty' => 3, 'cat' => 'Alat Multimedia'],
                ['name' => 'Desk Stand Mic', 'qty' => 3, 'cat' => 'Alat Musik'],
                ['name' => 'Mini Tripod', 'qty' => 2, 'cat' => 'Alat Multimedia'],
                ['name' => 'Webcam', 'qty' => 5, 'cat' => 'Alat Multimedia'],
                ['name' => 'Stabilizer', 'qty' => 2, 'cat' => 'Elektronik & AC'],
                ['name' => 'UPS 500 VA', 'qty' => 2, 'cat' => 'Elektronik & AC'],
                ['name' => 'UPS 1000 VA', 'qty' => 1, 'cat' => 'Elektronik & AC'],
                ['name' => 'Tripod Camera', 'qty' => 1, 'cat' => 'Alat Multimedia'],
                ['name' => 'Lampu TL', 'qty' => 1, 'cat' => 'Elektronik & AC'],
                ['name' => 'Lampu DL', 'qty' => 1, 'cat' => 'Elektronik & AC'],
                ['name' => 'Pointer', 'qty' => 2, 'cat' => 'Dekorasi & Alat Kantor'],
                ['name' => 'Bangku', 'qty' => 5, 'cat' => 'Furniture'],
                ['name' => 'Kipas Angin Kecil', 'qty' => 1, 'cat' => 'Elektronik & AC'],
                ['name' => 'Jam Dinding', 'qty' => 1, 'cat' => 'Dekorasi & Alat Kantor'],
                ['name' => 'Meja Panjang', 'qty' => 2, 'cat' => 'Furniture'],
                ['name' => 'Rak Besi', 'qty' => 3, 'cat' => 'Furniture'],
                ['name' => 'Stop Contact', 'qty' => 10, 'cat' => 'Elektronik & AC'],
                ['name' => 'Papan Tulis', 'qty' => 1, 'cat' => 'Dekorasi & Alat Kantor'],
                ['name' => 'Kotak Tisu', 'qty' => 2, 'cat' => 'Peralatan Dapur'],
                ['name' => 'SSD SATA', 'qty' => 2, 'cat' => 'Elektronik & AC'],
            ],
            'ruang ruth' => [
                ['name' => 'Bangku Plastik', 'qty' => 42, 'cat' => 'Furniture'],
            ],
            'ruang TU' => [
                ['name' => 'Lemari Besi 2 Pintu', 'qty' => 4, 'cat' => 'Furniture'],
                ['name' => 'Dispenser Electric', 'qty' => 1, 'cat' => 'Elektronik & AC'],
                ['name' => 'Meja Kantor', 'qty' => 4, 'cat' => 'Furniture'],
                ['name' => 'Printer', 'qty' => 1, 'cat' => 'Elektronik & AC'],
                ['name' => 'Lemari Besi Kecil', 'qty' => 1, 'cat' => 'Furniture'],
                ['name' => 'Rak Kayu', 'qty' => 1, 'cat' => 'Furniture'],
                ['name' => 'Brankas', 'qty' => 1, 'cat' => 'Furniture'],
                ['name' => 'Lemari Kaca', 'qty' => 1, 'cat' => 'Furniture'],
                ['name' => 'AC', 'qty' => 1, 'cat' => 'Elektronik & AC'],
                ['name' => 'Lampu TL', 'qty' => 3, 'cat' => 'Elektronik & AC'],
                ['name' => 'Kulkas Mini', 'qty' => 1, 'cat' => 'Elektronik & AC'],
                ['name' => 'PC Set', 'qty' => 3, 'cat' => 'Elektronik & AC'],
                ['name' => 'Tempat Sampah', 'qty' => 2, 'cat' => 'Peralatan Kebersihan & K3'],
            ]
        ];

        // 5. Insert Rooms and Items
        $roomCounter = 1;
        foreach ($dataset as $roomName => $items) {
            // Create Room
            $room = Room::create([
                'name'        => $roomName,
                'description' => 'Lokasi penyimpanan di ' . $roomName
            ]);

            $itemSeq = 1;
            foreach ($items as $itemData) {
                $qty = $itemData['qty'];
                $qtyRusakRingan = $itemData['qty_rusak_ringan'] ?? 0;
                $qtyRusakBerat = $itemData['qty_rusak_berat'] ?? 0;
                $qtyBaik = $qty - $qtyRusakRingan - $qtyRusakBerat;

                // Under user instruction: all units are available in database
                // (which maps to qty_tersedia = total active quantity)
                $qtyTersedia = $qtyBaik + $qtyRusakRingan; 

                // Generate Unique code INV-[ROOM_NO]-[ITEM_NO]
                $codeRoom = str_pad($roomCounter, 2, '0', STR_PAD_LEFT);
                $codeItem = str_pad($itemSeq, 3, '0', STR_PAD_LEFT);
                $assetCode = "INV-{$codeRoom}-{$codeItem}";

                // Map category
                $categoryName = $itemData['cat'];
                $categoryId = $categories[$categoryName]->id;

                // Create Item
                Item::create([
                    'slug'            => Str::slug($itemData['name']) . '-' . Str::random(5),
                    'entno'           => $assetCode,
                    'kode_aset'       => $assetCode,
                    'barcode'         => $assetCode,
                    'name'            => $itemData['name'],
                    'category_id'     => $categoryId,
                    'room_id'         => $room->id,
                    'merk_model'      => '-',
                    'spesifikasi'     => '-',
                    'quantity'        => $qty,
                    'unit'            => 'unit',
                    'qty_baik'        => $qtyBaik,
                    'qty_rusak_ringan'=> $qtyRusakRingan,
                    'qty_rusak_berat' => $qtyRusakBerat,
                    'qty_tersedia'    => $qtyTersedia,
                    'qty_dipinjam'    => 0,
                    'qty_diperbaiki'  => 0,
                    'qty_hilang'      => 0,
                    'qty_tidak_digunakan' => 0,
                    'qty_pengadaan'   => 0,
                    'condition'       => $qtyRusakBerat > 0 ? 'Rusak' : 'Baik',
                    'status'          => 'Tersedia',
                    'purchase_date'   => null,
                    'description'     => $itemData['notes'] ?? 'Aset inventaris ' . $roomName,
                    'keterangan'      => '-',
                    'is_write_off'    => false,
                ]);

                $itemSeq++;
            }

            $roomCounter++;
        }
    }
}
