</div>

<footer class="bg-white shadow mt-16 font-personal selection:bg-white selection:text-primary-blue">
    <div class="w-full mx-auto flex flex-col items-center justify-center">
        <ul
                class="flex flex-wrap flex-col sm:flex-row items-center justify-center text-md font-medium w-full bg-primary-blue text-white py-1 space-x-0 sm:space-x-8 lg:space-x-20">
            <li>
                <a href="<?= get_permalink(3); ?>" class="underline md:no-underline hover:underline">
                    Politique de confidentialit&eacute;
                </a>
            </li>
            <li>
                <a href="#" class="underline md:no-underline hover:underline">Cookies</a>
            </li>
            <li>
                <a href="#" class="underline md:no-underline hover:underline">Mentions l&eacute;gales</a>
            </li>
        </ul>
        <span class="block text-sm text-gray-500 sm:text-center py-1">© <?= date('Y') ?>
            <a href="/" class="underline md:no-underline hover:underline">ALBA Badminton</a>.
            Tous droits r&eacute;serv&eacute;s</span>
    </div>
</footer>

<!-- Bouton de retour en haut -->
<a href="#" id="backToTop"
   class="fixed bottom-7 right-7 bg-secondary-blue text-white rounded-full p-5 shadow-box-dropdown transition duration-300 ease-in-out opacity-0 hover:opacity-100 transform hover:scale-110">
    <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 16">
        <path stroke="currentColor" stroke-width="2" d="M8 12V4m0 0L4 8m4-4l4 4"/>
    </svg>
</a>

<script>
    // Fonction pour afficher/masquer le bouton de retour en haut
    const backToTopButton = document.getElementById("backToTop");
    const pxFromTop = 300;
    window.onscroll = function () {
        if (document.body.scrollTop > pxFromTop || document.documentElement.scrollTop > pxFromTop) {
            backToTopButton.style.opacity = 1; // Afficher le bouton
        } else {
            backToTopButton.style.opacity = 0; // Masquer le bouton
        }
    };

    // Fonction pour faire défiler vers le haut
    backToTopButton.onclick = function (e) {
        e.preventDefault();
        window.scrollTo({top: 0, behavior: "smooth"}); // Scroll smooth vers le haut
    };

    // Fonction pour faire défiler vers la section des dernières actualités
    const goToStats = document.getElementById("goToStats");
    goToStats.addEventListener("click", function (e) {
        e.preventDefault();
        document.getElementById("stats").scrollIntoView({behavior: "smooth"});
    });

    // Fonction pour faire défiler vers les bureaux précédents
    function goToOldManagers(theLink, goThere) {
        // enlever l'évènement par défaut
        event.preventDefault();
        if (goThere === "2024-2025") {
            // appel la function backToTopButton
            backToTopButton.click();
            // sortir de la fonction
            return;
        }
        document.getElementById(goThere).scrollIntoView({behavior: "smooth"});
    }
</script>


<!-- Script flowbite généré dans functions.php -->
<?php wp_footer(); ?>
</body>
</html>