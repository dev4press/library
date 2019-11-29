/*jslint regexp: true, nomen: true, undef: true, sloppy: true, eqeq: true, vars: true, white: true, plusplus: true, maxerr: 50, indent: 4 */
/*global d4plib_admin_data*/

;(function($, window, document, undefined) {
    window.wp = window.wp || {};
    window.wp.dev4press = window.wp.dev4press || {};

    window.wp.dev4press.admin = {
        scroll_offset: 40,
        active_element: null,
        init: function() {
            wp.dev4press.admin.components.interface.run();
            wp.dev4press.admin.components.scroller.run();

            wp.dev4press.admin.settings.color_picker.run();
            wp.dev4press.admin.settings.expandables.run();
            wp.dev4press.admin.settings.toggles.run();
            wp.dev4press.admin.settings.check_uncheck.run();

            $(window).bind("load resize orientationchange", function(){
                wp.dev4press.admin.components.scroller.resize();
            });
        },
        components: {
            scroller: {
                run: function() {
                    var $sidebar = $(".d4p-panel-scroller"),
                        $window = $(window);

                    if ($sidebar.length > 0) {
                        var offset = $sidebar.offset();

                        $window.scroll(function() {
                            if ($window.scrollTop() > offset.top && $sidebar.hasClass("d4p-scroll-active")) {
                                $sidebar.stop().animate({
                                    marginTop: $window.scrollTop() - offset.top + wp.dev4press.admin.scroll_offset
                                });
                            } else {
                                $sidebar.stop().animate({
                                    marginTop: 0
                                });
                            }
                        });
                    }
                },
                resize: function() {
                    if (document.body.clientWidth < 800) {
                        wp.dev4press.admin.scroll_offset = 60;
                    } else {
                        wp.dev4press.admin.scroll_offset = 40;
                    }

                    if (document.body.clientWidth < 640) {
                        $(".d4p-panel-scroller").removeClass("d4p-scroll-active");
                    } else {
                        $(".d4p-panel-scroller").addClass("d4p-scroll-active");
                    }
                }
            },
            interface: {
                run: function() {
                    $(".d4p-nav-button > a").click(function(e){
                        e.preventDefault();

                        $(this).next().slideToggle("fast");
                    });

                    if ($(".d4p-wrap .d4p-message .notice").length > 0) {
                        setTimeout(function () {
                            $(".d4p-wrap .d4p-message .notice").slideUp("slow");
                        }, 10000);
                    }
                }
            }
        },
        settings: {
            color_picker: {
                run: function() {
                    if ($(".d4p-color-picker").length > 0) {
                        $(".d4p-color-picker").wpColorPicker();
                    }
                }
            },
            check_uncheck: {
                run: function() {
                    $(".d4p-check-uncheck a").click(function(e){
                        e.preventDefault();

                        var checkall = $(this).attr("href").substr(1) === "checkall";

                        $(this).parent().parent().find("input[type=checkbox]").prop("checked", checkall);
                    });
                }
            },
            toggles: {
                run: function() {
                    $(document).on("click", ".d4p-group h3 i.fa.fa-caret-down, .d4p-group h3 i.fa.fa-caret-up", function() {
                        var closed = $(this).hasClass("fa-caret-down"),
                            content = $(this).parent().next();

                        if (closed) {
                            $(this).removeClass("fa-caret-down").addClass("fa-caret-up");
                            content.slideDown(300);
                        } else {
                            $(this).removeClass("fa-caret-up").addClass("fa-caret-down");
                            content.slideUp(300);
                        }
                    });

                    $(document).on("click", ".d4p-section-toggle .d4p-toggle-title", function() {
                        var icon = $(this).find("i.fa"),
                            closed = icon.hasClass("fa-caret-down"),
                            content = $(this).next();

                        if (closed) {
                            icon.removeClass("fa-caret-down").addClass("fa-caret-up");
                            content.slideDown(300);
                        } else {
                            icon.removeClass("fa-caret-up").addClass("fa-caret-down");
                            content.slideUp(300);
                        }
                    });
                }
            },
            expandables: {
                run: function() {
                    $(document).on("click", ".d4p-setting-expandable_pairs .button-secondary", function(e){
                        e.preventDefault();

                        var li = $(this).parent();

                        li.fadeOut(200, function(){
                            li.remove();
                        });
                    });

                    $(".d4p-setting-expandable_pairs a.button-primary").click(function(e) {
                        e.preventDefault();

                        var list = $(this).closest(".d4p-setting-expandable_pairs"),
                            next = $(".d4p-next-id", list),
                            next_id = next.val(),
                            el = $(".pair-element-0", list).clone();

                        $("input", el).each(function(){
                            var id = $(this).attr("id").replace("_0_", "_" + next_id + "_"),
                                name = $(this).attr("name").replace("[0]", "[" + next_id + "]");

                            $(this).attr("id", id).attr("name", name);
                        });

                        el.attr("class", "pair-element-" + next_id).fadeIn();
                        $(this).before(el);

                        next_id++;
                        next.val(next_id);
                    });

                    $(document).on("click", ".d4p-setting-expandable_text .button-secondary", function(e){
                        wp.dev4press.admin.settings.expandables.remove(this, e);
                        wp.dev4press.admin.settings.expandables.remove(this, e);
                    });

                    $(document).on("click", ".d4p-setting-expandable_raw .button-secondary", function(e){
                        wp.dev4press.admin.settings.expandables.remove(this, e);
                    });

                    $(".d4p-setting-expandable_text a.button-primary").click(function(e) {
                        wp.dev4press.admin.settings.expandables.add(this, e, ".d4p-setting-expandable_text");
                    });

                    $(".d4p-setting-expandable_raw a.button-primary").click(function(e) {
                        wp.dev4press.admin.settings.expandables.add(this, e, ".d4p-setting-expandable_raw");
                    });
                },
                add: function(ths, e, cls) {
                    e.preventDefault();

                    var list = $(ths).closest(cls),
                        next = $(".d4p-next-id", list),
                        next_id = next.val(),
                        el = $(".exp-text-element-0", list).clone();

                    $("input", el).each(function(){
                        var id = $(this).attr("id").replace("_0_", "_" + next_id + "_"),
                            name = $(this).attr("name").replace("[0]", "[" + next_id + "]");

                        $(this).attr("id", id).attr("name", name);
                    });

                    el.attr("class", "exp-text-element exp-text-element-" + next_id).fadeIn();
                    $("ol", list).append(el);

                    next_id++;
                    next.val(next_id);
                },
                remove: function(ths, e) {
                    e.preventDefault();

                    var li = $(ths).parent();

                    li.fadeOut(200, function(){
                        li.remove();
                    });
                }
            } 
        }
    };

    window.wp.dev4press.dialogs = {
        defaults: function() {
            return {
                width: 480,
                height: "auto",
                minHeight: 24,
                autoOpen: false,
                resizable: false,
                modal: true,
                closeOnEscape: false,
                zIndex: 300000,
                open: function() {
                    $(".gdpol-button-focus").focus();
                }
            };
        }
    };

    wp.dev4press.admin.init();
})(jQuery, window, document);
