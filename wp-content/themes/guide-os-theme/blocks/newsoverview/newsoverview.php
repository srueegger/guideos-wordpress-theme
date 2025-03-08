<?php
/**
 * NewsOverview Block template.
 *
 * @param array $block The block settings and attributes.
 */

// Load values and assign defaults.
$args = [
    'post_type' => 'post',
    'posts_per_page' => -1,
    'orderby' => 'date',
    'order' => 'DESC',
];
$posts = get_posts( $args );

// Include functions
require_once "functions.php";

// Support custom "anchor" values.
$anchor = '';
if ( ! empty( $block['anchor'] ) ) {
    $anchor = 'id="' . esc_attr( $block['anchor'] ) . '" ';
}
// Create class attribute allowing for custom "className" and "align" values.
$class_name = 'guideos-newsoverview';
if ( ! empty( $block['className'] ) ) {
    $class_name .= ' ' . $block['className'];
}
if ( ! empty( $block['align'] ) ) {
    $class_name .= ' align' . $block['align'];
}
?>

<div <?php echo esc_attr( $anchor ); ?>class="<?php echo esc_attr( $class_name ); ?>">
    <?php
    if(!empty($posts)) {
        foreach($posts as $news) {
            render_news_overview_item($news);
        }
    }
    ?>
</div>