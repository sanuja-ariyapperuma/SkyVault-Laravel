<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NationalitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $nationalities = [
            ['name' => 'American', 'code' => 'USA'],
            ['name' => 'Canadian', 'code' => 'CAN'],
            ['name' => 'British', 'code' => 'GBR'],
            ['name' => 'Australian', 'code' => 'AUS'],
            ['name' => 'Indian', 'code' => 'IND'],
            ['name' => 'Singaporean', 'code' => 'SGP'],
            ['name' => 'Chinese', 'code' => 'CHN'],
            ['name' => 'Japanese', 'code' => 'JPN'],
            ['name' => 'Korean', 'code' => 'KOR'],
            ['name' => 'Vietnamese', 'code' => 'VNM'],
            ['name' => 'Thai', 'code' => 'THA'],
            ['name' => 'Filipino', 'code' => 'PHL'],
            ['name' => 'Malaysian', 'code' => 'MYS'],
            ['name' => 'Indonesian', 'code' => 'IDN'],
            ['name' => 'Pakistani', 'code' => 'PAK'],
            ['name' => 'Bangladeshi', 'code' => 'BGD'],
            ['name' => 'Sri Lankan', 'code' => 'LKA'],
            ['name' => 'Afghan', 'code' => 'AFG'],
            ['name' => 'Iranian', 'code' => 'IRN'],
            ['name' => 'Iraqi', 'code' => 'IRQ'],
            ['name' => 'Saudi Arabian', 'code' => 'SAU'],
            ['name' => 'Egyptian', 'code' => 'EGY'],
            ['name' => 'Nigerian', 'code' => 'NGA'],
            ['name' => 'South African', 'code' => 'ZAF'],
            ['name' => 'Brazilian', 'code' => 'BRA'],
            ['name' => 'Argentinian', 'code' => 'ARG'],
            ['name' => 'Chilean', 'code' => 'CHL'],
            ['name' => 'Colombian', 'code' => 'COL'],
            ['name' => 'Peruvian', 'code' => 'PER'],
            ['name' => 'Venezuelan', 'code' => 'VEN'],
            ['name' => 'Guatemalan', 'code' => 'GTM'],
            ['name' => 'Panamanian', 'code' => 'PAN'],
            ['name' => 'Mexican', 'code' => 'MEX'],
            ['name' => 'Salvadoran', 'code' => 'SLV'],
            ['name' => 'Costa Rican', 'code' => 'CRI'],
            ['name' => 'Nicaraguan', 'code' => 'NIC'],
            ['name' => 'Honduran', 'code' => 'HND'],
            ['name' => 'Dominican', 'code' => 'DOM'],
            ['name' => 'Guyanese', 'code' => 'GUY'],
            ['name' => 'Bolivian', 'code' => 'BOL'],
            ['name' => 'Paraguayan', 'code' => 'PRY']
        ];

        foreach ($nationalities as $nationality) {
            DB::table('nationalities')->insert([
                'id' => Str::uuid(),
                'name' => $nationality['name'],
                'code' => $nationality['code'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
    }
}
