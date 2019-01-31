(function($) {
    function inherit(proto) {
        function F() {}
        F.prototype = proto;
        var object = new F();
        return object;
    }

    function Simplepage(callback) {
        if (typeof arguments.callee.instance !== "undefined") {
            return arguments.callee.instance;
        }

        this.callback = callback || function() {};
        this.formSubmitted = false;
        this.popup = false;

        this.callFunc = function(func, $target) {
            var self = this;

            if (func && typeof self[func] === "function") {
                self[func]($target);
            } else if (func) {
                //console.log(func + " is not registered");
            }
        };

        this.init = function(popup) {
            var self = this;

            var callbackForComplexField = function($target) {
                var func = $target.attr("data-onchange");
                if (!func) {
                    func = $target.attr("data-onchange-delayed");
                }
                if (func && typeof self[func] === "function") {
                    self[func]($target);
                } else if (func) {
                    //console.log(func + " is not registered");
                }
            };

            if (popup) {
                self.popup = true;
            }

            self.requestTimerId = 0;

            if (self.useGoogleApi) {
                self.initGoogleApi(callbackForComplexField);
            }
            if (self.useAutocomplete) {
                self.initAutocomplete(callbackForComplexField);
            }

            self.initPopups();
            self.initMasks();
            self.initTooltips();
            self.initDatepickers(callbackForComplexField);
            self.initTimepickers(callbackForComplexField);
            self.initFileUploader(function() {
                self.overlay();
            }, function() {
                self.removeOverlay();
            });
            self.initHandlers();
            self.initValidationRules();

            if (typeof self.callback === "function") {
                self.callback();
            }
        };

        this.initHandlers = function() {
            var self = this;
            $(self.mainContainer + " *[data-onchange]," + self.mainContainer + " *[data-onclick]").each(function() {
                var $element = $(this);

                var funcOnChange = $element.attr("data-onchange");
                if (funcOnChange) {
                    $element.on("change", function() {
                        self.callFunc(funcOnChange, $element);
                    });
                }

                var funcOnClick = $element.attr("data-onclick");
                if (funcOnClick) {
                    $element.on("click", function() {
                        self.callFunc(funcOnClick, $element);
                    });
                }
            });

            $(self.mainContainer).submit(function(event) {
                self.requestReloadAll();
                event.preventDefault();
                return false;
            });
        };

        this.addSystemFieldsInForm = function() {
            var self = this;
            if (self.formSubmitted) {
                $(self.mainContainer).append($("<input/>").attr("type", "hidden").attr("name", "submitted").val(1));
            }
        };

        this.validate = function() {
            var self = this;

            if (!self.checkRules(self.mainContainer)) {
                self.scroll();
                return false;
            }

            return true;
        };

        this.submit = function() {
            var self = this;

            if (!self.validate()) {
                return;
            }

            self.formSubmitted = true;
            $(self.mainContainer).submit();
        };

        /**
         * Adds delay for reload execution on 150 ms, it allows to check sequence of events and to execute only the last request to handle of more events in one reloading
         * @param  {Function} callback
         */
        this.requestReloadAll = function(callback) {
            var self = this;
            if (self.requestTimerId) {
                clearTimeout(self.requestTimerId);
                self.requestTimerId = 0;
            }
            self.requestTimerId = setTimeout(function() {
                self.reloadAll(callback);
            }, 150);
        };

        this.overlay = function() {
            var self = this;
            var $block = $(self.mainContainer);
            if ($block.length) {
                $block.find("input,select,textarea").attr("disabled", "disabled");
                $block.append(
                    $("<div>")
                    .addClass("simplepage_overlay")
                    .attr("id", $block.attr("id") + "_overlay")
                    .css({
                        "background": "url(" + self.additionalPath + self.resources.loading + ") no-repeat center center",
                        "opacity": 0.4,
                        "position": "absolute",
                        "width": $block.width(),
                        "height": $block.height(),
                        "z-index": 5000
                    })
                    .offset({
                        top: $block.offset().top,
                        left: $block.offset().left
                    })
                );
            }
        };

        this.removeOverlay = function() {
            var self = this;

            if (typeof self.mainContainer !== "undefined" && $(self.mainContainer).length) {
                $(self.mainContainer).find("input,select,textarea").removeAttr("disabled");
            }

            $(".simplepage_overlay").remove();
        };

        this.scroll = function() {
            var self = this,
                error = false,
                top = 10000,
                bottom = 0;

            var isOutsideOfVisibleArea = function(y) {
                if (y < $(document).scrollTop() || y > ($(document).scrollTop() + $(document).height())) {
                    return true;
                }
                return false;
            };

            if (self.popup) {
                return;
            }

            if (self.getPropertySafe("scrollToError")) {
                $($(self.mainContainer + " .simplecheckout-rule:visible")).each(function() {
                    if ($(this).parents(".simpleregister-block-content").length) {
                        var offset = $(this).parents(".simpleregister-block-content").offset();
                        if (offset.top < top) {
                            top = offset.top;
                        }
                        if (offset.bottom > bottom) {
                            bottom = offset.bottom;
                        }
                    }
                });
                if (top < 10000 && isOutsideOfVisibleArea(top)) {
                    jQuery("html, body").animate({
                        scrollTop: top
                    }, "slow");
                    error = true;
                } else if (bottom && isOutsideOfVisibleArea(bottom)) {
                    jQuery("html, body").animate({
                        scrollTop: bottom
                    }, "slow");
                    error = true;
                }
            }
        };

        this.reloadAll = function(callback) {
            var self = this;
            var postData;
            if (self.isReloading) {
                return;
            }
            self.addSystemFieldsInForm();
            self.isReloading = true;
            postData = $(self.mainContainer).find("input,select,textarea").serialize();
            $.ajax({
                url: self.mainUrl,
                data: postData + "&ajax=1",
                type: "POST",
                dataType: "text",
                beforeSend: function() {
                    self.overlay();
                },
                success: function(data) {
                    var newData = $(self.mainContainer, $(data)).get(0);
                    if (!newData && data) {
                        newData = data;
                    }
                    $(self.mainContainer).replaceWith(newData);
                    self.init();
                    if (typeof callback === "function") {
                        callback.call(self);
                    }
                    self.removeOverlay();
                    self.isReloading = false;
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    self.removeOverlay();
                    self.isReloading = false;
                }
            });
        };

        arguments.callee.instance = this;
    }

    Simplepage.prototype = inherit(simple);

    $(function() {
        var simplepage = new Simplepage(function() {
            try {
                simple.javascriptCallback();
            } catch (err) {

            }
        });

        simplepage.init();

        window.simplepage = simplepage;
        window.reloadAll = bind(simplepage.reloadAll, simplepage);
    });
})(jQuery);