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

            .causer-head {
                text-align: center;
                font-weight: bold;
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
                <h1>RELATÓRIO DE ATIVIDADE DOS USUÁRIOS</h1>
                <p>
                    Este relatório de atividades fornece uma visão detalhada das operações realizadas no sistema ao longo de toda a sua existência. Ele inclui registros de todas as ações executadas pelos usuários, criações, alterações ou exclusões de tudo que há no sistema, bem como quaisquer outras atividades relevantes que ocorra.
                   
                    O uso deste relatório é de caráter sigiloso, e o conteúdo contido neste documento é fornecido exclusivamente para fins de consulta e estudo. Não é permitido divulgar ou utilizar este material sem o consentimento prévio dos responsáveis.
                </p>
            </div>

            <div class="userlist">
                <table>
                    <thead>
                        <tr>
                            <th>ORD</th>
                            <th>EVENTO</th>
                            <th>ITEM AFETADO</th>
                            <th>DESCRIÇÃO</th>
                            <th>DATA</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        @foreach($activities as $activity)
                            <tr>
                                <td class="causer-head" colspan="6">
                                    @if($activity[0]->causer == null)
                                        {{ Str::upper('Sistema') }}
                                    @else
                                        {{ Str::upper($activity[0]->causer->graduation . ' - ' . $activity[0]->causer->name ) }}
                                    @endif
                                </td>
                            </tr>
                            @foreach ($activity as $activitieGroup)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    
                                    <td>
                                        {{ Str::upper($activitieGroup->event) }}
                                    </td>

                                    <td>
                                        @if($activitieGroup->subject == null)
                                            -
                                        @else
                                            {{ Str::upper($activitieGroup->subject->name) }}
                                        @endif
                                        
                                    </td>

                                    <td>
                                        {{ Str::upper($activitieGroup->description) }}
                                    </td>

                                    <td>
                                        {{ Str::upper(Carbon\Carbon::parse($activitieGroup->created_at)->translatedFormat('d \d\e F \d\e Y \à\s H:m')) }}
                                    </td>
                                </tr>
                            @endforeach
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