;/*jslint regexp: true, nomen: true, undef: true, sloppy: true, eqeq: true, vars: true, white: true, plusplus: true, maxerr: 50, indent: 4 */
(function($, window, document, undefined) {
    window.wp = window.wp || {};
    window.wp.dev4press = window.wp.dev4press || {};
    window.wp.dev4press.v49 = window.wp.dev4press.v49 || {};

    window.wp.dev4press.v49.metabox = {
        library: 'v49',
        init: function() {
            const wrapper = ".d4plib-" + wp.dev4press.v49.metabox.library + "-meta-box-wrapper";

            $(document).on(
                "click",
                wrapper + " .wp-tab-bar button",
                function(e) {
                    e.preventDefault();

                    var name = $(this).data("tab"),
                        tab = $(this).parent(),
                        tabs = $(this).closest(".wp-tab-bar"),
                        wrap = $(this).closest(wrapper);

                    tabs.find("li").removeClass("wp-tab-active");
                    tab.addClass("wp-tab-active");

                    wrap.find(".wp-tab-panel")
                        .removeClass("tabs-panel-active")
                        .addClass("tabs-panel-inactive");

                    wrap.find("#" + name)
                        .removeClass("tabs-panel-inactive")
                        .addClass("tabs-panel-active");
                }
            );

            $(document).on(
                "click",
                wrapper + " .d4p-check-uncheck a",
                function(e) {
                    e.preventDefault();

                    var checkall = $(this).attr("href").substring(1) === "checkall";

                    $(this).parent().parent().find("input[type=checkbox]").prop("checked", checkall);
                }
            );

            $(document).on(
                "change",
                wrapper + " .d4p-metabox-value-override",
                function(e) {
                    var sel = $(this).val(),
                        target = $(this).parent().parent().next();

                    if (sel === "yes") {
                        target.show();
                    } else {
                        target.hide();
                    }
                }
            )
        }
    };

    $(document).ready(
        function() {
            wp.dev4press.v49.metabox.init();
        }
    );
})(jQuery, window, document);
