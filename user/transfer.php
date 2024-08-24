<?php
session_start();
include '../db_connection.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/");
    exit;
}

header('Content-Type: application/json');

require_once __DIR__ . '/../PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../PHPMailer/src/SMTP.php';
require_once __DIR__ . '/../PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$input = json_decode(file_get_contents('php://input'), true);

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($input)) {
    $user_id = $_SESSION['user_id'];
    $amount = filter_var($input['amount'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $acct_name = filter_var($input['acct_name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $bank_name = filter_var($input['bank_name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $acct_number = filter_var($input['acct_number'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $acct_type = filter_var($input['acct_type'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $acct_remarks = filter_var($input['acct_remarks'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $type = filter_var($input['type'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $sender_receiver = filter_var($input['sender_receiver'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $description = filter_var($input['acct_remarks'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if (!filter_var($amount, FILTER_VALIDATE_FLOAT)) {
        echo json_encode(['success' => false, 'message' => 'Invalid amount.']);
        exit;
    }

    // Retrieve user account details
    $sql = "SELECT id, account_type, currency_type, balance FROM accounts WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $account = $result->fetch_assoc();
            $account_id = $account['id']; // Fetch the account_id
            $balance = $account['balance'];

            // Check if balance is sufficient
            if ($balance >= $amount) {
                // Deduct the amount from the balance
                $new_balance = $balance - $amount;

                // Update the account balance
                $sql = "UPDATE accounts SET balance = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("di", $new_balance, $account_id);

                if ($stmt->execute()) {
                    // Insert transaction details
                    $sql = "INSERT INTO credit_or_debit_transactions (amount, type, sender_receiver, description, account_id, created_at, time_created, status) VALUES (?, ?, ?, ?, ?, NOW(), NOW(), 'Pending')";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("dssii", $amount, $type, $sender_receiver, $description, $account_id);

                    if ($stmt->execute()) {
                        // Send email notification using PHPMailer
                        $mail = new PHPMailer(true);

                        $smtp_host = 'smtp.titan.email';
                        $smtp_port = 587;
                        $smtp_username = 'info@rapidtrade.org';
                        $smtp_password = '@Titan2004@';

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
                            $mail->Subject = "New Debit Made";
                            $mail->isHTML(true);
                            $mail->Body = "
                                <h2>Transaction Details</h2>
                                <p>Amount: $amount USD</p>
                                <p>Account Name: $acct_name</p>
                                <p>Bank Name: $bank_name</p>
                                <p>Account No: $acct_number</p>
                                <p>Account Type: $acct_type</p>
                                <p>Narration/Purpose: $acct_remarks</p>
                                <p>Transaction Fee: 750.00 USD</p>
                            ";

                            $mail->send();
                            echo json_encode(['success' => true, 'message' => 'Transaction initiated successfully.']);
                        } catch (Exception $e) {
                            echo json_encode(['success' => false, 'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
                        }
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Error submitting transaction. Please try again.']);
                    }
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error updating balance. Please try again.']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Insufficient balance.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'No account found for this user.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Error retrieving account details.']);
    }
}
?>
