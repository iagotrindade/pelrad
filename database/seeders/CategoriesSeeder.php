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
            'CONJUNTO RÁDIO PRO 5150',
            'RÁDIO SET/DE 42 A 50 MHZ PRO 5100',
            'ESTAÇÃO REPETIDORA PRO/5100',
            'CONJUNTO RÁDIO PRO 5100',
            'NOTEBOOK INTEL MODELO PENTIUN',
            'UNIDADE INTERFACE DE COMUNICAÇÃO',
            'ESTAÇÃO REPETIDORA GTR 8000',
            'CONVERSOR DE CORRENTE CONTÍNUA',
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
            'FALCON III',
            'BATERIA FALCON III',
            'CARREGADOR FALCON III',
            'CABO DE DADOS FALCON III',
            'ADAPTADOR USB FALCON III',
            'FALCON II',
            'CARREGADOR FALCON II',
            'PTT FALCON',
            'ANTENA DIPOLO',
            'RADIO YAESU SISTEM 600',
            'FONTE DE ALIMENTAÇÃO YAESU',
            'RÁDIO IC-A23',
            'VERTEX 1700',
            'RÁDIO TADIRAN',
            'BATTERY STORAGE TADIRAN',
            'FONTE DE ALIMENTAÇÃO',
            'WALTIMETRO',
            'AMPLIFICADOR DE POTÊNCIA',
            'ESTANTE DE AÇO',
            'BASE VEICULAR FALCON III',
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'name' => $category,
                'show_compliance' => true,
            ]);
        }
    }
}
