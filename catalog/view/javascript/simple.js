(function($) {
    var simple = {
        mainContainer: "",
        mainUrl: "",
        additionalPath: "",
        additionalParams: "",
        useAutocomplete: 0,
        useGoogleApi: 0,
        resources: {
            loading: "catalog/view/image/loading.gif",
            loadingSmall: "catalog/view/theme/default/image/loading.gif",
            next: "catalog/view/image/next_gray.png",
            nextCompleted: "catalog/view/image/next_green.png"
        },
        copyProperties: function(object) {
            for (var prop in object) {
                this[prop] = object[prop];
            }
        },
        getPropertySafe: function(name) {
            if (typeof this[name] !== "undefined") {
                return this[name];
            }

            return '';
        },
        validationRules: {
            notEmpty: function($rule) {
                var fieldId = $rule.attr("data-for");
                if (fieldId && $("#" + fieldId).length) {
                    var $field = $("#" + fieldId);

                    if (!$rule.attr("data-required")) {
                        $rule.hide();
                        return true;
                    }

                    if (!$field.val()) {
                        $rule.show();
                        return false;
                    } else {
                        $rule.hide();
                        return true;
                    }
                }
                return true;
            },
            equal: function($rule) {
                var fieldId = $rule.attr("data-for");
                if (fieldId && $("#" + fieldId).length) {
                    if ($rule.attr("data-equal") && $("#" + $rule.attr("data-equal")).length) {
                        var $field = $("#" + fieldId);
                        var $compare = $("#" + $rule.attr("data-equal"));
                        if ($compare.val() != $field.val()) {
                            $rule.show();
                            return false;
                        } else {
                            $rule.hide();
                            return true;
                        }
                    }
                }
                return true;
            },
            byLength: function($rule) {
                var fieldId = $rule.attr("data-for");
                if (fieldId && $("#" + fieldId).length) {
                    var $field = $("#" + fieldId);
                    var min = 0;
                    var max = 1000;
                    if ($rule.attr('data-length-min')) {
                        min = ~~$rule.attr('data-length-min');
                    }
                    if ($rule.attr('data-length-max')) {
                        max = ~~$rule.attr('data-length-max');
                    }

                    var value = $field.val();

                    if (!value && !$rule.attr("data-required")) {
                        $rule.hide();
                        return true;
                    }

                    if (value.length < min || value.length > max) {
                        $rule.show();
                        return false;
                    } else {
                        $rule.hide();
                        return true;
                    }
                }
                return true;
            },
            regexp: function($rule) {
                var fieldId = $rule.attr("data-for");
                if (fieldId && $("#" + fieldId).length) {
                    var $field = $("#" + fieldId);
                    var regexp = $rule.attr("data-regexp");
                    if (regexp) {
                        var value = $field.val();

                        if (!value && !$rule.attr("data-required")) {
                            $rule.hide();
                            return true;
                        }

                        try {
                            if (!value.match(regexp)) {
                                $rule.show();
                                return false;
                            } else {
                                $rule.hide();
                                return true;
                            }
                        } catch (err) {

                        }
                    }
                }
                return true;
            },
            api: function($rule, additionalParams) {
                var fieldId = $rule.attr("data-for");
                if (fieldId && $("#" + fieldId).length) {
                    var $field = $("#" + fieldId);
                    var filter = "";
                    if ($rule.attr("data-filter")) {
                        if ($("#" + $rule.attr("data-filter")).length) {
                            if ($("#" + $rule.attr("data-filter")).attr("type") == "radio") {
                                filter = $("#" + $rule.attr("data-filter") + ":checked").val();
                            } else {
                                filter = $("#" + $rule.attr("data-filter")).val();
                            }
                        } else if ($rule.attr("data-filter-value")) {
                            filter = $rule.attr("data-filter-value");
                        }
                    }

                    var method = $rule.attr("data-method");

                    if (method) {
                        var custom = $rule.attr("data-custom") ? true : false;
                        $.get("index.php?" + additionalParams + "route=common/simple_connector/validate&method=" + method + "&filter=" + filter + "&value=" + $field.val() + (custom ? "&custom=1 " : ""), function(data) {
                            if (data == "invalid") {
                                $rule.show();
                                return false;
                            } else {
                                $rule.hide();
                                return true;
                            }
                        });
                    }
                }
                return true;
            }
        },
        initValidationRules: function() {
            var self = this;
            $(self.mainContainer + " .simplecheckout-rule-group").each(function() {
                var $ruleGroup = $(this);
                var fieldId = $(this).attr("data-for");
                if (fieldId && $("#" + fieldId).length) {
                    $("#" + fieldId).change(function() {
                        var result = true;
                        $ruleGroup.find(".simplecheckout-rule").each(function() {
                            var $rule = $(this);
                            var type = $(this).attr("data-rule");

                            if (typeof self.validationRules[type] !== "undefined") {
                                if (!self.validationRules[type]($rule, self.additionalParams)) {
                                    result = false;
                                }
                            }
                        });
                        $("#" + fieldId).attr("data-valid", result ? "true" : "false");
                    });
                }
            });
        },
        checkRules: function(container) {
            var self = this;
            var fields = {};
            var resultAll = true;
            if ($(container + ":visible").length) {
                $(container + " .simplecheckout-rule-group").each(function() {
                    var ruleGroupResult = true;
                    var $ruleGroup = $(this);
                    var fieldId = $(this).attr("data-for");
                    if (fieldId && $("#" + fieldId).length) {
                        $ruleGroup.find(".simplecheckout-rule").each(function() {
                            var $rule = $(this);
                            var type = $(this).attr("data-rule");

                            if (typeof self.validationRules[type] !== "undefined") {
                                if (!self.validationRules[type]($rule, self.additionalParams)) {
                                    ruleGroupResult = false;
                                }
                            }
                        });
                        $("#" + fieldId).attr("data-valid", ruleGroupResult ? "true" : "false");
                        if (!ruleGroupResult) {
                            resultAll = false;
                        }
                    }
                });
            }

            return resultAll;
        },
        setAddressFields: function(block, countryId, zoneId, city, postcode, callbackAfterChanging) {
            var self = this;
            var setFields = function() {
                if (countryId) {
                    $("#" + block + "_country_id").val(countryId);
                }
                if (zoneId) {
                    $("#" + block + "_zone_id").val(zoneId);
                }
                if (city) {
                    $("#" + block + "_city").val(city);
                }
                if (postcode) {
                    $("#" + block + "_postcode").val(postcode);
                }

                if (typeof callbackAfterChanging === "function") {
                    callbackAfterChanging();
                } else if (typeof reloadAll === "function") {
                    reloadAll();
                }
            };

            if ($("#" + block + "_country_id").val() != countryId) {
                $("#" + block + "_zone_id").load("index.php?" + self.additionalParams + "route=common/simple_connector/zone&country_id=" + countryId, function() {
                    setFields();
                });
            } else {
                setFields();
            }
        },
        initFileUploader: function(beforeUploading, afterUploading) {
            var self = this;
            $("[data-file]").each(function() {
                try {
                    if (typeof AjaxUpload === "function") {
                        var hiddenInputId = $(this).attr("data-file");
                        var fileNameId = hiddenInputId ? "text_" + hiddenInputId : "";
                        new AjaxUpload(this, {
                            action: "index.php?" + self.additionalParams + "route=common/simple_connector/upload",
                            name: "file",
                            autoSubmit: true,
                            responseType: "json",
                            onSubmit: function(file, extension) {
                                if (typeof beforeUploading === "function") {
                                    beforeUploading(file, extension);
                                }
                            },
                            onComplete: function(file, json) {
                                if (json["file"]) {
                                    if (hiddenInputId) {
                                        $("#" + hiddenInputId).attr("value", json["file"]);
                                    }
                                    if (fileNameId) {
                                        $("#" + fileNameId).text(file);
                                    }
                                }

                                if (json["error"]) {
                                    if (hiddenInputId) {
                                        $("#" + hiddenInputId).attr("value", "");
                                    }
                                    if (fileNameId) {
                                        $("#" + fileNameId).text(json["error"]);
                                    }
                                }

                                if (typeof afterUploading === "function") {
                                    afterUploading(file, json);
                                }
                            }
                        });
                    }
                } catch (err) {}
            });
        },
        initAutocomplete: function(callbackAfterChanging) {
            var self = this;
            if (typeof($("#payment_address_city, #shipping_address_city, #register_city, #address_city").autocomplete) !== "undefined") {
                $("#payment_address_city, #shipping_address_city, #register_city, #address_city").each(function() {
                    var tmp = $(this).attr("data-onchange");
                    if (tmp) {
                        $(this).removeAttr("data-onchange");
                        $(this).attr("data-onchange-delayed", tmp);
                    }
                });
                $("#payment_address_city, #shipping_address_city, #register_city, #address_city").autocomplete({
                    source: function(request, response) {
                        $.ajax({
                            url: "index.php?" + self.additionalParams + "route=common/simple_connector/geo",
                            dataType: "json",
                            data: {
                                term: request.term
                            },
                            success: function(data) {
                                response($.map(data, function(item) {
                                    return {
                                        id: item.id,
                                        label: item.full,
                                        value: item.city,
                                        postcode: item.postcode,
                                        zone_id: item.zone_id,
                                        country_id: item.country_id,
                                        city: item.city
                                    };
                                }));
                            }
                        });
                    },
                    minLength: 2,
                    delay: 300,
                    select: function(event, ui) {
                        var name = $(this).attr("name");
                        var from = name.substr(0, name.indexOf("["));
                        var $target = $(this);
                        self.setAddressFields(from, ui.item.country_id, ui.item.zone_id, ui.item.city, ui.item.postcode, function() {
                            callbackAfterChanging($target);
                        });
                    }
                });
            }
        },
        initPopups: function() {
            if (typeof($.fancybox) == "function") {
                $(".fancybox").fancybox({
                    width: 560,
                    height: 560,
                    autoDimensions: false
                });
            }

            if (typeof($.colorbox) == "function") {
                $(".colorbox").colorbox({
                    width: 560,
                    height: 560
                });
            }

            if (typeof($.prettyPhoto) !== "undefined") {
                $("a[rel^='prettyPhoto']").prettyPhoto({
                    theme: "light_square",
                    opacity: 0.5,
                    social_tools: "",
                    deeplinking: false
                });
            }
        },
        initTooltips: function() {
            var self = this;
            $(self.mainContainer + " input, " + self.mainContainer + " select, " + self.mainContainer + " textarea").each(function() {
                if ($(this).attr("data-file")) {
                    $(".simplecheckout-tooltip[data-for='" + $(this).attr("data-file") + "']").show();
                } else {
                    if (typeof $(this).easyTooltip === "function") {
                        $(this).easyTooltip({
                            useElement: ".simplecheckout-tooltip[data-for='" + $(this).attr("id") + "']",
                            clickRemove: true
                        });
                    }
                }
            });
        },
        initMasks: function() {
            if (typeof $.mask !== "undefined") {
                var masked = [];
                $("input[data-mask]").each(function(indx) {
                    var mask = $(this).attr("data-mask");
                    var id = $(this).attr("id");
                    if (mask && id) {
                        masked[masked.length] = [id, mask];
                    }
                });
                try {
                    for (var i = 0; i < masked.length; i++) {
                        $("input[id=" + masked[i][0] + "]").mask(masked[i][1]);
                    }
                } catch (err) {}
            }
        },
        initDatepickers: function(callbackAfterChanging) {
            var self = this;
            var days = false;

            var checkWeekendAndHoliday = function(date) {
                var noWeekend = $.datepicker.noWeekends(date);
                if (noWeekend[0]) {
                    return checkNationalDay(date);
                } else {
                    return noWeekend;
                }
            };

            var checkNationalDay = function(date) {
                var days = [
                    [1, 1, "ru"],
                    [1, 7, "ru"],
                    [5, 9, "ru"]
                ];

                for (i = 0; i < days.length; i++) {
                    if (date.getMonth() == days[i][0] - 1 && date.getDate() == days[i][1]) {
                        return [false, days[i][2] + "_day"];
                    }
                }
                return [true, ""];
            };

            var addDays = function(add, onlyWeekdays) {
                var result = add | 0;
                var self = this;
                if (onlyWeekdays) {
                    var i = 1;
                    while (i <= result) {
                        var d = new Date();
                        d.setDate(d.getDate() + i);
                        var test = checkWeekendAndHoliday(d);
                        if (!test[0]) {
                            result++;
                        }
                        i++;
                    }
                }

                return result;
            };

            var checkDays = function(date) {
                for (var i = 0; i < days.length; i++) {
                    if (date.getDay() == days[i]) {
                        return [true, ""];
                    }
                }

                return [false, ""];
            };

            $(self.mainContainer).find("input[type=date],input[data-type=date]").each(function() {
                if (typeof($(this).datepicker) !== "undefined") {
                    var onlyWeekdays = $(this).attr("data-weekdays-only") ? true : false,
                        min = new Date(),
                        max = new Date();

                    if ($(this).attr("data-days-only")) {
                        days = $(this).attr("data-days-only").split(",");
                        onlyWeekdays = false;
                    }

                    if ($(this).attr("data-start-day")) {
                        min = $(this).attr("data-start-day");
                    } else if ($(this).attr("data-start-after")) {
                        min.setDate(min.getDate() + addDays($(this).attr("data-start-after"), onlyWeekdays));
                    }

                    if ($(this).attr("data-end-day")) {
                        max = $(this).attr("data-end-day");
                    } else if ($(this).attr("data-end-after")) {
                        max.setDate(max.getDate() + addDays($(this).attr("data-end-after"), onlyWeekdays));
                    }

                    $(this).datepicker({
                        firstDay: 1,
                        beforeShowDay: onlyWeekdays ? checkWeekendAndHoliday : (days ? checkDays : null),
                        minDate: min ? min : null,
                        maxDate: max ? max : null,
                        onSelect: function(dateText, inst) {
                            if (typeof callbackAfterChanging === "function") {
                                callbackAfterChanging($(this));
                            }
                        }
                    });
                }
            });
        },
        initTimepickers: function(callbackAfterChanging) {
            var self = this;
            $(self.mainContainer + " input[type=time]").each(function() {
                if (typeof($(this).timepicker) !== "undefined") {
                    var min = "";

                    if ($(this).attr("data-min-time")) {
                        min = ~~ ($(this).attr("data-min-time").split(":")[0]);
                    }

                    var max = "";

                    if ($(this).attr("data-max-time")) {
                        max = ~~ ($(this).attr("data-max-time").split(":")[0]);
                    }

                    var onlyHours = $(this).attr("data-hours-only") ? true : false;

                    $(this).timepicker({
                        hourMin: min,
                        hourMax: max,
                        showMinute: !onlyHours,
                        onSelect: function(datetimeText, datepickerInstance) {
                            if (typeof callbackAfterChanging === "function") {
                                callbackAfterChanging($(this));
                            }
                        },
                        onClose: function() {
                            if (typeof callbackAfterChanging === "function") {
                                callbackAfterChanging($(this));
                            }
                        }
                    });
                }
            });
        },
        initGoogleApi: function(callbackAfterChanging) {
            if (simple.useGoogleApi) {
                $("#payment_address_postcode, #shipping_address_postcode, #register_postcode, #address_postcode").each(function() {
                    var tmp = $(this).attr("data-onchange");
                    if (tmp) {
                        $(this).removeAttr("data-onchange");
                        $(this).attr("data-onchange-delayed", tmp);
                    }
                });
                $("#payment_address_postcode, #shipping_address_postcode, #register_postcode, #address_postcode").change(function() {
                    var $target = $(this);
                    var name = $(this).attr("name");
                    var from = name.substr(0, name.indexOf("["));
                    var geocoder = new google.maps.Geocoder();
                    var address = $("#" + from + "_postcode").val() + "," + $("#" + from + "_country_id option:selected").text();
                    var typeShort;
                    var anythingChanged = false;

                    if (geocoder) {
                        geocoder.geocode({
                            "address": address,
                            "language": $("#" + from + "_country_id option:selected").text()
                        }, function(results, status) {
                            if (status == google.maps.GeocoderStatus.OK) {
                                for (var result in results) {
                                    for (var component in results[result].address_components) {
                                        for (var type in results[result].address_components[component].types) {
                                            typeShort = results[result].address_components[component].types[type];
                                            if (typeShort == "administrative_area_level_1") {
                                                $("#" + from + "_zone_id option").filter(function() {
                                                    return $(this).text().replace(/\W/g, "") == results[result].address_components[component].long_name.replace(/\W/g, "");
                                                }).attr("selected", "selected");
                                                anythingChanged = true;
                                            }
                                            if (typeShort == "locality") {
                                                $("#" + from + "_city").val(results[result].address_components[component].long_name);
                                                anythingChanged = true;
                                            }
                                        }
                                    }
                                }
                                if (anythingChanged && typeof callbackAfterChanging === "function") {
                                    callbackAfterChanging($target);
                                }
                            } else {
                                //console.log("Geocoding failed: " + status);
                            }
                        });
                    }
                });
            }
        }
    };

    window.simple = simple;
})(jQuery);

if (typeof String.prototype.trim !== "function") {
    String.prototype.trim = function() {
        return this.replace(/^\s+|\s+$/g, "");
    };
}

function includeScript(url) {
    document.write("<script type='text/javascript' src='" + url + "'></script>");
}

function includeStyle(url) {
    document.write("<link rel='stylesheet' type='text/css' href='" + url + "' media='screen' />");
}

function bind(func, context) {
    return function() {
        return func.apply(context, arguments);
    };
}

function inherit(proto) {
    function F() {}
    F.prototype = proto;
    var object = new F();
    return object;
}