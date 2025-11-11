<?php
/**
 * Plugin Name: Fix Email Sender Address
 * Description: Configure WordPress email sender settings with admin dashboard controls
 * Version: 1.0
 * Author: SMAT Marketing
 * Author URI: https://wearesmat.com
 * Text Domain: fix-email-sender
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add email settings page to Tools menu
 */
function fes_add_email_settings_page() {
	add_management_page(
		__( 'Email Settings', 'fix-email-sender' ),
		__( 'Email Settings', 'fix-email-sender' ),
		'manage_options',
		'fes-email-settings',
		'fes_email_settings_page_html'
	);
}
add_action( 'admin_menu', 'fes_add_email_settings_page' );

/**
 * Register email settings
 */
function fes_register_email_settings() {
	// Register settings.
	register_setting(
		'fes_email_settings',
		'fes_email_from_email',
		array(
			'type'              => 'string',
			'sanitize_callback' => 'fes_sanitize_email',
			'default'           => get_option( 'admin_email' ),
		)
	);

	register_setting(
		'fes_email_settings',
		'fes_email_from_name',
		array(
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => get_option( 'blogname' ),
		)
	);

	register_setting(
		'fes_email_settings',
		'fes_email_reply_to',
		array(
			'type'              => 'string',
			'sanitize_callback' => 'fes_sanitize_email',
			'default'           => get_option( 'admin_email' ),
		)
	);

	// Add settings section.
	add_settings_section(
		'fes_email_settings_section',
		__( 'Email Configuration', 'fix-email-sender' ),
		'fes_email_settings_section_callback',
		'fes-email-settings'
	);

	// Add settings fields.
	add_settings_field(
		'fes_email_from_email',
		__( 'From Email Address', 'fix-email-sender' ),
		'fes_email_from_email_callback',
		'fes-email-settings',
		'fes_email_settings_section'
	);

	add_settings_field(
		'fes_email_from_name',
		__( 'From Name', 'fix-email-sender' ),
		'fes_email_from_name_callback',
		'fes-email-settings',
		'fes_email_settings_section'
	);

	add_settings_field(
		'fes_email_reply_to',
		__( 'Reply-To Email Address', 'fix-email-sender' ),
		'fes_email_reply_to_callback',
		'fes-email-settings',
		'fes_email_settings_section'
	);
}
add_action( 'admin_init', 'fes_register_email_settings' );

/**
 * Sanitize email address
 *
 * @param string $email Email address to sanitize.
 * @return string Sanitized email address.
 */
function fes_sanitize_email( $email ) {
	$email = sanitize_email( $email );
	if ( ! is_email( $email ) ) {
		add_settings_error(
			'fes_email_settings',
			'invalid_email',
			__( 'Please enter a valid email address.', 'fix-email-sender' ),
			'error'
		);
		return get_option( 'admin_email' );
	}
	return $email;
}

/**
 * Settings section callback
 */
function fes_email_settings_section_callback() {
	echo '<p>' . esc_html__( 'Configure the email sender information used by WordPress when sending emails.', 'fix-email-sender' ) . '</p>';
}

/**
 * From Email field callback
 */
function fes_email_from_email_callback() {
	$value = get_option( 'fes_email_from_email', get_option( 'admin_email' ) );
	?>
	<input type="email"
			name="fes_email_from_email"
			value="<?php echo esc_attr( $value ); ?>"
			class="regular-text"
			required>
	<p class="description">
		<?php esc_html_e( 'The email address that will appear in the "From" field of outgoing emails.', 'fix-email-sender' ); ?>
	</p>
	<?php
}

/**
 * From Name field callback
 */
function fes_email_from_name_callback() {
	$value = get_option( 'fes_email_from_name', get_option( 'blogname' ) );
	?>
	<input type="text"
			name="fes_email_from_name"
			value="<?php echo esc_attr( $value ); ?>"
			class="regular-text"
			required>
	<p class="description">
		<?php esc_html_e( 'The name that will appear in the "From" field of outgoing emails.', 'fix-email-sender' ); ?>
	</p>
	<?php
}

/**
 * Reply-To Email field callback
 */
function fes_email_reply_to_callback() {
	$value = get_option( 'fes_email_reply_to', get_option( 'admin_email' ) );
	?>
	<input type="email"
			name="fes_email_reply_to"
			value="<?php echo esc_attr( $value ); ?>"
			class="regular-text"
			required>
	<p class="description">
		<?php esc_html_e( 'The email address that will be used when recipients reply to emails.', 'fix-email-sender' ); ?>
	</p>
	<?php
}

/**
 * Render settings page HTML
 */
function fes_email_settings_page_html() {
	// Check user capabilities.
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// Show success message if settings were updated.
	if ( isset( $_GET['settings-updated'] ) ) {
		add_settings_error(
			'fes_email_settings',
			'fes_email_settings_message',
			__( 'Email settings saved successfully.', 'fix-email-sender' ),
			'success'
		);
	}

	// Show error/success messages.
	settings_errors( 'fes_email_settings' );
	?>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

		<form action="options.php" method="post">
			<?php
			// Output security fields.
			settings_fields( 'fes_email_settings' );

			// Output setting sections and fields.
			do_settings_sections( 'fes-email-settings' );

			// Output save button.
			submit_button( __( 'Save Email Settings', 'fix-email-sender' ) );
			?>
		</form>

		<hr>

		<div class="card">
			<h2><?php esc_html_e( 'Test Email Configuration', 'fix-email-sender' ); ?></h2>
			<p><?php esc_html_e( 'Send a test email to verify your settings are working correctly.', 'fix-email-sender' ); ?></p>

			<form method="post" action="">
				<?php wp_nonce_field( 'fes_test_email', 'fes_test_email_nonce' ); ?>
				<p>
					<label for="test_email_recipient">
						<?php esc_html_e( 'Send test email to:', 'fix-email-sender' ); ?>
					</label>
					<input type="email"
							id="test_email_recipient"
							name="test_email_recipient"
							value="<?php echo esc_attr( wp_get_current_user()->user_email ); ?>"
							class="regular-text"
							required>
				</p>
				<p>
					<?php submit_button( __( 'Send Test Email', 'fix-email-sender' ), 'secondary', 'send_test_email', false ); ?>
				</p>
			</form>
		</div>

		<hr>

		<div class="card">
			<h2><?php esc_html_e( 'Current Settings', 'fix-email-sender' ); ?></h2>
			<table class="widefat">
				<thead>
					<tr>
						<th><?php esc_html_e( 'Setting', 'fix-email-sender' ); ?></th>
						<th><?php esc_html_e( 'Value', 'fix-email-sender' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><strong><?php esc_html_e( 'From Email', 'fix-email-sender' ); ?></strong></td>
						<td><code><?php echo esc_html( get_option( 'fes_email_from_email', get_option( 'admin_email' ) ) ); ?></code></td>
					</tr>
					<tr>
						<td><strong><?php esc_html_e( 'From Name', 'fix-email-sender' ); ?></strong></td>
						<td><code><?php echo esc_html( get_option( 'fes_email_from_name', get_option( 'blogname' ) ) ); ?></code></td>
					</tr>
					<tr>
						<td><strong><?php esc_html_e( 'Reply-To', 'fix-email-sender' ); ?></strong></td>
						<td><code><?php echo esc_html( get_option( 'fes_email_reply_to', get_option( 'admin_email' ) ) ); ?></code></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<?php
}

/**
 * Handle test email sending
 */
function fes_handle_test_email() {
	// Check if test email form was submitted.
	if ( ! isset( $_POST['send_test_email'] ) ) {
		return;
	}

	// Verify nonce.
	if ( ! isset( $_POST['fes_test_email_nonce'] ) ||
		! wp_verify_nonce( $_POST['fes_test_email_nonce'], 'fes_test_email' ) ) {
		return;
	}

	// Check user capabilities.
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// Get and validate recipient email.
	$recipient = isset( $_POST['test_email_recipient'] ) ? sanitize_email( $_POST['test_email_recipient'] ) : '';

	if ( ! is_email( $recipient ) ) {
		add_settings_error(
			'fes_email_settings',
			'invalid_test_email',
			__( 'Please enter a valid recipient email address.', 'fix-email-sender' ),
			'error'
		);
		return;
	}

	// Send test email.
	$subject = __( 'Test Email from Fix Email Sender Plugin', 'fix-email-sender' );
	$message = sprintf(
		__( "This is a test email from your WordPress site.\n\nSite: %1\$s\nSent at: %2\$s\n\nEmail Settings:\n- From Email: %3\$s\n- From Name: %4\$s\n- Reply-To: %5\$s\n\nIf you received this email, your email settings are working correctly!", 'fix-email-sender' ),
		get_bloginfo( 'name' ),
		current_time( 'mysql' ),
		get_option( 'fes_email_from_email', get_option( 'admin_email' ) ),
		get_option( 'fes_email_from_name', get_option( 'blogname' ) ),
		get_option( 'fes_email_reply_to', get_option( 'admin_email' ) )
	);

	$sent = wp_mail( $recipient, $subject, $message );

	if ( $sent ) {
		add_settings_error(
			'fes_email_settings',
			'test_email_sent',
			sprintf( __( 'Test email sent successfully to %s. Check your inbox and spam folder.', 'fix-email-sender' ), $recipient ),
			'success'
		);
	} else {
		add_settings_error(
			'fes_email_settings',
			'test_email_failed',
			__( 'Failed to send test email. Please check your email configuration and server settings.', 'fix-email-sender' ),
			'error'
		);
	}
}
add_action( 'admin_init', 'fes_handle_test_email' );

/**
 * Filter wp_mail from email - uses high priority to override other plugins
 *
 * @param string $from_email Original from email.
 * @return string Modified from email.
 */
function fes_wp_mail_from( $from_email ) {
	$custom_email = get_option( 'fes_email_from_email' );

	if ( $custom_email && is_email( $custom_email ) ) {
		return $custom_email;
	}

	return $from_email;
}
add_filter( 'wp_mail_from', 'fes_wp_mail_from', 999999 );

/**
 * Filter wp_mail from name - uses high priority to override other plugins
 *
 * @param string $from_name Original from name.
 * @return string Modified from name.
 */
function fes_wp_mail_from_name( $from_name ) {
	$custom_name = get_option( 'fes_email_from_name' );

	if ( $custom_name ) {
		return sanitize_text_field( $custom_name );
	}

	return $from_name;
}
add_filter( 'wp_mail_from_name', 'fes_wp_mail_from_name', 999999 );

/**
 * Add Reply-To header to wp_mail - uses high priority to override other plugins
 *
 * @param array $args Email arguments.
 * @return array Modified email arguments.
 */
function fes_wp_mail_reply_to( $args ) {
	$reply_to = get_option( 'fes_email_reply_to' );

	if ( $reply_to && is_email( $reply_to ) ) {
		// Initialize headers array if it doesn't exist.
		if ( ! isset( $args['headers'] ) ) {
			$args['headers'] = array();
		}

		// Convert headers to array if it's a string.
		if ( is_string( $args['headers'] ) ) {
			$args['headers'] = explode( "\n", $args['headers'] );
		}

		// Add Reply-To header if it doesn't already exist.
		$has_reply_to = false;
		foreach ( $args['headers'] as $header ) {
			if ( stripos( $header, 'reply-to:' ) !== false ) {
				$has_reply_to = true;
				break;
			}
		}

		if ( ! $has_reply_to ) {
			$args['headers'][] = 'Reply-To: ' . $reply_to;
		}
	}

	return $args;
}
add_filter( 'wp_mail', 'fes_wp_mail_reply_to', 999999 );

/**
 * Override PHPMailer settings directly for maximum compatibility
 *
 * @param PHPMailer $phpmailer PHPMailer instance.
 */
function fes_force_phpmailer_sender_settings( $phpmailer ) {
	$from_email = get_option( 'fes_email_from_email' );
	$from_name  = get_option( 'fes_email_from_name' );
	$reply_to   = get_option( 'fes_email_reply_to' );

	if ( $from_email && is_email( $from_email ) ) {
		// Force the sender.
		$phpmailer->From = $from_email;

		if ( $from_name ) {
			$phpmailer->FromName = $from_name;
		}

		// Force the return path (helps with bounces).
		$phpmailer->Sender = $from_email;

		// Set From using PHPMailer method.
		try {
			$phpmailer->setFrom( $from_email, $from_name ? $from_name : '', false );
		} catch ( Exception $e ) {
			// Silently fail if setFrom throws an exception.
		}
	}

	// Add Reply-To header.
	if ( $reply_to && is_email( $reply_to ) ) {
		try {
			// Clear any existing reply-to addresses first.
			$phpmailer->clearReplyTos();
			$phpmailer->addReplyTo( $reply_to, $from_name ? $from_name : '' );
		} catch ( Exception $e ) {
			// Silently fail if addReplyTo throws an exception.
		}
	}
}
add_action( 'phpmailer_init', 'fes_force_phpmailer_sender_settings', 999999 );

/**
 * Additional filter for wp_mail arguments to ensure headers are set
 *
 * @param array $args Email arguments.
 * @return array Modified email arguments.
 */
function fes_force_mail_headers( $args ) {
	$from_email = get_option( 'fes_email_from_email' );
	$from_name  = get_option( 'fes_email_from_name' );
	$reply_to   = get_option( 'fes_email_reply_to' );

	// Only proceed if we have custom settings.
	if ( ! $from_email || ! is_email( $from_email ) ) {
		return $args;
	}

	// Ensure headers is an array.
	if ( ! isset( $args['headers'] ) || ! is_array( $args['headers'] ) ) {
		$args['headers'] = empty( $args['headers'] ) ? array() : array( $args['headers'] );
	}

	// Add/Override From header.
	$from_exists = false;
	foreach ( $args['headers'] as $key => $header ) {
		if ( stripos( $header, 'from:' ) === 0 ) {
			$args['headers'][ $key ] = 'From: ' . ( $from_name ? $from_name . ' <' . $from_email . '>' : $from_email );
			$from_exists             = true;
		}
	}

	if ( ! $from_exists ) {
		$args['headers'][] = 'From: ' . ( $from_name ? $from_name . ' <' . $from_email . '>' : $from_email );
	}

	// Add Return-Path header.
	$args['headers'][] = 'Return-Path: ' . $from_email;

	// Add Reply-To header if not already added.
	if ( $reply_to && is_email( $reply_to ) ) {
		$reply_exists = false;
		foreach ( $args['headers'] as $header ) {
			if ( stripos( $header, 'reply-to:' ) === 0 ) {
				$reply_exists = true;
				break;
			}
		}

		if ( ! $reply_exists ) {
			$args['headers'][] = 'Reply-To: ' . $reply_to;
		}
	}

	return $args;
}
add_filter( 'wp_mail', 'fes_force_mail_headers', 999999 );

/**
 * Log what's happening (for debugging)
 *
 * @param WP_Error $wp_error WordPress error object.
 */
function fes_log_mail_failures( $wp_error ) {
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG && defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG ) {
		error_log( 'Fix Email Sender - WordPress Mail Error: ' . print_r( $wp_error, true ) );
	}
}
add_action( 'wp_mail_failed', 'fes_log_mail_failures', 10, 1 );

/**
 * Add settings link to plugins page
 *
 * @param array $links Plugin action links.
 * @return array Modified plugin action links.
 */
function fes_add_settings_link( $links ) {
	$settings_link = '<a href="' . admin_url( 'tools.php?page=fes-email-settings' ) . '">' . __( 'Settings', 'fix-email-sender' ) . '</a>';
	array_unshift( $links, $settings_link );
	return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'fes_add_settings_link' );

/**
 * Plugin activation hook - set default values
 */
function fes_activate_plugin() {
	// Set default values if not already set.
	if ( ! get_option( 'fes_email_from_email' ) ) {
		update_option( 'fes_email_from_email', get_option( 'admin_email' ) );
	}

	if ( ! get_option( 'fes_email_from_name' ) ) {
		update_option( 'fes_email_from_name', get_option( 'blogname' ) );
	}

	if ( ! get_option( 'fes_email_reply_to' ) ) {
		update_option( 'fes_email_reply_to', get_option( 'admin_email' ) );
	}
}
register_activation_hook( __FILE__, 'fes_activate_plugin' );
