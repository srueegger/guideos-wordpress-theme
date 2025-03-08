<?php
// Sicherstellen, dass die Datei nicht direkt aufgerufen wurde
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/* Gutenberg Blocks registrieren */
function guideos_register_acf_blocks() {
    register_block_type( dirname( __DIR__ ) . '/blocks/newsoverview' );
}
add_action( 'init', 'guideos_register_acf_blocks' );