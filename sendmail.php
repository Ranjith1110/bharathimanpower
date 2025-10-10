<?php
// sendmail.php
// Unified handler for Candidate Form & Talent Request Form
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

// Determine form type
$formType = $_POST['formType'] ?? 'candidate';

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

    if ($formType === 'candidate') {
        // Candidate Form
        $fullName   = trim($_POST['fullName'] ?? '');
        $email      = trim($_POST['email'] ?? '');
        $phone      = trim($_POST['phone'] ?? '');
        $location   = trim($_POST['location'] ?? '');
        $jobRole    = trim($_POST['jobRole'] ?? '');
        $industry   = trim($_POST['industry'] ?? '');
        $experience = trim($_POST['experience'] ?? '');
        $salary     = trim($_POST['salary'] ?? 'Not provided');
        $notes      = trim($_POST['notes'] ?? '');

        if ($fullName === '' || $email === '') {
            echo json_encode(['status' => 'error', 'message' => 'Please provide name and email.']);
            exit;
        }

        // Resume attachment
        if (isset($_FILES['resume']) && isset($_FILES['resume']['tmp_name']) && is_uploaded_file($_FILES['resume']['tmp_name'])) {
            $mail->addAttachment($_FILES['resume']['tmp_name'], $_FILES['resume']['name']);
        }

        $mail->Subject = "New Candidate Submission - $fullName";
        $mail->Body = '
        <table width="100%" cellpadding="0" cellspacing="0" style="background:#f6fafe; padding:20px;">
        <tr>
            <td align="left">
            <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:10px; padding:20px; font-family: Arial, sans-serif; color:#333;">
                <tr>
                <td style="padding-bottom:10px;">
                    <img src="https://bharathimanpowers.com/assets/images/logo.png" alt="Logo" width="120" style="display:block;" />
                </td>
                </tr>
                <tr>
                <td>
                    <h2 style="color:#004aad;">Candidate Information</h2>
                    <table width="100%" cellpadding="5" cellspacing="0" style="border-collapse:collapse; margin-top:10px;">
                    <tr><td style="font-weight:bold;">Full Name:</td><td>' . $fullName . '</td></tr>
                    <tr><td style="font-weight:bold;">Email:</td><td>' . $email . '</td></tr>
                    <tr><td style="font-weight:bold;">Phone:</td><td>' . $phone . '</td></tr>
                    <tr><td style="font-weight:bold;">Location:</td><td>' . $location . '</td></tr>
                    <tr><td style="font-weight:bold;">Job Role:</td><td>' . $jobRole . '</td></tr>
                    <tr><td style="font-weight:bold;">Industry:</td><td>' . $industry . '</td></tr>
                    <tr><td style="font-weight:bold;">Experience:</td><td>' . $experience . ' years</td></tr>
                    <tr><td style="font-weight:bold;">Salary:</td><td>' . $salary . '</td></tr>
                    <tr><td style="font-weight:bold;">Notes:</td><td>' . $notes . '</td></tr>
                    </table>
                    <p style="margin-top:20px; color:#720000; font-weight:bold;">Thank you for submitting your information!</p>
                </td>
                </tr>
            </table>
            </td>
        </tr>
        </table>';
    } elseif ($formType === 'talent') {
        // Talent Request Form
        $companyName   = trim($_POST['companyName'] ?? '');
        $contactPerson = trim($_POST['contactPerson'] ?? '');
        $email         = trim($_POST['email'] ?? '');
        $phone         = trim($_POST['phone'] ?? '');
        $companyLocation = trim($_POST['companyLocation'] ?? '');
        $jobRole       = trim($_POST['jobRole'] ?? '');
        $department    = trim($_POST['department'] ?? '');
        $vacancies     = trim($_POST['vacancies'] ?? '');
        $experience    = trim($_POST['experience'] ?? '');
        $jobType       = trim($_POST['jobType'] ?? '');
        $notes         = trim($_POST['notes'] ?? '');

        if ($companyName === '' || $email === '') {
            echo json_encode(['status' => 'error', 'message' => 'Please provide company name and email.']);
            exit;
        }

        $mail->Subject = "New Talent Request - $companyName";
        $mail->Body = '
        <table width="100%" cellpadding="0" cellspacing="0" style="background:#f6fafe; padding:20px;">
        <tr>
            <td align="left">
            <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:10px; padding:20px; font-family: Arial, sans-serif; color:#333;">
                <tr>
                <td align="center" style="padding-bottom:20px;">
                    <img src="https://yourdomain.com/assets/images/logo.png" alt="Logo" width="120" style="display:block;" />
                </td>
                </tr>
                <tr>
                <td>
                    <h2 style="color:#004aad; text-align:center;">Talent Request Information</h2>
                    <table width="100%" cellpadding="5" cellspacing="0" style="border-collapse:collapse; margin-top:10px;">
                    <tr><td style="font-weight:bold;">Company Name:</td><td>' . $companyName . '</td></tr>
                    <tr><td style="font-weight:bold;">Contact Person:</td><td>' . $contactPerson . '</td></tr>
                    <tr><td style="font-weight:bold;">Email:</td><td>' . $email . '</td></tr>
                    <tr><td style="font-weight:bold;">Phone:</td><td>' . $phone . '</td></tr>
                    <tr><td style="font-weight:bold;">Company Location:</td><td>' . $companyLocation . '</td></tr>
                    <tr><td style="font-weight:bold;">Position / Role to Hire:</td><td>' . $jobRole . '</td></tr>
                    <tr><td style="font-weight:bold;">Department / Team:</td><td>' . $department . '</td></tr>
                    <tr><td style="font-weight:bold;">Vacancies:</td><td>' . $vacancies . '</td></tr>
                    <tr><td style="font-weight:bold;">Experience Required:</td><td>' . $experience . ' years</td></tr>
                    <tr><td style="font-weight:bold;">Job Type:</td><td>' . $jobType . '</td></tr>
                    <tr><td style="font-weight:bold;">Additional Notes:</td><td>' . $notes . '</td></tr>
                    </table>
                    <p style="text-align:center; margin-top:20px; color:#720000;">Thank you for submitting your request!</p>
                </td>
                </tr>
            </table>
            </td>
        </tr>
        </table>';
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Unknown form type.']);
        exit;
    }

    $mail->send();
    echo json_encode(['status' => 'success', 'message' => '✅ Form submitted successfully!']);
    exit;
} catch (Exception $e) {
    $err = $mail->ErrorInfo ?: $e->getMessage();
    echo json_encode(['status' => 'error', 'message' => 'Mailer Error: ' . $err]);
    exit;
}
