;(function(window) {
    const ConfirmSubmit = function(elem) {
        this.elem = elem;
    };

    ConfirmSubmit.prototype = {
        init: function() {
            this.save();

            const self = this;

            this.elem.addEventListener('submit',
                function() {
                    self.save();
                }
            );

            window.addEventListener(
                'beforeunload',
                function(e) {
                    if (self.elem.dataset.formStateOriginal !== self.serialize()) {
                        e.preventDefault();
                    }
                }
            );
        },
        save: function() {
            this.elem.dataset.formStateOriginal = this.serialize();
        },
        serialize: function() {
            const params = new URLSearchParams();

            new FormData(this.elem).forEach(
                function(value, key) {
                    params.append(key, String(value));
                }
            );

            return params.toString();
        }
    };

    window.ConfirmSubmit = ConfirmSubmit;
})(window);
