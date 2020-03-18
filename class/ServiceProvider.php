<?php

namespace ImportWPAddon\YoastSEO;

use ImportWP\Common\Importer\Template\Template;
use ImportWP\EventHandler;
use ImportWPAddon\YoastSEO\Importer\Template\YoastFields;

class ServiceProvider extends \ImportWP\ServiceProvider
{
    /**
     * @var YoastFields $yoast_fields
     */
    private $yoast_fields;

    /**
     * @param EventHandler $event_handler
     */
    private $event_handler;

    public function __construct($event_handler)
    {
        $this->event_handler = $event_handler;

        $yoast_fields = new YoastFields();

        // display
        $this->event_handler->listen('template.data_groups', [$yoast_fields, 'data_groups']);
        $this->event_handler->listen('template.fields', [$yoast_fields, 'fields']);
        $this->event_handler->listen('template.field_option_callbacks', [$yoast_fields, 'field_options']);

        // save
        $this->event_handler->listen('template.pre_process', [$yoast_fields, 'pre_process']);
        $this->event_handler->listen('template.process', [$yoast_fields, 'process']);
    }
}
