<?php
/**
 * The template for displaying Author bios
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */
?>

<?php
// only render the vcard if author != SCEWP

$author = get_user_by( 'slug', get_query_var( 'author_name' ) );
$user_id = $author->ID;

//$user_id = get_the_author_meta('ID'); this is used throughout codex but empty if author has no posts

if ( $user_id != 5 ) {

	$size = "extended";
	render_author_vcard( $user_id, $size );
}
?>