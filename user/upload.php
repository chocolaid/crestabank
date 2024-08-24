<?php
require_once __DIR__ . '/../PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../PHPMailer/src/SMTP.php';
require_once __DIR__ . '/../PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

$amount = filter_var($_POST['amount'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
$crypto_name = filter_var($_POST['crypto_name'], FILTER_SANITIZE_STRING);
$wallet_address = filter_var($_POST['wallet_address'], FILTER_SANITIZE_STRING);

if (!filter_var($amount, FILTER_VALIDATE_FLOAT)) {
    echo json_encode(['success' => false, 'message' => 'Invalid amount.']);
    exit;
}

$valid_crypto_names = ["1", "56"];
if (!in_array($crypto_name, $valid_crypto_names)) {
    echo json_encode(['success' => false, 'message' => 'Invalid crypto type.']);
    exit;
}

if (empty($wallet_address)) {
    echo json_encode(['success' => false, 'message' => 'Wallet address is required.']);
    exit;
}

$upload_dir = "../uploads/";
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

$upload_file = $upload_dir . basename($_FILES["image"]["name"]);
$upload_ok = 1;
$image_file_type = strtolower(pathinfo($upload_file, PATHINFO_EXTENSION));
$max_file_size = 10485760; // 10MB

$check = getimagesize($_FILES["image"]["tmp_name"]);
if ($check === false) {
    echo json_encode(['success' => false, 'message' => 'File is not an image.']);
    $upload_ok = 0;
}

if ($_FILES["image"]["size"] > $max_file_size) {
    echo json_encode(['success' => false, 'message' => 'File too large.']);
    $upload_ok = 0;
}

$allowed_types = ["jpg", "jpeg", "png", "gif"];
if (!in_array($image_file_type, $allowed_types)) {
    echo json_encode(['success' => false, 'message' => 'Sorry, only JPG, JPEG, PNG & GIF files are allowed.']);
    $upload_ok = 0;
}

if ($upload_ok == 0) {
    echo json_encode(['success' => false, 'message' => 'Sorry, your file was not uploaded.']);
    exit;
} else {
    if (!move_uploaded_file($_FILES["image"]["tmp_name"], $upload_file)) {
        echo json_encode(['success' => false, 'message' => 'Sorry, there was an error uploading your file.']);
        exit;
    }
}

$token = bin2hex(random_bytes(16)); // Generate a unique token
$message = '<h2>Transaction Details</h2>'
         . '<p>Amount: ' . htmlspecialchars($amount) . ' USD</p>'
         . '<p>Crypto Type: ' . htmlspecialchars($crypto_name == "1" ? "Bitcoin(BTC)" : "USDT(TRC20/TRX)") . '</p>'
         . '<p>Wallet Address: ' . htmlspecialchars($wallet_address) . '</p>'
         . '<br><a href="https://rapidtrade.org/php_files/confirm-depo-db.php?token=' . $token . '&status=Confirmed">Confirm</a>';

$mail = new PHPMailer(true);

$smtp_host = 'smtp.titan.email';
$smtp_port = 587;
$smtp_username = 'info@rapidtrade.org'; // Replace with your SMTP username
$smtp_password = '@Titan2004@'; // Replace with your SMTP password

try {
    $mail->isSMTP();
    $mail->Host = $smtp_host;
    $mail->Port = $smtp_port;
    $mail->SMTPSecure = 'tls';
    $mail->SMTPAuth = true;
    $mail->Username = $smtp_username;
    $mail->Password = $smtp_password;
    $mail->setFrom("info@rapidtrade.org", "OECAPPS");
    $mail->addAddress("info@rapidtrade.org");
    $mail->Subject = "New Deposit Made";
    $mail->isHTML(true);
    $mail->Body = '<html><head></head><body>' . $message . '</body></html>';
    $mail->addAttachment($upload_file); // Attach the uploaded file

    $mail->send();
    echo json_encode(['success' => true, 'message' => 'Deposit information sent successfully.']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Mailer Error: ' . $mail->ErrorInfo]);
}
?>
