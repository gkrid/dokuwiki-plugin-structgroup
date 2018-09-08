<?php
namespace dokuwiki\plugin\structgroup\types;

use dokuwiki\plugin\struct\meta\QueryBuilder;
use dokuwiki\plugin\struct\meta\QueryBuilderWhere;
use dokuwiki\plugin\struct\meta\StructException;
use dokuwiki\plugin\struct\meta\ValidationException;
use dokuwiki\plugin\struct\types\AbstractMultiBaseType;

class Group extends AbstractMultiBaseType {

    protected $config = array(
        'existingonly' => true,
        'autocomplete' => array(
            'mininput' => 2,
            'maxresult' => 5,
        ),
    );

    /**
     * @param string $rawvalue the user to validate
     * @return int|string|void
     */
    public function validate($rawvalue) {
        $rawvalue = parent::validate($rawvalue);

        if($this->config['existingonly']) {
            /** @var \helper_plugin_structgroup_authgroup $authgroup */
            $authgroup = plugin_load('helper', 'structgroup_authgroup');

            if(! in_array($rawvalue, $authgroup->getGroups())) {
                throw new ValidationException('Group not found', $rawvalue);
            }
        }

        return $rawvalue;
    }

    /**
     * @param string $value the user to display
     * @param \Doku_Renderer $R
     * @param string $mode
     * @return bool
     */
    public function renderValue($value, \Doku_Renderer $R, $mode) {
        $R->cdata('@' . $value);
        return true;
    }

    /**
     * Autocompletion for user names
     *
     * @todo should we have any security mechanism? Currently everybody can look up groups
     * @return array
     */
    public function handleAjax() {
        global $INPUT;

        /** @var \helper_plugin_structgroup_authgroup $authgroup */
        $authgroup = plugin_load('helper', 'structgroup_authgroup');

        // check minimum length
        $lookup = trim($INPUT->str('search'));
        if(utf8_strlen($lookup) < $this->config['autocomplete']['mininput']) return array();

        // results wanted?
        $max = $this->config['autocomplete']['maxresult'];
        if($max <= 0) return array();

        $groups = (array) $authgroup->getGroups($max, $lookup);

        // reformat result for jQuery UI Autocomplete
        return array_map(function ($group) {
            return array(
                'label' => '@' . $group,
                'value' => $group
            );
        }, $groups);
    }
}
