# Dev4Press Library
## Changelog

### Version 4.4 - 2023.10.16
* New: render Changelog and History About panels from markdown
* New: email detection support for WooCommerce standard emails
* New: email detection support for WooCommerce `WP_Email` class
* New: method in `Str` to split camelcase string to word
* New: included PHP library `ParsedownExtra`
* Edit: updated email detection for new WordPress emails
* Edit: library changelog files switched to the Markdown format
* Fix: group select rendering problem with the selected value

### Version 4.3.5 - 2023.10.05
* Edit: minor changes to the `DBLite` handling of the logged query

### Version 4.3.4 - 2023.10.02
* New: class `File` has method to wrap `WP_Filesystem` use
* Edit: settings import from file now uses WordPress native functions

### Version 4.3.3 - 2023.09.26
* Edit: more changes related to PHPCS and WPCS validation

### Version 4.3.2 - 2023.09.25
* New: added several more UI icons

### Version 4.3.1 - 2023.09.20
* Edit: more changes related to PHPCS and WPCS validation

### Version 4.3 - 2023.09.05
* New: full validation with the PHPCS and additional WPCS rules
* New: some forms panel split into the elements files for re-usability
* New: settings elements can have toggleable additional information
* New: settings elements can have buttons
* New: class `Wizard` for setting up Setup Wizard panels
* New: class `Help` for setting up admin side context help
* New: class `Settings` direct preload of the multisite network settings
* New: class `HTAccess` expanded with the method to check .HTACCESS status
* New: class `Elements` expanded with new rendering methods
* New: helper `Data` class to hold some common data and lists
* New: many smaller new methods and functions added through the library
* New: added several more UI icons
* Edit: improvements to the `Panel` class URLs and forms loading
* Edit: improvements to the `DBLite` class by using magic `__call` method
* Edit: improvements to the Geolocation process and classes
* Edit: improvements to the coding standards and formatting
* Edit: improvements to the default Help panels content
* Edit: massive improvements to Features in relation to network mode plugins
* Edit: many updates to the plugin settings panel rendering
* Edit: many styling improvements to the various parts of the interface
* Edit: improvements to the rendering of the settings controls
* Edit: few improvements to the plugins `Store` class
* Edit: class `HTAccess` improves the writing to the file
* Edit: class for `PostBack` can handle network and blog mode plugins
* Edit: optimization of the font generating file sources
* Edit: updates to the translations and strings included
* Edit: several more strings escaping and kses-ing for display
* Updated: JS Cookie 3.0.5
* Updated: Built-in cacert.pem 20230822
* Removed: clipboard JS file since it is already in WordPress core
* Removed: standalone print and debug functions as no longer used
* Removed: class `Debug` as it is now obsolete
* Removed: all previously deprecated methods
* Fix: several more instances for missing sanitation in the options rendering
* Fix: header navigation in multisite mode show items that don't belong
