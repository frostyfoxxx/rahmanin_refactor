<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'name' => 'Студент',
            'slug' => 'student'
        ]);
        DB::table('roles')->insert([
            'name' => 'Администратор',
            'slug' => 'admin'
        ]);
        DB::table('roles')->insert([
            'name' => 'Секретарь приемной комиссии',
            'slug' => 'admission-secretary'
        ]);
        DB::table('roles')->insert([
            'name' => 'Работник приемной комиссии',
            'slug' => 'admissions-officer'
        ]);
    }
}
