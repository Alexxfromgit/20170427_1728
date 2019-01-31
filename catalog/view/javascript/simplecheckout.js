(function($) {
    function Simplecheckout(route, callback) {
        if (typeof arguments.callee.instance !== "undefined") {
            return arguments.callee.instance;
        }

        var checkIsInContainer = function($element, selector) {
            if ($element.parents(selector).length) {
                return true;
            }
            return false;
        };

        this.callback = callback || function() {};
        this.mainRoute = route;
        this.popup = false;

        this.selectors = {
            paymentForm: "#simplecheckout_payment_form",
            paymentButtons: "#simplecheckout_payment_form div.buttons:last",
            step: ".simplecheckout-step",
            buttons: "#buttons",
            buttonPrev: "#simplecheckout_button_prev",
            buttonNext: "#simplecheckout_button_next",
            buttonCreate: "#simplecheckout_button_confirm",
            buttonBack: "#simplecheckout_button_back",
            stepsMenu: "#simplecheckout_step_menu",
            stepsMenuItem: ".simple-step",
            stepsMenuDelimiter: ".simple-step-delimiter",
            proceedText: "#simplecheckout_proceed_payment",
            agreementCheckBox: "#agreement_checkbox",
            agreementWarning: "#agreement_warning",
            block: ".simplecheckout-block"
        };

        this.classes = {
            stepsMenuCompleted: "simple-step-completed",
            stepsMenuCurrent: "simple-step-current"
        };

        this.blocks = [];
        this.$steps = [];
        this.requestTimerId = 0;
        this.backCount = 0;
        this.currentStep = 1;
        this.requestedStep = 1;
        this.stepReseted = false;
        this.formSubmitted = false;

        this.callFunc = function(func, $target) {
            var self = this;

            if (func && typeof self[func] === "function") {
                self[func]($target);
            } else if (func) {
                //console.log(func + " is not registered");
            }
        };

        this.registerBlock = function(object) {
            var self = this;
            object.setParent(self);
            self.blocks.push(object);
        };

        this.initBlocks = function() {
            var self = this;
            for (var i in self.blocks) {
                if (!self.blocks.hasOwnProperty(i)) continue;

                self.blocks[i].init();
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
                self.setDirty();
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

            self.addObserver();
            self.initPopups();
            self.initMasks();
            self.initTooltips();
            self.initDatepickers(callbackForComplexField);
            self.initTimepickers(callbackForComplexField);
            self.initFileUploader(function() {
                self.overlayAll();
            }, function() {
                self.removeOverlays();
            });
            self.initHandlers();
            self.initBlocks();
            self.initSteps();
            self.scroll();
            self.initValidationRules();

            if (typeof self.callback === "function") {
                self.callback();
            }
        };

        this.initHandlers = function() {
            var self = this;
            $(self.mainContainer + " *[data-onchange]," + self.mainContainer + " *[data-onclick]").each(function() {
                var bind = true,
                    $element = $(this);
                for (var i in self.blocks) {
                    if (!self.blocks.hasOwnProperty(i)) continue;

                    if (checkIsInContainer($element, self.blocks[i].currentContainer)) {
                        bind = false;
                        break;
                    }
                }
                if (bind) {
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
                }
            });
        };

        this.addObserver = function() {
            var self = this;
            $(self.mainContainer + " input[type=radio]," + self.mainContainer + " input[type=checkbox]," + self.mainContainer + " select").on("change", function() {
                if (!checkIsInContainer($(this), self.selectors.paymentForm)) {
                    self.setDirty();
                }
            });

            $(self.mainContainer + " input," + self.mainContainer + " textarea").on("keydown", function() {
                if (!checkIsInContainer($(this), self.selectors.paymentForm)) {
                    self.setDirty();
                }
            });
        };

        this.setDirty = function() {
            var self = this;
            $(self.selectors.paymentForm).attr("data-invalid", "true").empty();
            $(self.mainContainer + " *[data-payment-button=true]").remove();
            self.formSubmitted = false;
            if (self.currentStep == self.stepsCount) {
                $(self.selectors.buttons).show();
                $(self.selectors.buttonCreate).show();
            }
        };

        this.preventOrderDeleting = function(callback) {
            var self = this;
            $.get("index.php?" + self.additionalParams + "route=" + self.mainRoute + "/prevent_delete", function() {
                if (typeof callback === "function") {
                    callback();
                }
            });
        };

        this.clickOnConfirmButton = function() {
            var self = this;

            var $paymentForm = $(self.selectors.paymentForm);

            if (self.isPaymentFormEmpty()) {
                return;
            }

            var gatewayLink = $paymentForm.find("div.buttons a:last").attr("href");
            var $submitButton = $paymentForm.find("div.buttons input[type=button]:last,div.buttons input[type=submit]:last,div.buttons button:last,div.buttons a.button:last:not([href])");
            var $lastButton = $paymentForm.find("input[type=button]:last,input[type=submit]:last,button:last");
            var lastLink = $paymentForm.find("a:last").attr('href');

            var overlayButton = function() {
                $(self.selectors.buttonCreate).attr("disabled", "disabled");
                if (!$(".wait").length) {
                    $(self.selectors.buttonCreate).after("<span class='wait'>&nbsp;<img src='" + self.additionalPath + self.resources.loadingSmall + "' alt='' /></span>");
                }
            };

            var removeOverlay = function() {
                $(self.selectors.buttonCreate).removeAttr("disabled");
                $('.wait').remove();
            };

            if (typeof gatewayLink !== "undefined" && gatewayLink !== "" && gatewayLink !== "#") {
                overlayButton();
                self.preventOrderDeleting(function() {
                    removeOverlay();
                    window.location = gatewayLink;
                    self.proceed();
                });
            } else if ($submitButton.length) {
                overlayButton();
                self.preventOrderDeleting(function() {
                    removeOverlay();
                    if (!$submitButton.attr("disabled")) {
                        $submitButton.click();
                    }
                    self.proceed();
                });
            } else if ($lastButton.length) {
                overlayButton();
                self.preventOrderDeleting(function() {
                    removeOverlay();
                    if (!$lastButton.attr("disabled")) {
                        $lastButton.click();
                    }
                    self.proceed();
                });
            } else if (typeof lastLink !== "undefined" && lastLink !== "" && lastLink !== "#") {
                overlayButton();
                self.preventOrderDeleting(function() {
                    removeOverlay();
                    window.location = lastLink;
                    self.proceed();
                });
            }
        };

        this.isPaymentFormValid = function() {
            var self = this;
            return !self.isPaymentFormEmpty() && !$(self.selectors.paymentForm).attr("data-invalid") ? true : false;
        };

        this.isPaymentFormVisible = function() {
            var self = this;
            return !self.isPaymentFormEmpty() && $(self.selectors.paymentForm + " :visible:not(form)").length > 0 ? true : false;
        };

        this.isPaymentFormEmpty = function() {
            var self = this;
            return $(self.selectors.paymentForm).length && $(self.selectors.paymentForm + " *").length > 0 ? false : true;
        };

        this.replaceCreateButtonWithConfirm = function() {
            var self = this;

            var $paymentForm = $(self.selectors.paymentForm);

            if (self.isPaymentFormEmpty()) {
                return;
            }

            var $gatewayLink = $paymentForm.find("div.buttons a:last");
            var $submitButton = $paymentForm.find("div.buttons input[type=button]:last,div.buttons input[type=submit]:last,div.buttons button:last,div.buttons a.button:last:not([href])");
            var $lastButton = $paymentForm.find("input[type=button]:last,input[type=submit]:last,button:last");
            var $lastLink = $paymentForm.find("a:last");

            var $obj = false;

            if ($gatewayLink.length) {
                $obj = $gatewayLink;
            } else if ($submitButton.length) {
                $obj = $submitButton;
            } else if ($lastButton.length) {
                $obj = $lastButton;
            } else if ($lastLink.length) {
                $obj = $lastLink;
            }

            if ($obj) {
                var $clone = $obj.clone(false);
                $(self.selectors.buttonCreate).hide().before($clone);

                $clone.attr("data-payment-button", "true").bind("click", function() {
                    self.preventOrderDeleting();
                    self.proceed();
                    $obj.click();
                });
            } else {
                $(self.selectors.buttons).hide();
                self.preventOrderDeleting();
            }
        };

        this.proceed = function() {
            var self = this;
            if (self.getPropertySafe("displayProceedText") && !self.isPaymentFormVisible()) {
                $(self.selectors.proceedText).show();
            }
        };

        this.gotoStep = function($target) {
            var self = this;
            var step = $target.attr("data-step");
            if (step < self.currentStep) {
                self.currentStep = step;
                self.setDirty();
                self.displayCurrentStep();
            }
        };

        this.previousStep = function($target) {
            var self = this;
            if (self.currentStep > 1) {
                self.currentStep--;
                self.setDirty();
                self.displayCurrentStep();
            }
        };

        this.nextStep = function($target) {
            var self = this;
            if (!self.validate()) {
                return;
            }
            if (self.currentStep < self.$steps.length) {
                self.currentStep++;
            }
            self.submitForm();
        };

        this.saveStep = function() {
            var self = this;
            if (self.currentStep) {
                $(self.mainContainer).append($("<input/>").attr("type", "hidden").attr("name", "next_step").val(self.currentStep));
            }
        };

        this.ignorePost = function() {
            var self = this;
            $(self.mainContainer).append($("<input/>").attr("type", "hidden").attr("name", "ignore_post").val(1));
        };

        this.addSystemFieldsInForm = function() {
            var self = this;
            if (self.formSubmitted) {
                $(self.mainContainer).append($("<input/>").attr("type", "hidden").attr("name", "create_order").val(1));
            }
            if (self.currentStep) {
                $(self.mainContainer).append($("<input/>").attr("type", "hidden").attr("name", "next_step").val(self.currentStep));
            }
        };

        this.initSteps = function() {
            var self = this;
            var i = 1;

            self.stepReseted = false;
            self.$steps = [];
            self.stepsCount = $(self.mainContainer + " " + self.selectors.step).length ? $(self.mainContainer + " " + self.selectors.step).length : 1;

            $(self.mainContainer + " " + self.selectors.step).each(function() {
                var $step = $(this);
                self.$steps.push($step);
                // check steps before current for errors and set step with error as current
                var $errorBlocks = $step.find(self.selectors.block + "[data-error=true]");
                if (i < self.currentStep && $errorBlocks.length) {
                    self.currentStep = i;
                    self.stepReseted = true;
                }
                i++;
            });

            if (self.stepsCount > 1 && !self.stepReseted && self.currentStep == self.stepsCount && $(self.mainContainer).attr("data-error") == "true") {
                self.currentStep--;
                self.stepReseted = true;
            }

            $(self.selectors.paymentButtons).hide();

            if (!self.isPaymentFormVisible()) {
                $(self.selectors.paymentForm).css("margin", "0px");
            }

            if (self.errors) {
                //console.log("errors in blocks: " + self.errors);
            }

            self.displayCurrentStep();
        };

        this.displayCurrentStep = function() {
            var self = this;

            var initButtons = function() {
                if (self.stepsCount > 1) {
                    if (self.currentStep == 1) {
                        $(self.selectors.buttonPrev).hide();
                    } else {
                        $(self.selectors.buttonBack).hide();
                    }
                    if (self.currentStep < self.stepsCount) {
                        $(self.selectors.buttonNext).show();
                        $(self.selectors.buttonCreate).hide();
                    }
                    $(self.selectors.agreementCheckBox).hide();
                    $(self.selectors.agreementWarning).hide();
                    if (self.currentStep == self.stepsCount - 1) {
                        $(self.selectors.agreementCheckBox).show();
                        $(self.selectors.agreementWarning).show();
                    }
                }

                if (self.currentStep == self.stepsCount) {
                    $(self.selectors.buttonNext).hide();
                    self.replaceCreateButtonWithConfirm();
                }
            };

            var initStepsMenu = function() {
                $(self.selectors.stepsMenu + " " + self.selectors.stepsMenuItem).removeClass(self.classes.stepsMenuCompleted).removeClass(self.classes.stepsMenuCurrent);
                $(self.selectors.stepsMenu + " " + self.selectors.stepsMenuDelimiter + " img").attr("src", self.additionalPath + self.resources.next);

                for (var i = 1; i < self.currentStep; i++) {
                    $(self.selectors.stepsMenu + " " + self.selectors.stepsMenuItem + "[data-step=" + i + "]").addClass(self.classes.stepsMenuCompleted);
                    $(self.selectors.stepsMenu + " " + self.selectors.stepsMenuDelimiter + "[data-step=" + (i + 1) + "] img").attr("src", self.additionalPath + self.resources.nextCompleted);
                }
                $(self.selectors.stepsMenu + " " + self.selectors.stepsMenuItem + "[data-step=" + self.currentStep + "]").addClass(self.classes.stepsMenuCurrent);
            };

            var hideSteps = function() {
                $(self.mainContainer + " " + self.selectors.step).hide();
            };

            var isLastStepHasOnlyPaymentForm = function() {
                var $lastStep = $(self.mainContainer + " " + self.selectors.step + ":last");
                return $lastStep.find(self.selectors.block).length == 1 && $lastStep.find(self.selectors.paymentForm).length == 1 ? true : false;
            };

            if (self.currentStep == self.stepsCount && !self.isPaymentFormVisible() && self.isPaymentFormValid() && (isLastStepHasOnlyPaymentForm() || self.formSubmitted)) {
                self.clickOnConfirmButton();
                if (isLastStepHasOnlyPaymentForm()) {
                    self.currentStep--;
                }
            }

            hideSteps();

            if (typeof self.$steps[self.currentStep - 1] !== "undefined") {
                self.$steps[self.currentStep - 1].show();
            }

            initStepsMenu();
            initButtons();
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
                $($(self.mainContainer + " [data-error=true]:visible")).each(function() {
                    var offset = $(this).offset();
                    if (offset.top < top) {
                        top = offset.top;
                    }
                    if (offset.bottom > bottom) {
                        bottom = offset.bottom;
                    }
                });
                $($(self.mainContainer + " .simplecheckout-rule:visible")).each(function() {
                    if ($(this).parents(".simplecheckout-block").length) {
                        var offset = $(this).parents(".simplecheckout-block").offset();
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

            if (self.getPropertySafe("scrollToPaymentForm") && !error) {
                if (self.isPaymentFormVisible()) {
                    top = $(self.selectors.paymentForm).offset().top;
                    if (top && isOutsideOfVisibleArea(top)) {
                        jQuery("html, body").animate({
                            scrollTop: top
                        }, "slow");
                    }
                }
            }

            if ($("#simplecheckout_step_menu").length) {
                top = $("#simplecheckout_step_menu").offset().top;
                if (top && isOutsideOfVisibleArea(top)) {
                    jQuery("html, body").animate({
                        scrollTop: top
                    }, "slow");
                }
            }
        };

        this.validate = function() {
            var self = this;
            var result = true;

            for (var i in self.blocks) {
                if (!self.blocks.hasOwnProperty(i)) continue;

                if (!self.blocks[i].validate()) {
                    result = false;
                }
            }

            if (!result) {
                self.scroll();
            }

            return result;
        };

        this.backHistory = function() {
            var self = this;
            history.go(-1);
        };

        this.createOrder = function() {
            var self = this;
            if (!self.validate()) {
                return;
            }
            self.formSubmitted = true;
            self.submitForm();
        };

        this.submitForm = function() {
            var self = this;
            self.requestReloadAll();
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

        this.overlayAll = function() {
            var self = this;
            for (var i in self.blocks) {
                if (!self.blocks.hasOwnProperty(i)) continue;

                self.blocks[i].overlay();
            }
            $(self.mainContainer + " " + self.selectors.block).each(function() {
                if (!$(this).data("initialized")) {
                    SimplecheckoutBlock.prototype.overlay($(this));
                }
            });
        };

        this.removeOverlays = function() {
            var self = this;

            $(".simplecheckout_overlay").remove();

            if (typeof self.mainContainer !== "undefined" && $(self.mainContainer).length) {
                $(self.mainContainer).find("input:not([data-dummy]),select,textarea").removeAttr("disabled");
            }
        };

        /**
         * Reload all blocks via main controller which includes all registered blocks as childs
         * @param  {Function} callback
         */
        this.reloadAll = function(callback) {
            var self = this;
            if (self.isReloading) {
                return;
            }
            self.addSystemFieldsInForm();
            self.isReloading = true;
            var postData = $(self.mainContainer).find("input,select,textarea").serialize();
            $.ajax({
                url: self.mainUrl,
                data: postData + "&ajax=1",
                type: "POST",
                dataType: "text",
                beforeSend: function() {
                    self.overlayAll();
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
                    self.removeOverlays();
                    self.isReloading = false;
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    self.removeOverlays();
                    self.isReloading = false;
                }
            });
        };

        this.reloadBlock = function(container, callback) {
            var self = this;
            if (self.isReloading) {
                return;
            }
            self.isReloading = true;
            var postData = $(self.mainContainer).find("input,select,textarea").serialize();
            $.ajax({
                url: self.mainUrl,
                data: postData + "&ajax=1",
                type: "POST",
                dataType: "text",
                beforeSend: function() {},
                success: function(data) {
                    var newData = $(container, $(data)).get(0);
                    if (!newData && data) {
                        newData = data;
                    }
                    $(container).replaceWith(newData);
                    self.init();
                    if (typeof callback === "function") {
                        callback.call(self);
                    }
                    self.isReloading = false;
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    self.isReloading = false;
                }
            });
        };

        arguments.callee.instance = this;
    }

    Simplecheckout.prototype = inherit(simple);

    /**
     * It is parent of all blocks
     */

    function SimplecheckoutBlock(container, route) {
        this.currentContainer = container;
        this.currentRoute = route;
    }

    SimplecheckoutBlock.prototype = inherit(simple);

    SimplecheckoutBlock.prototype.setParent = function(object) {
        this.simplecheckout = object;
    };

    SimplecheckoutBlock.prototype.getParent = function() {
        return typeof this.simplecheckout !== "undefined" ? this.simplecheckout : false;
    };

    SimplecheckoutBlock.prototype.reloadAll = function(callback) {
        if (this.getParent()) {
            this.getParent().requestReloadAll(callback);
        } else {
            this.reload();
        }
    };

    SimplecheckoutBlock.prototype.reload = function(callback) {
        var self = this;
        if (self.isReloading) {
            return;
        }
        self.isReloading = true;
        var postData = $(self.currentContainer).find("input,select,textarea").serialize();
        $.ajax({
            url: "index.php?" + self.additionalParams + "route=" + self.currentRoute,
            data: postData + "&ajax=1",
            type: "POST",
            dataType: "text",
            beforeSend: function() {
                self.overlay();
            },
            success: function(data) {
                var newData = $(self.currentContainer, $(data)).get(0);
                if (!newData && data) {
                    newData = data;
                }
                $(self.currentContainer).replaceWith(data);
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

    SimplecheckoutBlock.prototype.load = function(callback, container) {
        var self = this;
        if (self.isLoading) {
            return;
        }
        if (typeof callback !== "function") {
            container = callback;
            callback = null;
        }
        self.isLoading = true;
        $.ajax({
            url: "index.php?" + self.additionalParams + "route=" + self.currentRoute,
            type: "GET",
            dataType: "text",
            beforeSend: function() {
                self.overlay();
            },
            success: function(data) {
                var newData = $(self.currentContainer, $(data)).get(0);
                if (!newData && data) {
                    newData = data;
                }
                if (newData) {
                    if (container) {
                        $(container).html(newData);
                    } else {
                        $(self.currentContainer).replaceWith(newData);
                    }
                    self.init();
                }
                if (typeof callback === "function") {
                    callback();
                }
                self.removeOverlay();
                self.isLoading = false;
            },
            error: function(xhr, ajaxOptions, thrownError) {
                self.removeOverlay();
                self.isLoading = false;
            }
        });
    };

    SimplecheckoutBlock.prototype.overlay = function($useBlock) {
        var self = this;
        var $block = $useBlock || $(self.currentContainer);
        if ($block.length) {
            $block.find("input,select,textarea").attr("disabled", "disabled");
            $block.append("<div class='simplecheckout_overlay' id='" + $block.attr("id") + "_overlay'></div>");
            $block.find(".simplecheckout_overlay")
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
                });
        }
    };

    SimplecheckoutBlock.prototype.removeOverlay = function() {
        var self = this;

        if (typeof self.currentContainer !== "undefined" && $(self.currentContainer).length) {
            $(self.currentContainer).find("input:not([data-dummy]),select,textarea").removeAttr("disabled");
        }

        $(this.currentContainer + "_overlay").remove();
    };

    SimplecheckoutBlock.prototype.hasError = function() {
        return $(this.currentContainer).attr("data-error") ? true : false;
    };

    SimplecheckoutBlock.prototype.init = function() {
        var self = this;

        var callFunc = function(func, $target) {
            if (func && typeof self[func] === "function") {
                self[func]($target);
            } else if (func) {
                //console.log(func + " is not registered");
            }
        };

        $(self.currentContainer + " *[data-onchange]").on("change", function() {
            callFunc($(this).attr("data-onchange"), $(this));
        });

        $(self.currentContainer + " *[data-onclick]").on("click", function() {
            callFunc($(this).attr("data-onclick"), $(this));
        });

        if (self.isEmpty()) {
            ////console.log(self.currentContainer + " is empty");
        }

        if (!self.hasError() && $(self.currentContainer).attr("data-hide")) {
            $(self.currentContainer).hide();
        }

        self.addFocusHandler();
        self.restoreFocus();

        $(self.currentContainer).data("initialized", true);
    };

    SimplecheckoutBlock.prototype.validate = function() {
        var self = this;

        return self.checkRules(self.currentContainer);
    };

    SimplecheckoutBlock.prototype.isEmpty = function() {
        if ($(this.currentContainer).find("*").length) {
            return false;
        }
        return true;
    };

    SimplecheckoutBlock.prototype.shareMethod = function(name, asName) {
        SimplecheckoutBlock.prototype[asName] = bind(this[name], this);
    };

    SimplecheckoutBlock.prototype.displayWarning = function() {
        $(this.currentContainer).find(".simplecheckout-warning-block").show();
    };

    SimplecheckoutBlock.prototype.hideWarning = function() {
        $(this.currentContainer).find(".simplecheckout-warning-block").hide();
    };

    SimplecheckoutBlock.prototype.focusedFieldId = '';

    SimplecheckoutBlock.prototype.addFocusHandler = function() {
        var self = this;
        $(self.currentContainer).find("input,textarea").focus(function() {
            self.focusedFieldId = $(this).attr("id");
        });
        $(self.currentContainer).find("input,textarea").blur(function() {
            self.focusedFieldId = '';
        });
    };

    SimplecheckoutBlock.prototype.restoreFocus = function() {
        var self = this;
        if (typeof self.focusedFieldId !== "undefined" && self.focusedFieldId && $(self.currentContainer).find("#" + self.focusedFieldId).length > 0) {
            $(self.currentContainer).find("#" + self.focusedFieldId).focus();
        }
    };

    function SimplecheckoutCart(container, route) {
        this.currentContainer = container;
        this.currentRoute = route;

        this.init = function() {
            var self = this;
            SimplecheckoutBlock.prototype.init.apply(self, arguments);
            self.initMiniCart();
        };

        this.initMiniCart = function() {
            var self = this,
                total = $("#simplecheckout_cart_total").html();

            if (total) {
                $("#cart_total").html(total);
                $("#cart-total").html(total);
                $("#cart_menu .s_grand_total").html(total);
                $("#weight").text($("#simplecheckout_cart_weight").text());
                if (self.getPropertySafe("currentTheme") == "shoppica2") {
                    $("#cart_menu div.s_cart_holder").html("");
                    $.getJSON("index.php?" + self.additionalParams + "route=tb/cartCallback", function(json) {
                        if (json["html"]) {
                            $("#cart_menu span.s_grand_total").html(json["total_sum"]);
                            $("#cart_menu div.s_cart_holder").html(json["html"]);
                        }
                    });
                }
                if (self.getPropertySafe("currentTheme") == "shoppica") {
                    $("#cart_menu div.s_cart_holder").html("");
                    $.getJSON("index.php?" + self.additionalParams + "route=module/shoppica/cartCallback", function(json) {
                        if (json["output"]) {
                            $("#cart_menu span.s_grand_total").html(json["total_sum"]);
                            $("#cart_menu div.s_cart_holder").html(json["output"]);
                        }
                    });
                }
            }
        };

        this.increaseProductQuantity = function($target) {
            var self = this;

            var $quantity = $target.parent().find("input");
            var quantity = parseFloat($quantity.val());
            if (!isNaN(quantity)) {
                $quantity.val(quantity + 1);
                self.reloadAll();
            }
        };

        this.decreaseProductQuantity = function($target) {
            var self = this;

            var $quantity = $target.parent().find("input");
            var quantity = parseFloat($quantity.val());
            if (!isNaN(quantity) && quantity > 1) {
                $quantity.val(quantity - 1);
                self.reloadAll();
            }
        };

        this.changeProductQuantity = function($target) {
            var self = this;

            var quantity = parseFloat($target.val());
            if (!isNaN(quantity)) {
                self.reloadAll();
            }
        };

        this.removeProduct = function($target) {
            var self = this;

            var productKey = $target.attr("data-product-key");
            $("#simplecheckout_remove").val(productKey);

            self.reloadAll();
        };

        this.removeGift = function($target) {
            var self = this;

            var giftKey = $target.attr("data-gift-key");
            $("#simplecheckout_remove").val(giftKey);

            self.reloadAll();
        };

        this.removeCoupon = function($target) {
            var self = this;

            $("input[name='coupon']").val("");
            self.reloadAll();
        };

        this.removeReward = function($target) {
            var self = this;

            $("input[name='reward']").val("");
            self.reloadAll();
        };

        this.removeVoucher = function($target) {
            var self = this;

            $("input[name='voucher']").val("");
            self.reloadAll();
        };
    }

    SimplecheckoutCart.prototype = inherit(SimplecheckoutBlock.prototype);

    function SimplecheckoutLogin(container, route) {
        this.currentContainer = container;
        this.currentRoute = route;

        this.init = function() {
            var self = this;
            SimplecheckoutBlock.prototype.init.apply(self, arguments);
        };

        this.initPopupLayer = function() {
            var self = this;
            var position = $("#simple_login_layer").parent().css("position");
            if (!$("#simple_login_layer").length || position == "fixed" || position == "relative" || position == "absolute") {
                $("#simple_login_layer").remove();
                $("#simple_login").remove();
                $("body").append("<div id='simple_login_layer'></div><div id='simple_login'><div id='temp_popup_container'></div></div>");
                $("#simple_login_layer").on("click", function() {
                    self.close();
                });
            }
            $("#simple_login_layer").css("height", $(document).height());
            $("#simple_login_layer").fadeTo(500, 0.8);
        };

        this.openPopup = function() {
            var self = this;
            self.initPopupLayer();
            if (!$(self.currentContainer).html()) {
                self.load(function() {
                    if ($(self.currentContainer).html()) {
                        self.resizePopup();
                    } else {
                        self.closePopup();
                    }
                }, "#temp_popup_container");
            } else {
                self.hideWarning();
                self.resizePopup();
            }
        };

        this.resizePopup = function() {
            $("#simple_login").show();
            $("#simple_login").css("height", $(this.currentContainer).outerHeight() + 20);
            $("#simple_login").css("top", $(window).height() / 2 - $("#simple_login").outerHeight() / 2);
            $("#simple_login").css("left", $(window).width() / 2 - $("#simple_login").outerWidth() / 2);
        };

        this.closePopup = function() {
            var self = this;
            $("#simple_login_layer").fadeOut(500, function() {
                $(this).hide().css("opacity", "1");
            });
            $("#simple_login").fadeOut(500, function() {
                $(this).hide();
            });
        };

        this.openFlat = function() {
            var self = this;
            if (!$(self.currentContainer).length) {
                $("<div id='temp_flat_container'><img src='" + self.additionalPath + self.resources.loading + "'></div>").insertBefore(self.loginBoxBefore);
                self.load("#temp_flat_container");
            }
            self.hideWarning();
            $(self.currentContainer).show();
        };

        this.closeFlat = function() {
            $(this.currentContainer).hide();
        };

        this.isOpened = function() {
            return $("#temp_flat_container *:visible").length ? true : false;
        };

        this.open = function() {
            var self = this;
            if (self.getPropertySafe("logged")) {
                return;
            }
            if (self.getPropertySafe("loginBoxBefore")) {
                self.openFlat();
            } else {
                self.openPopup();
            }
        };

        this.close = function() {
            var self = this;
            if (self.getPropertySafe("loginBoxBefore")) {
                self.closeFlat();
            } else {
                self.closePopup();
            }
        };

        this.login = function() {
            var self = this;
            this.reload(function() {
                if (!self.hasError()) {
                    self.closePopup();
                    self.closeFlat();
                    if (self.getParent()) {
                        self.getParent().saveStep();
                        self.getParent().ignorePost();
                        self.getParent().reloadAll();
                    } else {
                        window.location.reload();
                    }
                } else {
                    self.resizePopup();
                }
            });
        };
    }

    SimplecheckoutLogin.prototype = inherit(SimplecheckoutBlock.prototype);

    function SimplecheckoutComment(container, route) {
        this.currentContainer = container;
        this.currentRoute = route;

        this.init = function() {
            var self = this;
            SimplecheckoutBlock.prototype.init.apply(self, arguments);
        };

        this.copyOnAllEntries = function($target) {
            $('textarea[name=comment]').val($target.val());
        };
    }

    SimplecheckoutComment.prototype = inherit(SimplecheckoutBlock.prototype);

    function SimplecheckoutShipping(container, route) {
        this.currentContainer = container;
        this.currentRoute = route;

        this.init = function() {
            var self = this;
            SimplecheckoutBlock.prototype.init.apply(self, arguments);
        };

        this.validate = function() {
            var self = this;
            var result = true;

            if ($(self.currentContainer + ":visible").length && !$(self.currentContainer + " input:checked").length) {
                self.displayWarning();
                result = false;
            }

            if (!SimplecheckoutBlock.prototype.validate.apply(self, arguments)) {
                result = false;
            }

            return result;
        };
    }

    SimplecheckoutShipping.prototype = inherit(SimplecheckoutBlock.prototype);

    function SimplecheckoutPayment(container, route) {
        this.currentContainer = container;
        this.currentRoute = route;

        this.init = function() {
            var self = this;
            SimplecheckoutBlock.prototype.init.apply(self, arguments);
        };

        this.validate = function() {
            var self = this;
            var result = true;

            if ($(self.currentContainer + ":visible").length && !$(self.currentContainer + " input:checked").length) {
                self.displayWarning();
                result = false;
            }

            if (!SimplecheckoutBlock.prototype.validate.apply(self, arguments)) {
                result = false;
            }

            return result;
        };
    }

    SimplecheckoutPayment.prototype = inherit(SimplecheckoutBlock.prototype);

    function SimplecheckoutForm(container, route) {
        this.currentContainer = container;
        this.currentRoute = route;

        this.init = function() {
            var self = this;
            SimplecheckoutBlock.prototype.init.apply(self, arguments);
        };

        this.validate = function() {
            var self = this;
            var result = true;

            if (!SimplecheckoutBlock.prototype.validate.apply(self, arguments)) {
                result = false;
            }

            return result;
        };

        this.reloadAll = function($element) {
            var self = this;
            setTimeout(function() {
                if (!$element.attr("data-valid") || $element.attr("data-valid") == "true") {
                    SimplecheckoutBlock.prototype.reloadAll.apply(self, arguments);
                }
            }, 0);

        };
    }

    SimplecheckoutForm.prototype = inherit(SimplecheckoutBlock.prototype);

    $(function() {
        var simplecheckout = new Simplecheckout("checkout/simplecheckout", function() {
            try {
                simple.javascriptCallback();
            } catch (err) {

            }
        });

        simplecheckout.registerBlock(new SimplecheckoutCart("#simplecheckout_cart", "checkout/simplecheckout_cart"));
        simplecheckout.registerBlock(new SimplecheckoutShipping("#simplecheckout_shipping", "checkout/simplecheckout_shipping"));
        simplecheckout.registerBlock(new SimplecheckoutPayment("#simplecheckout_payment", "checkout/simplecheckout_payment"));
        simplecheckout.registerBlock(new SimplecheckoutForm("#simplecheckout_customer", "checkout/simplecheckout_customer"));
        simplecheckout.registerBlock(new SimplecheckoutForm("#simplecheckout_payment_address", "checkout/simplecheckout_payment_address"));
        simplecheckout.registerBlock(new SimplecheckoutForm("#simplecheckout_shipping_address", "checkout/simplecheckout_shipping_address"));
        simplecheckout.registerBlock(new SimplecheckoutComment("#simplecheckout_comment", "checkout/simplecheckout_comment"));

        simplecheckout.init();

        var login = new SimplecheckoutLogin("#simplecheckout_login", "checkout/simplecheckout_login");
        login.setParent(simplecheckout);
        login.init();
        login.shareMethod("open", "openLoginBox");

        $('#cart, table.cart, .mini-cart-info, table.s_cart_items').on('click', 'td.remove img, td.remove img, a.s_button_remove', function() {
            setTimeout(function() {
                simplecheckout.reloadAll();
            }, 500);
        });

        window.simplecheckout = simplecheckout;
        window.reloadAll = bind(simplecheckout.reloadAll, simplecheckout);
        window.reloadBlock = bind(simplecheckout.reloadBlock, simplecheckout);
    });
})(jQuery);