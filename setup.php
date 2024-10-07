<?php

use ImportWP\Common\Addon\AddonBasePanel;
use ImportWP\Common\Addon\AddonFieldDataApi;
use ImportWP\Common\Addon\AddonInterface;
use ImportWP\Common\Addon\AddonPanelDataApi;
use ImportWP\Common\Importer\Template\Template;
use ImportWP\Common\Model\ImporterModel;

iwp_register_importer_addon('YoastSEO', 'iwp-yoast', function (AddonInterface $addon) {

    $addon->register_panel('Yoast SEO', 'yoast', function (AddonBasePanel $panel) {

        // Core
        $panel->register_field('SEO Title', 'wpseo_title')
            ->save(function (AddonFieldDataApi $api) {
                iwp_yoast_save_meta($api, '_yoast_wpseo_title', ['term' => 'wpseo_title', 'user' => 'wpseo_title']);
            });

        $panel->register_field('SEO Description', 'wpseo_metadesc')
            ->save(function (AddonFieldDataApi $api) {
                iwp_yoast_save_meta($api, '_yoast_wpseo_metadesc', ['term' => 'wpseo_desc', 'user' => 'wpseo_metadesc']);
            });

        // all templates ['post', 'page', 'woocommerce-product', 'term']
        $panel->register_field('Breadcrumb title', 'wpseo_bctitle')
            ->enabled('iwp_yoast_run_on_all_except_user')
            ->save(function (AddonFieldDataApi $api) {
                iwp_yoast_save_meta($api, '_yoast_wpseo_bctitle', ['term' => 'wpseo_bctitle']);
            });

        $panel->register_field('Focus Keyphrase', 'wpseo_focuskw')
            ->enabled('iwp_yoast_run_on_all_except_user')
            ->save(function (AddonFieldDataApi $api) {
                iwp_yoast_save_meta($api, '_yoast_wpseo_focuskw', ['term' => 'wpseo_focuskw']);
            });

        $panel->register_field('Facebook Title', 'wpseo_opengraph_title')
            ->enabled('iwp_yoast_run_on_all_except_user')
            ->save(function (AddonFieldDataApi $api) {
                iwp_yoast_save_meta($api, '_yoast_wpseo_opengraph-title', ['term' => 'wpseo_opengraph-title']);
            });

        $panel->register_field('Facebook Description', 'wpseo_opengraph_description')
            ->enabled('iwp_yoast_run_on_all_except_user')
            ->save(function (AddonFieldDataApi $api) {
                iwp_yoast_save_meta($api, '_yoast_wpseo_opengraph-description', ['term' => 'wpseo_opengraph-description']);
            });

        $panel->register_attachment_fields('Facebook Image', 'wpseo_opengraph_image', 'Facebook Image location')
            ->enabled('iwp_yoast_run_on_all_except_user')
            ->save(function (AddonFieldDataApi $api) {
                iwp_yoast_save_meta_image($api, '_yoast_wpseo_opengraph-image', ['term' => 'wpseo_opengraph-image']);
            });

        $panel->register_field('Twitter Title', 'wpseo_twitter_title')
            ->enabled('iwp_yoast_run_on_all_except_user')
            ->save(function (AddonFieldDataApi $api) {
                iwp_yoast_save_meta($api, '_yoast_wpseo_twitter-title', ['term' => 'wpseo_twitter-title']);
            });

        $panel->register_field('Twitter Description', 'wpseo_twitter_description')
            ->enabled('iwp_yoast_run_on_all_except_user')
            ->save(function (AddonFieldDataApi $api) {
                iwp_yoast_save_meta($api, '_yoast_wpseo_twitter-description', ['term' => 'wpseo_twitter-description']);
            });

        $panel->register_attachment_fields('Twitter Image', 'wpseo_twitter_image', 'Twitter Image location')
            ->enabled('iwp_yoast_run_on_all_except_user')
            ->save(function (AddonFieldDataApi $api) {
                iwp_yoast_save_meta_image($api, '_yoast_wpseo_twitter-image', ['term' => 'wpseo_twitter-image']);
            });

        // Advanced
        $panel->register_field('Canonical URL', 'wpseo_canonical')
            ->enabled('iwp_yoast_run_on_all_except_user')
            ->save(function (AddonFieldDataApi $api) {
                iwp_yoast_save_meta($api, '_yoast_wpseo_canonical', ['term' => 'wpseo_canonical']);
            });

        $panel->register_field('Allow Search Engines', 'wpseo_noindex')
            ->options([
                ['value' => '', 'label' => 'Current Default'],
                ['value' => 'index', 'label' => 'Yes'],
                ['value' => 'noindex', 'label' => 'No'],
            ])
            ->default('')
            ->enabled('iwp_yoast_run_on_all_except_user')
            ->save('iwp_yoast_save_wpseo_noindex');

        // post templates ['post', 'page', 'woocommerce-product']
        $panel->register_field('Search Engines Follow links', 'wpseo_nofollow')
            ->options([
                ['value' => 'yes', 'label' => 'Yes'],
                ['value' => 'no', 'label' => 'No'],
            ])
            ->default('yes')
            ->enabled('iwp_yoast_run_on_post')
            ->save(function (AddonFieldDataApi $api) {
                iwp_yoast_save_meta($api, '_yoast_wpseo_meta-robots-nofollow');
            });

        $panel->register_field('Meta robots advanced', 'wpseo_robots_adv')
            ->options([
                ['value' => '-', 'label' => 'Site-wide default'],
                ['value' => 'none', 'label'  => 'None'],
                ['value' => 'noimageindex', 'label'  => 'No Image Index'],
                ['value' => 'noarchive', 'label'  => 'No Archive'],
                ['value' => 'nosnippet', 'label'  => 'No Snippet'],
            ])
            ->default('-')
            ->enabled('iwp_yoast_run_on_post')
            ->save(function (AddonFieldDataApi $api) {
                iwp_yoast_save_meta($api, '_yoast_wpseo_meta-robots-adv');
            });

        $panel->register_field('Cornerstone content', 'wpseo_is_cornerstone')
            ->options([
                ['value' => 'yes', 'label' => 'Yes'],
                ['value' => 'no', 'label' => 'No'],
            ])
            ->default('no')
            ->enabled('iwp_yoast_run_on_post')
            ->save('iwp_yoast_save_wpseo_is_cornerstone');

        // User
        $panel->register_field('Do not allow search engines to show this author\'s archives in search results', 'wpseo_noindex_author')
            ->options([
                ['value' => 'on', 'label' => 'Yes'],
                ['value' => '', 'label' => 'No'],
            ])
            ->default('no')
            ->enabled('iwp_yoast_run_on_user')
            ->save(function (AddonFieldDataApi $api) {
                iwp_yoast_save_meta($api, 'wpseo_noindex_author');
            });


        $panel->save('iwp_yoast_save_term_meta');
    });
});

/**
 * Save Yoast term meta to options table
 *
 * @param AddonPanelDataApi $api
 * 
 * @return void
 */
function iwp_yoast_save_term_meta(AddonPanelDataApi $api)
{
    if ($api->get_mapper() !== 'term') {
        return;
    }

    // Save term metadata in options table
    $importer_model = $api->importer_model();
    $object_id = $api->object_id();

    $meta = $api->get_meta();
    if (empty($meta)) {
        return;
    }

    $meta = array_reduce($meta, function ($carry, $item) {
        $carry[$item['key']] = $item['value'];
        return $carry;
    }, []);

    $option_key = 'wpseo_taxonomy_meta';
    $wpseo_taxonomy_meta = (array) get_option($option_key);
    $taxonomy = $importer_model->getSetting('taxonomy');

    if (!isset($wpseo_taxonomy_meta[$taxonomy])) {
        $wpseo_taxonomy_meta[$taxonomy] = array();
    }

    if (!isset($wpseo_taxonomy_meta[$taxonomy][$object_id])) {
        $wpseo_taxonomy_meta[$taxonomy][$object_id] = array();
    }

    // merge in new meta
    $wpseo_taxonomy_meta[$taxonomy][$object_id] = array_merge($wpseo_taxonomy_meta[$taxonomy][$object_id], $meta);
    update_option($option_key, $wpseo_taxonomy_meta);
}

/**
 * @param Template $template
 * @param ImporterModel $importer_model
 * 
 * @return boolean
 */
function iwp_yoast_run_on_all_except_user($template, $importer_model)
{
    return $template->get_mapper() != 'user';
}

/**
 * @param Template $template
 * @param ImporterModel $importer_model
 * 
 * @return boolean
 */
function iwp_yoast_run_on_post($template, $importer_model)
{
    return in_array($template->get_mapper(), ['post', 'page', 'woocommerce-product'], true);
}

/**
 * @param Template $template
 * @param ImporterModel $importer_model
 * 
 * @return boolean
 */
function iwp_yoast_run_on_user($template, $importer_model)
{
    return $template->get_mapper() === 'user';
}

/**
 * @param AddonFieldDataApi $api
 * @param string $key
 * @param array $conditions
 * @param mixed $value
 * 
 * @return void
 */
function iwp_yoast_save_meta(AddonFieldDataApi $api, $key, $conditions = [], $value = false)
{
    $mapper = $api->get_mapper();

    if ($value === false) {
        $value = $api->get_field_data();
    }

    if (isset($conditions['term']) && $mapper == 'term') {
        $api->store_meta($conditions['term'], $value, false);
        return;
    } elseif (isset($conditions['user']) && $mapper == 'user') {
        $key = $conditions['user'];
    }

    $api->update_meta($key, $value);
}

/**
 * @param AddonFieldDataApi $api
 * @param string $key
 * @param array $conditions
 * 
 * @return void
 */
function iwp_yoast_save_meta_image($api, $deafult, $conditions = [])
{
    $image_id = $image_url = '';

    $attachment_ids = $api->process_attachment();
    if ($attachment_ids && !empty($attachment_ids)) {
        $image_id = array_shift($attachment_ids);
        $image_url = wp_get_attachment_url($image_id);
    }

    iwp_yoast_save_meta($api, $deafult, $conditions, $image_url);
    iwp_yoast_save_meta($api, $deafult . '-id', array_reduce(array_keys($conditions), function ($carry, $key) use ($conditions) {
        $carry[$key] = $conditions[$key] . '-id';
        return $carry;
    }, []), $image_id);
}

/**
 * @param AddonFieldDataApi $api
 * 
 * @return void
 */
function iwp_yoast_save_wpseo_noindex($api)
{
    switch ($api->get_field_data()) {
        case 'index':
            $value = 2;
            break;
        case 'noindex':
            $value = 1;
            break;
        default:
            $value = 0;
            break;
    }

    iwp_yoast_save_meta($api, '_yoast_wpseo_meta-robots-noindex', ['term' => 'wpseo_noindex'], $value);
}

/**
 * @param AddonFieldDataApi $api
 * 
 * @return void
 */
function iwp_yoast_save_wpseo_is_cornerstone($api)
{
    switch ($api->get_field_data()) {
        case 'yes':
            $value = 1;
            break;
        default:
            $value = 0;
            break;
    }

    iwp_yoast_save_meta($api, '_yoast_wpseo_is_cornerstone', [], $value);
}

/**
 * Add field list to exporter
 */
add_filter('iwp/exporter/taxonomy/fields', function ($fields, $template_args) {

    $fields['children']['yoast'] = [
        'key' => 'yoast',
        'label' => 'Yoast',
        'loop' => false,
        'fields' => [
            'wpseo_title',
            'wpseo_desc',
            'wpseo_bctitle',
            'wpseo_focuskw',
            'wpseo_opengraph-title',
            'wpseo_opengraph-description',
            'wpseo_opengraph-image',
            'wpseo_opengraph-image-id',
            'wpseo_twitter-title',
            'wpseo_twitter-description',
            'wpseo_twitter-image',
            'wpseo_twitter-image-id',
            'wpseo_canonical',
            'wpseo_noindex',
        ],
        'children' => []
    ];

    return $fields;
}, 10, 2);

/**
 * Populate exporter field with data
 */
add_filter('iwp/exporter/taxonomy/setup_data', function ($record, $template_args) {

    $term_id = $record['term_id'];
    $option_key = 'wpseo_taxonomy_meta';
    $wpseo_taxonomy_meta = (array) get_option($option_key);

    $data = [];

    if (isset($wpseo_taxonomy_meta[$template_args], $wpseo_taxonomy_meta[$template_args][$term_id])) {
        $data = $wpseo_taxonomy_meta[$template_args][$term_id];
    }

    $record['yoast'] = $data;
    return $record;
}, 10, 2);
