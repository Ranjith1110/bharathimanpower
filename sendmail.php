<?php
// sendmail.php
// Unified handler for Candidate, Talent Request, and Contact Forms
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load PHPMailer
$autoload = __DIR__ . '/vendor/autoload.php';
if (!file_exists($autoload)) {
    echo json_encode(['status' => 'error', 'message' => 'PHPMailer not found. Run composer install.']);
    exit;
}
require $autoload;

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit;
}

// Determine form type - defaults to 'contact' if not specified
$formType = $_POST['formType'] ?? 'contact';

$mail = new PHPMailer(true);

try {
    // SMTP settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'hr@bharathimanpowers.com';
    $mail->Password   = 'bwff xkbu oqsp stte';      // App password
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    // From & To
    $mail->setFrom('hr@bharathimanpowers.com', 'Website Forms');
    $mail->addAddress('hr@bharathimanpowers.com');

    $mail->isHTML(true);    

    // --- NEW: Reusable Email Template Components ---
    $templateHeader = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            body { font-family: Arial, sans-serif; background-color: #f4f7f6; margin: 0; padding: 0; }
            .container { max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
            .header { background-color: #004aad; padding: 20px; text-align: center; }
            .header img { max-width: 150px; }
            .content { padding: 30px; color: #333333; line-height: 1.6; }
            .content h2 { color: #004aad; margin-top: 0; }
            .data-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            .data-table td { padding: 12px 0; border-bottom: 1px solid #eeeeee; }
            .data-table tr:last-child td { border-bottom: none; }
            .data-table .label { font-weight: bold; color: #555555; width: 40%; }
            .footer { background-color: #f4f7f6; padding: 20px; text-align: center; font-size: 12px; color: #888888; }
            .message-box { background-color: #f8f9fa; border-left: 4px solid #004aad; padding: 15px; margin-top: 20px; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <img src="https://bharathimanpowers.com/assets/images/logo.png" alt="Bharathi Manpowers Logo">
            </div>
            <div class="content">';

    $templateFooter = '
            </div>
            <div class="footer">
                This is an automated notification from your website.
            </div>
        </div>
    </body>
    </html>';


    if ($formType === 'candidate') {
        // Candidate Form
        $fullName   = htmlspecialchars(trim($_POST['fullName'] ?? ''));
        $email      = htmlspecialchars(trim($_POST['email'] ?? ''));
        $phone      = htmlspecialchars(trim($_POST['phone'] ?? ''));
        $location   = htmlspecialchars(trim($_POST['location'] ?? ''));
        $jobRole    = htmlspecialchars(trim($_POST['jobRole'] ?? ''));
        $industry   = htmlspecialchars(trim($_POST['industry'] ?? ''));
        $experience = htmlspecialchars(trim($_POST['experience'] ?? ''));
        $salary     = htmlspecialchars(trim($_POST['salary'] ?? 'Not provided'));
        $notes      = htmlspecialchars(trim($_POST['notes'] ?? ''));

        if (empty($fullName) || empty($email)) {
            echo json_encode(['status' => 'error', 'message' => 'Please provide name and email.']);
            exit;
        }

        if (isset($_FILES['resume']) && $_FILES['resume']['error'] == UPLOAD_ERR_OK) {
            $mail->addAttachment($_FILES['resume']['tmp_name'], $_FILES['resume']['name']);
        }

        $mail->Subject = "New Candidate Submission: " . $fullName;
        $mail->Body = $templateHeader . '
                <h2>New Candidate Submission</h2>
                <p>A new candidate has submitted their details through the website. The information is provided below:</p>
                <table class="data-table">
                    <tr><td class="label">Full Name:</td><td>' . $fullName . '</td></tr>
                    <tr><td class="label">Email:</td><td>' . $email . '</td></tr>
                    <tr><td class="label">Phone:</td><td>' . $phone . '</td></tr>
                    <tr><td class="label">Location:</td><td>' . $location . '</td></tr>
                    <tr><td class="label">Applied Job Role:</td><td>' . $jobRole . '</td></tr>
                    <tr><td class="label">Industry:</td><td>' . $industry . '</td></tr>
                    <tr><td class="label">Total Experience:</td><td>' . $experience . ' years</td></tr>
                    <tr><td class="label">Expected Salary:</td><td>' . $salary . '</td></tr>
                    <tr><td class="label">Additional Notes:</td><td>' . nl2br($notes) . '</td></tr>
                </table>
        ' . $templateFooter;
    } elseif ($formType === 'talent') {
        // Talent Request Form
        $companyName   = htmlspecialchars(trim($_POST['companyName'] ?? ''));
        $contactPerson = htmlspecialchars(trim($_POST['contactPerson'] ?? ''));
        $email         = htmlspecialchars(trim($_POST['email'] ?? ''));
        $phone         = htmlspecialchars(trim($_POST['phone'] ?? ''));
        $companyLocation = htmlspecialchars(trim($_POST['companyLocation'] ?? ''));
        $jobRole       = htmlspecialchars(trim($_POST['jobRole'] ?? ''));
        $department    = htmlspecialchars(trim($_POST['department'] ?? ''));
        $vacancies     = htmlspecialchars(trim($_POST['vacancies'] ?? ''));
        $experience    = htmlspecialchars(trim($_POST['experience'] ?? ''));
        $jobType       = htmlspecialchars(trim($_POST['jobType'] ?? ''));
        $notes         = htmlspecialchars(trim($_POST['notes'] ?? ''));

        if (empty($companyName) || empty($email)) {
            echo json_encode(['status' => 'error', 'message' => 'Please provide company name and email.']);
            exit;
        }

        $mail->Subject = "New Talent Request from " . $companyName;
        $mail->Body = $templateHeader . '
                <h2>New Talent Request</h2>
                <p>A new talent request has been submitted by <strong>' . $companyName . '</strong>. The details are below:</p>
                <table class="data-table">
                    <tr><td class="label">Company Name:</td><td>' . $companyName . '</td></tr>
                    <tr><td class="label">Contact Person:</td><td>' . $contactPerson . '</td></tr>
                    <tr><td class="label">Email:</td><td>' . $email . '</td></tr>
                    <tr><td class="label">Phone:</td><td>' . $phone . '</td></tr>
                    <tr><td class="label">Company Location:</td><td>' . $companyLocation . '</td></tr>
                    <tr><td class="label">Role to Hire:</td><td>' . $jobRole . '</td></tr>
                    <tr><td class="label">Department:</td><td>' . $department . '</td></tr>
                    <tr><td class="label">No. of Vacancies:</td><td>' . $vacancies . '</td></tr>
                    <tr><td class="label">Experience Required:</td><td>' . $experience . ' years</td></tr>
                    <tr><td class="label">Job Type:</td><td>' . $jobType . '</td></tr>
                    <tr><td class="label">Additional Notes:</td><td>' . nl2br($notes) . '</td></tr>
                </table>
        ' . $templateFooter;
    } elseif ($formType === 'contact') {
        // Simple Contact Form
        $message = htmlspecialchars(trim($_POST['message'] ?? ''));

        if (empty($message)) {
            echo json_encode(['status' => 'error', 'message' => 'Please enter a message.']);
            exit;
        }

        $mail->Subject = 'New Message from Website Contact Form';
        $mail->Body = $templateHeader . '
                <h2>New Contact Message</h2>
                <p>You have received a new message from the "Drop us a line!" form on your website:</p>
                <div class="message-box">
                    <p>' . nl2br($message) . '</p>
                </div>
        ' . $templateFooter;
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Unknown form type.']);
        exit;
    }

    $mail->send();
    echo json_encode(['status' => 'success', 'message' => '✅ Your message has been sent successfully!']);
    exit;
} catch (Exception $e) {
    $err = $mail->ErrorInfo ?: $e->getMessage();
    echo json_encode(['status' => 'error', 'message' => 'Mailer Error: ' . $err]);
    exit;
}
