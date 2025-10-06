<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Load PHPMailer

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $location = $_POST['location'];
    $jobRole = $_POST['jobRole'];
    $industry = $_POST['industry'];
    $experience = $_POST['experience'];
    $salary = $_POST['salary'] ?? "Not provided";
    $notes = $_POST['notes'];

    // File upload handling
    $resumePath = "";
    if (isset($_FILES['resume']) && $_FILES['resume']['error'] == 0) {
        $resumePath = $_FILES['resume']['tmp_name'];
        $resumeName = $_FILES['resume']['name'];
    }

    // Setup PHPMailer
    $mail = new PHPMailer(true);
    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';   // e.g., smtp.gmail.com
        $mail->SMTPAuth   = true;
        $mail->Username   = 'ranjithram878@gmail.com'; // Replace with your email
        $mail->Password   = 'efbi okbd pxtp zexw';    // Use App Password for Gmail
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // Sender & recipient
        $mail->setFrom('ranjithram878@gmail.com', 'Candidate Form');
        $mail->addAddress('ranjithram878@gmail.com'); // Where you want to receive info

        // Attach resume if uploaded
        if ($resumePath) {
            $mail->addAttachment($resumePath, $resumeName);
        }

        // Email content
        $mail->isHTML(true);
        $mail->Subject = "New Candidate Submission - $fullName";
        $mail->Body    = "
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

        // Send mail
        $mail->send();
        echo "<script>alert('Your application has been submitted successfully!'); window.location.href='index.html';</script>";
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
