<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Conformidade {{$config->squad . ' ' . $config->company}}</title>
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
                max-width: 1000px;
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
                margin: 10px 0;
            }

            .header-report-title p {
                text-align: left
            }

            .material-list {
                width: 100%;
                margin-bottom: 20px;
            }
            
            .user-image {
                width: 40px;
                height: 40px;
                border-radius: 50%;
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

            .pdf-date-info {
                display: flex;
                justify-content: center;
                align-content: center;
                margin-bottom: 40px;
                text-align: center;
            }

            .page-break {
                page-break-after: always;
            }
        </style>

        <div class="pdf-container">
            <div class="pdf-header">
                <div class="header-img-area">
                    <img class="header-image" src="{{ public_path('storage/panel-assets/eb-logo.png') }}" alt="Exército Brasileiro">
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
                <h1>CONFORMIDADE PELOTÃO RÁDIO CCPCR</h1>
            </div>

            <div class="material-list">
                <table>
                    <thead>
                        <tr>
                            <th>MATERIAL RÁDIO</th>
                            <th>PREVISTO</th>
                            <th>EM DESTINO</th>
                            <th>DISPONÍVEL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                            <tr>    
                                <td>
                                    {{ Str::upper($category->name) }}
                                </td>
                                
                                <td>
                                    @if(!empty($category->materials))
                                        {{ $category['material_count'] }}
                                    @else 
                                        -
                                    @endif
                                </td>

                                <td>
                                    {{ $category['outside_material'] }}
                                </td>

                                <td>
                                    {{ $category['available_material'] }}    
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if (!empty($maintenanceMaterials) || !empty($maintenanceMaterials))
                <div class="page-break"></div>

                <div class="header-report-title">
                    <h1>DISTRIBUIÇÃO DE EQUIPAMENTO RÁDIO EM DESTINO</h1>
                </div>

                <div class="material-list">
                    <table>
                        <thead>
                            <tr>
                                <th>MATERIAL</th>
                                <th>DESTINO</th>
                                <th>QUANTIDADE</th>
                                <th>OM</th>
                            </tr>
                        </thead>

                        <tbody>
                            @if (!empty($maintenanceMaterials))
                                @foreach($maintenanceMaterials as $index => $maintenance)
                                    <tr>    
                                        <td>
                                            {{ Str::upper($maintenance['material']->name) }}
                                        </td>
                                        
                                        @if ($index == 0)
                                            <td rowspan="{{ count($maintenanceMaterials) }}">
                                                MANUTENÇÃO
                                            </td>
                                        @endif

                                        <td>
                                            1
                                        </td>

                                        <td>
                                            {{ Str::upper($maintenance['maintenance']->destiny)}}
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            
                            @if (!empty($loansWithDetails))
                                @foreach($loansWithDetails as $name => $loan)
                                    <tr>    
                                        <td>
                                            {{ Str::upper($name) }}
                                        </td>
                                        

                                        <td>
                                            {{ Str::upper($loan['to']) }}
                                        </td>

                                        <td>
                                            {{ Str::upper($loan['count']) }}
                                        </td>

                                        <td>
                                            {{ Str::upper($loan['om']) }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                                
                        </tbody>
                    </table>
                </div>
            @endif

            <div class="pdf-date-info">
                <p>Quartel em Porto Alegre – RS, {{Carbon\Carbon::now()->translatedFormat('d \d\e F \d\e Y')}}</p>
            </div>
        </div>
    </body>
</html>