<?php
/**
 * Mailchimp.
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

?>

<div class="ngl-metabox-flex">

	<div class="ngl-metabox-flex">
		<div class="ngl-metabox-header ngl-metabox-header-c">
			<?php esc_html_e( 'Audience', 'newsletter-glue' ); ?>
		</div>
		<div class="ngl-field">
			<?php
				$audience  = newsletterglue_get_option( 'audience', $connection );
				$audiences = $api->get_audiences();
				if ( ! $audience ) {
					$audience = array_keys( $audiences );
					$audience = $audience[0];
				}
				newsletterglue_select_field( array(
					'id' 			=> 'ngl_audience',
					'legacy'		=> true,
					'helper'		=> __( 'Who receives your email.', 'newsletter-glue' ),
					'class'			=> 'ngl-ajax',
					'options'		=> $audiences,
					'default'		=> $audience,
				) );
			?>
		</div>
	</div>

	<div class="ngl-metabox-flex">
		<div class="ngl-metabox-header">
			<?php esc_html_e( 'Segment / tag', 'newsletter-glue' ); ?>
		</div>
		<div class="ngl-field">
			<?php

				$segment = newsletterglue_get_option( 'segment', $connection );

				if ( ! $segment ) {
					$segment = '_everyone';
				}

				newsletterglue_select_field( array(
					'id' 			=> 'ngl_segment',
					'legacy'		=> true,
					'helper'		=> __( 'A specific group of subscribers.', 'newsletter-glue' ),
					'options'		=> $api->get_segments( $audience ),
					'default'		=> $segment,
					'class'			=> 'ngl-ajax',
				) );

			?>
		</div>
	</div>

</div>

<div class="ngl-metabox-flex">

	<div class="ngl-metabox-flex">
		<div class="ngl-metabox-header">
			<?php esc_html_e( 'From name', 'newsletter-glue' ); ?>
		</div>
		<div class="ngl-field">
			<?php
				newsletterglue_text_field( array(
					'id' 			=> 'ngl_from_name',
					'helper'		=> __( 'Your subscribers will see this name in their inbox.', 'newsletter-glue' ),
					'value'			=> newsletterglue_get_option( 'from_name', $connection ),
					'class'			=> 'ngl-ajax',
				) );
			?>
		</div>
	</div>

	<div class="ngl-metabox-flex">
		<div class="ngl-metabox-header">
			<?php esc_html_e( 'From email', 'newsletter-glue' ); ?>
		</div>
		<div class="ngl-field">
			<?php
				newsletterglue_text_field( array(
					'id' 			=> 'ngl_from_email',
					'helper'		=> __( 'Subscribers will see and reply to this email address.', 'newsletter-glue' ),
					'value'			=> newsletterglue_get_option( 'from_email', $connection ),
					'class'			=> 'ngl-ajax',
				) );
			?>
		</div>
	</div>

</div>

<div class="ngl-metabox-flex">

	<div class="ngl-metabox-flex">
		<div class="ngl-metabox-header">
			<?php esc_html_e( 'Sent by Newsletter Glue', 'newsletter-glue' ); ?>
		</div>

		<div class="ngl-field ngl-credits">
			<label>
				<input type="checkbox" name="ngl_credits" id="ngl_credits" value="1" class="ngl-ajax" <?php checked( 1, get_option( 'newsletterglue_credits' ) ); ?> />
				<strong>🎉 <?php _e( 'Promote us, and we&rsquo;ll promote you back:', 'newsletter-glue' ); ?></strong>
			</label>

			<span><?php _e( 'Check this box to add the words<br />"Seamlessly sent by Newsletter Glue"<br />to the bottom of your newsletter.<br />Don&rsquo;t worry, it&rsquo;s small.', 'newsletter-glue' ); ?></span>
			<span><?php printf( __( 'Then, %s and we&rsquo;ll feature your newsletter.<br />%s', 'newsletter-glue' ), '<a href="https://ctt.ac/A25aM" target="_blank">' . __( 'let us know', 'newsletter-glue' ) . '</a>',
				'<a href="https://docs.memberhero.pro/article/5-sent-by" target="_blank" class="ngl-lighter">' . __( 'Learn more.', 'newsletter-glue' ) . '</a>' ); ?></span>

		</div>
	</div>

</div>