<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Hash;

use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users  =   [
            [
                'officer_id'        =>  'PTG12345',
                'fullname'          =>  'Super Admin',
                'place_of_birth'    =>  'Jakarta',
                'date_of_birth'     =>  '1991-01-01',
                'gender'            =>  'Laki-Laki',
                'address'           =>  'Jakarta',
                'phone'             =>  '080012348765',
                'isAdmin'           =>  TRUE,
                'status'            =>  TRUE,
                'password'          =>  Hash::make('PTG12345'),
                'created_at'        =>  Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'        =>  Carbon::now()->format('Y-m-d H:i:s'),
            ],

            [
                'officer_id'        =>  'PTG67890',
                'fullname'          =>  'Petugas',
                'place_of_birth'    =>  'Tangerang',
                'date_of_birth'     =>  '1992-02-02',
                'gender'            =>  'Perempuan',
                'address'           =>  'Tangerang',
                'phone'             =>  '081198765432',
                'isAdmin'           =>  false,
                'status'            =>  TRUE,
                'password'          =>  Hash::make('PTG67890'),
                'created_at'        =>  Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'        =>  Carbon::now()->format('Y-m-d H:i:s'),
            ]
        ];

        foreach ($users as $user) {
            $arr    =   User::firstOrCreate($user);
        }
    }
}
