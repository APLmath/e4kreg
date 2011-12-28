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

// The E4KReg options and their default values
$e4kreg_options = array(
  'e4kreg_g_user' => 'user',
  'e4kreg_g_pass' => 'pass',
  'e4kreg_g_spreadsheet' => '12345'
);

register_activation_hook(__FILE__, 'e4kreg_activate');
register_deactivation_hook(__FILE__, 'e4kreg_deactivate');

function e4kreg_activate() {
  foreach ($e4kreg_options as $option_name => $default) {
    add_option($option_name, $default);
  }
}

function e4kreg_deactivate() {
  foreach ($e4kreg_options as $option_name => $default) {
    delete_option($option_name);
  }
}

if (is_admin()) {
  add_action('admin_menu', 'e4kreg_admin_menu');

  function e4kreg_admin_menu() {
    add_options_page('E4K Registration Settings', 'E4K Registration',
      'administrator', 'e4k-reg', 'e4kreg_admin_page');
    add_action('admin_init', 'e4kreg_register_settings')
  }
}

function e4kreg_register_settings() {
  foreach ($e4kreg_options as $option_name => $default) {
    register_setting('e4kreg-settings-group', $option_name);
  }
}

function e4kreg_admin_page() {
?>
<div class="wrap">
<h2>E4K Registration Settings</h2>
<form method="post" action="options.php">
  <?php settings_fields('e4kreg-settings-group'); ?>
  <?php do_settings('e4kreg-settings-group'); ?>
  <h4>Authentication</h4>
  <p><label for="e4kreg_g_user">Gmail username</label></p>
  <p><input type="text" id="e4kreg_g_user" name="e4kreg_g_user"
      value="<?php echo get_option('e4kreg_g_user') ?>" /></p>
  <p><label for="e4kreg_g_user">Gmail password</label></p>
  <p><input type="text" id="e4kreg_g_pass" name="e4kreg_g_pass"
      value="<?php echo get_option('e4kreg_g_pass') ?>" /></p>
  <p><label for="e4kreg_g_spreadsheet">Google Spreadsheet Key</label></p>
  <p><input type="text" id="e4kreg_g_spreadsheet" name="e4kreg_g_user"
      value="<?php echo get_option('e4kreg_g_spreadsheet') ?>" /></p>
  <p class="submit">
    <input type="submit" class="button-primary"
     value="<?php _e('Save Changes') ?>" />
  </p>
</form>
</div>
<?php
}
?>