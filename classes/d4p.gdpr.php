<?php

/*
Name:    d4pLib_Class_Pretty_Print_R
Version: v2.0.2
Author:  Milan Petrovic
Email:   milan@gdragon.info
Website: https://www.dev4press.com/

== Acknowledgment ==
Class 'd4p_pretty_print_r' is based on the 'nice_r' class.
Author:  uuf6429
Website: https://github.com/uuf6429/nice_r

== Copyright ==
Copyright 2008 - 2017 Milan Petrovic (email: milan@gdragon.info)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if (!class_exists('d4p_pretty_print_r')) {
    class d4p_pretty_print_r {
        protected static $BEEN_THERE = '__GDPR_INFINITE_RECURSION__';

        protected $value;
        protected $is_root = true;

        public $css_class = 'gdp_r';
        public $html_id = 'gdp_r_v';
        public $js_func = 'gdp_r_toggle';

        public $inspect_methods = false;
        public $collapsed = true;
        public $display_docs = true;
        public $display_footer = true;

        protected $_has_reflection = null;
        protected $_visible_mods = array('abstract', 'final', 'private', 'protected', 'public', 'static');

        public $STR_EMPTY_ARRAY = 'Empty Array';
        public $STR_NO_PROPERTIES = 'No Properties';
        public $STR_NO_METHODS = 'No Methods';
        public $STR_INFINITE_RECURSION_WARNING = 'Infinite Recursion Detected!';
        public $STR_STR_DESC = '%d characters';
        public $STR_RES_DESC = '%s type';
        public $STR_ARR_DESC = '%d elements';
        public $STR_OBJ_DESC = '%d properties';
        public $STR_FOOTER_CALL = 'Called From';
        public $STR_FOOTER_LINE = 'line';

        public $ICON_DOWN = '&#9660;';
        public $ICON_RIGHT = '&#9658;';

        public function __construct() {
            if (is_null($this->_has_reflection)) {
                $this->_has_reflection = class_exists('ReflectionClass');
            }
        }

        public static function instance($value, $footer = true, $collapsed = true, $inspect_methods = false) {
            static $instance = null;

            if (null === $instance) {
                $instance = new d4p_pretty_print_r();
            }

            $instance->is_root = true;
            $instance->value = $value;
            $instance->collapsed = $collapsed;
            $instance->inspect_methods = $inspect_methods;
            $instance->display_footer = $footer;

            return $instance;
        }

        public function generate() {
            return $this->_generate_root($this->value, $this->css_class);
        }

        public function render() {
            echo $this->generate();
        }

        protected function _esc_html($text) {
            return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
        }

        protected function _generate_keyvalues($array, &$html) {
            $has_subitems = false;

            foreach ($array as $k => $v) {
                if ($k !== self::$BEEN_THERE) {
                    $html.= $this->_generate_keyvalue($k, $v);
                    $has_subitems = true;
                }
            }

            return $has_subitems;
        }

        protected function _inspect_array(&$html, &$var) {
            if (!$this->_generate_keyvalues($var, $html)) {
                $html.= '<span class="'.$this->css_class.'_ni">'.$this->STR_EMPTY_ARRAY.'</span>';
            }
        }

        protected function _inspect_object(&$html, &$var) {
            if (!$this->_generate_keyvalues((array) $var, $html)) {
                $html.= '<span class="'.$this->css_class.'_ni">'.$this->STR_NO_PROPERTIES.'</span>';
            }

            if ($this->inspect_methods) {
                $has_subitems = false;

                foreach ((array)get_class_methods($var) as $method) {
                    $html.= $this->_generate_callable($var, $method);
                    $has_subitems = true;
                }

                if (!$has_subitems) {
                    $html.= '<span class="'.$this->css_class.'_ni">'.$this->STR_NO_METHODS.'</span>';
                }
            }
        }

        protected function _generate_root($var, $class = '') {
            $html = '<div class="'.$class.' '.$this->css_class.'_root">';

            $root_wrapper = false;
            if ($this->collapsed && (is_array($var) || is_object($var))) {
                $root_wrapper = true;
            }

            $id = '';
            $cls = $class;

            if ($root_wrapper) {
                $t = gettype($var);
                $d = '';

                switch ($t) {
                    case 'array':
                        $d.= ', '.sprintf($this->STR_ARR_DESC, count($var));
                        break;
                    case 'object':
                        $d.= ', <span class="'.$cls.'_rn">'.get_class($var).'</span>, '.sprintf($this->STR_OBJ_DESC, count(get_object_vars($var)));
                        break;
                }

                $id = $this->html_id.'_v'.$this->_generate_dropid();

                $html.= '<a role="button" class="'.$cls.'_c '.$cls.'_aa" href="#" data-branch="'.$id.'">';
                $html.= '<span class="'.$cls.'_a">'.$this->ICON_RIGHT.'</span>';
                $html.= '<span class="'.$cls.'_k">&middot &middot &middot &middot &middot</span>';
                $html.= '<span class="'.$cls.'_d">(<span>'.ucwords($t).'</span>'.$d.')</span>';
                $html.= '</a>';

                $cls.= '_v';
            }

            $html.= $this->_generate_value($var, $cls, $id);

            if ($this->display_footer) {
                $_ = debug_backtrace();

                while($d = array_pop($_)) {
                    if ((strToLower($d['function']) == 'gdp_r') || (strToLower($d['function']) == 'gdp_rx')) {
                        break;
                    }
                }

                $html.= $this->_generate_footer($d);
            }

            $html.= '</div>';

            return $html;
        }

        protected function _generate_value($var, $class = '', $id = '') {
            $BEENTHERE = self::$BEEN_THERE;
            $class.= ' '.$this->css_class.'_t_'.gettype($var);

            $html = '<div id="'.$id.'" class="'.$class.'">';

            switch (true) {
                case is_array($var):
                    if (isset($var[$BEENTHERE])) {
                        $html.= '<span class="'.$this->css_class.'_ir">'.$this->STR_INFINITE_RECURSION_WARNING.'</span>';
                    } else {
                        $var[$BEENTHERE] = true;

                        $this->_inspect_array($html, $var);

                        unset($var[$BEENTHERE]);
                    }
                    break;
                case is_object($var):
                    if (isset($var->$BEENTHERE)) {
                        $html.= '<span class="'.$this->css_class.'_ir">'.$this->STR_INFINITE_RECURSION_WARNING.'</span>';
                    } else {
                        $var->$BEENTHERE = true;

                        $this->_inspect_object($html, $var);

                        unset($var->$BEENTHERE);
                    }
                    break;
                default:
                    $html.= $this->_generate_keyvalue('', $var);
                    break;
            }

            $html.= '</div>';

            return $html;
        }

        protected function _generate_footer($d) {
            $footer = '<div class="'.$this->css_class.'_f">';
            $footer.= $this->STR_FOOTER_CALL.' <code>'.$d['file'].'</code> ';
            $footer.= $this->STR_FOOTER_LINE.' <code>'.$d['line'].'</code>';
            $footer.= '</div>';

            return $footer;
        }

        protected function _generate_dropid() {
            static $id = 0;

            return ++$id;
        }

        protected function _generate_keyvalue($key, $val) {
            $id = $this->_generate_dropid();
            $p = '';
            $d = '';
            $t = gettype($val);
            $is_hash = ($t == 'array') || ($t == 'object');

            switch ($t) {
                case 'boolean':
                    $p = $val ? 'TRUE' : 'FALSE';
                    break;
                case 'integer':
                case 'double':
                    $p = (string) $val;
                    break;
                case 'string':
                    $d.= ', '.sprintf($this->STR_STR_DESC, strlen($val));
                    $p = $val;
                    break;
                case 'resource':
                    $d.= ', '.sprintf($this->STR_RES_DESC, get_resource_type($val));
                    $p = (string) $val;
                    break;
                case 'array':
                    $d.= ', '.sprintf($this->STR_ARR_DESC, count($val));
                    break;
                case 'object':
                    $d.= ', '.get_class($val).', '.sprintf($this->STR_OBJ_DESC, count(get_object_vars($val)));
                    break;
            }

            $cls = $this->css_class;
            $xcls = !$is_hash ? $cls.'_ad' : $cls.'_aa';

            $html = '<a role="button" class="'.$cls.'_c '.$xcls.'" href="#" data-branch="'.$this->html_id.'_v'.$id.'">';
            $html.= '<span class="'.$cls.'_a">'.$this->ICON_RIGHT.'</span>';
            $html.= '<span class="'.$cls.'_k">'.$this->_esc_html($key).'</span>';
            $html.= '<span class="'.$cls.'_d">(<span>'.ucwords($t).'</span>'.$d.')</span>';
            $html.= '<span class="'.$cls.'_p '.$cls.'_t_'.$t.'">'.$this->_esc_html($p).'</span>';
            $html.= '</a>';

            if ($is_hash) {
                $html.= $this->_generate_value($val, $cls.'_v', $this->html_id.'_v'.$id);
            }

            return $html;
        }

        protected function _format_phpdoc($doc) {
            $doc = $this->_esc_html($doc);

            $doc = preg_replace('/(\\n)\\s+?\\*([\\s\\/])/', '$1 *$2', $doc);
            $doc = preg_replace('/(\\s)(@\\w+)/', '$1<b>$2</b>', $doc);
            $doc = nl2br(str_replace(' ', '&nbsp;', $doc));

            $doc = preg_replace('/(((f|ht){1}tp:\\/\\/)[-a-zA-Z0-9@:%_\\+.~#?&\\/\\/=]+)/', '<a href="$1">$1</a>', $doc);
            $doc = preg_replace('/(www\\.[-a-zA-Z0-9@:%_\\+.~#?&\\/=]+)/', '<a href="http://$1">$1</a>', $doc);
            $doc = preg_replace('/([_\\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\\.)+[a-z]{2,3})/', '<a href="mailto:$1">$1</a>', $doc);

            return $doc;
        }

        protected function _generate_callable($context, $callback) {
            $id = $this->_generate_dropid();
            $ref = null;
            $name = 'Anonymous';
            $cls = $this->css_class;
            $mods = array();

            if ($this->_has_reflection) {
                if (is_null($context)) {
                    $ref = new ReflectionFunction($callback);
                } else {
                    $ref = new ReflectionMethod($context, $callback);

                    foreach (array(
                        'abstract'      => $ref->isAbstract(),
                        'constructor'   => $ref->isConstructor(),
                        'deprecated'    => $ref->isDeprecated(),
                        'destructor'    => $ref->isDestructor(),
                        'final'         => $ref->isFinal(),
                        'internal'      => $ref->isInternal(),
                        'private'       => $ref->isPrivate(),
                        'protected'     => $ref->isProtected(),
                        'public'        => $ref->isPublic(),
                        'static'        => $ref->isStatic(),
                        'magic'         => substr($ref->name, 0, 2) === '__',
                        'returnsRef'    => $ref->returnsReference(),
                        'inherited'     => get_class($context) !== $ref->getDeclaringClass()->name
                    ) as $name => $cond) {
                        if ($cond) {
                            $mods[] = $name;
                        }
                    }
                }

                $name = $ref->getName();
            } elseif (is_string($callback)) {
                $name = $callback;
            }

            if (!is_null($ref)) {
                $doc = $this->display_docs ? $ref->getDocComment() : null;
                $prms = array();

                foreach ($ref->getParameters() as $p) {
                    $prms[] = '$'.$p->getName().($p->isDefaultValueAvailable() ? ' = <span class="'.$cls.'_mv">'.var_export($p->getDefaultValue(), true).'</span>' : '');
                }
            } else {
                $doc = null;
                $prms = array('???');
            }

            $xcls = is_null($doc) || $doc === false ? $cls.'_ad' : $cls.'_aa';

            $hmod = implode(' ', array_intersect($mods, $this->_visible_mods));

            foreach ($mods as $mod) {
                $xcls.= ' '.$this->css_class.'_m_'.$mod;
            }

            if ($hmod != '') {
                $hmod = '<span class="'.$this->css_class.'_mo">'.$hmod.'</span> ';
            }

            $html  = '<a role="button" class="'.$cls.'_c '.$cls.'_m '.$xcls.'" href="#" data-branch="'.$this->html_id.'_v'.$id.'">';
            $html.= '<span class="'.$cls.'_a" id="'.$this->html_id.'_a'.$id.'">'.$this->ICON_RIGHT.'</span>';
            $html.= '<span class="'.$cls.'_k">'.$hmod.$this->_esc_html($name).'<span class="'.$cls.'_ma">(<span>'.implode(', ', $prms).'</span>)</span></span>';
            $html.= '</a>';

            if ($doc) {
                $html.= '<div id="'.$this->html_id.'_v'.$id.'" class="'.$this->css_class.'_v '.$this->css_class.'_t_comment">';
                $html.= $this->_format_phpdoc($doc);
                $html.= '</div>';
            }

            return $html;
        }
    }
}

if (!function_exists('gdp_r')) {
    function gdp_r($value, $footer = true, $collapsed = true, $inspect_methods = true){
        $n = d4p_pretty_print_r::instance($value, $footer, $collapsed, $inspect_methods);

        $n->render();
    }

    function gdp_rx($value, $footer = true, $collapsed = true, $inspect_methods = true) {
        $n = d4p_pretty_print_r::instance($value, $footer, $collapsed, $inspect_methods);

        return $n->generate();
    }
}

if (!function_exists('gdp_rs')) {
    function gdp_rs($value, $echo = true) {
        $result = '';

        if (is_bool($value)) {
            $result = '<div class="gdp_rs gdp_rs_bool">'.($value ? 'TRUE' : 'FALSE').'</div>';
        } else if (is_null($value)) {
            $result = '<div class="gdp_rs gdp_rs_null">NULL</div>';
        } else if (is_string($value)) {
            if (empty($value)) {
                $result = '<div class="gdp_rs gdp_rs_empty">EMPTY</div>';
            } else {
                $result = '<div class="gdp_rs gdp_rs_string">'.$value.'</div>';
            }
        } else if (is_int($value) || is_float($value)) {
            $result = '<div class="gdp_rs gdp_rs_number">'.$value.'</div>';
        }

        if ($echo) {
            echo $result;
        } else {
            return $result;
        }
    }
}

if (!function_exists('gdp_ra')) {
    function gdp_ra($value, $echo = true, $footer = true, $collapsed = true, $inspect_methods = true) {
        $simple = gdp_rs($value, false);

        if ($simple == '') {
            $simple = gdp_rx($value, $footer, $collapsed, $inspect_methods);
        }

        if ($echo) {
            echo $simple;
        } else {
            return $simple;
        }
    }
}
