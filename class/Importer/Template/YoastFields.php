<?php

namespace ImportWPAddon\YoastSEO\Importer\Template;

use ImportWP\Common\Importer\ParsedData;
use ImportWP\Common\Importer\Template\PostTemplate;
use ImportWP\Common\Importer\Template\Template;
use ImportWP\Common\Model\ImporterModel;
use ImportWP\Container;

class YoastFields
{
    public function data_groups($groups)
    {
        return array_merge((array) $groups, [
            'yoast'
        ]);
    }

    public function field_options($callbacks, Template $template)
    {
        $callbacks['yoast.test_1'] = [$this, 'yoast_test_callback_1'];
        $callbacks['yoast.test_2'] = [$this, 'yoast_test_callback_2'];
        return $callbacks;
    }

    public function fields($fields, Template $template)
    {
        $mapper = $template->get_mapper();

        $yoast_fields = [
            // Content Optimisation
            $template->register_field('SEO Title', 'wpseo_title'),
            $template->register_field('SEO Description', 'wpseo_desc'),
            $template->register_field('Focus Keyphrase', 'wpseo_focuskw'),
        ];

        if (in_array($mapper, ['post', 'page', 'woocommerce-product', 'term'], true)) {
            $yoast_fields = array_merge($yoast_fields, [
                // Facebook
                $template->register_field('Facebook Title', 'wpseo_opengraph_title'),
                $template->register_field('Facebook Description', 'wpseo_opengraph_description'),
                // $template->register_field('Facebook Image', 'wpseo_opengraph_image'),
                $template->register_attachment_fields('Facebook Image', 'wpseo_opengraph_image', 'Facebook Image location', []),
                // Twitter
                $template->register_field('Twitter Title', 'wpseo_twitter_title'),
                $template->register_field('Twitter Description', 'wpseo_twitter_description'),
                $template->register_attachment_fields('Twitter Image', 'wpseo_twitter_image', 'Twitter Image location', []),
                // Advanced
                $template->register_field('Canonical URL', 'wpseo_canonical'),
                $template->register_field('Allow Search Engines', 'wpseo_noindex', [
                    'options' => [
                        ['value' => '', 'label' => 'Current Default'],
                        ['value' => 'index', 'label' => 'Yes'],
                        ['value' => 'noindex', 'label' => 'No'],
                    ],
                    'default' => ''
                ]),
            ]);
        }

        if (in_array($mapper, ['post', 'page', 'woocommerce-product'], true)) {
            $yoast_fields = array_merge($yoast_fields, [
                // page/post
                $template->register_field('Search Engines Follow links', 'wpseo_nofollow', [
                    'options' => [
                        ['value' => 'yes', 'label' => 'Yes'],
                        ['value' => 'no', 'label' => 'No'],
                    ],
                    'default' => 'yes'
                ]),
                $template->register_field('Meta robots advanced', 'wpseo_robots_adv', [
                    'options' => [
                        ['value' => '-', 'label' => 'Site-wide default'],
                        ['value' => 'none', 'label'  => 'None'],
                        ['value' => 'noimageindex', 'label'  => 'No Image Index'],
                        ['value' => 'noarchive', 'label'  => 'No Archive'],
                        ['value' => 'nosnippet', 'label'  => 'No Snippet'],
                    ],
                    'default' => '-'
                ]),
                $template->register_field('Cornerstone content', 'wpseo_is_cornerstone', [
                    'options' => [
                        ['value' => 'yes', 'label' => 'Yes'],
                        ['value' => 'no', 'label' => 'No'],
                    ],
                    'default' => 'no'
                ])
            ]);
        }

        return array_merge($fields, [
            $template->register_group('Yoast SEO', 'yoast', $yoast_fields)
        ]);
    }

    public function pre_process(ParsedData $data, ImporterModel $importer_model, Template $template)
    {
        $mapper = $template->get_mapper();
        switch ($mapper) {
            case 'user':
                $field_map = [
                    'wpseo_title' => 'yoast.wpseo_title',
                    'wpseo_metadesc' => 'yoast.wpseo_desc',
                ];
                break;
            case 'term':
                $field_map = [
                    'wpseo_title' => 'yoast.wpseo_title',
                    'wpseo_desc' => 'yoast.wpseo_desc',
                    'wpseo_focuskw' => 'yoast.wpseo_focuskw',
                    'wpseo_opengraph-title' => 'yoast.wpseo_opengraph_title',
                    'wpseo_opengraph-description' => 'yoast.wpseo_opengraph_description',
                    'wpseo_opengraph_image' => 'yoast.wpseo_opengraph_image',
                    'wpseo_twitter-title' => 'yoast.wpseo_twitter_title',
                    'wpseo_twitter-description' => 'yoast.wpseo_twitter_description',
                    'wpseo_twitter_image' => 'yoast.wpseo_twitter_image',
                    'wpseo_canonical' => 'yoast.wpseo_canonical',
                    'wpseo_noindex' => 'yoast.wpseo_noindex',
                    // 'wpseo_nofollow' => 'yoast.wpseo_nofollow',
                    // 'wpseo_robots_adv' => 'yoast.wpseo_robots_adv',
                    // 'wpseo_is_cornerstone' => 'yoast.wpseo_is_cornerstone',
                ];
                break;
            default:
                $field_map = [
                    '_yoast_wpseo_title' => 'yoast.wpseo_title',
                    '_yoast_wpseo_metadesc' => 'yoast.wpseo_desc',
                    '_yoast_wpseo_focuskw' => 'yoast.wpseo_focuskw',
                    '_yoast_wpseo_opengraph-title' => 'yoast.wpseo_opengraph_title',
                    '_yoast_wpseo_opengraph-description' => 'yoast.wpseo_opengraph_description',
                    'wpseo_opengraph_image' => 'yoast.wpseo_opengraph_image',
                    '_yoast_wpseo_twitter-title' => 'yoast.wpseo_twitter_title',
                    '_yoast_wpseo_twitter-description' => 'yoast.wpseo_twitter_description',
                    'wpseo_twitter_image' => 'yoast.wpseo_twitter_image',
                    '_yoast_wpseo_canonical' => 'yoast.wpseo_canonical',
                    '_yoast_wpseo_meta-robots-noindex' => 'yoast.wpseo_noindex',
                    // 'wpseo_nofollow' => 'yoast.wpseo_nofollow',
                    '_yoast_wpseo_meta-robots-adv' => 'yoast.wpseo_robots_adv',
                    '_yoast_wpseo_is_cornerstone' => 'yoast.wpseo_is_cornerstone',
                ];
                break;
        }


        $yoast_field_map = [];

        foreach ($field_map as $key => $value) {
            if (true !== $importer_model->isEnabledField($value)) {
                continue;
            }

            if (in_array($key, ['wpseo_opengraph_image', 'wpseo_twitter_image'], true)) {
                $image_data = $data->getData('yoast');
                $attachment = [];
                foreach ($image_data as $field => $field_value) {
                    if (preg_match('/^yoast\.' . $key . '\.(\S+)$/', $field, $matches) !== 1) {
                        continue;
                    }
                    $attachment[$matches[1]] = $field_value;
                }

                $yoast_field_map[$key] = $attachment;
            } else {
                $yoast_field_map[$key] = $data->getValue($value, 'yoast');
            }
        }

        $data->replace($yoast_field_map, 'yoast');

        return $data;
    }

    public function process($id, ParsedData $data, ImporterModel $importer_model, Template $template)
    {
        $mapper = $template->get_mapper();
        switch ($mapper) {
            case 'user':
                $this->process_user($id, $data, $importer_model, $template);
                break;
            case 'term':
                $this->process_term($id, $data, $importer_model, $template);
                break;
            default:
                $this->process_post($id, $data, $importer_model, $template);
                break;
        }
    }

    public function process_post($id, ParsedData $data, ImporterModel $importer_model, PostTemplate $template)
    {
        $meta = $data->getData('yoast');

        if (isset($meta['_yoast_wpseo_meta-robots-noindex'])) {
            switch ($meta['_yoast_wpseo_meta-robots-noindex']) {
                case 'index':
                    $meta['_yoast_wpseo_meta-robots-noindex'] = 2;
                    break;
                case 'noindex':
                    $meta['_yoast_wpseo_meta-robots-noindex'] = 1;
                    break;
                default:
                    $meta['_yoast_wpseo_meta-robots-noindex'] = 0;
                    break;
            }
        }

        if (isset($meta['_yoast_wpseo_is_cornerstone'])) {
            switch ($meta['_yoast_wpseo_is_cornerstone']) {
                case 'yes':
                    $meta['_yoast_wpseo_is_cornerstone'] = 1;
                    break;
                default:
                    $meta['_yoast_wpseo_is_cornerstone'] = 0;
                    break;
            }
        }



        if (isset($meta['wpseo_opengraph_image']) || isset($meta['wpseo_twitter_image'])) {

            /**
             * @var Filesystem $filesystem
             */
            $filesystem = Container::getInstance()->get('filesystem');

            /**
             * @var Ftp $ftp
             */
            $ftp = Container::getInstance()->get('ftp');

            /**
             * @var Attachment $attachment
             */
            $attachment = Container::getInstance()->get('attachment');

            if (isset($meta['wpseo_opengraph_image'])) {
                $attachment_ids = $template->process_attachment($id, $meta['wpseo_opengraph_image'], '', $filesystem, $ftp, $attachment);
                if ($attachment_ids && !empty($attachment_ids)) {
                    $image_id = array_shift($attachment_ids);
                    $image_url = wp_get_attachment_url($image_id);
                    $meta['_yoast_wpseo_opengraph-image-id'] = $image_id;
                    $meta['_yoast_wpseo_opengraph-image'] = $image_url;
                }
                unset($meta['wpseo_opengraph_image']);
            }

            if (isset($meta['wpseo_twitter_image'])) {
                $attachment_ids = $template->process_attachment($id, $meta['wpseo_twitter_image'], '', $filesystem, $ftp, $attachment);
                if ($attachment_ids && !empty($attachment_ids)) {
                    $image_id = array_shift($attachment_ids);
                    $image_url = wp_get_attachment_url($image_id);
                    $meta['_yoast_wpseo_twitter-image-id'] = $image_id;
                    $meta['_yoast_wpseo_twitter-image'] = $image_url;
                }
                unset($meta['wpseo_twitter_image']);
            }
        }

        foreach ($meta as $key => $value) {
            update_post_meta($id, $key, $value);
        }
    }

    public function process_term($id, ParsedData $data, ImporterModel $importer_model, Template $template)
    {
        $option_key = 'wpseo_taxonomy_meta';
        $wpseo_taxonomy_meta = (array) get_option($option_key);
        $taxonomy = $importer_model->getSetting('taxonomy');

        $meta = $data->getData('yoast');

        if (!isset($wpseo_taxonomy_meta[$taxonomy])) {
            $wpseo_taxonomy_meta[$taxonomy] = array();
        }

        if (!isset($wpseo_taxonomy_meta[$taxonomy][$id])) {
            $wpseo_taxonomy_meta[$taxonomy][$id] = array();
        }

        if (isset($meta['wpseo_opengraph_image']) || isset($meta['wpseo_twitter_image'])) {

            /**
             * @var Filesystem $filesystem
             */
            $filesystem = Container::getInstance()->get('filesystem');

            /**
             * @var Ftp $ftp
             */
            $ftp = Container::getInstance()->get('ftp');

            /**
             * @var Attachment $attachment
             */
            $attachment = Container::getInstance()->get('attachment');

            if (isset($meta['wpseo_opengraph_image'])) {
                $attachment_ids = $template->process_attachment(null, $meta['wpseo_opengraph_image'], '', $filesystem, $ftp, $attachment);
                if ($attachment_ids && !empty($attachment_ids)) {
                    $image_id = array_shift($attachment_ids);
                    $image_url = wp_get_attachment_url($image_id);
                    $meta['wpseo_opengraph-image-id'] = $image_id;
                    $meta['wpseo_opengraph-image'] = $image_url;
                }
                unset($meta['wpseo_opengraph_image']);
            }

            if (isset($meta['wpseo_twitter_image'])) {
                $attachment_ids = $template->process_attachment(null, $meta['wpseo_twitter_image'], '', $filesystem, $ftp, $attachment);
                if ($attachment_ids && !empty($attachment_ids)) {
                    $image_id = array_shift($attachment_ids);
                    $image_url = wp_get_attachment_url($image_id);
                    $meta['wpseo_twitter-image-id'] = $image_id;
                    $meta['wpseo_twitter-image'] = $image_url;
                }
                unset($meta['wpseo_twitter_image']);
            }
        }

        // merge in new meta
        $wpseo_taxonomy_meta[$taxonomy][$id] = array_merge($wpseo_taxonomy_meta[$taxonomy][$id], $meta);
        update_option($option_key, $wpseo_taxonomy_meta);
    }

    public function process_user($id, ParsedData $data, ImporterModel $importer_model, Template $template)
    {
        $meta = $data->getData('yoast');
        foreach ($meta as $key => $value) {
            update_user_meta($id, $key, $value);
        }
    }

    public function process_image($id, $field, ParsedData $data)
    {
    }

    /**
     * Yoast Test Field Callback
     * 
     * @param ImporterModel $importer_model
     *
     * @return array
     */
    public function yoast_test_callback_1($importer_model)
    {
        return [
            ['value' => 2, 'label' => 'Two'],
        ];
    }

    /**
     * Yoast Test Field Callback
     * 
     * @param ImporterModel $importer_model
     *
     * @return array
     */
    public function yoast_test_callback_2($importer_model)
    {
        return [
            ['value' => 1, 'label' => 'One'],
        ];
    }
}
