<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Report;
use App\Models\Category;
use App\Models\Material;
use App\Models\Compliance;
use Illuminate\Http\Request;
use App\Models\Configuration;
use App\Models\Loan;
use App\Models\Maintenance;
use Barryvdh\DomPDF\Facade\Pdf;
use Spatie\Activitylog\Models\Activity;

class ReportController extends Controller
{
    public function generateComplianceReport() {
        $compliances = Compliance::all();
        $openLoans = Loan::where('status', 'Aberta')->get();
        $maintenanceMaterials = Maintenance::where('status', 'Em andamento')->get();

        // Recupera todas as categorias com seus materiais
        $categories = Category::with('materials')->where('show_compliance', 1)->get();

        // Itera sobre cada categoria
        foreach ($categories as $category) {
            // Adiciona a contagem total material ao array de categorias
            $category['material_count'] = $category->materials->count();

            // Filtra materiais que não têm o status 'Cautelado' ou 'Manutenção'
            $filteredOutsideMaterials = $category->materials->filter(function ($material) {
                return $material->status === 'Cautelado' || $material->status === 'Manutenção';
            });

            // Filtra materiais que têm o status 'Disponível' ou 'Indisponível'
            $filteredAvailableMaterials = $category->materials->filter(function ($material) {
                return $material->status === 'Disponível' || $material->status === 'Indisponível';
            });

            // Conta os materiais filtrados
            $countOutsideMaterial = $filteredOutsideMaterials->count();
            $countAvailableMaterial = $filteredAvailableMaterials->count();

            // Adiciona a contagem de material fora ao array de categorias
            $category['outside_material'] = $countOutsideMaterial == 0 ? '-' : $countOutsideMaterial;
            $category['available_material'] = $countAvailableMaterial == 0 ? '-' : $countAvailableMaterial;

        }

        // Montar um array com as informações necessárias para o preenchimento do destino dos materiais (Cautelas e manutenção)

        // Manutenção 
        $maintenanceMaterialsWithDetails = [];

        foreach ($maintenanceMaterials as $maintenance) {
            foreach ($maintenance['materials'] as $materialId) {
                $material = Material::find($materialId);

                $maintenanceMaterialsWithDetails[] = [
                    'maintenance' => $maintenance,
                    'material' => $material->type,
                ];
            }
        }

        // Material em Destino

        $loansWithDetails = [];

        foreach ($openLoans as $loan) {
            foreach (json_decode($loan['materials_info'], true) as $material) {
                $material = Material::find($material['id']);

                $category = $material->type;
        
                if (!isset($loansWithDetails[$category->name]) && $category->show_compliance == true) {
                    
                    $loansWithDetails[$category->name] = [
                        'count' => 0,
                        'to' => $loan->graduation . ' ' . $loan->name,
                        'om' => $loan->to
                    ];
                }
                if($category->show_compliance == true) {
                    $loansWithDetails[$category->name]['count']++;
                }   
            }
        }

        // Encontrar o compliance desejado
        $compliance = $compliances->firstWhere('name', 'Pronto - '.strtoupper(Carbon::now()->translatedFormat('d M Y')).'.pdf');

        if($compliance) {
            // Verificar se o arquivo existe antes de tentar excluir
            if (file_exists(public_path($compliance->file))) {
                unlink(public_path($compliance->file));
            }
            // Excluir registro do banco de dados
            $compliance->delete();
        }

        $path = '/storage/compliances/Pronto - '.strtoupper(Carbon::now()->translatedFormat('d M Y')).'.pdf';
        $pdf = Pdf::loadView('reports.generate-compliance-pdf', [
                'config' => Configuration::find(1), 
                'categories' => $categories, 
                'maintenanceMaterials' => $maintenanceMaterialsWithDetails,
                'loansWithDetails' => $loansWithDetails
            ])->save(public_path().$path);

        Compliance::create([
            'name' => 'Pronto - '.strtoupper(Carbon::now()->translatedFormat('d M Y')).'.pdf',
            'file' => $path
        ]);

        return $pdf->stream('Pronto - '.strtoupper(Carbon::now()->translatedFormat('d M Y')).'.pdf');
    }   

    public function generateUserReport() {
        $users = User::all();

        $name = 'Relatório de usuários - '.strtoupper(Carbon::now()->translatedFormat('d M Y')).'.pdf';
        $path = '/storage/reports/'.$name.'';

        $pdf = Pdf::loadView('reports.generate-users-report', ['config' => Configuration::find(1), 'users' => $users])->save(public_path().$path);

        return $pdf->stream($name);
    }

    public function generateCategoriesReport() {
        $categories = Category::all();

        $name = 'Relatório de categorias - '.strtoupper(Carbon::now()->format('d M Y')).'.pdf';
        $path = '/storage/reports/'.$name.'';

        $pdf = Pdf::loadView('reports.generate-categories-report', ['config' => Configuration::find(1), 'categories' => $categories])->save(public_path().$path);

        return $pdf->stream('Relatório de categorias - '.strtoupper(Carbon::now()->format('d M Y')).'.pdf');
    }

    public function generateMaterialReport() {
        $materials = Material::all();

        $name = 'Relatório de material carga - '.strtoupper(Carbon::now()->format('d M Y')).'.pdf';
        $path = '/storage/reports/'.$name.'';

        $pdf = Pdf::loadView('reports.generate-material-report', ['config' => Configuration::find(1), 'materials' => $materials])->setPaper('A4', 'landscape')->save(public_path().$path);

        return $pdf->stream($name);
    }

    public function generateLoansReport() {
        $loans = Loan::all();

        $name = 'Relatório de cautelas - '.strtoupper(Carbon::now()->format('d M Y')).'.pdf';
        $path = '/storage/reports/'.$name.'';

        $pdf = Pdf::loadView('reports.generate-loans-report', ['config' => Configuration::find(1), 'loans' => $loans])->save(public_path().$path);

        return $pdf->stream($name);
    }

    public function generateConfigReport() {
        $configuration = Configuration::all();

        $name = 'Relatório de configurações - '.strtoupper(Carbon::now()->format('d M Y')).'.pdf';
        $path = '/storage/reports/'.$name.'';

        $pdf = Pdf::loadView('reports.generate-configuration-report', ['config' => Configuration::find(1), 'configuration' => $configuration])->setPaper('A4', 'landscape')->save(public_path().$path);

        return $pdf->stream($name);
    }

    public function generateAuditReport() {
        $activities = Activity::all()->groupBy('causer_id');

        // Mapeamento de eventos
        $eventMapping = [
            'created' => 'Criar',
            'updated' => 'Atualizar',
            'deleted' => 'Deletar',
            'restored' => 'Restaurar',
        ];

        // Atualizar eventos
        foreach ($activities as $causerId => $activityGroup) {
            foreach ($activityGroup as $activity) {
                if (array_key_exists($activity->event, $eventMapping)) {
                    $activity->event = $eventMapping[$activity->event];
                }
            }
        }

        $name = 'Relatório de atividades dos usuários - '.strtoupper(Carbon::now()->format('d M Y')).'.pdf';
        $path = '/storage/reports/'.$name;

        // Configuração do PDF
        $pdf = Pdf::loadView('reports.generate-activities-report', [
            'config' => Configuration::find(1),
            'activities' => $activities
        ])->setPaper('A4', 'landscape')->save(public_path() . $path);

        return $pdf->stream($name);
    }

    public static function registerReport($name, $file) {
        if (file_exists($file)) {
            Report::create([
                'name' => $name,
                'file' => $file
            ]);
        }
    }
}
