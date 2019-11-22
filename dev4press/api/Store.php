<?php

/*
Name:    Dev4Press\Core\Plugins\Settings
Version: v2.9.1
Author:  Milan Petrovic
Email:   support@dev4press.com
Website: https://www.dev4press.com/

== Copyright ==
Copyright 2008 - 2019 Milan Petrovic (email: support@dev4press.com)

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

namespace Dev4Press\API;

if (!defined('ABSPATH')) {
    exit;
}

class Store {
    private $_plugins = array(
        "gd-bbpress-toolbox" => array(
            "code" => "gd-bbpress-toolbox",
            "name" => "GD bbPress Toolbox",
            "description" => "Expand bbPress powered forums with attachments upload, BBCodes support, signatures, widgets, quotes, toolbar menu, activity tracking, enhanced widgets, extra views...",
            "punchline" => "Enhancing WordPress forums powered by bbPress",
            "color" => "#224760",
        ),
        "gd-clever-widgets" => array(
            "code" => "gd-clever-widgets",
            "name" => "GD Clever Widgets",
            "description" => "A collection of sidebars widgets for unit conversion, advanced navigation, QR Code, videos, posts and authors information, enhanced versions of default widgets and more.",
            "punchline" => "Powerful widgets to enhance your website",
            "color" => "#744D08",
        ),
        "gd-content-tools" => array(
            "code" => "gd-content-tools",
            "name" => "GD Content Tools",
            "description" => "Register and control custom post types and taxonomies. Powerful meta fields and meta boxes management. Extra widgets, custom rewrite rules, enhanced features...",
            "punchline" => "Enhancing WordPress Content Management",
            "color" => "#AD0067",
        ),
        "gd-crumbs-navigator" => array(
            "code" => "gd-crumbs-navigator",
            "name" => "GD Crumbs Navigator",
            "description" => "Breadcrumbs based navigation, fully responsive and customizable, supporting post types, all types of archives, 404 pages, search results and third-party plugins.",
            "punchline" => "Improve your website navigation with Breadcrumbs",
            "color" => "#0CA991",
        ),
        "gd-knowledge-base" => array(
            "code" => "gd-knowledge-base",
            "name" => "GD Knowledge Base",
            "description" => "Complete knowledge base system supporting all themes, with different content types, FAQ, products, live search, feedbacks and ratings, built-in analytics and more.",
            "punchline" => "The knowledge base plugin you have been waiting for",
            "color" => "#3c6d29",
        ),
        "gd-mail-queue" => array(
            "code" => "gd-mail-queue",
            "name" => "GD Mail Queue",
            "description" => "Intercept wp_mail function, convert emails to HTML and implements flexible mail queue system for sending emails, with support for email sending engines and services.",
            "punchline" => "Queue based, enhanced email sending system",
            "color" => "#773355",
        ),
        "gd-members-directory-for-bbpress" => array(
            "code" => "gd-members-directory-for-bbpress",
            "name" => "GD Members Directory for bbPress",
            "description" => "Easy to use plugin for adding forum members directory page into bbPress powered forums including members filtering and additional widgets for listing members in the sidebar.",
            "punchline" => "Members Directory for bbPress powered forums",
            "color" => "#057C8C",
        ),
        "gd-power-search-for-bbpress" => array(
            "code" => "gd-power-search-for-bbpress",
            "name" => "GD Power Search for bbPress",
            "description" => "Enhanced and powerful search for bbPress powered forums, with options to filter results by post author, forums, publication period, topic tags and few other things.",
            "punchline" => "Advanced search for bbPress powered forums",
            "color" => "#670240",
        ),
        "gd-quantum-theme-for-bbpress" => array(
            "code" => "gd-quantum-theme-for-bbpress",
            "name" => "GD Quantum Theme for bbPress",
            "description" => "Responsive and modern theme to fully replace default bbPress theme templates and styles, with multiple colour schemes and Customizer integration for more control.",
            "punchline" => "New theme for bbPress powered forums",
            "color" => "#D67500",
        ),
        "gd-press-tools" => array(
            "code" => "gd-press-tools",
            "name" => "GD Press Tools",
            "description" => "Collection of various administration, backup, cleanup, debug, events logging, tweaks and other useful tools and addons that can help with everyday tasks and optimization.",
            "punchline" => "Powerful administration plugin for WordPress",
            "color" => "#333333",
        ),
        "gd-rating-system" => array(
            "code" => "gd-rating-system",
            "name" => "GD Rating System",
            "description" => "Powerful, highly customizable and versatile ratings plugin to allow your users to vote for anything you want. Includes different rating methods and add-ons.",
            "punchline" => "Ultimate rating plugin for WordPress",
            "color" => "#262261",
        ),
        "gd-security-toolbox" => array(
            "code" => "gd-security-toolbox",
            "name" => "GD Security Toolbox",
            "description" => "A collection of many security related tools for .htaccess hardening with security events log, ReCaptcha, firewall, and tweaks collection, login and registration control and more.",
            "punchline" => "Proactive protection and security hardening",
            "color" => "#6F1A1A",
        ),
        "gd-seo-toolbox" => array(
            "code" => "gd-seo-toolbox",
            "name" => "GD SEO Toolbox",
            "description" => "Toolbox plugin with a number of search engine optimization related modules for Sitemaps, Robots.txt, Robots Meta and Knowledge Graph control, with more modules to be added.",
            "punchline" => "Search Engine Optimization for WordPress",
            "color" => "#C65C0F",
        ),
        "gd-swift-navigator" => array(
            "code" => "gd-swift-navigator",
            "name" => "GD Swift Navigator",
            "description" => "Add Swift, powerful and easy to use navigation control in the page corner with custom number of action buttons, popup navigation menus or custom HTML content.",
            "punchline" => "Swift, powerful and easy to use Navigation",
            "color" => "#0773B7",
        ),
        "gd-topic-polls" => array(
            "code" => "gd-topic-polls",
            "name" => "GD Topic Polls",
            "description" => "Implements polls system for bbPress powered forums, where users can add polls to topics, with a wide range of settings to control voting, poll closing, display of results and more.",
            "punchline" => "Enhance bbPress forums with topic polls",
            "color" => "#01665e",
        ),
        "gd-topic-prefix" => array(
            "code" => "gd-topic-prefix",
            "name" => "GD Topic Prefix",
            "description" => "Implements topic prefixes system, with support for styling customization, forum specific prefix groups with use of user roles, default prefixes, filtering of topics by prefix and more.",
            "punchline" => "Easy to use topic prefixes for bbPress forums",
            "color" => "#A10A0A",
        ),
        "gd-webfonts-toolbox" => array(
            "code" => "gd-webfonts-toolbox",
            "name" => "GD WebFonts Toolbox",
            "description" => "An easy way to add Web Fonts (Google, Adobe, Typekit) and local FontFaces to standard and custom CSS selectors, with WordPress editor integration and more.",
            "punchline" => "Easy and powerful Web fonts integration",
            "color" => "#6F0392",
        )
    );

    public function __construct() { }

    /** @return Store */
    public static function instance() {
        static $instance = null;

        if (!isset($instance)) {
            $instance = new Store();
        }

        return $instance;
    }

    public function plugins() {
        return $this->_plugins;
    }

    public function name($code) {
        return isset($this->_plugins[$code]) ? $this->_plugins[$code]['name'] : '';
    }

    public function description($code) {
        return isset($this->_plugins[$code]) ? $this->_plugins[$code]['description'] : '';
    }

    public function punchline($code) {
        return isset($this->_plugins[$code]) ? $this->_plugins[$code]['punchline'] : '';
    }

    public function color($code) {
        return isset($this->_plugins[$code]) ? $this->_plugins[$code]['color'] : '';
    }

    public function url($code) {
        return isset($this->_plugins[$code]) ? 'https://plugins.dev4press.com/'.$code.'/' : '';
    }
}
