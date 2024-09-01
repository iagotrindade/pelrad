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
                margin-bottom: 80px;
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

            .drrs-display {
                display: flex;
                justify-content: center;
                align-items: center;
                flex-wrap: wrap;
            }

            .drr-area {
                display: flex;
                flex-direction: column;
                align-items: center;
                width: 250px;
                border-radius: 5px;
                border: 1px solid #ccc;
                margin: 30px 50px;
            }

            .station-row {
                display: flex;
                justify-content: space-between;
                width: 290px;
            }

            .pdr-station {
                display: flex;
                justify-content: center;
                width: 100%;
            }
            
            .pdr-station-names {
                border: 1px solid #ccc;
                border-radius: 360px;
                width: 50px;
                height: 50px;
                text-align: center;
                padding: 10px;
                margin-top: -75px;
                margin-bottom: 20px;
            }

            .station {
                border: 1px solid #ccc;
                border-radius: 360px;
                width: 50px;
                height: 50px;
                text-align: center;
                padding: 10px;
                margin-bottom: 35px;
            }

            .first-station-row {
                margin-top: -25px;
            }

            .last-station-row {
                margin-bottom: -20px;
            }

            .station-name, .station-slug {
                font-size: 10px;
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
                <p>Nome: NOME DA REDE</p>
            </div>
             
            <div class="drr-area">
                <div class="pdr-station">
                    <div class="pdr-station-names">
                        <p class="station-name">POSTO 1</p>
                        <p class="station-slug">SLUG DO POSTO</p>
                    </div>
                </div>

                <div class="station-row first-station-row">
                    <div class="station">
                        <p class="station-name">POSTO 2</p>
                        <p class="station-slug">SLUG DO POSTO</p>
                    </div>

                    <div class="station">
                        <p class="station-name">POSTO 3</p>
                        <p class="station-slug">SLUG DO POSTO</p>
                    </div>
                </div>

                <div class="station-row">
                    <div class="station">
                        <p class="station-name">POSTO 2</p>
                        <p class="station-slug">SLUG DO POSTO</p>
                    </div>

                    <div class="station">
                        <p class="station-name">POSTO 3</p>
                        <p class="station-slug">SLUG DO POSTO</p>
                    </div>
                </div>

                <div class="station-row ">
                    <div class="station-area last-station-row">
                        <div class="station">
                            <p class="station-name">POSTO 2</p>
                            
                        </div>

                        
                    </div>
                        

                    <div class="station last-station-row">
                        <p class="station-name">POSTO 3</p>
                        <p class="station-slug">SLUG DO POSTO</p>
                    </div>
                </div>
            </div>
        </div>  
    </body>
</html>