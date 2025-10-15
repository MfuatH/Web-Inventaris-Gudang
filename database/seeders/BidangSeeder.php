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
            'PSDA',
            'Irigasi',
            'SWP',
            'Binfat',
        ];

        foreach ($names as $name) {
            Bidang::firstOrCreate(['nama' => $name]);
        }
    }
}


