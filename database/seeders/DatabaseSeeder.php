<?php

namespace Database\Seeders;

use App\Models\Loan;
use App\Models\User;
use App\Models\Category;
use App\Models\Material;
use App\Models\Component;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Compliance;
use App\Models\Configuration;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Database\Factories\MaterialFactory;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Category::factory(2)->create();
        Material::factory(5)->create();
        
        User::factory()->create([
            'avatar' => fake()->imageUrl(),
            'graduation' => '3º Sgt',
            'name' => 'Iago Silva',
            'email' => 'iago23st1@gmail.com',
            'password' => Hash::make('12345678'),
        ]);

        Configuration::factory()->create([
            'organization' => "3º BATALHÃO DE COMUNICAÇÕES E GUERRA ELETRÔNICA",
            'organization_slug' => "3º B Com GE",
            'company' => "CCPCR",
            'squad' => "PELOTÃO RÁDIO",
            'squad_leader' => "CÉSAR AUGUSTO DOS SANTOS - 2º Ten",
            'company_leader' => "MIGUEL DOS ANJOS - CAP",
            'organization_s4' => "THIAGO RANGEL - CAP",
        ]);
    }
}
