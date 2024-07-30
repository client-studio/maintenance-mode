<?php
/**
 * Plugin Name: Maintenance Mode
 * Description: Redirects all non-admin traffic to the /maintenance/ page, except for specific user agents
 * Version: 1.2
 * Author: Client.Studio
 */

 function maintenance_redirect() {
  $maintenance_url = site_url('/maintenance/');
  $current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
  $allowed_user_agents = array('Googlebot', 'Bingbot', 'Slurp');
  
  // Check if the 'vip' parameter is present in the URL
  if (isset($_GET['vip'])) {
    return; // Allow access if 'vip' parameter is present
  }

  // Check if the user agent is in the allowed list or if the user is an administrator
  $is_allowed_user_agent = false;
  foreach ($allowed_user_agents as $user_agent) {
    if (stripos($_SERVER['HTTP_USER_AGENT'], $user_agent) !== false) {
      $is_allowed_user_agent = true;
      break;
    }
  }

  if (!current_user_can('administrator') && $current_url !== $maintenance_url && !$is_allowed_user_agent) {
    wp_redirect($maintenance_url);
    exit;
  }
}
add_action('template_redirect', 'maintenance_redirect');
