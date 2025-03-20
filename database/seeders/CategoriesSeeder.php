<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            // MOTOROLA
            'ANTENA SINCLAIR',
            'ESTAÇÃO REPETIDORA GTR 8000',
            'RÁDIO XTL 1500',
            'RÁDIO XTL 2200',
            'RÁDIO XTS 1500',
            'RÁDIO XTS 2500',
            'RÁDIO APX 2000',
            'COMBINADO TELEFÔNICO REMOTO',
            'COMBINADO REMOTO GPS',
            'BOLSA TRANSPORTE XTS',
            'CONSOLE DE DESPACHO MCC 7500',
            'RASTREADOR SATELITAL GPS',
            'MOTOBRIDGE 8 PORTAS',
            'MOTOBRIDGE 4 PORTAS',
            'CARREGADOR MÚLTIPLO DE BATERIA',
            'NOTEBOOK ACER',
            'NOTEBOOK SAMSUNG',
            'ANTENA XTL',
            'REPETIDORA DVR',
            'BATERIA DVR',
            'FONTE E CARREGADOR DVR',
            'CARREGADOR INDIVIDUAL APX',
            'BATERIA RESERVA APX 2000',
            'BATERIA RESERVA XTS ',
            'PTT DE MÃO PARA APX 2000',
            'CARREGADOR IND E FONTE XTS',
            'CLIPS DE CINTO APX 2000',
            'CLIPS DE CINTO XTS 1500/2500',
            'ANTENA TÁTICA DVR',
            'ANTENA MÓVEL DVR',
            'CASE TIPO BAÚ / 80X50X50 CMZ',
            'NOTEBOOK ROBUSTECIDO',
            
            // HARIS
            'RF 7800V HH 001 (FALCON III)',
            'BATERIA FALCON III',
            'CARREGADOR FALCON III',
            'CABO DE DADOS FALCON III',
            'ADAPTADOR USB FALCON III',
            'ADAPTADOR VISOR FALCON III',
            'RF 7850M HH 001 MB (FALCON III MULTI-BANDA)',
            'MPR 9600 MP (FALCON II)',
            'CARREGADOR FALCON II',
            'PTT FALCON',
            'FONTE DE ALIMENTAÇÃO YAESU',
            'FONTE DE ALIMENTAÇÃO',
            'BASE VEICULAR FALCON III',
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'name' => $category,
                'show_compliance' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
