<?php
/*
Plugin Name:  Recensioni Libri
Plugin URI:   https://github.com/strange97/recensioni-libri
Description:  Cutom post type for book's reviews
Version:      20171118
Author:       Maurizio Peisino
License:      GPL3
License URI:  https://www.gnu.org/licenses/gpl-3.0.html
Text Domain:  recensioni
Domain Path:  /languages
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function create_post_type() {
  register_post_type( 'recensioni_libri',
    array(
      'labels' => array(
        'name' => __( 'Recensioni libri' ),
        'singular_name' => __( 'Recensione libro' )
      ),
      'public' => true,
      'has_archive' => true,
    )
  );
}
add_action( 'init', 'create_post_type' );