<!DOCTYPE html>
<html lang="pt_BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Relatório de cautelas {{$config->squad . ' ' . $config->company}}</title>
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
                <h1>RELATÓRIO DE CAUTELAS</h1>
                <p>
                    Este relatório de cautela de materiais do sistema fornece uma visão detalhada dos materiais cautelados, abrangendo todas as transações realizadas. Ele inclui dados essenciais para a gestão e rastreamento dos materiais durante o período de empréstimo.

                    O objetivo deste relatório é proporcionar uma visão clara e atualizada das cautelas, facilitando a gestão eficiente, rastreamento preciso e análise das transações de material.
                </p>
            </div>

            <div class="user-list">
                <table>
                    <thead>
                        <tr>
                            <th>ORD</th>
                            <th>OM</th>
                            <th>NOME</th>
                            <th>CONTATO</th>
                            <th>STATUS</th>
                            <th>RETORNO</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($loans as $loan)
                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td>
                                    {{ Str::upper($loan->to) }}
                                </td>
                                
                                <td>
                                    {{ Str::upper($loan->gradutaion. ' ' . $loan->name) }}
                                </td>

                                <td>
                                    {{ Str::upper($loan->contact) }}
                                </td>

                                <td>
                                    {{ Str::upper($loan->status) }}
                                </td>

                                <td>
                                    {{ Str::upper(Carbon\Carbon::parse($loan->return_date)->translatedFormat('d \d\e F \d\e Y')) }}
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