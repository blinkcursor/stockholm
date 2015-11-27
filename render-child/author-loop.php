<?php
/**
 * The template for displaying Author bios
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */
?>


<h2 class="author-heading"><?php printf( __( 'Published %s', 'render-child' ), get_the_date() ); ?></h2>

<?php
// only render the vcard if ! on author page & author != SCEWP

$user_id = get_the_author_meta('ID');

if ( !is_author() && $user_id != 5 ) {

	$size = ( is_single() ) ? "full" : "mini";

	render_author_vcard( $user_id, $size );
}
?>