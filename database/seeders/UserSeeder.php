<?php

namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'  =>  'Gerson Villarroel',
            'email' =>  'gersonvillarroeltorrico@gmail.com',
            'password'  =>  bcrypt('123456') // metodo para encriptar contraseña
        ]);

        User::factory(9)->create();
    }
}
