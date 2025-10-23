<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $name = htmlspecialchars(trim($_POST['name'] ?? ''));
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars(trim($_POST['message'] ?? ''));

    // Set your church email
    $to = "sopurupromise2@gmail.com";  // <-- CHANGE THIS TO YOUR CHURCH EMAIL
    $subject = "New Contact Message from Anglican Church Website";

    // Validation
    if (empty($name) || empty($email) || empty($message)) {
        echo "<script>alert('All fields are required.'); window.history.back();</script>";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email address.'); window.history.back();</script>";
        exit;
    }

    // Email body
    $body = "
    You have received a new message from the contact form on your website:

    Name: $name
    Email: $email

    Message:
    $message
    ";

    // Headers
    $headers = "From: Anglican Church Website <no-reply@epiphanychurch.org>\r\n";
    $headers .= "Reply-To: $email\r\n";

    // Attempt to send email
    if (mail($to, $subject, $body, $headers)) {
        echo "<script>alert('Thank you, $name! Your message has been sent successfully.'); window.location.href='index.html';</script>";
    } else {
        echo "<script>alert('Sorry, we could not send your message at this time. Please try again later.'); window.history.back();</script>";
    }
} else {
    // If accessed directly, redirect to homepage
    header("Location: index.html");
    exit;
}
?>
