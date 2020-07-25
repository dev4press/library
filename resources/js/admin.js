/*jslint regexp: true, nomen: true, undef: true, sloppy: true, eqeq: true, vars: true, white: true, plusplus: true, maxerr: 50, indent: 4 */
/*global d4plib_admin_data*/

;(function($, window, document, undefined) {
    window.wp = window.wp || {};
    window.wp.dev4press = window.wp.dev4press || {};

    window.wp.dev4press.admin = {
        scroll_offset: 40,
        active_element: null,
        init: function() {
            wp.dev4press.admin.components.scroller.run();
            wp.dev4press.admin.components.interface.run();
            wp.dev4press.admin.components.notices.run();

            if (d4plib_admin_data.page.panel === 'settings') {
                wp.dev4press.admin.panels.settings.run();
            }

            $(window).bind("load resize orientationchange", function() {
                wp.dev4press.admin.components.scroller.resize();
            });
        },
        panels: {
            settings: {
                run: function() {
                    wp.dev4press.admin.settings.init();

                    $("#" + d4plib_admin_data.plugin.prefix + "-form-settings").confirmsubmit();

                    if ($("#d4p-settings-mark").length === 1) {
                        wp.dev4press.admin.panels.settings.mark();
                    }
                },
                mark: function() {
                    $(".d4p-panel-mark button").click(function() {
                        $("#d4p-settings-mark").val("").trigger("input");
                    });

                    var $groups = $(".d4p-group"),
                        $titles = $(".d4p-group > h3"),
                        $sections = $(".d4p-settings-section > h4"),
                        $content = $(".d4p-settings-table > tbody > tr");

                    $("#d4p-settings-mark").on("input", function() {
                        var term = $(this).val();

                        $groups.show();
                        $titles.unmark();
                        $sections.show().unmark();
                        $content.show().unmark();

                        if (term) {
                            $content.mark(term, {
                                done: function() {
                                    $content.not(":has(mark)").hide();
                                }
                            });

                            $sections.mark(term, {
                                done: function() {
                                    $sections.each(function(idx, el) {
                                        if ($(el).find("mark").length > 0) {
                                            $(el).parent().find(".d4p-settings-table > tbody > tr").show();
                                        } else {
                                            $(el).hide();
                                        }
                                    });
                                }
                            });

                            $titles.mark(term, {
                                done: function() {
                                    $titles.each(function(idx, el) {
                                        if ($(el).find("mark").length > 0) {
                                            $(el).parent().find(".d4p-settings-table > tbody > tr").show();
                                        }
                                    });
                                }
                            });

                            $titles.each(function(idx, el) {
                                var $group = $(el).parent(), height = 0,
                                    $elements = $(".d4p-settings-section", $group);

                                $elements.each(function(i, e) {
                                    if ($(e).height() > 0) {
                                        height += $(e).height();
                                    }
                                });

                                if (height === 0) {
                                    $group.hide();
                                }
                            });
                        }
                    });
                }
            }
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
                        $(".d4p-panel-scroller").removeClass("d4p-scroll-active").stop().css("margin-top", 0);
                    } else {
                        $(".d4p-panel-scroller").addClass("d4p-scroll-active");
                    }
                }
            },
            interface: {
                run: function() {
                    $(".d4p-nav-button > a").click(function(e) {
                        e.preventDefault();

                        $(this).next().slideToggle("fast");
                    });

                    if ($(".d4p-wrap .d4p-message .notice").length > 0) {
                        setTimeout(function() {
                            $(".d4p-wrap .d4p-message .notice").slideUp("slow");
                        }, 10000);
                    }
                }
            },
            notices: {
                run: function() {
                    $("#wpbody-content > div.notice").detach().prependTo(".d4p-wrap");
                }
            }
        },
        settings: {
            init: function() {
                wp.dev4press.admin.settings.color_picker.run();
                wp.dev4press.admin.settings.expandables.run();
                wp.dev4press.admin.settings.toggles.run();
                wp.dev4press.admin.settings.check_uncheck.run();
            },
            color_picker: {
                run: function() {
                    if ($(".d4p-color-picker").length > 0) {
                        $(".d4p-color-picker").wpColorPicker();
                    }
                }
            },
            check_uncheck: {
                run: function() {
                    $(".d4p-check-uncheck a").click(function(e) {
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
                    $(document).on("click", ".d4p-setting-expandable_pairs .button-secondary", function(e) {
                        e.preventDefault();

                        var li = $(this).parent();

                        li.fadeOut(200, function() {
                            li.remove();
                        });
                    });

                    $(document).on("click", ".d4p-setting-expandable_text .button-secondary", function(e) {
                        wp.dev4press.admin.settings.expandables.remove(this, e);
                        wp.dev4press.admin.settings.expandables.remove(this, e);
                    });

                    $(document).on("click", ".d4p-setting-expandable_raw .button-secondary", function(e) {
                        wp.dev4press.admin.settings.expandables.remove(this, e);
                    });

                    $(".d4p-setting-expandable_pairs a.button-primary").click(function(e) {
                        e.preventDefault();

                        var list = $(this).closest(".d4p-setting-expandable_pairs"),
                            next = $(".d4p-next-id", list),
                            next_id = next.val(),
                            el = $(".pair-element-0", list).clone();

                        $("input", el).each(function() {
                            var id = $(this).attr("id").replace("_0_", "_" + next_id + "_"),
                                name = $(this).attr("name").replace("[0]", "[" + next_id + "]");

                            $(this).attr("id", id).attr("name", name);
                        });

                        el.attr("class", "pair-element-" + next_id).fadeIn();
                        $(this).before(el);

                        next_id++;
                        next.val(next_id);
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

                    $("input", el).each(function() {
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

                    li.fadeOut(200, function() {
                        li.remove();
                    });
                }
            }
        }
    };

    window.wp.dev4press.dialogs = {
        storage: {
            url: ''
        },
        classes: function(extra, hide_close) {
            var cls = "wp-dialog d4p-dialog d4p-dialog-modal";

            if (extra !== "") {
                cls += " " + extra;
            }

            if (typeof hide_close !== "undefined") {
                if (hide_close) {
                    cls += " d4p-dialog-hidex";
                }
            }

            return cls;
        },
        default_button: function(button, has_focus, button_text) {
            var id = "d4p-dialog-button-id-" + button,
                cls = "d4p-dialog-button-" + button + (has_focus ? " button-has-focus" : ""),
                text = typeof button_text !== "undefined" ? button_text : d4plib_admin_data.ui.buttons[button];

            return {
                id: id,
                class: cls,
                text: text,
                data: {
                    icon: button
                }
            };
        },
        default_dialog: function() {
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
                    $(".button-has-focus").focus();
                }
            };
        },
        icons: function(id, icon_html) {
            $(id).next().find(".ui-dialog-buttonset button").each(function() {
                if (typeof icon_html === "undefined") {
                    var icon = $(this).data("icon");

                    if (icon !== "") {
                        $(this).find("span.ui-button-text").prepend(d4plib_admin_data.ui.icons[icon]);
                    }
                } else {
                    $(this).find("span.ui-button-text").prepend(icon_html);
                }
            });
        }
    };

    window.wp.dev4press.ajaxtask = {
        prefix: '',
        button: '',
        handler: '',
        nonce: '',
        progres: {
            active: false,
            stop: false,
            done: 0,
            total: 0
        },
        init: function(prefix, button, handler, nonce) {
            this.prefix = prefix;
            this.button = button;
            this.handler = handler;
            this.nonce = nonce;

            $(this.button).click(function(e) {
                if (wp.dev4press.ajaxtask.progres.active) {
                    wp.dev4press.ajaxtask.stop();
                } else {
                    wp.dev4press.ajaxtask.start();
                }
            });
        },
        start: function() {
            this.progres.active = true;

            $(this.button).val(d4plib_admin_data.ui.buttons.stop);

            $("#" + this.prefix + "-process").slideDown();
            $("#" + this.prefix + "-progress pre").html("");

            this._call({operation: "start"}, this._callback.start);
        },
        stop: function() {
            this.progres.stop = true;

            $(this.button).attr("disabled", true);
        },
        run: function() {
            this._call({operation: "run"}, this._callback.process);
        },
        _call: function(data, callback) {
            var args = {
                url: ajaxurl + "?action=" + this.handler + "&_ajax_nonce=" + this.nonce,
                type: "post",
                dataType: "json",
                data: data,
                success: callback
            };

            $.ajax(args);
        },
        _write: function(message) {
            $("#" + this.prefix + "-progress pre").append(message + "\r\n");
        },
        _callback: {
            start: function(json) {
                var p = wp.dev4press.ajaxtask.progres;

                p.current = 0;
                p.total = json.total;

                wp.dev4press.ajaxtask._write(json.message);

                wp.dev4press.ajaxtask.run();
            },
            stop: function(json) {
                wp.dev4press.ajaxtask.progres.active = false;
                wp.dev4press.ajaxtask._write(json.message);
            },
            process: function(json) {
                if (wp.dev4press.ajaxtask.progres.stop) {
                    wp.dev4press.ajaxtask._call({operation: "break"}, wp.dev4press.ajaxtask._callback.stop);
                } else {
                    wp.dev4press.ajaxtask.progres.done = json.done;

                    wp.dev4press.ajaxtask._write(json.message);

                    if (wp.dev4press.ajaxtask.progres.done < wp.dev4press.ajaxtask.progres.total) {
                        wp.dev4press.ajaxtask.run();
                    } else {
                        wp.dev4press.ajaxtask.stop();
                        wp.dev4press.ajaxtask._call({operation: "stop"}, wp.dev4press.ajaxtask._callback.stop);
                    }
                }
            }
        }
    };

    wp.dev4press.admin.init();
})(jQuery, window, document);
