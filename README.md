# Fix Email Sender Address

**Version:** 1.0
**License:** GPL v2 or later
**Requires at least:** WordPress 5.0
**Tested up to:** WordPress 6.7
**Requires PHP:** 7.4

## Description

This plugin allows you to configure your site's email sender information through the WordPress admin dashboard interface. Prevents emails from being sent from generic server addresses like `wordpress@yourhostname.com`

This plugin provides a user-friendly admin interface located under **Tools → Email Settings** where you can dynamically configure:

- **From Email Address** - The email that appears as the sender
- **From Name** - The name that appears as the sender
- **Reply-To Email Address** - Where replies are directed

All settings are applied dynamically across all WordPress emails, including:

- User registration emails
- Password reset emails
- Comment notifications
- Contact form submissions
- WooCommerce order notifications
- Any other emails sent via `wp_mail()`

## Features

### Core Features

- **Easy Configuration Interface** - Simple admin panel under Tools menu
- **Dynamic Email Settings** - Change sender info without editing code
- **Built-in Test Email** - Verify your settings work correctly
- **Maximum Compatibility** - Multiple filter hooks ensure settings override other plugins
- **PHPMailer Integration** - Direct PHPMailer manipulation for stubborn hosts
- **Visual Settings Display** - See your current configuration at a glance
- **Security First** - Email validation, nonce verification, and capability checks

### Technical Features

- High-priority filters (999999) to override other plugins
- Multiple email filter hooks for comprehensive coverage:
  - `wp_mail_from`
  - `wp_mail_from_name`
  - `wp_mail` (for headers)
  - `phpmailer_init` (direct PHPMailer control)
- Proper Reply-To header implementation
- Return-Path header support
- Email validation and sanitization
- Error logging (when WP_DEBUG is enabled)
- Clean, well-documented code following WordPress coding standards

## Installation

### Method 1: Manual Installation

1. Download the plugin files
2. Upload the `fix-email-sender` folder to `/wp-content/plugins/`
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Go to **Tools → Email Settings** to configure

### Method 2: Direct Upload

1. Go to **Plugins → Add New** in your WordPress admin
2. Click **Upload Plugin**
3. Choose the plugin ZIP file
4. Click **Install Now**
5. Activate the plugin
6. Go to **Tools → Email Settings** to configure

### First-Time Setup

Upon activation, the plugin automatically sets default values based on your WordPress configuration:

- **From Email** defaults to your site's admin email
- **From Name** defaults to your site's name
- **Reply-To** defaults to your site's admin email

You can customize these immediately after activation.

## Usage

### Configuring Email Settings

1. Navigate to **Tools → Email Settings** in your WordPress admin dashboard
2. You'll see three configuration fields:

   **From Email Address**
   - Enter the email address you want to appear as the sender
   - Must be a valid email address
   - Example: `office@yourdomain.com`

   **From Name**
   - Enter the name you want to appear as the sender
   - This is what recipients see before the email address
   - Example: `Your Company Name`

   **Reply-To Email Address**
   - Enter the email where you want replies to be sent
   - Can be the same as or different from the From Email
   - Example: `support@yourdomain.com`

3. Click **Save Email Settings**
4. You'll see a success message confirming your settings were saved

### Testing Your Configuration

The plugin includes a built-in test email feature:

1. After saving your settings, scroll to the **Test Email Configuration** section
2. Enter a recipient email address (defaults to your user email)
3. Click **Send Test Email**
4. Check your inbox (and spam folder) for the test email
5. Verify the sender information is correct

The test email includes:

- Your site name
- Timestamp
- All current email settings
- Confirmation message

### Viewing Current Settings

The **Current Settings** table at the bottom of the settings page displays:

- Your active From Email
- Your active From Name
- Your active Reply-To address

This allows you to quickly verify your configuration without editing.

## Frequently Asked Questions

### Why do I need this plugin?

By default, WordPress sends emails from generic server addresses (like `wordpress@yourhostname.com`), which:

- Look unprofessional
- May be marked as spam
- Don't match your brand
- Can't receive replies

This plugin fixes all of these issues with an easy-to-use interface.

### Will this work with my email plugin?

Yes! This plugin uses high-priority filters (999999) to ensure your settings override other plugins. It works alongside:

- Contact Form 7
- WPForms
- Gravity Forms
- WooCommerce
- Easy Digital Downloads
- Any plugin using `wp_mail()`

### What if emails still show the wrong sender?

If emails still show incorrect sender information:

1. **Check your settings** - Make sure you saved them correctly
2. **Send a test email** - Use the built-in test feature
3. **Check spam filters** - Some email providers override sender info
4. **Enable debugging** - Set `WP_DEBUG` to `true` in wp-config.php
5. **Check your host** - Some hosts restrict sender addresses
6. **SPF/DKIM Records** - Ensure your domain's DNS is configured correctly

### Does this plugin send emails?

No, this plugin only controls WHO the email appears to be from. It doesn't:

- Send emails itself
- Replace your SMTP configuration
- Handle email delivery

For email delivery issues, consider an SMTP plugin like WP Mail SMTP or Post SMTP.

### Can I use different sender addresses for different email types?

Not directly. This plugin sets a global sender for all WordPress emails. If you need different senders for different email types, you'd need custom code to hook into specific email functions.

### Is this plugin compatible with SMTP plugins?

Yes! This plugin works great with SMTP plugins. Use an SMTP plugin for reliable email delivery, and this plugin for sender identity control.

### Will this work on shared hosting?

Yes, it's designed to work on any hosting environment. The plugin uses multiple methods to ensure compatibility even on restrictive hosts.

## Support & Compatibility

### WordPress Compatibility

- Requires WordPress 5.0 or higher
- Tested up to WordPress 6.7
- Works with Classic Editor and Block Editor

### PHP Compatibility

- Requires PHP 7.4 or higher
- Tested with PHP 8.0, 8.1, 8.2, and 8.3

### Plugin Compatibility

Works with popular plugins including:

- WooCommerce
- Contact Form 7
- WPForms
- Gravity Forms
- Easy Digital Downloads
- BuddyPress
- bbPress
- And many more!

## Troubleshooting

### Emails Not Sending

This plugin controls sender identity, not email delivery. If emails aren't sending at all:

- Check your server's email configuration
- Consider using an SMTP plugin
- Enable WP_DEBUG to see error messages

### Sender Still Shows Wrong Address

1. Clear any caching plugins
2. Deactivate other email-related plugins temporarily
3. Check if your host enforces specific sender addresses
4. Verify your domain's DNS/SPF records

### Test Email Not Received

1. Check your spam/junk folder
2. Verify the recipient email is correct
3. Check server error logs
4. Contact your hosting provider about email restrictions

## Changelog

### Version 1.0 (2025-11-11)

**Initial Release**

Features:

- Admin settings page under Tools menu
- Three configurable fields (From Email, From Name, Reply-To)
- Built-in test email functionality
- Current settings display table
- High-priority email filters for maximum compatibility
- Direct PHPMailer integration
- Settings link on plugins page
- Automatic default values on activation
- Email validation and sanitization
- Security hardening (nonces, capability checks)
- Error logging for debugging
- WordPress coding standards compliant

## Development

### Hooks & Filters

The plugin implements these WordPress hooks:

**Actions:**

- `admin_menu` - Adds settings page
- `admin_init` - Registers settings and handles test emails
- `phpmailer_init` - Configures PHPMailer directly
- `wp_mail_failed` - Logs email failures
- `plugin_action_links_{basename}` - Adds settings link

**Filters:**

- `wp_mail_from` (priority 999999) - Sets from email
- `wp_mail_from_name` (priority 999999) - Sets from name
- `wp_mail` (priority 999999) - Adds headers including Reply-To

### Settings Storage

Settings are stored in WordPress options table:

- `fes_email_from_email` - From email address
- `fes_email_from_name` - From name
- `fes_email_reply_to` - Reply-to email address

### Function Prefix

All functions are prefixed with `fes_` to avoid conflicts:
- `fes_add_email_settings_page()`
- `fes_register_email_settings()`
- `fes_wp_mail_from()`
- etc.

## Privacy & Data

This plugin:

- **Does NOT collect** any user data
- **Does NOT send** data to external services
- **Does NOT track** users
- **Only stores** the three email configuration settings in your WordPress database
- **Does NOT modify** email content

## Security

Security features:

- Email validation using WordPress `sanitize_email()` and `is_email()`
- Nonce verification for all form submissions
- Capability checks (`manage_options` required)
- Input sanitization for all fields
- SQL injection prevention via WordPress APIs
- XSS prevention via proper escaping

## Credits

**Developed by:** Chris Ocen

Built with care for the WordPress community.

## License

This plugin is licensed under the GPL v2 or later.

```
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
```

## Support

For bug reports, feature requests, or general support:

- Check the FAQ section above
- Review the Troubleshooting section
- Contact: https://ocenchris.com

## Contributing

We welcome contributions! If you'd like to contribute:

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Submit a pull request

Please follow WordPress coding standards and include clear commit messages.

---

**Thank you for using Fix Email Sender Address!**

If this plugin helps your WordPress site, please consider leaving a review.
