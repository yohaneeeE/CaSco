<?php
// store_result.php
session_start();
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    http_response_code(400);
    echo json_encode(['status'=>'error','message'=>'No JSON received.']);
    exit;
}

// optionally validate structure
$_SESSION['careerResult'] = $input;
echo json_encode(['status'=>'ok']);
