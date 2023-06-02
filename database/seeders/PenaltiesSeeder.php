<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;

use App\Models\Penalty;

class PenaltiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $penalties  =   [
            [
                'name'          =>  'Terlambat',
                'value'         =>  '5',
                'status'        =>  TRUE,
                'created_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name'          =>  'Rusak',
                'value'         =>  '50',
                'status'        =>  TRUE,
                'created_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name'          =>  'Hilang',
                'value'         =>  '100',
                'status'        =>  TRUE,
                'created_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
            ],
        ];

        foreach ($penalties as $penalty) {
            $arr    =   Penalty::firstOrCreate($penalty);
        }
    }
}
