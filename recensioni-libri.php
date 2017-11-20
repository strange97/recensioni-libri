<?php
/*
Plugin Name:  Recensioni Libri
Plugin URI:   https://github.com/strange97/recensioni-libri
Description:  Cutom post type for book's reviews
Version:      1.0
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
			'menu_icon' => 'dashicons-book-alt',
			'rewrite' => array('slug' => 'recensioni'),
			'supports' => array(
				'title',
				'editor',
				'thumbnail',
			), 
			'taxonomies' => array(
				'autore',
				'genere',
				'editore',
			),
		)
	);
	register_taxonomy( 'autore', 'recensioni_libri', 
		array(
			'labels' => array(
				'name' => __( 'Autori' ),
				'singular_name' => __( 'Autore' ),
				'add_new_item' => __( 'Aggiungi Autore' ),
				'separate_items_with_commas' => __( 'Autori separati da virgola' ),
				'choose_from_most_used' => __( 'Scegli Autori già inseriti' ),
				'not_found' => __('Nessun Autore trovato'),
			),
		)
	);
	register_taxonomy( 'genere', 'recensioni_libri', 
		array(
			'labels' => array(
				'name' => __( 'Generi' ),
				'singular_name' => __( 'Genere' ),
				'add_new_item' => __( 'Aggiungi Genere' ),
				'separate_items_with_commas' => __( 'Generi separati da virgola' ),
				'choose_from_most_used' => __( 'Scegli Generi già inseriti' ),
				'not_found' => __('Nessun Genere trovato'),
			),
		)
	);
	register_taxonomy( 'editore', 'recensioni_libri', 
		array(
			'labels' => array(
				'name' => __( 'Editori' ),
				'singular_name' => __( 'Editore' ),
				'add_new_item' => __( 'Aggiungi Editore' ),
				'separate_items_with_commas' => __( 'Editori separati da virgola' ),
				'choose_from_most_used' => __( 'Scegli Editori già inseriti' ),
				'not_found' => __('Nessun Editori trovato'),
			),
		)
	);
}
add_action( 'init', 'create_post_type' );

function add_custom_meta_boxes() {
    add_meta_box( 
        'info_libro',
        __( 'Info libro' ),
        'render_info_libro_meta_box',
        'recensioni_libri',
        'side',
        'default'
    );
}

function render_info_libro_meta_box($post) {
	wp_nonce_field( 'info_libro_meta_box', 'info_libro_meta_box_nonce' );
?>
	<label for="numero_pagine"><em>Prezzo</em></label><br />
	<input type="number" name="prezzo" id="prezzo" min="0.01" step="0.01" value="<?php echo get_post_meta($post->ID, 'prezzo', true); ?>"/> €<br />
	<label for="numero_pagine"><em>Numero di pagine</em></label><br />
	<input type="number" name="numero_pagine" id="numero_pagine" min="1" value="<?php echo get_post_meta($post->ID, 'numero_pagine', true); ?>"/><br />
	<label for="tempo_lettura"><em>Tempo di lettura (ore)</em></label><br />
	<input type="number" name="tempo_lettura" id="tempo_lettura" min="1" step="1" value="<?php echo get_post_meta($post->ID, 'tempo_lettura', true); ?>"/><br />
	<label for="anno_pubblicazione"><em>Anno di pubblicazione</em></label><br />
	<input type="number" name="anno_pubblicazione" id="anno_pubblicazione" min="1455" max="<?php print date('Y') + 1; ?>" value="<?php echo get_post_meta($post->ID, 'anno_pubblicazione', true); ?>"/><br />
	<label for="voto"><em>Voto</em></label><br />
	<input type="text" name="voto" id="voto" pattern="[0-9]+([\.,][5])?" title="Da 1 a 10 con mezzi voti" value="<?php echo get_post_meta($post->ID, 'voto', true); ?>"/>
<?php
} 

function save_metaboxes($post_id, $post){
	if (!isset( $_POST['info_libro_meta_box_nonce']) || !wp_verify_nonce($_POST['info_libro_meta_box_nonce'], 'info_libro_meta_box')) {
		return;
	}
	
	if ( isset( $_POST['prezzo'] ) ) {
        update_post_meta( $post_id, 'prezzo', floatval( $_POST['prezzo'] ) );
    }
	if ( isset( $_POST['numero_pagine'] ) ) {
        update_post_meta( $post_id, 'numero_pagine', absint( $_POST['numero_pagine'] ) );
    }
    if ( isset( $_POST['tempo_lettura'] ) ) {
        update_post_meta( $post_id, 'tempo_lettura', absint( $_POST['tempo_lettura'] ) );
    }
    if ( isset( $_POST['anno_pubblicazione'] ) ) {
        update_post_meta( $post_id, 'anno_pubblicazione', absint( $_POST['anno_pubblicazione'] ) );
    }
    if ( isset( $_POST['voto'] ) ) {
        update_post_meta( $post_id, 'voto', floatval( str_replace(',', '.', $_POST['voto']) ) );
    }
}

add_action( 'add_meta_boxes_recensioni_libri', 'add_custom_meta_boxes' );
add_action( 'save_post_recensioni_libri', 'save_metaboxes' );