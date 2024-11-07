<?php

// Inclure WordPress pour accéder aux fonctions
require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php' );

get_header();

//////////////////////// Traitement du formulaire de contact ////////////////////////

if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
	echo 'test POST OK';
	// Vérification du nonce pour s'assurer que la soumission vient du site
	if ( ! isset( $_POST['contact_nonce'] ) || ! wp_verify_nonce( $_POST['contact_nonce'], 'contact_form_nonce' ) ) {
		echo '
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
	<strong class="font-bold">Erreur de sécurité !</strong>
	<span class="block sm:inline">La soumission n\'a pas été validée.</span>
</div>';

	} else {
		echo 'test nonce OK';
		if ( isset( $_POST['first_name'] ) && isset( $_POST['name'] ) && isset( $_POST['email'] ) && isset( $_POST['object'] ) && isset( $_POST['message'] ) ) {
			echo 'test all datas OK';
			// Récupération des données du formulaire
			$first_name = sanitize_text_field( $_POST['first_name'] );
			$name       = strtoupper( sanitize_text_field( $_POST['name'] ) );
			$email      = sanitize_email( $_POST['email'] );
			$object     = sanitize_text_field( $_POST['object'] );
			$message    = sanitize_textarea_field( $_POST['message'] );

			// Envoi du mail
			$to      = 'maxbaudedu28@gmail.com';
			$subject = 'Nouveau message de ' . $first_name . ' ' . $name;
			$headers = array(
				'From'     => 'webmaster@alba.test',
				'Reply-To' => 'webmaster@alba.test',
				'X-Mailer' => 'PHP/' . phpversion(),
			);

			$body = "
<html>
<body>
<h2>Nouveau message de <strong>$first_name $name</strong></h2>
<p><strong>Email : </strong> $email</p>
<p><strong>Objet : </strong> $object</p>
<p><strong>Message : </strong> $message</p>
</body>
</html>
";
			echo "$body\n
			Mail envoyé à l'@ mail : $to";

			$mail = mail( $to, $subject, $body, $headers );

			if ( $mail ) {
				echo '
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
	<strong class="font-bold">Message envoyé avec succès !</strong>
	<span class="block sm:inline">Nous vous recontacterons dans les plus brefs délais.</span>
</div>';
			} else {
				echo '
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
	<strong class="font-bold">Erreur lors de l\'envoi du message !</strong>
	<span class="block sm:inline">Veuillez réessayer ultérieurement.</span>
</div>';
			}
// Redirection pour éviter la resoumission du formulaire
			wp_redirect( get_permalink( 134 ) );
			exit;
		}
	}
}

get_footer();