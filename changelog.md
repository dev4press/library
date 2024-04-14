# Dev4Press Library

## Changelog

### Version 4.8 - 2024.04.18

* New: `Core` object has priority options for the loading actions
* New: `Settings` object can specify some groups as network wide
* New: settings now can render the `Clear` button for text-based field
* New: added several more UI icons
* Edit: improvements to the default styling for dashboard and tabs
* Edit: improvements to base `Panel` object content file names
* Edit: improvements to the `License` base class related to API updates
* Edit: built-in cacert.pem 20240311
* Fix: some `Core` properties were private not protected
* Fix: license check job initialized with filter not action

### Version 4.7.3 - 2024.03.21

* Fix: problem with the Table object and orderby processing

### Version 4.7.2 - 2024.03.15

* Edit: few more updates to the options rendering class
* Fix: several more instances for missing sanitation in the options rendering

### Version 4.7.1 - 2024.03.04

* Edit: `IP` class option for forwarded IP keys

### Version 4.7 - 2024.02.12

* New: option for expandable pairs has new layout and styling
* New: expanded the `Elements` method `input` with more attributes
* New: expanded the `BBP` class with `can_use_pretty_urls` method
* New: expanded the `BBP` class with new roles check methods for `user_id`
* New: expanded the `WPR` class with new roles check methods for `user_id`
* New: expanded the `File` class with `put_contents` method
* New: added several more UI icons
* Edit: rewritten `IP` class for getting visitor IP with extra options
* Edit: various improvements to the metabox handling and styling
* Edit: additional filter for the multisite menu integration for blogs
* Edit: improvements to the admin page header navigation styling
* Edit: settings `Render` class uses `Elements` for input types
* Edit: various improvements for expandable pairs control
* Edit: changes related to WordPress and PHP code standards
* Edit: built-in cacert.pem 20231212
* Removed: all previously deprecated methods
* Fix: problem with the rendering of hierarchy checkboxes
* Fix: missing default elements for expandable pairs control
* Fix: several issues with validation of data for `Widget` class
* Fix: issue with the action URL method for admin class

### Version 4.6 - 2023.12.28

* New: metabox styling rebuilt and improved with sidebar styling support
* Edit: show knowledge base button/link for individual features
* Edit: various improvements to the `Sanitize` class
* Edit: expanded the `KSES` validation for the `Input` control
* Fix: few issues with rendering of the widgets tab control
* Fix: method for basic cleanup in `Sanitize` class fails in some cases
* Fix: input rendering class stripping `min` and `max` attributes
* Fix: checkbox and radio rendering not matching selected value
