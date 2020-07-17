<?php
/**
 * Settings UI.
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

?>

<div class="ui large header">

	<?php esc_html_e( 'Email Defaults', 'newsletter-glue' ); ?>

	<div class="sub header"><?php echo wp_kses_post( __( 'All newsletters will default to the details shown here.<br /> Change details for individual newsletters at the bottom of each new post.', 'newsletter-glue' ) ); ?></div>

</div>

<div class="ngl-metabox">

	<?php if ( ! $connection ) : ?>

	<div class="ngl-metabox-flex">
		<div class="ngl-metabox-msg is-notice"><a href="<?php echo esc_url( admin_url( 'admin.php?page=ngl-connect' ) ); ?>"><?php _e( 'Start by connecting your email software &#x21C4;', 'newsletter-glue' ); ?></a></div>
	</div>

	<?php else : ?>

	<?php include( 'settings-' . $connection . '.php' ); ?>

	<?php endif; ?>

</div>