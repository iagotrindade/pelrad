<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Relatório de configurações Pelotão Rádio CCPCR</title>
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

            .user-list {
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
        </style>

        <div class="pdf-container">
            <div class="pdf-header">
                <div class="header-img-area">
                    <img class="header-image" src="{{url("storage/panel-assets/eb-logo.png")}}" alt="Exército Brasileiro">
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
                <h1>RELATÓRIO DE CONFIGURAÇÕES</h1>
                <p>*As configurações abaixo são utilizadas para criar as cautelas.</p>
            </div>

            <div class="user-list">
                <table>
                    <thead>
                        <tr>
                            <th>ORD</th>
                            <th>OM</th>
                            <th>ABREVIAÇÃO DA OM</th>
                            <th>COMPANHIA</th>
                            <th>PELOTÃO</th>
                            <th>COMANDANTE DE PELOTÃO</th>
                            <th>COMANDANTE DE COMPANHIA</th>
                            <th>S4</th>
                            <th>ÚLTIMA ALTERAÇÃO EM</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($configuration as $config)
                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td>
                                    {{ Str::upper($config->organization) }}
                                </td>
                                
                                <td>
                                    {{ Str::upper($config->organization_slug) }}
                                </td>

                                <td>
                                    {{ Str::upper($config->company) }}
                                </td>

                                <td>
                                    {{ Str::upper($config->squad) }}
                                </td>   

                                <td>
                                    {{ Str::upper($config->squad_leader) }}
                                </td>

                                <td>
                                    {{ Str::upper($config->company_leader) }}
                                </td>

                                <td>
                                    {{ Str::upper($config->organization_s4) }}
                                </td>

                                <td>
                                    {{ Str::upper(Carbon\Carbon::parse($config->updated_at)->translatedFormat('d \d\e F \d\e Y \à\s H:m')) }}
                                </td>
                            </tr>
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