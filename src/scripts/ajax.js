;/*jslint regexp: true, nomen: true, undef: true, sloppy: true, eqeq: true, vars: true, white: true, plusplus: true, maxerr: 50, indent: 4 */
/*global d4plib_admin_dialogs*/

(function($, window, document) {
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

            $(document).on(
                "click",
                this.button,
                function() {
                    if (wp.dev4press.ajaxtask.progres.active) {
                        wp.dev4press.ajaxtask.stop();
                    } else {
                        wp.dev4press.ajaxtask.start();
                    }
                }
            );
        },
        start: function() {
            this.progres.active = true;

            $(this.button).val(d4plib_admin_dialogs.buttons.stop);

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
})(jQuery, window, document);
