<?php

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

session_start();

// Inclure WordPress pour accéder aux fonctions
require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

$config = require_once $_SERVER['DOCUMENT_ROOT'] . '/config/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['contact_form']['success'] = false;
    // Récupération des données du formulaire même si elles sont vides
    $_SESSION['contact_form']['first_name'] = $_POST['first_name'];
    $_SESSION['contact_form']['name'] = $_POST['name'];
    $_SESSION['contact_form']['email'] = $_POST['email'];
    $_SESSION['contact_form']['object'] = $_POST['object'];
    $_SESSION['contact_form']['message'] = $_POST['message'];

    // Vérification du nonce pour s'assurer que la soumission vient du site
    if (!isset($_POST['contact_nonce']) || !wp_verify_nonce($_POST['contact_nonce'], 'contact_form_nonce')) {
        $_SESSION['contact_form']['error'] = 'Erreur de vérification du formulaire.';
    } else {
        if (empty($_POST['first_name']) || empty($_POST['name']) || empty($_POST['email']) || empty($_POST['object']) || empty($_POST['message'])) {
            $_SESSION['contact_form']['error'] = "Tous les champs sont obligatoires.<br>Merci de remplir entièrement le formulaire.";
        } else {

            // Récupération des données du formulaire
            $_SESSION['contact_form']['first_name'] = ucwords(sanitize_text_field($_POST['first_name']));
            $_SESSION['contact_form']['name'] = strtoupper(sanitize_text_field($_POST['name']));
            $_SESSION['contact_form']['email'] = sanitize_email($_POST['email']);
            $_SESSION['contact_form']['object'] = sanitize_text_field($_POST['object']);
            $_SESSION['contact_form']['message'] = sanitize_textarea_field($_POST['message']);

            // Envoi du mail
            $mail = new PHPMailer(true);

            try {
                // Configuration du serveur
                $mail->isSMTP();
                $mail->Host = $config['smtp']['host'];
                $mail->SMTPAuth = true;
                $mail->Username = $config['smtp']['username'];
                $mail->Password = $config['smtp']['password'];
                $mail->Port = $config['smtp']['port'];
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  // Activation de TLS
                $mail->CharSet = 'UTF-8';

                // Destinataires
                $mail->setFrom($config['smtp']['from_email'], $config['smtp']['from_name']);
                $mail->addAddress($config['smtp']['from_email'], $config['smtp']['from_name']);
                $mail->addReplyTo($_POST['email'], $_POST['name']);

                // Contenu
                $mail->isHTML(true);
                $mail->Subject = '[ALBA WEB] Nouveau message de ' . $_SESSION['contact_form']['first_name'] . ' ' . $_SESSION['contact_form']['name'];

                // Corps du message en HTML
                $mail->Body = "
            <h2>" . htmlspecialchars($_SESSION['contact_form']['object']) . "</h2>
            <p><strong>Prénom :</strong> " . $_SESSION['contact_form']['first_name'] . "</p>
            <p><strong>Nom :</strong> " . $_SESSION['contact_form']['name'] . "</p>
            <p><strong>Email :</strong> " . $_SESSION['contact_form']['email'] . "</p>
            <p><strong>Message :</strong><br>" . nl2br($_SESSION['contact_form']['message']) . "</p>
        ";

                // Version texte pour les clients mail qui ne supportent pas l'HTML
                $mail->AltBody = "
                    Objet : " . $_SESSION['contact_form']['object'] . "
                    Prénom : " . $_SESSION['contact_form']['first_name'] . "
                    Nom : " . strtoupper($_SESSION['contact_form']['name']) . "
                    Email : " . $_SESSION['contact_form']['email'] . "
                    Message : " . $_SESSION['contact_form']['message'] . "
                ";

                $isSend = $mail->send();
                if ($isSend) {
                    $_SESSION['contact_form']['success'] = true;
                    echo "$mail->Body\n\n";
                    echo 'Message envoyé avec succès';
                } else {
                    $_SESSION['contact_form']['error'] = 'Erreur lors de l\'envoi du mail.';
                }
            } catch (Exception $e) {
                echo "Le message n'a pas pu être envoyé. Erreur : {$mail->ErrorInfo}";
            }
        }
    }
} else {
    echo 'Méthode non autorisée';
}

// Redirection pour éviter la resoumission du formulaire
wp_redirect(get_permalink(134) . '#contact-form');
die();