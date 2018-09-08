<?php
/**
 * DokuWiki Plugin structsection (Action Component)
 */
// must be run within Dokuwiki
use dokuwiki\plugin\structgroup\meta\ApplicationAspectKernel;

if (!defined('DOKU_INC')) die();

class action_plugin_structgroup extends DokuWiki_Action_Plugin {
    /**
     * Registers a callback function for a given event
     *
     * @param Doku_Event_Handler $controller DokuWiki's event controller object
     * @return void
     */
    public function register(Doku_Event_Handler $controller) {
        $controller->register_hook('PLUGIN_STRUCT_TYPECLASS_INIT', 'BEFORE', $this, 'handle_init');
    }

    public function handle_init(Doku_Event &$event, $param) {
        $event->data['Group'] = 'dokuwiki\\plugin\\structgroup\\types\\Group';
    }
}
// vim:ts=4:sw=4:et: