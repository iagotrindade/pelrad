<!DOCTYPE html>
<html lang="pt_BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Cautela de Material {{$data['to']}}</title>
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

            .header-personal-data {
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                margin-bottom: 20px;
                text-align: center
            }

            .header-personal-data p {
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

            .pdf-devolution-info {
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: flex-start;
                margin-bottom: 20px;
            }

            .pdf-date-info {
                display: flex;
                justify-content: center;
                align-content: center;
                margin-bottom: 40px;
                text-align: center;
            }

            .pdf-subscriptions, .supported-subscription, .squad-leader-subscription, .company-leader-subscription, .om-s4-subscription {
                display: flex;
                flex-direction: column;
                justify-content: flex-start;
                align-items: center;
                width: 100%;
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

            <div class="header-personal-data">
                <h1>Cautela de Material da {{$config->company}}</h1>
                <p>1 .Declaro para os fins legais que eu, {{$data['name']}}, do {{$data['to']}}, recebi do Aux do {{$config->squad}} da {{$config->company}}, do {{$config->organization_slug}} o material abaixo relacionado:</p>
            </div>

            <div class="pdf-table">
                <table>
                    <thead>
                        <tr>
                            <th>Ord</th>
                            <th>Descrição</th>
                            <th>Qnt</th>
                            <th>Nr. Série</th>
                            <th>Obs</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data['material_group'] as $key => $group)
                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td>
                                    {{ $group['groupName'] }} 
                                    @if ($group['groupComponents']->isNotEmpty())
                                        (
                                            @foreach ($group['groupComponents'] as $component)
                                                {{ $component->name }}
                                            @endforeach
                                        )
                                    @endif
                                </td>

                                <td>{{ $group['qtd'] }}</td>
                                
                                <td>
                                    @foreach ($group['materials'] as $index => $groupMaterial)
                                        {{$groupMaterial->serial_number}}{{ $index < count($group['materials']) - 1 ? ' - ' : '' }}
                                    @endforeach
                                </td>

                                <td>{{ $group['obs'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pdf-devolution-info">
                <br>
                <p>2. Data prevista para devolução do material: {{Carbon\Carbon::createFromFormat('Y-m-d', $data['return_date'])->translatedFormat('d \d\e F \d\e Y')}}</p>
                <p>3. O material deverá ser entregue manutenido e em horário de expediente.</p>
                <p>4. O material não deve ser recautelado para outras OM’s.</p>
            </div>

            <div class="pdf-date-info">
                <p>Quartel em Porto Alegre – RS, {{Carbon\Carbon::now()->translatedFormat('d \d\e F \d\e Y')}}</p>
            </div>

            <div class="pdf-subscriptions">
                <div class="supported-subscription">
                    <p>______________________________________________</p>
                    <p>{{$data['name']}} – {{$data['graduation']}}</p>
                    <p>Identidade: {{$data['idt']}}</p>
                    <p>Telefone: {{$data['contact']}}</p>
                </div>

                <div class="squad-leader-subscription">
                    <p>______________________________________________</p>
                    <p>{{$config->squad_leader}}</p>
                    <p>Comandante de Pelotão</p>
                </div>

                <div class="company-leader-subscription">
                    <p>______________________________________________</p>
                    <p>{{$config->company_leader}}</p>
                    <p>Comandante de Companhia</p>
                </div>

                <div class="om-s4-subscription">
                    <p>______________________________________________</p>
                    <p>{{$config->organization_s4}}</p>
                    <p>Chefe da 4ª Sessão</p>
                </div>
            </div>
        </div>
    </body>

</html>
