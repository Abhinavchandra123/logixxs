<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Verify reCAPTCHA response
    $recaptchaResponse = $_POST['g-recaptcha-response'];

    $secretKey = '6Lc-4MsnAAAAAAnj0afHoeSELJDIRAemBRcSUiVG'; // Replace with your actual reCAPTCHA secret key provided by Google

    // Make a POST request to the reCAPTCHA API for verification
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = array(
        'secret' => $secretKey,
        'response' => $recaptchaResponse
    );

    $options = array(
        'http' => array(
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data)
        )
    );

    $context = stream_context_create($options);
    $verify = file_get_contents($url, false, $context);
    $captchaSuccess = json_decode($verify)->success;

    // Check if reCAPTCHA verification was successful
    if ($captchaSuccess) {
        // Perform any necessary validation or data processing

        // Prepare email message
        $to = "info@instadesign.in";  // Replace with the recipient's email address
        $subject = "Mail from website instadesign.in";
        $message = "Name: $name\n";
        $message .= "Email: $email\nMessage: $message\n";

        // Send email
        $headers = "$email";  // Replace with the sender's email address or a valid domain email
        if (mail($to, $subject, $message, $headers)) {
            header('Location: contact.html?success=true');
            exit;
        } else {
            echo "Failed to send email.";
        }
    } else {
        echo "reCAPTCHA verification failed.";
    }
} else {
    // Redirect the user back to the form page if accessed directly
    header('Location: contact.html');
    exit;
}
?>