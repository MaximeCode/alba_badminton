<?php
/* Template Name: calendrier */
get_header();

$linkCalendar = get_option('club_link_calendar');
?>

    <section>
        <?= display_titlePage() ?>

        <iframe src="<?= $linkCalendar ?>"
                class="w-full h-[90vh]">
        </iframe>
        <p class="text-right text-lg mt-6">Source : CODEP (site officiel :
            <a class="underline text-primary-blue" href="https://badminton28.fr/" title="Site officiel du CODEP">badminton.fr</a>)
        </p>
    </section>

<?php
get_footer();
