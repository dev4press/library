# Dev4Press Library

## Changelog

### Version 4.7 - 2024.01.15

* New: option for expandable pairs has new layout and styling
* New: expanded the `Elements` method `input` with more attributes
* New: added several more UI icons
* Edit: various improvements to the metabox handling and styling
* Edit: settings `Render` class uses `Elements` for input types
* Edit: various improvements for expandable pairs control
* Edit: built-in cacert.pem 20231212
* Fix: problem with the rendering of hierarchy checkboxes
* Fix: missing default elements for expandable pairs control
* Fix: several issues with validation of data for `Widget` class

### Version 4.6 - 2023.12.28

* New: metabox styling rebuilt and improved with sidebar styling support
* Edit: show knowledge base button/link for individual features
* Edit: various improvements to the `Sanitize` class
* Edit: expanded the `KSES` validation for the `Input` control
* Fix: few issues with rendering of the widgets tab control
* Fix: method for basic cleanup in `Sanitize` class fails in some cases
* Fix: input rendering class stripping `min` and `max` attributes
* Fix: checkbox and radio rendering not matching selected value

### Version 4.5.2 - 2023.12.19

* Edit: function `json_encode` replaced with `wp_json_encode`
* Edit: improved `IP` class method for getting visitor IP
* Edit: various validation changes and function use changes
* Fix: potential vulnerability related to visitor IP method

### Version 4.5.1 - 2023.12.18

* Edit: few minor tweaks in the Customizer `Manager` class
* Fix: few escaping issues with rendering some settings

### Version 4.5 - 2023.12.12

* New: licensing support for settings and validation
* New: `Enqueue` classes prefixes now match the library version
* New: `Background` abstract class for handling background jobs
* New: `AJAX` and `CRON` expanded classes for the `Background` class
* New: settings elements related to the license value
* New: admin interface group box with support for tabs
* Edit: several improvements to the main `Wizard` class and styling
* Edit: fully rewritten cleanup method for `HTACCESS` class
* Edit: big styling changes and improvements for the plugin interface
* Edit: various changes to several plugin core classes
* Edit: improvements to the admin header display with extra buttons
* Edit: improvements to the `Features` panel filtering
* Edit: unique library prefix for files added by `Enqueue`
* Fix: issue with the screen options handling on networks setups
* Fix: minor problem with the getting group settings
