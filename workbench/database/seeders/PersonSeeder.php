<?php

namespace Workbench\Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use VPremiss\Crafty\Facades\Crafty;

class PersonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name' => 'أبو عبد الله'],
            ['name' => 'أبو بلال'],
        ];

        Crafty::chunkedDatabaseInsertion('people', $data);
    }
}
