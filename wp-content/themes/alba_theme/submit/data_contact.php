<?php

session_start();

// Inclure WordPress pour accéder aux fonctions
require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

//get_header();

//////////////////////// Traitement du formulaire de contact ////////////////////////

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['contact_form']['success'] = false;
    // Récupération des données du formulaire même si elles sont vides
    $_SESSION['contact_form']['first_name'] = $_POST['first_name'];
    $_SESSION['contact_form']['name'] = $_POST['name'];
    $_SESSION['contact_form']['email'] = $_POST['email'];
    $_SESSION['contact_form']['object'] = $_POST['object'];
    $_SESSION['contact_form']['message'] = $_POST['message'];
    echo 'test POST OK';
    // Vérification du nonce pour s'assurer que la soumission vient du site
    if (!isset($_POST['contact_nonce']) || !wp_verify_nonce($_POST['contact_nonce'], 'contact_form_nonce')) {
        $_SESSION['contact_form']['error'] = 'Erreur de vérification du formulaire.';
    } else {
        echo 'test nonce OK';
        if (empty($_POST['first_name']) || empty($_POST['name']) || empty($_POST['email']) || empty($_POST['object']) || empty($_POST['message'])) {
            $_SESSION['contact_form']['error'] = "Tous les champs sont obligatoires.<br>Merci de remplir entièrement le formulaire.";
            $_SESSION['contact_form']['test_fn'] = $_POST['first_name'];
        } else {
            echo 'test all datas OK';
            // Récupération des données du formulaire
            $_SESSION['contact_form']['first_name'] = sanitize_text_field($_POST['first_name']);
            $_SESSION['contact_form']['name'] = strtoupper(sanitize_text_field($_POST['name']));
            $_SESSION['contact_form']['email'] = sanitize_email($_POST['email']);
            $_SESSION['contact_form']['object'] = sanitize_text_field($_POST['object']);
            $_SESSION['contact_form']['message'] = sanitize_textarea_field($_POST['message']);

            // Envoi du mail
            $to = 'exemple@gmail.com';
            $subject = 'Nouveau message de ' . $_SESSION['contact_form']['first_name'] . ' ' . $_SESSION['contact_form']['name'];
//            $headers = array(
//                'From' => "$_SESSION['contact_form']['email']",
//                'Reply-To' => "$to",
//                'X-Mailer' => 'PHP/' . phpversion(),
//            );

            $body = "
<h2>Nouveau message de <strong>{${$_SESSION['contact_form']['first_name']}} {${$_SESSION['contact_form']['name']}}</strong></h2>
<p><strong>Email : </strong> {${$_SESSION['contact_form']['email']}}</p>
<p><strong>Objet : </strong> {${$_SESSION['contact_form']['object']}}</p>
<p><strong>Message : </strong> {${$_SESSION['contact_form']['message']}}</p>
";

            $mail = mail($to, $subject, $body);

            if ($mail) {
                $_SESSION['contact_form']['success'] = true;
                echo "$body\n
			Mail envoyé à l'@ mail : $to";
            } else {
                $_SESSION['contact_form']['error'] = 'Erreur lors de l\'envoi du mail.';
            }
        }
        // Redirection pour éviter la resoumission du formulaire
        wp_redirect(get_permalink(134) . '#contact-form');
        die();
    }
}