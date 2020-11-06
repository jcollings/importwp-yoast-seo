<?php

/**
 * Plugin Name: ImportWP - Yoast SEO Importer Addon
 * Plugin URI: https://www.importwp.com
 * Description: Allow ImportWP to import Yoast SEO fields.
 * Author: James Collings <james@jclabs.co.uk>
 * Version: 2.0.23 
 * Author URI: https://www.importwp.com
 * Network: True
 */

add_action('admin_init', 'iwp_yoast_check');

function iwp_yoast_requirements_met()
{
    return false === (is_admin() && current_user_can('activate_plugins') &&  (!function_exists('wpseo_init') || (!function_exists('import_wp_pro') && !function_exists('import_wp')) || version_compare(IWP_VERSION, '2.0.21', '<')));
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

    require_once $base_path . '/class/autoload.php';
    require_once $base_path . '/setup.php';
}
add_action('plugins_loaded', 'iwp_yoast_setup', 9);

function iwp_yoast_notice()
{
    echo '<div class="error">';
    echo '<p><strong>ImportWP - Yoast SEO Importer Addon</strong> requires that you have <strong>ImportWP v2.0.21 or newer</strong>, and <strong>Yoast SEO</strong> installed.</p>';
    echo '</div>';
}
