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
    $active_plugins = get_option('active_plugins');
    $inactive_plugins = array_diff_key($plugins, array_flip($active_plugins));

    if (count($active_plugins) > 0) {
        echo '<h2>Activated Plugins:</h2>';
        echo '<ul>';

        foreach ($active_plugins as $plugin_path) {
            $plugin_info = $plugins[$plugin_path];
            $plugin_file = WP_PLUGIN_DIR . '/' . $plugin_path;
            $install_date = date('F j, Y', filemtime($plugin_file));
            echo '<li>' . $plugin_info['Name'] . ' (Installed on ' . $install_date . ')</li>';
        }

        echo '</ul>';
    } else {
        echo '<p>No activated plugins currently.</p>';
    }

    if (count($inactive_plugins) > 0) {
        echo '<h2>Deactivated Plugins:</h2>';
        echo '<ul>';

        foreach ($inactive_plugins as $plugin_path => $plugin_info) {
            $plugin_file = WP_PLUGIN_DIR . '/' . $plugin_path;
            $install_date = date('F j, Y', filemtime($plugin_file));
            echo '<li>' . $plugin_info['Name'] . ' (Installed on ' . $install_date . ')</li>';
        }

        echo '</ul>';
    } else {
        echo '<p>No deactivated plugins currently.</p>';
    }
}

function add_custom_plugins_page() {
    add_menu_page('Installed Plugins', 'Installed Plugins', 'manage_options', 'installed-plugins', 'display_installed_plugins');
}

add_action('admin_menu', 'add_custom_plugins_page');
