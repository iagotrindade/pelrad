<!DOCTYPE html>
<html lang="pt_BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Relatório de atividade dos usuários Pelotão Rádio CCPCR</title>
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

            .causer-head {
                text-align: center;
                font-weight: bold;
                background-color: #f2f2f2;
            }
        </style>

        @for ($i = 0; $i < $drrData['drr_quantity']; $i++)
            <div class="pdf-container">
                <div class="header-report-title">
                    <h1>QUADRO REDE RÁDIO</h1>
                    <h1>{{Str::upper($drrData['name'])}}</h1>
                </div>

                <div class="userlist">
                    <table>
                        <thead>
                            <tr>
                                <th>POSTO</th>
                                <th>RESPONSÁVEL</th>
                                <th>TEL CONTATO</th>
                                <th>RADIOPERADOR</th>
                                <th>CANAIS</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            @foreach ($drrData['stations_data'] as $data)
                                <tr>
                                    <td>
                                        {{  Str::upper($data['station_name'])   }}
                                    </td>
                                                
                                    <td>
                                        {{  Str::upper($data['responsible'])    }}
                                    </td>

                                    <td>
                                        {{  Str::upper($data['phone'])  }}  
                                    </td>

                                    <td>
                                        {{  Str::upper($data['radop'])  }}
                                    </td>

                                    @if (   $loop->iteration == 1   )
                                        <td rowspan="{{count($drrData['stations_data'])}}">
                                            PRINCIPAL: {{  Str::upper($drrData['frequency'])  }}
                                            <br>
                                            RESERVA: {{  Str::upper($drrData['alternative_frequency'])  }}
                                        </td>
                                    @endif   
                                </tr>
                            @endforeach     
                        </tbody>
                    </table>
                </div>
            </div>

            <br>
        @endfor
    </body>
</html>