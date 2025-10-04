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
    $mail->Username   = 'ranjithram878@gmail.com';  // SMTP username
    $mail->Password   = 'efbi okbd pxtp zexw';      // App password
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    // From & To
    $mail->setFrom('ranjithram878@gmail.com', 'Website Forms');
    $mail->addAddress('ranjithram878@gmail.com');

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
        $mail->Body = "
            <h2>Candidate Information</h2>
            <p><b>Full Name:</b> $fullName</p>
            <p><b>Email:</b> $email</p>
            <p><b>Phone:</b> $phone</p>
            <p><b>Location:</b> $location</p>
            <p><b>Job Role:</b> $jobRole</p>
            <p><b>Industry:</b> $industry</p>
            <p><b>Experience:</b> $experience years</p>
            <p><b>Expected Salary:</b> $salary</p>
            <p><b>Notes:</b> $notes</p>
        ";
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
        $mail->Body = "
            <h2>Talent Request Information</h2>
            <p><b>Company Name:</b> $companyName</p>
            <p><b>Contact Person:</b> $contactPerson</p>
            <p><b>Email:</b> $email</p>
            <p><b>Phone:</b> $phone</p>
            <p><b>Company Location:</b> $companyLocation</p>
            <p><b>Position / Role to Hire:</b> $jobRole</p>
            <p><b>Department / Team:</b> $department</p>
            <p><b>Vacancies:</b> $vacancies</p>
            <p><b>Experience Required:</b> $experience years</p>
            <p><b>Job Type:</b> $jobType</p>
            <p><b>Additional Notes:</b> $notes</p>
        ";
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
