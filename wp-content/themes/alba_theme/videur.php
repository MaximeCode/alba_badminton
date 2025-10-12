<?php
/* Template Name: Videur */
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🚫 Accès Refusé 🚫</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            position: relative;
        }

        /* Effet de brume animée */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 50% 50%, rgba(139, 0, 0, 0.1) 0%, transparent 70%);
            animation: pulse 4s ease-in-out infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 0.3;
            }

            50% {
                opacity: 0.6;
            }
        }

        .container {
            position: relative;
            z-index: 10;
            text-align: center;
            max-width: 900px;
            padding: 20px;
        }

        /* Image du videur */
        .bouncer-image {
            width: 400px;
            height: 400px;
            margin: 0 auto 30px;
            border-radius: 50%;
            border: 5px solid #8B0000;
            box-shadow: 0 0 50px rgba(139, 0, 0, 0.5), 0 0 100px rgba(139, 0, 0, 0.3);
            overflow: hidden;
            animation: glow 2s ease-in-out infinite;
            position: relative;
        }

        .bouncer-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        @keyframes glow {

            0%,
            100% {
                box-shadow: 0 0 50px rgba(139, 0, 0, 0.5), 0 0 100px rgba(139, 0, 0, 0.3);
            }

            50% {
                box-shadow: 0 0 70px rgba(139, 0, 0, 0.7), 0 0 120px rgba(139, 0, 0, 0.5);
            }
        }

        /* Bulle de dialogue */
        .speech-bubble {
            position: relative;
            background: #ffffff;
            border-radius: 20px;
            padding: 30px 40px;
            margin: 40px auto;
            max-width: 600px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
            border: 3px solid #8B0000;
        }

        .speech-bubble::before {
            content: '';
            position: absolute;
            top: -30px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 0;
            border-left: 25px solid transparent;
            border-right: 25px solid transparent;
            border-bottom: 30px solid #8B0000;
        }

        .speech-bubble::after {
            content: '';
            position: absolute;
            top: -24px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 0;
            border-left: 22px solid transparent;
            border-right: 22px solid transparent;
            border-bottom: 27px solid #ffffff;
        }

        .speech-text {
            font-size: 24px;
            color: #1a1a1a;
            font-weight: bold;
            line-height: 1.6;
            min-height: 120px;
        }

        .cursor {
            display: inline-block;
            width: 3px;
            height: 28px;
            background: #8B0000;
            margin-left: 3px;
            animation: blink 0.7s infinite;
        }

        @keyframes blink {

            0%,
            49% {
                opacity: 1;
            }

            50%,
            100% {
                opacity: 0;
            }
        }

        /* Bouton */
        .exit-button {
            background: linear-gradient(135deg, #8B0000 0%, #DC143C 100%);
            color: white;
            border: none;
            padding: 20px 50px;
            font-size: 22px;
            font-weight: bold;
            border-radius: 50px;
            cursor: pointer;
            box-shadow: 0 10px 30px rgba(139, 0, 0, 0.4);
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-top: 30px;
        }

        .exit-button:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(139, 0, 0, 0.6);
            background: linear-gradient(135deg, #DC143C 0%, #8B0000 100%);
        }

        .exit-button:active {
            transform: translateY(-2px);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .bouncer-image {
                width: 300px;
                height: 300px;
            }

            .speech-text {
                font-size: 18px;
            }

            .speech-bubble {
                padding: 20px 25px;
            }

            .exit-button {
                padding: 15px 35px;
                font-size: 18px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="bouncer-image">
            <img src="<?= get_template_directory_uri() ?>/assets/img/videur.png" alt="Videur">
        </div>

        <div class="speech-bubble">
            <div class="speech-text" id="text"></div>
        </div>

        <button class="exit-button" id="exitBtn" style="display: none;">
            🚪 Je pars...
        </button>
    </div>

    <script>
        const fullText = "STOP ! Tu cherches l'admin ? 🛑 Déjà oublie, c'est pas pour toi mon pote. Cette zone est strictement réservée aux VIP du club... et toi, t'es clairement pas sur la liste ! Allez ouste, dégage avant que je me fâche ! 👮‍♂️";
        const textElement = document.getElementById('text');
        const exitBtn = document.getElementById('exitBtn');
        let index = 0;

        // Son de machine à écrire
        function playTypeSound() {
            const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBTGH0fPTgjMGHm7A7+OZUR0MSJPZ7bZgGwU7kdXz0H0sBC1zwO3akDwICWKo5+ipUxIKRp/g8r5sIQUxh9Hz04IzBh5uwO/jmVEdDEiT2e22YBsFO5HV89B9LAQtc8Dt2pA8CAliqOfpqFMSCkaZ4A==');
            audio.volume = 0.1;
            audio.play().catch(() => {}); // Ignorer les erreurs si le son ne peut pas être joué
        }

        function typeWriter() {
            if (index < fullText.length) {
                const char = fullText.charAt(index);
                textElement.innerHTML += char;

                // Jouer le son pour les caractères (pas les espaces)
                if (char !== ' ') {
                    playTypeSound();
                }

                index++;

                // Vitesse variable : plus lent pour les emojis et la ponctuation
                const delay = (char === '!' || char === '?') ? 300 :
                    (fullText.charCodeAt(index - 1) > 127) ? 70 : 30;

                setTimeout(typeWriter, delay);
            } else {
                // Afficher le bouton une fois le texte terminé
                setTimeout(() => {
                    exitBtn.style.display = 'inline-block';
                    exitBtn.style.animation = 'fadeIn 0.5s ease-in';
                }, 500);
            }
        }

        // Animation de fade in pour le bouton
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
        `;
        document.head.appendChild(style);

        // Démarrer l'animation après un court délai
        setTimeout(typeWriter, 500);

        // Fermer l'onglet quand on clique sur le bouton
        exitBtn.addEventListener('click', function() {
            // Tenter de fermer l'onglet (fonctionne si l'onglet a été ouvert par JavaScript)
            window.close();

            // Si window.close() ne fonctionne pas (restrictions navigateur)
            // Rediriger vers une page vide ou le site principal
            setTimeout(() => {
                window.location.href = 'about:blank';
            }, 100);
        });

        // Easter egg : triple clic sur l'image
        let clickCount = 0;
        document.querySelector('.bouncer-image').addEventListener('click', function() {
            clickCount++;
            if (clickCount === 3) {
                textElement.innerHTML = "Ah ! T'es têtu toi ! Mais non, toujours pas d'accès ! 🚫 En revanche, si tu trouve une faille de sécurité dans le code source de mon app, n'hésite pas à me le signaler !<br>Je te souhaite d'être un White Hat jeune Padawan !";
                clickCount = 0;
            }
        });
    </script>
</body>

</html>