<?php
/*
Plugin Name: Plugin Installation Checker
Description: Displays a list of installed plugins with installation dates in the WordPress admin area.
Version: 1.0.0
Author: Vencer Olermo
*/

defined('ABSPATH') || exit; // Prevent direct access to this file

class Plugin_Installation_Checker {
    public function __construct() {
        add_action('admin_menu', array($this, 'add_custom_plugins_page'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_styles'));
        add_action('admin_init', array($this, 'create_assets_folder'));
    }

    public function add_custom_plugins_page() {
        add_menu_page('Installed Plugins', 'Installed Plugins', 'manage_options', 'installed-plugins', array($this, 'display_installed_plugins'));
    }

    public function display_installed_plugins() {
        $plugins = get_plugins();
        $active_plugins = get_option('active_plugins');
        $inactive_plugins = array_diff_key($plugins, array_flip($active_plugins));

        echo '<div class="plugin-installation-checker">';
        echo '<div class="plugin-installation-checker-header">';
        echo '<h1>Plugin Installation Checker</h1>';
        echo '<p>Displays a list of installed plugins with installation dates in the WordPress admin area.</p>';
        echo '</div>';

        echo '<div class="plugin-installation-checker-content">';

        echo '<div class="plugin-installation-checker-column">';
        echo '<h2>Activated Plugins:</h2>';

        if (count($active_plugins) > 0) {
            echo '<table>';

            foreach ($active_plugins as $plugin_path) {
                $plugin_info = $plugins[$plugin_path];
                $plugin_file = WP_PLUGIN_DIR . '/' . $plugin_path;
                $install_date = date('F j, Y', filemtime($plugin_file));
                echo '<tr>';
                echo '<td>' . esc_html($plugin_info['Name']) . '</td>';
                echo '<td>Installed on ' . esc_html($install_date) . '</td>';
                echo '</tr>';
            }

            echo '</table>';
        } else {
            echo '<p>No activated plugins currently.</p>';
        }

        echo '</div>';

        echo '<div class="plugin-installation-checker-column">';
        echo '<h2>Deactivated Plugins:</h2>';

        if (count($inactive_plugins) > 0) {
            echo '<table>';

            foreach ($inactive_plugins as $plugin_path => $plugin_info) {
                $plugin_file = WP_PLUGIN_DIR . '/' . $plugin_path;
                $install_date = date('F j, Y', filemtime($plugin_file));
                echo '<tr>';
                echo '<td>' . esc_html($plugin_info['Name']) . '</td>';
                echo '<td>Installed on ' . esc_html($install_date) . '</td>';
                echo '</tr>';
            }

            echo '</table>';
        } else {
            echo '<p>No deactivated plugins currently.</p>';
        }

        echo '</div>';

        echo '</div>';
        echo '</div>';
    }

    public function enqueue_styles() {
        wp_enqueue_style('plugin-installation-checker-style', plugins_url('assets/css/plugin-installation-checker.css', __FILE__));
    }

    public function create_assets_folder() {
        $assets_folder = plugin_dir_path(__FILE__) . 'assets';

        if (!is_dir($assets_folder)) {
            mkdir($assets_folder);
        }
    }
}

new Plugin_Installation_Checker();
