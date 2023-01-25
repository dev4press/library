/*jslint regexp: true, nomen: true, undef: true, sloppy: true, eqeq: true, vars: true, white: true, plusplus: true, maxerr: 50, indent: 4 */

;(function($, window, document, undefined) {
    window.wp = window.wp || {};
    window.wp.dev4press = window.wp.dev4press || {};

    window.wp.dev4press.metabox = {
        init: function() {
            $(document).on("click", ".d4plib-meta-box-wrapper .d4p-check-uncheck a", function(e) {
                e.preventDefault();

                var checkall = $(this).attr("href").substring(1) === "checkall";

                $(this).parent().parent().find("input[type=checkbox]").prop("checked", checkall);
            });
        }
    };

    $(document).ready(function() {
        wp.dev4press.metabox.init();
    });
})(jQuery, window, document);
