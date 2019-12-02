;(function($, window, document, undefined){
    var ConfirmSubmit = function(elem, options) {
        this.elem = elem;
        this.$elem = $(elem);
        this.options = options;

        this.metadata = this.$elem.data("confirm-submit");
    };

    ConfirmSubmit.prototype = {
        defaults: { },

        init: function() {
            this.config = $.extend({}, this.defaults, this.options, this.metadata);
            this.$elem.data("form-state-original", this.$elem.serialize());

            var $form = this.$elem;

            $(window).on('beforeunload', function(e) {
                if ($form.data("form-state-original") !== $form.serialize()) {
                    return false;
                }

                return true;
            });
        }
    };

    ConfirmSubmit.defaults = ConfirmSubmit.prototype.defaults;

    $.fn.confirmsubmit = function(options) {
        return this.each(function() {
            new ConfirmSubmit(this, options).init();
        });
    };
})(jQuery, window, document);
