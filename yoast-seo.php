<?php

/**
 * Plugin Name: Import WP - Yoast SEO Importer Addon
 * Plugin URI: https://www.importwp.com
 * Description: Allow Import WP to import Yoast SEO fields.
 * Author: James Collings <james@jclabs.co.uk>
 * Version: 2.1.1 
 * Author URI: https://www.importwp.com
 * Network: True
 */

add_action('admin_init', 'iwp_yoast_check');

function iwp_yoast_requirements_met()
{
    return false === (is_admin() && current_user_can('activate_plugins') &&  (!function_exists('wpseo_init') || (!function_exists('import_wp_pro') && !function_exists('import_wp')) || version_compare(IWP_VERSION, '2.5.0', '<')));
}

function iwp_yoast_check()
{
    if (!iwp_yoast_requirements_met()) {

        add_action('admin_notices', 'iwp_yoast_notice');

        deactivate_plugins(plugin_basename(__FILE__));

        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }
    }
}

function iwp_yoast_setup()
{
    if (!iwp_yoast_requirements_met()) {
        return;
    }

    $base_path = dirname(__FILE__);

    require_once $base_path . '/setup.php';

    // Install updater
    if (file_exists($base_path . '/updater.php') && !class_exists('IWP_Updater')) {
        require_once $base_path . '/updater.php';
    }

    if (class_exists('IWP_Updater')) {
        $updater = new IWP_Updater(__FILE__, 'importwp-yoast-seo');
        $updater->initialize();
    }
}
add_action('plugins_loaded', 'iwp_yoast_setup', 9);

function iwp_yoast_notice()
{
    echo '<div class="error">';
    echo '<p><strong>Import WP - Yoast SEO Importer Addon</strong> requires that you have <strong>Import WP v2.5.0 or newer</strong>, and <strong>Yoast SEO</strong> installed.</p>';
    echo '</div>';
}
