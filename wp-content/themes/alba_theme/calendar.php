<?php
/* Template Name: calendrier */
get_header();

$linkCalendar = get_option('club_link_calendar');
$season = get_option('club_calendar_year_settings');
?>

    <section>
        <?= display_titlePage() ?><?php if (!empty($season)) echo '<h3 class="text-2xl font-semibold mb-4 underline decoration-primary-blue"> Pour la saison ' . $season . '</h3>'; ?>

        <?php if (!empty($linkCalendar)) {
            echo '<iframe src="' . $linkCalendar . '" class="w-full h-[90vh]"></iframe>
                  <p class="text-right text-lg mt-6">Source : CODEP <!--(site officiel :
                      <a class="underline text-primary-blue" href="https://badminton28.fr/" title="Site officiel du CODEP">badminton.fr</a>)-->
                  </p>';
        } else {
            echo '<p class="text-lg">Le lien vers le calendrier n\'a pas encore été renseigné.</p>';
        } ?>

    </section>

<?php
get_footer();
