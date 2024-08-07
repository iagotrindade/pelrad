<!DOCTYPE html>
<html lang="pt_BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Diagrama Rede Rádio</title>
    </head>
    
    <body>
        <style>
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

            .page-break {
                page-break-after: always;
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
                margin-bottom: 20px;
            }

            .pdf-header h1 {
                font-size: 18px;
                text-align: center;
                margin-bottom: 10px;
            }

            .pdf-header p {
                font-size: 14px;
                text-align: center;
                margin-bottom: 10px;
            }

            .drr-area {
                position: relative;
                width: 100%;
                height: 100vh;
                border: 1px solid #000;
                margin-bottom: 20px;
            }

            .center {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                text-align: center;
            }
        </style>

        <div class="pdf-container">
            <div class="pdf-header">
                <h1>Diagrama Rede Rádio</h1>
                <p>Nome: {{ $drrData['name'] }}</p>
            </div>
                
            <div class="drr-area">
                <div class="f3e">F3E</div>
                <div class="manitu">Manitu</div>
                <div class="s3">S3</div>

                @foreach($drrData['stations_data'] as $index => $station)
                    <div class="station" id="posto{{ $index + 1 }}">
                        <div class="station-name">{{ $station['station_name'] }}</div>
                        <div class="station-slug">{{ $station['station_slug'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </body>
</html>