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

/*
The administrative and activation related functions.
*/
function e4kreg_activate() {
  add_option('e4kreg_g_user', 'user');
  add_option('e4kreg_g_pass', 'pass');
  add_option('e4kreg_g_spreadsheet', 'key');
  add_option('e4kreg_date_phase1', '1/1/2012');
  add_option('e4kreg_date_phase2', '1/2/2012');
  add_option('e4kreg_date_close', '1/3/2012');
  add_option('e4kreg_date_freeze', '1/4/2012');
  add_option('e4kreg_limit_phase1', 200);
  add_option('e4kreg_limit_phase2', 300);
  add_option('e4kreg_limit_wl', 50);
}

function e4kreg_deactivate() {
  delete_option('e4kreg_g_user');
  delete_option('e4kreg_g_pass');
  delete_option('e4kreg_g_spreadsheet');
  delete_option('e4kreg_date_phase1');
  delete_option('e4kreg_date_phase2');
  delete_option('e4kreg_date_close');
  delete_option('e4kreg_date_freeze');
  delete_option('e4kreg_limit_phase1');
  delete_option('e4kreg_limit_phase2');
  delete_option('e4kreg_limit_wl');
}

function e4kreg_register_settings() {
  register_setting('e4kreg-settings-group', 'e4kreg_g_user');
  register_setting('e4kreg-settings-group', 'e4kreg_g_pass');
  register_setting('e4kreg-settings-group', 'e4kreg_g_spreadsheet');
  register_setting('e4kreg-settings-group', 'e4kreg_date_phase1');
  register_setting('e4kreg-settings-group', 'e4kreg_date_phase2');
  register_setting('e4kreg-settings-group', 'e4kreg_date_close');
  register_setting('e4kreg-settings-group', 'e4kreg_date_freeze');
  register_setting('e4kreg-settings-group', 'e4kreg_limit_phase1');
  register_setting('e4kreg-settings-group', 'e4kreg_limit_phase2');
  register_setting('e4kreg-settings-group', 'e4kreg_limit_wl');
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
  <h3>Google Settings</h3>
  <table class="form-table">
    <tr valign="top">
      <th scope="row">Gmail username</th>
      <td><input type="text" name="e4kreg_g_user"
           value="<?php echo get_option('e4kreg_g_user'); ?>" />
      </td>
    </tr>
    <tr valign="top">
      <th scope="row">Gmail password</th>
      <td><input type="password" name="e4kreg_g_pass"
           value="<?php echo get_option('e4kreg_g_pass'); ?>" />
      </td>
    </tr>
    <tr valign="top">
      <th scope="row">Google Spreadsheet Key</th>
      <td><input type="text" name="e4kreg_g_spreadsheet"
           value="<?php echo get_option('e4kreg_g_spreadsheet'); ?>" />
      </td>
    </tr>
  </table>
  <h3>Registration Dates</h3>
  <table class="form-table">
    <tr valign="top">
      <th scope="row">Phase 1 Opening Date</th>
      <td><input type="text" name="e4kreg_date_phase1"
           value="<?php echo get_option('e4kreg_date_phase1'); ?>" />
      </td>
    </tr>
    <tr valign="top">
      <th scope="row">Phase 2 Opening Date</th>
      <td><input type="text" name="e4kreg_date_phase2"
           value="<?php echo get_option('e4kreg_date_phase2'); ?>" />
      </td>
    </tr>
    <tr valign="top">
      <th scope="row">Closing Date</th>
      <td><input type="text" name="e4kreg_date_close"
           value="<?php echo get_option('e4kreg_date_close'); ?>" />
      </td>
    </tr>
    <tr valign="top">
      <th scope="row">Freeze Date</th>
      <td><input type="text" name="e4kreg_date_freeze"
           value="<?php echo get_option('e4kreg_date_freeze'); ?>" />
      </td>
    </tr>
  </table>
  <h3>Registration Limits</h3>
  <table class="form-table">
    <tr valign="top">
      <th scope="row">Phase 1 Limit</th>
      <td><input type="text" name="e4kreg_limit_phase1"
           value="<?php echo get_option('e4kreg_limit_phase1'); ?>" />
      </td>
    </tr>
    <tr valign="top">
      <th scope="row">Phase 2 Limit</th>
      <td><input type="text" name="e4kreg_limit_phase2"
           value="<?php echo get_option('e4kreg_limit_phase2'); ?>" />
      </td>
    </tr>
    <tr valign="top">
      <th scope="row">Waitlist Limit</th>
      <td><input type="text" name="e4kreg_limit_wl"
           value="<?php echo get_option('e4kreg_limit_wl'); ?>" />
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

/*
Google spreadsheet client
*/

function e4kreg_get_spreadsheet_service() {
  require_once('Zend/Loader.php');
  Zend_Loader::loadClass('Zend_Http_Client');
  Zend_Loader::loadClass('Zend_Gdata');
  Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
  Zend_Loader::loadClass('Zend_Gdata_Spreadsheets');
  
  try {
    $service = Zend_Gdata_Spreadsheets::AUTH_SERVICE_NAME;
    $g_user = get_option('e4kreg_g_user');
    $g_pass = get_option('e4kreg_g_pass');
    $client = Zend_Gdata_ClientLogin::getHttpClient($g_user, $g_pass, $service);
    $spreadsheetService = new Zend_Gdata_Spreadsheets($client);
  
    return $spreadsheetService;
  }
  catch (Exception $e) {
    return false;
  }
}

/*
Where the action starts.
*/

register_activation_hook(__FILE__, 'e4kreg_activate');
register_deactivation_hook(__FILE__, 'e4kreg_deactivate');

if (is_admin()) {
  add_action('admin_menu', 'e4kreg_admin_menu');
}
?>