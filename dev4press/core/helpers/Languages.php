<?php

namespace Dev4Press\Core\Helpers;

class Languages {
    static private $_current_instance = null;

    public $list = array(
        'da_DK' => array('native' => 'Dansk', 'english' => 'Danish'),
        'de_AT' => array('native' => 'Deutsch Österreich', 'english' => 'German Austria'),
        'de_DE' => array('native' => 'Deutsch', 'english' => 'German'),
        'de_CH' => array('native' => 'Deutsch Schweiz', 'english' => 'German Switzerland'),
        'es_AR' => array('native' => 'Español de Argentina', 'english' => 'Spanish Argentina'),
        'es_ES' => array('native' => 'Español', 'english' => 'Spanish'),
        'es_MX' => array('native' => 'Español de México', 'english' => 'Spanish Mexico'),
        'fr_FR' => array('native' => 'Français', 'english' => 'French'),
        'fr_BE' => array('native' => 'Français de Belgique', 'english' => 'French Belgian'),
        'fr_CA' => array('native' => 'Français Canadien', 'english' => 'French Canadian'),
        'it_IT' => array('native' => 'Italiano', 'english' => 'Italian'),
        'nl_NL' => array('native' => 'Nederlands', 'english' => 'Dutch'),
        'pl_PL' => array('native' => 'Polski', 'english' => 'Polish'),
        'pt_BR' => array('native' => 'Português do Brasil', 'english' => 'Brazilian Portuguese'),
        'pt_PT' => array('native' => 'Português', 'english' => 'Portuguese'),
        'ru_RU' => array('native' => 'Русский', 'english' => 'Russian'),
        'sr_RS' => array('native' => 'Српски', 'english' => 'Serbian')
    );

    public function __construct() { }

    public static function instance() {
        if (is_null(self::$_current_instance)) {
            self::$_current_instance = new Languages();
        }

        return self::$_current_instance;
    }

    public function plugin_translations($translations) {
        $list = array();

        foreach ($translations as $code => $obj) {
            $list[$code] = $this->list[$code] + $obj;

            if (!isset($list[$code]['contributors'])) {
                $list[$code]+= array('contributors' => array());
            }
        }

        return $list;
    }
}
