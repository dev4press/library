<?php

use function Dev4Press\v36\Functions\panel;

?>
<div class="d4p-content">
    <div class="d4p-group d4p-group-information d4p-group-import">
        <h3><?php _e( "Important Information", "d4plib" ); ?></h3>
        <div class="d4p-group-inner">
            <p><?php _e( "With this tool you can import all plugin settings from the export file made using the Export tool. If you made changes to the export file, the import will fail.", "d4plib" ); ?></p>
            <p><?php _e( "For import tools to work correctly, you must import a file made with the export tool running the same versions of the plugin for both import and export!", "d4plib" ); ?></p>
        </div>
    </div>

    <div class="d4p-group d4p-group-information d4p-group-import">
        <h3><?php _e( "Import from File", "d4plib" ); ?></h3>
        <div class="d4p-group-inner">
            <p><?php _e( "Select file you want to import from", "d4plib" ); ?>:</p>
            <br/>
            <input type="file" name="import_file"/>
        </div>
    </div>

	<?php panel()->include_accessibility_control(); ?>
</div>
