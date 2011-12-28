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

register_activation_hook(__FILE__, 'e4kreg_activate');
register_deactivation_hook(__FILE__, 'e4kreg_deactivate');

function e4kreg_activate() {
  add_option('e4kreg_g_user', '');
  add_option('e4kreg_g_pass', '');
  add_option('e4kreg_g_key', '');
}

function e4kreg_deactivate() {
  delete_option('e4kreg_g_user');
  delete_option('e4kreg_g_pass');
  delete_option('e4kreg_g_key');
}

if (is_admin()) {
  add_action('admin_menu', 'e4kreg_admin_menu');

  function e4kreg_admin_menu() {
    add_options_page('E4K Registration', 'E4K Registration Settings', 'administrator', 'e4kreg', 'e4kreg_admin_page');
  }
}

function e4kreg_admin_page() {
?>
<div class="wrap">
<h2>E4K Registration Settings</h2>
<p>Cool...
</p>
</div>
<?php
}
?>