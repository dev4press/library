<div class="d4p-content">
    <div class="d4p-group d4p-group-information d4p-group-import">
        <h3><?php _e("Important Information", "d4plib"); ?></h3>
        <div class="d4p-group-inner">
            <p><?php _e("With this tool you can import all plugin settings from the export file made using the Export tool. If you made changes to the export file, the import will fail.", "d4plib"); ?></p>
        </div>
    </div>

    <div class="d4p-group d4p-group-information d4p-group-import">
        <h3><?php _e("Import from File", "d4plib"); ?></h3>
        <div class="d4p-group-inner">
            <p><?php _e("Select file you want to import from", "d4plib"); ?>:</p>
            <br/>
            <input type="file" name="import_file"/>
        </div>
    </div>

    <?php d4p_panel()->include_accessibility_control(); ?>
</div>
