<?php
/*
Plugin Name: Plugin Installation Checker
Description: Displays a list of installed plugins with installation dates in the WordPress admin area.
Version: 1.0.0
Author: Vencer Olermo
*/
if (!defined('ABSPATH')) {
    echo 'Dont';
    exit;
}

function display_installed_plugins() {
    $plugins = get_plugins();

    if (count($plugins) > 0) {
        echo '<h2>Installed Plugins:</h2>';
        echo '<ul>';

        foreach ($plugins as $plugin_path => $plugin_info) {
            $plugin_file = WP_PLUGIN_DIR . '/' . $plugin_path;
            $install_date = date('F j, Y', filemtime($plugin_file));
            echo '<li>' . $plugin_info['Name'] . ' (Installed on ' . $install_date . ')</li>';
        }

        echo '</ul>';
    } else {
        echo 'No plugins are currently installed.';
    }
}

function add_custom_plugins_page() {
    add_menu_page('Installed Plugins', 'Installed Plugins', 'manage_options', 'installed-plugins', 'display_installed_plugins');
}

add_action('admin_menu', 'add_custom_plugins_page');