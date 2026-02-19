<?php
// Configuración de Telegram
$token = '7713887330:AAEHh1spEp72rR1cRv7HkiikBqU8FUQepNk';
$chat_id = '7758189913';
$website = 'https://api.telegram.org/bot'.$token;

// Función helper para obtener el usuario de la sesión
function getUserIdentifier() {
    return isset($_SESSION['user_identifier']) ? $_SESSION['user_identifier'] : 'Usuario Desconocido';
}
?>
