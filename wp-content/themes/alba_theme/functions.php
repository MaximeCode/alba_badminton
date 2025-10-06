<?php

$capability = 'edit_posts';

// Button used a lot of times
function primaryButton(int $idPage, string $text, ?string $paramName = null, ?string $paramValue = null, ?string $classSup = null): string
{
  global $alba_theme_variables;
  $class = $alba_theme_variables['classBtn'] . ($classSup ? " $classSup" : '');
  $url = get_permalink($idPage) . ($paramName && $paramValue ? "?$paramName=$paramValue" : '');
  return "<a class=\"$class\" href=\"$url\">$text</a>";
}

// Toutes les variables globales de mon thème sont déclarées ici
function alba_theme_variables(): array
{
  return [
    'h3' => "mb-4 text-3xl underline decoration-oct-rose",
    'animCardNews' => "transform transition duration-200 ease-in-out hover:bg-primary-blue hover:bg-opacity-10 hover:scale-105",
    'animRotateArrow' => "transform transition-transform duration-500 group-hover:rotate-180",
    'animBase' => "transform transition duration-200 ease-in-out",
    'classLi' => "block py-2 px-3 rounded transform transition duration-200 ease-in-out hover:bg-white hover:text-oct-rose md:py-3",
    'classDivDropdown' => "z-10 hidden font-normal bg-oct-rose rounded-lg shadow-box-dropdown w-44 border-white border-6",
    'classLiDropdown' => "flex items-center justify-between w-full py-2 px-3 rounded transform transition duration-200 ease-in-out group-hover:bg-white group-hover:text-oct-rose lg:w-auto lg:py-3 uppercase",
    'classLiSubDropdown' => "flex items-center justify-between w-full px-4 py-2 leading-7 hover:bg-white hover:text-oct-rose",
    'classBtn' => "text-lg md:text-xl text-white bg-secondary-blue hover:bg-secondary-blue/75 rounded-lg px-5 py-3 transform transition duration-100 ease-in-out",
    'seeAllThings' => "block text-center py-1 border-2 leading-7 hover:text-white hover:bg-oct-rose border-oct-rose text-oct-rose rounded-full text-base",
    'subLi' => "block px-4 py-2 leading-7 hover:bg-white hover:text-oct-rose",
  ];
}

function showVar($var): void
{
  echo '<pre>';
  print_r($var);
  echo '</pre>';
  die();
}

add_action('wp_head', function () {
  global $alba_theme_variables;
  $alba_theme_variables = alba_theme_variables();
});

// Désactiver la barre d'administration pour tous les utilisateurs
add_filter('show_admin_bar', '__return_false');

function alba_theme_enqueue_styles(): void
{
  // Enregistrer le fichier CSS personnalisé de votre thème
  wp_enqueue_style('alba-style', get_stylesheet_directory_uri() . '/style.css');
}

add_action('wp_enqueue_scripts', 'alba_theme_enqueue_styles');

// Support des balises <title>
add_theme_support('title-tag');
/// For SEO and speed load page
function optimize_critical_rendering(): void
{
  // Defer non-critical JS
  wp_script_add_data('jquery', 'defer', true);
  wp_script_add_data('block-library/style', 'defer', true);

  // Remove unnecessary CSS/JS
  remove_action('wp_head', 'wp_print_styles');
  remove_action('wp_head', 'wp_print_head_scripts');
}

add_action('wp_enqueue_scripts', 'optimize_critical_rendering');

function defer_non_critical_css($html, $handle): string
{
  if ($handle === 'non-critical-style') {
    return str_replace("rel='stylesheet'", "rel='preload' as='style' onload=\"this.onload=null;this.rel='stylesheet'\"", $html);
  }
  return $html;
}

add_filter('style_loader_tag', 'defer_non_critical_css', 10, 2);

/// End Speed load page

function alba_theme_enqueue_scripts(): void
{
  // Enqueue Flowbite JS
  wp_enqueue_script('flowbite', get_template_directory_uri() . '/assets/js/flowbite.min.js', array(), null, true);

  // Enqueue Slick Slider CSS et JS
  wp_enqueue_style('slick-css', get_template_directory_uri() . '/assets/css/slick.css', array(), '1.8.1');
  wp_enqueue_script('slick-js', get_template_directory_uri() . '/assets/js/slick.min.js', array('jquery'), '1.8.1', true);

  // Enqueue votre script personnalisé pour initialiser Slick Slider
  wp_enqueue_script('custom-slick-init', get_template_directory_uri() . '/assets/js/slick-init.js', array(
    'jquery',
    'slick-js'
  ), null, true);

  // Equeue Lightbox JS (agrandissement des images au clic)
  wp_enqueue_script('custom-lightbox-js', get_template_directory_uri() . '/assets/js/lightBox.js', array(), null, true);

  // Enqueue JQuery (si nécessaire)
  wp_enqueue_script('jquery');
}

add_action('wp_enqueue_scripts', 'alba_theme_enqueue_scripts');

// Allow SVG
add_filter('wp_check_filetype_and_ext', function ($data, $file, $filename, $mimes) {

  global $wp_version;
  if ($wp_version !== '4.7.1') {
    return $data;
  }

  $filetype = wp_check_filetype($filename, $mimes);

  return [
    'ext' => $filetype['ext'],
    'type' => $filetype['type'],
    'proper_filename' => $data['proper_filename']
  ];
}, 10, 4);

function wppln_mime_types($mimes)
{
  $mimes['svg'] = 'image/svg+xml';

  return $mimes;
}

add_filter('upload_mimes', 'wppln_mime_types');

function wppln_fix_svg(): void
{
  echo '<style>
        .attachment-266x266, .thumbnail img {
             width: 100% !important;
             height: auto !important;
        }
        </style>';
}

add_action('admin_head', 'wppln_fix_svg');

function my_custom_comment_form($args)
{
  $args['class_submit'] = 'bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600'; // Bouton personnalisé
  $args['comment_field'] = '<p class="comment-form-comment">
        <textarea id="comment" name="comment" class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" rows="5" required></textarea>
    </p>';
  return $args;
}

add_filter('comment_form_defaults', 'my_custom_comment_form');

add_filter('comment_form_default_fields', function ($fields) {
  // Supprime le champ site web
  if (isset($fields['url'])) {
    unset($fields['url']);
  }

  if (isset($fields['cookies'])) {
    $fields['cookies'] = str_replace(
      'Enregistrer mon nom, mon e-mail et mon site dans le navigateur pour mon prochain commentaire.',
      'Enregistrer mon nom et mon e-mail dans le navigateur pour mon prochain commentaire.',
      $fields['cookies']
    );
  }
  return $fields;
});

// fonction d'affichage de la grille des membres du bureau ou de la ligue
function showGridBureau(array $members): void
{
  echo '<div class="bg-white rounded-2xl p-5 grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 divide-y divide-primary-blue sm:divide-none">';
  foreach ($members as $member) {
    echo sprintf(
      '<div class="grid grid-rows-[auto_2fr_auto] gap-4 justify-center items-center text-center text-lg p-3">
                            <p class="underline font-bold text-xl">%s</p>
                            <div class="row-span-1">%s</div>
                            <p class="row-span-1 italic text-xl">%s</p>
                        </div>',
      ucwords($member['position']),
      wp_get_attachment_image((!empty($member['image_id']) ? $member['image_id'] : 523), '', false, array(
        'loading' => 'lazy',
        'class' => "w-1/2 max-w-56 m-auto rounded-2xl transform transition duration-300 ease-in-out hover:scale-105 row-span-2",
      )),
      ucwords($member['name'])
    );
  }
  echo '</div>';
}

// fonction de génération du breadcrumb sur chaque page (ajouté ds le header.php)
function generate_breadcrumbs($special_parent = ""): void
{
  if (!is_front_page() && !is_404() && !is_search()) {
    $mb = !is_404() ? 'mb-8' : '';
    $breadcrumb = '<nav class="' . $mb . ' max-w-max text-md flex justify-center items-center px-5 py-3 text-primary-blue border border-primary-blue/50 rounded-xl bg-gray-50 dark:bg-gray-800 dark:border-gray-700" aria-label="Breadcrumb">';
    // Lien vers la page d'accueil >> svg = Home
    $breadcrumb .= '<li class="inline-flex items-center">
            <a href="/" title="Accueil" class="inline-flex items-center font-medium hover:text-secondary-blue dark:text-gray-400 dark:hover:text-white">
                <svg class="w-5 h-5 md:w-6 md:h-6 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 1 1 1-1h2a1 1 0 1 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                        </svg>
            </a>
        </li>';
    $breadcrumb .= '<ol class="inline-flex flex-wrap flex-1 items-center gap-1 md:gap-2 rtl:space-x-reverse">';

    // Ajout des pages parentes
    if (is_page()) {
      global $post;
      $ancestors = get_post_ancestors($post);
      $ancestors = array_reverse($ancestors);
      foreach ($ancestors as $ancestor) {
        $breadcrumb .= '<li>
                    <div class="flex items-center">
                        <svg class="rtl:rotate-180 block w-4 h-4 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M3 4a1 1 0 0 0-.822 1.57L6.632 12l-4.454 6.43A1 1 0 0 0 3 20h13.153a1 1 0 0 0 .822-.43l4.847-7a1 1 0 0 0 0-1.14l-4.847-7a1 1 0 0 0-.822-.43H3Z" clip-rule="evenodd"/>
                        </svg>

                        <a href="' . get_permalink($ancestor) . '" class="flex-1 whitespace-normal break-words ms-1 font-medium hover:text-secondary-blue md:ms-2 dark:text-gray-400 dark:hover:text-white">'
          . get_the_title($ancestor) . '
                        </a>
                    </div>
                </li>';
      }
    }

    // Si c'est un article, ajouter "Actualités" comme parent dans le breadcrumb
    if (is_single() && get_post_type() === 'post') {
      $breadcrumb .= '<li>
        <div class="flex items-center">
            <svg class="rtl:rotate-180 block w-4 h-4 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                <path fill-rule="evenodd" d="M3 4a1 1 0 0 0-.822 1.57L6.632 12l-4.454 6.43A1 1 0 0 0 3 20h13.153a1 1 0 0 0 .822-.43l4.847-7a1 1 0 0 0 0-1.14l-4.847-7a1 1 0 0 0-.822-.43H3Z" clip-rule="evenodd"/>
            </svg>
            <a href="' . get_permalink(26) . '" class="flex-1 whitespace-normal break-words ms-1 font-medium hover:text-secondary-blue md:ms-2 dark:text-gray-400 dark:hover:text-white">Les actualit&eacute;s du club</a>
        </div>
    </li>';
    }

    // Si c'est une page d'interclub, ajouter "Interclubs" comme parent dans le breadcrumb
    if (get_post_type() === 'sports_team') {
      $breadcrumb .= '<li>
        <div class="flex items-center">
            <svg class="rtl:rotate-180 block w-4 h-4 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                <path fill-rule="evenodd" d="M3 4a1 1 0 0 0-.822 1.57L6.632 12l-4.454 6.43A1 1 0 0 0 3 20h13.153a1 1 0 0 0 .822-.43l4.847-7a1 1 0 0 0 0-1.14l-4.847-7a1 1 0 0 0-.822-.43H3Z" clip-rule="evenodd"/>
            </svg>
            <a href="' . get_permalink(190) . '" class="flex-1 whitespace-normal break-words ms-1 font-medium hover:text-secondary-blue md:ms-2 dark:text-gray-400 dark:hover:text-white">Interclubs</a>
        </div>
    </li>';
    }

    // Ajout de la page enfant
    $breadcrumb .= '<li aria-current="page">
            <div class="flex items-center">
                <svg class="rtl:rotate-180 block w-4 h-4 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd" d="M3 4a1 1 0 0 0-.822 1.57L6.632 12l-4.454 6.43A1 1 0 0 0 3 20h13.153a1 1 0 0 0 .822-.43l4.847-7a1 1 0 0 0 0-1.14l-4.847-7a1 1 0 0 0-.822-.43H3Z" clip-rule="evenodd"/>
                </svg>
                <span class="flex-1 whitespace-normal break-words ms-1 font-medium text-gray-500 md:ms-2 dark:text-gray-400">'
      . get_the_title() . '
                </span>
            </div>
        </li>';

    $breadcrumb .= '</ol>';
    $breadcrumb .= '</nav>';

    echo $breadcrumb;
  }
}

// fonction qui retourne si la clé entrée en paramètre est la première clé du tableau
function isFirstKey(string $key, array $array): bool
{
  return array_key_first($array) == $key;
}

// fonction qui retourne si la clé entrée en paramètre est la dernière clé du tableau
function isLastKey(string $key, array $array): bool
{
  return array_key_last($array) == $key;
}

////// Custom Meta Box //////

//// Main function to register all meta boxes
add_filter('rwmb_meta_boxes', 'alba_register_all_meta_boxes');

function alba_register_all_meta_boxes($meta_boxes): array
{
  if (is_admin() && isset($_GET['post'])) {
    $post_id = (int)$_GET['post'];
    // post id of Homepage page : 53
    if ($post_id === 53) {
      // Meta Box for Homepage
      $prefix_home = 'home_';
      $meta_boxes[] = [
        'title' => esc_html__('Les partenaires du club pour la saison 2024 - 2025', 'alba_theme'),
        'id' => $prefix_home . 'info',
        'post_types' => ['page'],
        'show' => [
          'template' => ['homePage.php'],
        ],
        'context' => 'normal',
        'priority' => 'high',
        'fields' => [
          [
            'type' => 'image_advanced',
            'name' => __('Logo des partenaires', 'alba_theme'),
            'id' => $prefix_home . 'img_id',
            'clone' => true,
          ],
        ],
      ];
    }
  }
  return $meta_boxes;
}

// Custom dropdown title page //
// Add custom field for menu title only on child pages
function add_menu_title_meta_box(): void
{
  // Get the current post ID
  $post_id = isset($_GET['post']) ? $_GET['post'] : null;

  // Only proceed if we have a post ID
  if ($post_id) {
    // Get the post's parent ID
    $post_parent = wp_get_post_parent_id($post_id);

    // Only add the meta box if this is a child page (has a parent)
    if ($post_parent > 0) {
      add_meta_box(
        'menu_title_meta_box',
        'Menu Title',
        'menu_title_meta_box_html',
        'page'
      );
    }
  } else {
    // For new pages, we'll add the meta box and hide it with JavaScript if it's not a child page
    add_meta_box(
      'menu_title_meta_box',
      'Menu Title',
      'menu_title_meta_box_html',
      'page'
    );
    add_action('admin_footer', 'menu_title_visibility_script');
  }
}

add_action('add_meta_boxes', 'add_menu_title_meta_box');

// Meta box HTML
function menu_title_meta_box_html($post): void
{
  $value = get_post_meta($post->ID, 'menu_title', true);
?>
  <label for="menu_title">Court titre pour le menu de la barre de navigation</label>
  <input type="text" id="menu_title" name="menu_title" value="<?= esc_attr($value) ?>" class="widefat">
  <p class="description">Laissez vide pour utiliser le titre complet de la page</p>
<?php
}

// JavaScript to hide/show meta box based on parent selection
function menu_title_visibility_script()
{
?>
  <script type="text/javascript">
    jQuery(document).ready(function($) {
      const theparentId = $("#parent_id");

      // Function to toggle meta box visibility
      function toggleMenuTitleMetaBox() {
        var parentId = theparentId.val();
        if (parentId && parentId > 0) {
          $("#menu_title_meta_box").show();
        } else {
          $("#menu_title_meta_box").hide();
        }
      }

      // Initial check
      toggleMenuTitleMetaBox();

      // Watch for changes to the parent dropdown
      theparentId.on("change", toggleMenuTitleMetaBox);
    });
  </script>
<?php
}

// Save meta box data
function save_menu_title_meta_box($post_id): void
{
  // Only save if this is a child page
  if (wp_get_post_parent_id($post_id) > 0) {
    if (array_key_exists('menu_title', $_POST)) {
      update_post_meta(
        $post_id,
        'menu_title',
        sanitize_text_field($_POST['menu_title'])
      );
    }
  }
}

add_action('save_post', 'save_menu_title_meta_box');

///////// function which displays the title in <h2> tag with a specific class //////////
function display_titlePage(): string
{
  return '<h2 class="text-4xl font-bold mb-8">' . get_the_title() . '</h2>';
}

//////// Menu in Admin panel to settings details like number of courts, members...

// Add the menu page (club settings)
add_action('admin_menu', function () {
  global $capability;
  add_menu_page(
    esc_html__('Paramètres du club', 'alba_theme'),
    esc_html__('Paramètres du club', 'alba_theme'),
    $capability,
    'club-settings',
    'render_club_settings_page',
    'dashicons-admin-settings',
    30
  );
});

// Register settings (club settings)
add_action('admin_init', function () {
  //// Homepage section ///////////////////////////////////////
  add_settings_section(
    'club_homepage_settings',
    "",
    'render_title_section',
    'club-settings',
    ['theTitle' => "Page d'accueil"]
  );

  // Add image field
  add_settings_field(
    'club_logo_icon',
    esc_html__('Logo du club', 'alba_theme'),
    'render_image_field',
    'club-settings',
    'club_homepage_settings',
    ['field_name' => 'club_logo_icon']
  );

  // 1st title of homepage
  add_settings_field(
    'club_title_homepage',
    esc_html__('Titre principal', 'alba_theme'),
    'render_text_field',
    'club-settings',
    'club_homepage_settings',
    ['field_name' => 'club_title_homepage']
  );

  // 1st paragraph in homepage
  add_settings_field(
    'club_paragraph_homepage',
    esc_html__('Paragraphe de pr&eacute;sentation', 'alba_theme'),
    'render_textarea_field',
    'club-settings',
    'club_homepage_settings',
    ['field_name' => 'club_paragraph_homepage']
  );

  // Nb of members
  add_settings_field(
    'club_members_count',
    esc_html__('Nombre de membres', 'alba_theme'),
    'render_number_field',
    'club-settings',
    'club_homepage_settings',
    ['field_name' => 'club_members_count']
  );

  //// PresLeClub section ///////////////////////////////////////
  add_settings_section(
    'club_pres_settings',
    "",
    'render_title_section',
    'club-settings',
    ['theTitle' => "Page de pr&eacute;sentation du club"]
  );

  // Add image field
  add_settings_field(
    'club_family_img',
    esc_html__('Photo de famille', 'alba_theme'),
    'render_image_field',
    'club-settings',
    'club_pres_settings',
    ['field_name' => 'club_family_img']
  );

  // caption below image
  add_settings_field(
    'club_legend_img',
    esc_html__("L&eacute;gende sous l'image", 'alba_theme'),
    'render_textarea_field',
    'club-settings',
    'club_pres_settings',
    ['field_name' => 'club_legend_img']
  );

  //// Calendar section ///////////////////////////////////////
  add_settings_section(
    'club_calendar_settings',
    "",
    'render_title_section',
    'club-settings',
    ['theTitle' => "Calendrier du CODEP"]
  );

  // Link of calendar in Google Sheets
  add_settings_field(
    'club_link_calendar',
    esc_html__('Lien vers le calendrier avec Google Sheets', 'alba_theme'),
    'render_text_field',
    'club-settings',
    'club_calendar_settings',
    [
      'field_name' => 'club_link_calendar',
      'desc' => esc_html__("Besoin d'aide pour obtenir le lien ? J'ai créé un petit tuto rien que pour vous ! 😉🏸", 'alba_theme'),
      'link' => 'wp-content/tuto/Tutoriel_Google-Sheets.pdf'
    ]
  );

  // Input text to add the year of the calendar (ex: 2024-2025)
  add_settings_field(
    'club_calendar_year_settings',
    esc_html__('Saison du calendrier', 'alba_theme'),
    'render_text_field',
    'club-settings',
    'club_calendar_settings',
    [
      'field_name' => "club_calendar_year_settings",
      'desc' => esc_html__('Exemple : 2024 - 2025', 'alba_theme')
    ]
  );

  //// Contact section ///////////////////////////////////////
  add_settings_section(
    'club_contact_settings',
    "",
    'render_title_section',
    'club-settings',
    ['theTitle' => "Page de contact"]
  );
  // Small title (questions)
  add_settings_field(
    'club_contact_questions',
    esc_html__("Titre secondaire", 'alba_theme'),
    'render_textarea_field',
    'club-settings',
    'club_contact_settings',
    ['field_name' => 'club_contact_questions']
  );

  // Mail
  add_settings_field(
    'club_mail',
    esc_html__('Mail', 'alba_theme'),
    'render_email_field',
    'club-settings',
    'club_contact_settings',
    [
      'field_name' => 'club_mail',
      'desc' => esc_html__('Email où recevoir les demandes / questions des visiteurs', 'alba_theme')
    ]
  );

  // Tel
  add_settings_field(
    'club_tel',
    esc_html__('Téléphone', 'alba_theme'),
    'render_tel_field',
    'club-settings',
    'club_contact_settings',
    [
      'field_name' => 'club_tel',
      'std' => '+33',
      'pattern' => '\+[0-9]{2}[0-9\s]*'
    ]
  );

  // Adresse postale
  add_settings_field(
    'club_address',
    esc_html__('Adresse postale du gymnase', 'alba_theme'),
    'render_text_field',
    'club-settings',
    'club_contact_settings',
    ['field_name' => 'club_address']
  );


  // Register the settings [save]
  //// Homepage section
  register_setting('club_settings', 'club_logo_icon');
  register_setting('club_settings', 'club_title_homepage');
  register_setting('club_settings', 'club_paragraph_homepage');
  register_setting('club_settings', 'club_members_count');

  //// presLeClub
  register_setting('club_settings', 'club_family_img');
  register_setting('club_settings', 'club_legend_img');

  /// Calendar
  register_setting('club_settings', 'club_link_calendar');
  register_setting('club_settings', 'club_calendar_year_settings');

  //// Contact
  register_setting('club_settings', 'club_contact_questions');
  register_setting('club_settings', 'club_mail');
  register_setting('club_settings', 'club_tel');
  register_setting('club_settings', 'club_address');
});

function render_title_section($args): void
{
  $title = $args['theTitle'];
?>
  <style>
    .section-divider {
      margin: 3em 0 1em 0;
      border-top: 2px solid #2271b1;
    }

    .section-title {
      color: #2271b1;
      font-size: 1.3em;
      margin: 1em 0;
    }
  </style>
  <hr class="section-divider">
  <h2 class="section-title"><?= esc_html__($title, 'alba_theme'); ?></h2>
<?php
}

// Fonction pour rendre le champ email
function render_email_field($args): void
{
  $value = get_option($args['field_name']);
?>
  <input
    type="email"
    name="<?php echo esc_attr($args['field_name']); ?>"
    value="<?php echo esc_attr($value); ?>"
    class="regular-text"
    size="60">
  <?php if (isset($args['desc'])): ?>
    <p class="description"><?php echo esc_html($args['desc']); ?></p>
  <?php endif; ?>
<?php
}

// Fonction pour rendre le champ téléphone
function render_tel_field($args): void
{
  $value = get_option($args['field_name']) ?: $args['std'];
?>
  <input
    type="text"
    name="<?php echo esc_attr($args['field_name']); ?>"
    value="<?php echo esc_attr($value); ?>"
    class="regular-text"
    pattern="<?php echo $args['pattern']; ?>"
    max="12">
<?php
}

// Render text field
function render_text_field($args): void
{
  $value = get_option($args['field_name']);
?>
  <input
    type="text"
    name="<?php echo esc_attr($args['field_name']); ?>"
    value="<?php echo esc_attr($value); ?>"
    class="large-text"
    min="0">
  <?php if (isset($args['desc'])): ?>
    <p class="description"><?= esc_html($args['desc']) ?>
      <?php if (isset($args['link'])): ?>
        <a href="/<?= esc_html($args['link']) ?>" download>
          <svg style="margin-left: 10px" xmlns="http://www.w3.org/2000/svg" width="20px" fill="#2271b1"
            viewBox="0 0 512 512">
            <!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
            <path d="M288 32c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 242.7-73.4-73.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l128 128c12.5 12.5 32.8 12.5 45.3 0l128-128c12.5-12.5 12.5-32.8
    0-45.3s-32.8-12.5-45.3 0L288 274.7 288 32zM64 352c-35.3 0-64 28.7-64 64l0 32c0 35.3 28.7 64 64 64l384 0c35.3 0 64-28.7 64-64l0-32c0-35.3-28.7-64-64-64l-101.5 0-45.3 45.3c-25 25-65.5
    25-90.5 0L165.5 352 64 352zm368 56a24 24 0 1 1 0 48 24 24 0 1 1 0-48z" />
          </svg>
        </a>
      <?php endif; ?>
    </p>
  <?php endif; ?>
<?php
}

// Render number field
function render_number_field($args): void
{
  $value = get_option($args['field_name']);
?>
  <input
    type="number"
    name="<?php echo esc_attr($args['field_name']); ?>"
    value="<?php echo esc_attr($value); ?>"
    class="small-text"
    min="0">
<?php
}

// Render textarea field
function render_textarea_field($args): void
{
  $value = get_option($args['field_name']);
?>
  <textarea
    name="<?php echo esc_attr($args['field_name']); ?>"
    class="large-text"
    rows="5"><?php echo esc_html($value); ?></textarea>
<?php
}

// Render the settings page
function render_club_settings_page(): void
{
  if (!current_user_can('manage_options')) {
    return;
  }
?>
  <div class="wrap">
    <h1><?php echo esc_html__('Param&eacute;trage des infos du club', 'alba_theme'); ?></h1>
    <form action="options.php" method="post">
      <?php
      settings_fields('club_settings');
      do_settings_sections('club-settings');
      submit_button();
      ?>
    </form>
  </div>
<?php
}

// Render image field function
function render_image_field($args): void
{
  $image_id = get_option($args['field_name']);
  $image_url = $image_id ? wp_get_attachment_url($image_id) : '';
  $unique_id = 'image-upload-' . $args['field_name'];
?>
  <div class="image-upload-wrap" id="<?php echo esc_attr($unique_id); ?>">
    <input type="hidden" name="<?php echo esc_attr($args['field_name']); ?>"
      id="<?php echo esc_attr($args['field_name']); ?>"
      value="<?php echo esc_attr($image_id); ?>">

    <div class="image-preview">
      <?php if ($image_url): ?>
        <img src="<?php echo esc_url($image_url); ?>" style="max-height: 150px" alt="Icône du club">
      <?php endif; ?>
    </div>

    <input type="button" class="button upload-image-button"
      data-target="<?php echo esc_attr($unique_id); ?>"
      value="<?php esc_attr_e('Insérer une image', 'alba_theme'); ?>" />

    <?php if ($image_url): ?>
      <input type="button" class="button remove-image-button"
        data-target="<?php echo esc_attr($unique_id); ?>"
        value="<?php esc_attr_e("Supprimer l'image", 'alba_theme'); ?>" />
    <?php endif; ?>
  </div>

  <script>
    jQuery(document).ready(function($) {
      $('.upload-image-button[data-target="<?php echo esc_js($unique_id); ?>"]').on("click", function() {
        const container = $("#" + $(this).data("target"));
        const hiddenInput = container.find("input[type=\"hidden\"]");
        const previewContainer = container.find(".image-preview");
        const removeButton = container.find(".remove-image-button");

        const customUploader = wp.media({
          title: '<?php esc_html_e('Sélectionner une image', 'alba_theme'); ?>',
          button: {
            text: '<?php esc_html_e('Utiliser cette image', 'alba_theme'); ?>'
          },
          multiple: false
        });

        customUploader.on("select", function() {
          const attachment = customUploader.state().get("selection").first().toJSON();
          hiddenInput.val(attachment.id);
          previewContainer.html("<img src=\"" + attachment.url + "\" style=\"max-width: 250px;\" alt=\"\">");
          removeButton.show();
        });

        customUploader.open();
      });

      $('.remove-image-button[data-target="<?php echo esc_js($unique_id); ?>"]').on("click", function() {
        const container = $("#" + $(this).data("target"));
        container.find("input[type=\"hidden\"]").val("");
        container.find(".image-preview").empty();
        $(this).hide();
      });
    });
  </script>
<?php
}

// First, enqueue the WordPress media scripts
add_action('admin_enqueue_scripts', function ($hook) {
  if ('toplevel_page_club-settings' !== $hook) {
    return;
  }
  wp_enqueue_media();
});
