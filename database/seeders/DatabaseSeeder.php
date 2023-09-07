<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\Hash;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password'=> Hash::make('12345'),
            'role'=> 'admin',
        ]);
        \App\Models\Product::create([
            'name_product' => 'Milo Es Cup',
            'quantity' => '100',
            'price'=> '10000',
        ]);
    }
}
