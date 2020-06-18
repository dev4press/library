/*jslint regexp: true, nomen: true, undef: true, sloppy: true, eqeq: true, vars: true, white: true, plusplus: true, maxerr: 50, indent: 4 */

;(function($, window, document, undefined) {
    window.wp = window.wp || {};
    window.wp.dev4press = window.wp.dev4press || {};

    window.wp.dev4press.metabox = {
        init: function() {
            $(document).on("click", ".d4plib-meta-box-wrapper .wp-tab-bar a", function(e) {
                e.preventDefault();

                var tab = $(this).attr("href").substr(1),
                    wrap = $(this).closest(".d4plib-meta-box-wrapper");

                $(this).closest("ul").find("li").removeClass("wp-tab-active");
                $(this).parent().addClass("wp-tab-active");

                wrap.find(".wp-tab-panel")
                    .removeClass("tabs-panel-active")
                    .addClass("tabs-panel-inactive");

                wrap.find("#" + tab)
                    .removeClass("tabs-panel-inactive")
                    .addClass("tabs-panel-active");
            });

            $(document).on("click", ".d4plib-meta-box-wrapper .d4p-check-uncheck a", function(e) {
                e.preventDefault();

                var checkall = $(this).attr("href").substr(1) === "checkall";

                $(this).parent().parent().find("input[type=checkbox]").prop("checked", checkall);
            });
        }
    };

    wp.dev4press.metabox.init();
})(jQuery, window, document);
