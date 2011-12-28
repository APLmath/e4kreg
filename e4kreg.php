<?php
/*
Plugin Name: E4KReg
Plugin URI: http://e4k.berkeley.edu
Description: Google Spreadsheets-based registration system for E4K.
Version: 1.0
Author: Andrew Lee
Author URI: http://andrewlee.info
License: GPL
*/

function e4kreg_activate() {
  add_option('e4kreg_g_user', 'user');
  add_option('e4kreg_g_pass', 'pass');
  add_option('e4kreg_g_spreadsheet', 'key');
}

function e4kreg_deactivate() {
  delete_option('e4kreg_g_user');
  delete_option('e4kreg_g_pass');
  delete_option('e4kreg_g_spreadsheet');
}

function e4kreg_register_settings() {
  register_setting('e4kreg-settings-group', 'e4kreg_g_user');
  register_setting('e4kreg-settings-group', 'e4kreg_g_pass');
  register_setting('e4kreg-settings-group', 'e4kreg_g_spreadsheet');
}

function e4kreg_admin_menu() {
  add_options_page('E4K Registration Settings', 'E4K Registration',
    'administrator', 'e4k-reg', 'e4kreg_admin_page');
  add_action('admin_init', 'e4kreg_register_settings');
}

function e4kreg_admin_page() {
?>
<div class="wrap">
<h2>E4K Registration Settings</h2>
<form method="post" action="options.php">
  <?php settings_fields('e4kreg-settings-group'); ?>
  <h4>Google Settings</h4>
  <table class="form-table">
    <tr valign="top">
      <th scope="row">Gmail username</th>
      <td><input type="text" name="e4kreg_g_user"
           value="<?php echo get_option('e4k_g_user'); ?>" />
      </td>
    </tr>
    <tr valign="top">
      <th scope="row">Gmail password</th>
      <td><input type="text" name="e4kreg_g_pass"
           value="<?php echo get_option('e4k_g_pass'); ?>" />
      </td>
    </tr>
    <tr valign="top">
      <th scope="row">Google Spreadsheet Key</th>
      <td><input type="text" name="e4kreg_g_spreadsheet"
           value="<?php echo get_option('e4k_g_spreadsheet'); ?>" />
      </td>
    </tr>
  </table>

  <p class="submit">
    <input type="submit" class="button-primary"
     value="<?php _e('Save Changes'); ?>" />
  </p>
</form>
</div>
<?php
}

register_activation_hook(__FILE__, 'e4kreg_activate');
register_deactivation_hook(__FILE__, 'e4kreg_deactivate');

if (is_admin()) {
  add_action('admin_menu', 'e4kreg_admin_menu');
}
?>