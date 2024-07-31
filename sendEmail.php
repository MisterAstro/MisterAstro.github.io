<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

// Initialiser les variables
$message = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier que les champs requis sont définis et non vides
    if (isset($_POST['contactName']) && isset($_POST['contactEmail']) && isset($_POST['contactSubject']) && isset($_POST['contactMessage'])) {
        $contactName = trim($_POST['contactName']);
        $contactEmail = trim($_POST['contactEmail']);
        $contactSubject = trim($_POST['contactSubject']);
        $contactMessage = trim($_POST['contactMessage']);

        // Validation simple
        if (empty($contactName) || empty($contactEmail) || empty($contactMessage)) {
            $error = 'Please fill in all required fields.';
        } elseif (!filter_var($contactEmail, FILTER_VALIDATE_EMAIL)) {
            $error = 'Please enter a valid email address.';
        } elseif (strlen($contactMessage) < 15) {
            $error = 'Please enter your message. It should have at least 15 characters.';
        } else {
            // Configurer PHPMailer
            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'tomtom.brabant@gmail.com'; // Remplacez par votre adresse e-mail
                $mail->Password   = 'yeml opxg tvlg wajk'; // Remplacez par votre mot de passe d'application
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                
                $mail->addAddress('tomtom.brabant@gmail.com', 'Thomas Brabant');

                $mail->isHTML(true);
                $mail->Subject = htmlspecialchars($contactSubject);
                $mail->Body    = "Name: " . htmlspecialchars($contactName) . "<br>" .
                                 "Email: " . htmlspecialchars($contactEmail) . "<br>" .
                                 "Message: <br>" . nl2br(htmlspecialchars($contactMessage));

                $mail->send();
                $message = 'Your message was sent, thank you!';
            } catch (Exception $e) {
                $error = 'There was a problem sending your message. Mailer Error: ' . $mail->ErrorInfo;
            }
        }
    } else {
        $error = 'Please fill in all required fields.';
    }
}

// Afficher le message
if (!empty($error)) {
    echo '<div id="message-warning">' . $error . '</div>';
} else {
    echo '<div id="message-success">' . $message . '</div>';
}
?>
