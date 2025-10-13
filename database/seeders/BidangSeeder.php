<?php

namespace Database\Seeders;

use App\Models\Bidang;
use Illuminate\Database\Seeder;

class BidangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $names = [
            'Sekretariat',
            'Perencanaan',
            'Keuangan',
            'Operasional',
            'Umum',
        ];

        foreach ($names as $name) {
            Bidang::firstOrCreate(['nama' => $name]);
        }
    }
}


