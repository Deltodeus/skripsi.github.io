<?php

use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admins')->insert(array(
            array(
                'user_id' => '2',
                'name' => 'budi doremi',
                'address' => 'jl.nenas no 31 jakarta barat',
                'phone' => '081221111123',
                'birth_date' => '1999-05-01',
                'birth_place' => 'semarang',
                'gender' => 'male',
                'email' => 'budi333@gmail.com',
                'profile_picture' => 'image/user3.jpg'
            )
        ));
    }
}
