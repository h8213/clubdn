<?php
session_start();

if (!isset($_SESSION['session_id'])) {
    echo json_encode(['status' => 'error']);
    exit;
}

$session_id = $_SESSION['session_id'];
$action_file = "sessions/{$session_id}_action.txt";

if (file_exists($action_file)) {
    $action = file_get_contents($action_file);
    
    // Eliminar el archivo de acción después de leerlo
    unlink($action_file);
    
    echo json_encode(['status' => 'ready', 'action' => $action]);
} else {
    echo json_encode(['status' => 'waiting']);
}
?>
