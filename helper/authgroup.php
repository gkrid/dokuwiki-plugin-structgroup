<?php
/**
 * DokuWiki Plugin struct (Helper Component)
 */

// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();

class helper_plugin_structgroup_authgroup extends DokuWiki_Plugin {
    protected $groupsloaded = false;
    protected $groups = array();

    protected function loadGroups() {
        /** @var \DokuWiki_Auth_Plugin $auth */
        global $auth;

        if(!$auth->canDo('getUsers')) {
            msg('The user backend can not search for users', -1);
            return false;
        }

        $groups = array_map(function($userinfo) { return $userinfo['grps']; }, $auth->retrieveUsers());
        $groups = call_user_func_array('array_merge', $groups);

        $this->groups = array_unique($groups);
        $this->groupsloaded = true;
    }

    public function getGroups($length = NULL, $filter = '') {
        if (!$this->groupsloaded) {
            $this->loadGroups();
        }

        $groups = $this->groups;
        if ($filter != '') $groups = array_filter($groups, function ($group) use ($filter) {
            return strpos($group, $filter) === 0;
        });

        return array_slice($groups, 0, $length);
    }

}