<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategoriData = [
            ['nama' => 'Barang'],
            ['nama' => 'Jasa']
        ];

        foreach ($kategoriData as $data) {
            Kategori::factory()->create($data);
        }
    }
}
