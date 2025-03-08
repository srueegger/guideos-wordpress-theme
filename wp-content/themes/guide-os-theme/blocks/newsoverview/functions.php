<?php
// Sicherstellen, dass die Datei nicht direkt aufgerufen wurde
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

function render_news_overview_item($news) {
    $postID = esc_attr($news->ID);
    //Beitragsbild und Alt Attribute auslesen
    $imageUrl = get_the_post_thumbnail_url($postID, 'full');
    $imageAlt = get_post_meta($postID, '_wp_attachment_image_alt', true);
    ?>
    <div class="news-overview-item">
        <div class="image">
            <img loading="lazy" src="<?php echo esc_url($imageUrl); ?>" alt="<?php echo esc_attr($imageAlt); ?>">
        </div>
        <div class="content">
            <h2><?php echo get_the_title($postID); ?></h2>
            <p><?php echo get_the_excerpt($postID); ?></p>
            <a href="<?php echo get_the_permalink($postID); ?>" class="button">Weiterlesen</a>
        </div>
    </div>
    <?php
}