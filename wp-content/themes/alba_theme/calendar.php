<?php
/* Template Name: calendrier */
get_header();

//var_dump(the_title());
?>

    <section>
        <?= display_titlePage() ?>

        <iframe src="https://docs.google.com/spreadsheets/d/e/2PACX-1vSXDxaqPt2o4XB6CJeqO7ZI_1o_QOMVM6zt_oHiH0dgSYqyhvSXokMUTo_CQNLgdCP9GdAomkdNCiv_/pubhtml?gid=1556273338&amp;single=true&amp;widget=true&amp;headers=false"
                class="w-full h-[90vh]">
        </iframe>
        <p class="text-right text-lg mt-6">Source : CODEP (site officiel :
            <a class="underline text-primary-blue" href="https://badminton28.fr/" title="Site officiel du CODEP">badminton.fr</a>)
        </p>
    </section>

<?php
get_footer();
