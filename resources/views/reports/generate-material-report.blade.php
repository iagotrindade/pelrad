<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Relatório de material carga {{$config->squad . ' ' . $config->company}}</title>
    </head>
    <body>
        <style>
            .page-break {
                page-break-after: always;
            }

            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                font-family: 'Times New Roman', Times, serif;
                font-size: 12px;
            }

            body {
                display: flex;
                justify-content: center;
            }

            .pdf-container {
                max-width: 1200px;
                padding: 20px
            }

            .pdf-header {
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                margin-bottom: 20px
            }

            .header-img-area {
                display: flex;
                justify-content: center;
                text-align: center;
            }

            .header-image {
                width: 80px;
                height: 80px;
                margin-bottom: 10px;
            }

            .header-text {
                text-align: center;
            }

            .header-report-title {
                font-size: 24px;
                text-align: center;
                margin-bottom: 10px;
            }

            .header-report-title p {
                text-align: left
            }

            .material-list {
                width: 100%;
                margin-bottom: 20px;
            }
            
            .material-image {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                display: inline-block;
            }

            .material-coponent-item {
                text-align: left
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-block: 10px
            }

            th, td {
                border: 1px solid #ddd;
                padding: 8px;
                text-align: center;
            }

            th {
                background-color: #f2f2f2;
                text-align: center;
            }

            .separator {
                background-color: #f2f2f2;
            }

            .pdf-date-info {
                display: flex;
                justify-content: center;
                align-content: center;
                margin-bottom: 40px;
                text-align: center;
            }
        </style>

        <div class="pdf-container">
            <div class="pdf-header">
                <div class="header-img-area">
                    <img class="header-image" src="{{ public_path('storage/panel_assets/eb-logo.png') }}" alt="Exército Brasileiro">
                </div>

                <p class="header-text">
                    MINISTÉRIO DA DEFESA<br>
                    EXÉRCITO BRASILEIRO<br>
                    COMANDO MILITAR DO SUL<br>
                    {{$config->organization}}<br>
                    (3ª Cia Trns/ 3º BE/ 1917)<br>
                </p>
            </div>

            <div class="header-report-title">
                <h1>RELATÓRIO DE MATERIAL CARGA</h1>
                <p>Este relatório de informações dos materiais do sistema oferece uma visão abrangente e detalhada dos diversos materiais cadastrados juntamento com seus componentes. Ele inclui dados essenciais que auxiliam na gestão, rastreamento e análise dos materiais disponíveis no sistema.</p>
            </div>

            <div class="material-list">
                <table>
                    <thead>
                        <tr>
                            <th>IMAGEM</th>
                            <th>NOME</th>
                            <th>NR FICHA</th>
                            <th>NR PATRIMÔNIO</th>
                            <th>VALOR DE PATRIMÔNIO</th>
                            <th>NR DE SÉRIE</th>
                            <th>DATA DE INCLUSÃO</th>
                            <th>STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($materials as $material)
                            <tr>
                                <td>
                                    @if (!empty($material->images[0]))
                                        <img class="material-image" src="{{ public_path('storage/'.$material->images[0].'') }}" alt="Imagem do Material">
                                    @else 
                                        <img class="material-image" src="{{ public_path('storage/panel_assets/material_placeholder.png') }}" alt="Imagem do Material">
                                    @endif
                                    

                                </td>
                                    
                                <td>
                                    {{ Str::upper($material->name) }}
                                </td>
                                    
                                <td>
                                    {{ Str::upper($material->record_number) }}
                                    
                                </td>

                                <td>
                                    {{ Str::upper($material->patrimony_number) }}
                                </td>

                                <td>
                                    {{ Str::upper($material->patrimony_value) }}
                                </td>

                                <td>
                                    {{ Str::upper($material->serial_number) }}
                                </td>

                                <td>
                                    {{ Str::upper(Carbon\Carbon::parse($material->inclusion_date)->translatedFormat('d \d\e F \d\e Y')) }}
                                </td>

                                <td>
                                    {{ Str::upper($material->status) }}
                                </td>
                            </tr>

                            @if ($material->type->components->isNotEmpty())             
                                <tr>
                                    <th colspan="3">COMPONENTES</th>
                                    <th colspan="3">NR SERIE</th>
                                    <th colspan="2">QUANTIDADE</th>
                                </tr>

                                @foreach($material->type->components as $component)
                                    <tr>
                                        <td colspan="3">{{ $component->name }}</td>
                                        <td colspan="3">{{ $component->serial_number }}</td>
                                        <td colspan="2">{{ $component->quantity }}</td>
                                    </tr>
                                @endforeach

                                <tr>
                                    <th colspan="8"></th>
                                </tr>
                            @else 
                                <tr>
                                    <th colspan="8"></th>
                                </tr>
                            @endif
                                
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pdf-date-info">
                <p>Quartel em Porto Alegre – RS, {{Carbon\Carbon::now()->translatedFormat('d \d\e F \d\e Y')}}</p>
            </div>
        </div>
    </body>
</html>