<?php
/* Template Name: contact */
session_start();
get_header();

$base_svg = 8;
$md_svg = 10;
$size_svg = "w-$base_svg h-$base_svg md:w-$md_svg md:h-$md_svg";

// animation lien contact
$animbase = "transform transition duration-100 ease-in-out";

// add value in form only if error
$first_name = isset($_SESSION['contact_form']['error']) ? $_SESSION['contact_form']['first_name'] : '';
$name = isset($_SESSION['contact_form']['error']) ? $_SESSION['contact_form']['name'] : '';
$email = isset($_SESSION['contact_form']['error']) ? $_SESSION['contact_form']['email'] : '';
$object = isset($_SESSION['contact_form']['error']) ? $_SESSION['contact_form']['object'] : '';
$message = isset($_SESSION['contact_form']['error']) ? $_SESSION['contact_form']['message'] : '';
?>

    <!--Appliquer un fond transparent et ajuster le texte lors de l'autocomplétion des champs de formulaire-->
    <style>
        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus,
        textarea:-webkit-autofill,
        textarea:-webkit-autofill:hover,
        textarea:-webkit-autofill:focus {
            -webkit-text-fill-color: #374151; /* Couleur du texte */
            -webkit-box-shadow: 0 0 0 1000px transparent inset; /* Fond transparent */
            box-shadow: 0 0 0 1000px transparent inset;
            transition: background-color 5000s ease-in-out 0s;
        }

    </style>

    <section>
        <h2 class="text-4xl font-bold mb-8"><?php the_title(); ?></h2>

        <h3 class="text-3xl font-bold mb-8">Une question ?<br>
            Une demande particulière destinée au membre du bureau ?</h3>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-y-12 justify-center items-center">
            <!--Contact-->
            <section class="w-full md:w-10/12 lg:max-xl:w-full xl:w-10/12 mx-auto bg-white px-5 py-3 rounded-2xl">
                <h4 class="text-2xl font-bold mb-12">Contactez-nous :</h4>
                <address class="flex flex-col not-italic gap-y-12 md:text-lg text-center md:text-left">
                    <!--Email-->
                    <p class="inline-flex items-center">
                        <svg class="<?= $size_svg ?> text-primary-blue basis-1/12 md:basis-2/12" aria-hidden="true"
                             xmlns="http://www.w3.org/2000/svg"
                             width="24"
                             height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 8v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8m18 0-8.029-4.46a2 2 0 0 0-1.942 0L3 8m18 0-9 6.5L3 8"/>
                        </svg>
                        <span class="font-bold basis-5/12 md:basis-4/12">Par mail :</span>
                        <a href="mailto:#"
                           class="text-lg md:text-xl italic basis-6/12 md:basis-6/12 hover:text-primary-blue hover:underline"
                           target="_blank">mailbureau@alba.fr</a>
                    </p>
                    <!--Téléphone-->
                    <p class="inline-flex items-center">
                        <svg class="<?= $size_svg ?> text-primary-blue basis-1/12 md:basis-2/12" aria-hidden="true"
                             xmlns="http://www.w3.org/2000/svg"
                             width="24"
                             height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M18.427 14.768 17.2 13.542a1.733 1.733 0 0 0-2.45 0l-.613.613a1.732 1.732 0 0 1-2.45 0l-1.838-1.84a1.735 1.735 0 0 1 0-2.452l.612-.613a1.735 1.735 0 0 0 0-2.452L9.237 5.572a1.6 1.6 0 0 0-2.45 0c-3.223 3.2-1.702 6.896 1.519 10.117 3.22 3.221 6.914 4.745 10.12 1.535a1.601 1.601 0 0 0 0-2.456Z"/>
                        </svg>
                        <span class="font-bold basis-5/12 md:basis-4/12">Par téléphone :</span>
                        <a href="tel:#" target="_blank"
                           class="text-lg md:text-xl italic basis-6/12 md:basis-6/12 hover:text-primary-blue hover:underline">06
                            12 34 56
                            78</a>
                    </p>
                    <!--Adresse Postale-->
                    <div class="inline-flex items-center">
                        <svg class="<?= $size_svg ?> text-primary-blue basis-1/12 md:basis-2/12" aria-hidden="true"
                             xmlns="http://www.w3.org/2000/svg"
                             width="24"
                             height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M11 16v-5.5A3.5 3.5 0 0 0 7.5 7m3.5 9H4v-5.5A3.5 3.5 0 0 1 7.5 7m3.5 9v4M7.5 7H14m0 0V4h2.5M14 7v3m-3.5 6H20v-6a3 3 0 0 0-3-3m-2 9v4m-8-6.5h1"/>
                        </svg>
                        <span class="font-bold basis-5/12 md:basis-4/12">Par courrier :</span>
                        <a href="https://maps.app.goo.gl/Y1Sjv7R973MWP3ga6" target="_blank"
                           class="text-lg md:text-xl italic basis-6/12 md:basis-6/12 text-balance hover:text-primary-blue hover:underline">
                            6 Rue Jean Boudrie, Lucé 28110</a>
                    </div>
                </address>
            </section>
            <!--G maps-->
            <section>
                <iframe
                        class="w-full h-[350px] md:h-[400px] lg:h-[450px] rounded-lg shadow-box-dropdown"
                        src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d661.6875319259813!2d1.452711!3d48.4421353!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e40bec6466a01b%3A0x29028dc9df4f57c3!2sAmicale%20de%20Luc%C3%A9%20Badminton!5e0!3m2!1sfr!2sfr!4v1730218070184!5m2!1sfr!2sfr"
                        style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
            </section>
        </div>

        <!--Button to contact form below-->
        <div class="grid place-items-center">
            <button type="button"
                    class="<?= $classBtn ?> my-16">
                <a href="#contact-form" class="inline-flex items-center" id="btnGoToForm">Ou via le formulaire
                    ci-dessous
                    <svg class="w-6 h-6 text-white ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                         width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 19V5m0 14-4-4m4 4 4-4"/>
                    </svg>
                </a>
            </button>
        </div>

        <!--After data send-->
        <?php if (isset($_SESSION['contact_form']['success'])): ?>
            <?php if ($_SESSION['contact_form']['success']): ?> <!--If SUCCESS-->
                <div id="alert-3"
                     class="w-1/2 m-auto flex items-start p-4 mb-4 text-green-800 rounded-lg bg-green-100 border border-green-400"
                     role="alert">
                    <svg class="flex-shrink-0 w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                         fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                    </svg>
                    <span class="sr-only">Info</span>
                    <div class="ms-3">
                        <span class="sr-only bg-green-900/50">Info</span>
                        <p><strong class="font-bold">Message envoyé !</strong></p>
                        <p class="block sm:inline">Votre message a bien été envoyé.</p>
                        <!--Récapitulatif des données envoyées-->
                        <div class="mt-4">
                            <p class="font-bold mb-3">Récapitulatif :</p>
                            <ul class="list-disc list-inside">
                                <li>Prénom : <?= $_SESSION['contact_form']['first_name'] ?></li>
                                <li>Nom : <?= $_SESSION['contact_form']['name'] ?></li>
                                <li>Email : <?= $_SESSION['contact_form']['email'] ?></li>
                                <li>Objet : <?= $_SESSION['contact_form']['object'] ?></li>
                                <li>Message : <?= $_SESSION['contact_form']['message'] ?></li>
                            </ul>
                        </div>
                    </div>
                    <button type="button"
                            class=" ms-auto -mx-1.5 -my-1.5 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-300 inline-flex items-center justify-center h-8 w-8"
                            data-dismiss-target="#alert-3" aria-label="Close">
                        <span class="sr-only">Close</span>
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                    </button>
                </div>
            <?php elseif (!$_SESSION['contact_form']['success']): ?> <!--If ERROR-->
                <div id="alert-2"
                     class="w-1/2 m-auto flex items-start p-4 mb-4 text-red-800 rounded-lg bg-red-100 border border-red-400"
                     role="alert">
                    <svg class="flex-shrink-0 w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                         fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                    </svg>
                    <span class="sr-only">Info</span>
                    <div class="ms-3">
                        <span class="sr-only bg-red-900/50">Info</span>
                        <p><strong class="font-bold">Erreur lors de l'envoi du message !</strong></p>
                        <p class="block sm:inline">Votre message n'a pas pu &ecirc;tre envoyé.</p>
                        <!--Récapitulatif des données envoyées-->
                        <div class="mt-4">
                            <p><?= $_SESSION['contact_form']['error'] ?></p>
                        </div>
                    </div>
                    <button type="button"
                            class=" ms-auto -mx-1.5 -my-1.5 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-300 inline-flex items-center justify-center h-8 w-8"
                            data-dismiss-target="#alert-3" aria-label="Close">
                        <span class="sr-only">Close</span>
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                    </button>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <!--Contact form-->
        <form id="contact-form" method="post" action="<?= get_template_directory_uri() . '/submit/data_contact.php' ?>"
              class="w-full lg:w-3/4 mx-auto font-personal bg-white rounded-2xl p-10">

            <?php wp_nonce_field('contact_form_nonce', 'contact_nonce'); ?>
            <div class="grid md:grid-cols-2 md:gap-10 lg:gap-16">
                <div class="flex flex-col justify-between">
                    <!--Nom Complet-->
                    <div class="grid">
                        <!--Prénom-->
                        <div class="relative z-0 w-full group">
                            <input type="text" name="first_name" id="floating_first_name"
                                   class="block py-2.5 px-0 w-full text-gray-900 bg-transparent border-0 border-b-2 border-primary-blue/50 appearance-none focus:outline-none focus:ring-0 focus:border-primary-blue peer"
                                   placeholder=" " value="<?= $first_name ?>"/>
                            <label for="floating_first_name"
                                   class="peer-focus:font-medium absolute duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                Votre Prénom
                            </label>
                        </div>
                        <!--Nom-->
                        <div class="relative z-0 w-full mt-10 group">
                            <input type="text" name="name" id="floating_name"
                                   class="block py-2.5 px-0 w-full text-gray-900 bg-transparent border-0 border-b-2 border-primary-blue/50 appearance-none focus:outline-none focus:ring-0 focus:border-primary-blue peer"
                                   placeholder=" " value="<?= $name ?>"/>
                            <label for="floating_name"
                                   class="peer-focus:font-medium absolute duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                Votre Nom
                            </label>
                        </div>
                    </div>
                    <!--Email-->
                    <div class="relative z-0 w-full mt-10 group">
                        <input type="email" name="email" id="floating_email"
                               class="block py-3 px-0 w-full text-gray-900 bg-transparent border-0 border-b-2 border-primary-blue/50 focus:outline-none focus:ring-0 focus:border-primary-blue peer"
                               placeholder=" " required value="<?= $email ?>"/>
                        <label for="floating_email"
                               class="peer-focus:font-medium absolute duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                            Votre Email
                        </label>
                    </div>
                    <p id="helper-text-explanation" class="md:hidden mt-2 text-sm text-gray-500 italic">Pour pouvoir
                        vous
                        recontacter
                        suite à votre message.</p>
                </div>
                <div>
                    <!--Objet-->
                    <div class="relative z-0 w-full mt-10 md:mt-0 group">
                        <input type="text" name="object" id="floating_object"
                               class="block py-2.5 px-0 w-full text-gray-900 bg-transparent border-0 border-b-2 border-primary-blue/50 appearance-none focus:outline-none focus:ring-0 focus:border-primary-blue peer"
                               placeholder=" " required value="<?= $object ?>"/>
                        <label for="floating_object"
                               class="peer-focus:font-medium absolute duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                            Objet du message
                        </label>
                    </div>
                    <!--Message-->
                    <div class="relative z-0 w-full mt-10 group">
                        <textarea name="message" id="floating_message"
                                  class="block py-2.5 px-0 w-full text-gray-900 bg-transparent border-0 border-b-2 border-primary-blue/50 appearance-none focus:outline-none focus:ring-0 focus:border-primary-blue peer"
                                  placeholder=" " required rows="5"><?= $message ?></textarea>
                        <label for="floating_message"
                               class="peer-focus:font-medium absolute duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                            Message
                        </label>
                    </div>
                </div>
            </div>
            <!--Message d'aide pour le mail-->
            <div class="md:grid md:grid-cols-2 md:gap-10 hidden">
                <p id="helper-text-explanation" class="mt-2 text-sm text-gray-500 italic">Pour pouvoir vous recontacter
                    suite à votre message.</p>
            </div>
            <!--Checkbox autorisation RGPD-->
            <div class="w-full lg:w-3/4 mx-auto flex flex-col md:flex-row justify-center items-center mt-10 cursor-pointer">
                <input id="checkbox-1" type="checkbox" required
                       class="w-5 h-5 text-primary-blue focus:ring-0 bg-gray-100 border-gray-300 rounded-full cursor-pointer">
                <label for="checkbox-1" class="mt-5 md:mt-0 md:ms-5 text-sm font-medium text-gray-700 cursor-pointer">
                    J'autorise ce site à utiliser mes données personnelles saisies ci-dessus pour répondre à ma demande
                    de
                    contact. Pour en savoir plus sur la gestion de vos données personnelles, veuillez consulter notre
                    <a href="<?= get_permalink(3) ?>" target="_blank"
                       class="text-primary-blue hover:underline text-balance">
                        politique de confidentialité
                    </a>.
                </label>
            </div>
            <!--Submit Btn-->
            <div class="grid place-content-center mt-10">
                <button type="submit"
                        class="text-base md:text-lg rounded-full text-white bg-primary-blue hover:bg-primary-blue/75 px-5 py-3 transform transition duration-100 ease-in-out">
                    Envoyer
                </button>
            </div>

        </form>
    </section>

    <script>
        const goToForm = document.getElementById("btnGoToForm");

        goToForm.addEventListener("click", function (e) {
            e.preventDefault();
            document.getElementById("contact-form").scrollIntoView({behavior: "smooth"});
        });
    </script>

<?php
get_footer();
unset($_SESSION['contact_form']);
