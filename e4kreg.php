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

require_once('includes/admin.php');

register_activation_hook(__FILE__, 'e4kreg_activate');
register_deactivation_hook(__FILE__, 'e4kreg_deactivate');

if (is_admin()) {
  add_action('admin_menu', 'e4kreg_admin_menu');
}
?>