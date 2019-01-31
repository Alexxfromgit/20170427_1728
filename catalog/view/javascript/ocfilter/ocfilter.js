(function($){
	var ocfilter = {
		timers: {},
		values: {},
		options: {},
		offset: null,
		init: function(options, module) {
      this.options = $.extend({}, options);

			this.options.element.ocfilter = $('.box-content', module);

			this.offset = this.options.element.ocfilter.offset();

	    this.options.element.fields = $('.option-values input, .option-values select option', this.options.element.ocfilter);
		  this.options.element.target = $('.ocf-target', this.options.element.ocfilter);
		  this.options.element.labels = $('label', this.options.element.ocfilter);
			this.options.element.values = $('label, select option', this.options.element.ocfilter);
	    this.options.element.desription = $('.option-name b', this.options.element.ocfilter);

      var $this = this;

			this.options.element.values.each(function(){
				$this.values[$(this).attr('id')] = $(this);
			});

			this.options.element.target.live('change', function() {
		    $this.options.php.params = this.value;

		    var target = $(this), selectbox = target.parents('.ocfilter-selectbox');

		    /* Marked selected values */
	      if (target.is('select')) {
	        if ($('option:first', target).attr('selected')) {
		        target.removeClass('selected');
		      } else if (!target.hasClass('selected')) {
		        target.addClass('selected');
		      }
				} else if (target.attr('type') == 'checkbox') {
		      target.parent().toggleClass('selected');
		    } else if (target.attr('type') == 'radio') {
		      $('label', target.parents('div.ocfilter-option')).removeClass('selected');

		      target.parent().addClass('selected');
		    }

				if (selectbox.length) {
		      var label = target.parent('label'), clone = label.clone(), id = 'sb-' + label.attr('id'), selecteds = target.parents('.ocfilter-selectbox').find('.selecteds'), length = selecteds.find('span').length;

		      if (target.prop('checked')) {
	          selectbox.addClass('selected');

		        clone.find('small, input').remove();

	          if (target.attr('type') == 'radio') {
	            selecteds.find('span').remove();
						}

						var text = clone.text();

						if (text.length > 20) {
	      			text = text.substr(0, 20) + '..';
						}

		        selecteds.append('<span id="' + id + '">' + text + '</span>').find('i').remove();
		      } else {
		        if (length === 1) {
	            selectbox.removeClass('selected');

		          $('#' + id).replaceWith('<i>' + $this.options.text.any + '</i>');
		        } else {
		          $('#' + id).remove();
		        }
		      }

	        target = selectbox.find('.selecteds');
				}

		    if ($this.options.php.showButton) {
					$this.scroll(target);

					$this.update();
		    } else {
		      var url = $this.options.php.link;

		      if ($this.options.php.params) url += '&' + $this.options.php.index + '=' + $this.options.php.params;

		      window.location = url;
		    }

		    return false;
		  });

			if (this.options.php.showPrice) {
        this.options.sliders.push({
          callback: {
            option_id: 'p',
            left: this.options.element.priceFrom,
            right: this.options.element.priceTo,
            priceMin: this.options.element.priceMin,
            priceMax: this.options.element.priceMax,
            manualPrice: this.options.php.manualPrice
          },
          scale: this.options.element.priceScale,
          dual: true,
					min: this.options.php.minPrice,
					max: this.options.php.maxPrice,
					minVal: this.options.php.minPriceValue,
					maxVal: this.options.php.maxPriceValue,
          onMove: function() {
            this.callback.left.text(this.minVal);
            this.callback.right.text(this.maxVal);

            if (this.callback.manualPrice) {
              this.callback.priceMin.val(this.minVal);
              this.callback.priceMax.val(this.maxVal);
            }
  			 	},
					onLoad: function() {
            if (this.callback.manualPrice) {
							var tb = this;

							tb.callback.priceMin.on('change', function(){
								if (this.value == '') return false;
								if (this.value < parseInt(tb.min) || this.value > parseInt(tb.maxVal)) this.value = tb.min;

								tb.minVal = this.value;
								tb.setState().end();
							});

							tb.callback.priceMax.on('change', function(){
		            if (this.value == '') return false;
								if (this.value > parseInt(tb.max) || this.value < parseInt(tb.minVal)) this.value = tb.max;

								tb.maxVal = this.value;
								tb.setState().end();
							});
						}
					}
        });
			}

			/* Set other sliders */
			for (var i = 0; i < this.options.sliders.length; i++) {
				var slider = $.extend({
          onMove: function() {
            this.callback.left.text(this.minVal);

            if (this.dual) {
              this.callback.right.text(this.maxVal);
            }
  			 	},
          onChange: function() {
  					this.scale.data('ocfilter').params.remove.call(this.scale.data('ocfilter'), this.callback.option_id);
  					this.scale.data('ocfilter').params.set.call(this.scale.data('ocfilter'), this.callback.option_id, this.minVal + '-' + this.maxVal);
  					this.scale.data('ocfilter').scroll(this.scale);
  			    this.scale.data('ocfilter').update();
  				}
        }, this.options.sliders[i]);

			  slider.scale.data('ocfilter', this).trackbar(slider);
			}

			/* Selectbox switcher values show/hide */
	    $('.ocfilter-selectbox .selecteds', this.options.element.ocfilter).live('click', function(e) {
				var $this = $(this).parent('.ocfilter-selectbox');

	      if (!this.hasClass('active')) {
	        $('.ocfilter-selectbox').removeClass('active');
	        this.addClass('active');
	      } else {
	        this.removeClass('active');
	      }
	    });

	    /* Options show/hide */
			$('.show-hidden-options', this.options.element.ocfilter).live('click', function() {
				$(this).toggleClass('active').attr('title', ($(this).attr('title') == $this.options.text.hide ? $this.options.text.show_all : $this.options.text.hide));

				$('.hidden-options', $this.options.element.ocfilter).toggleClass('visible');
  		});

	    /* Values show/hide */
			$('.show-hidden-values', this.options.element.ocfilter).live('click', function() {
        $('.show-hidden-values.active').not(this).click();
				$(this).toggleClass('active').text($(this).text() == $this.options.text.hide ? $this.options.text.show_all : $this.options.text.hide);

				$('.hidden-values', $(this).parent('div')).toggleClass('visible');
			});

			/* Hide hidden and selectbox values on click them out */
		  $(document).click(function(e){
		    if (!$(e.target).parents('.ocfilter-selectbox').length) $('.ocfilter-selectbox.active').removeClass('active');
        if (!$(e.target).parents('.hidden-values.visible').length && !$(e.target).hasClass('show-hidden-values')) $('.show-hidden-values.active').click();
		  });

	    /* Price inputs show/hide */
			if (this.options.php.manualPrice) {
				this.options.element.priceScale.live({
					mouseover: function(){
		        $this.delayShow($('#fields-price-' + $this.options.php.module), 'fields-price-show-' + $this.options.php.module, 250);

		    		$this.clearTimeout('fields-price-hide-' + $this.options.php.module);
					},
					mouseout: function() {
						$this.delayHide($('#fields-price-' + $this.options.php.module), 'fields-price-hide-' + $this.options.php.module, 1000);

		        $this.clearTimeout('fields-price-show-' + $this.options.php.module);
					}
				});

		    $('#fields-price-' + this.options.php.module).live({
					mouseover: function(){
						$(this).addClass('hover');

		        $this.clearTimeout('fields-price-hide-' + $this.options.php.module);
					},
					mouseout: function() {
		        $(this).removeClass('hover');

						$this.delayHide($(this), 'fields-price-hide-' + $this.options.php.module, 1000);
					}
				});
			}

			/* Float button show/hide */
	    if (this.options.php.showButton || this.options.php.showPrice) {
				this.options.element.ocfilter.live({
					mouseover: function(){
		        $this.delayShow($('#button-float-' + $this.options.php.module), 'button-float-show-' + $this.options.php.module, 250);

		    		$this.clearTimeout('button-float-hide-' + $this.options.php.module);
					},
					mouseout: function() {
						$this.delayHide($('#button-float-' + $this.options.php.module), 'button-float-hide-' + $this.options.php.module, 1500);

		        $this.clearTimeout('button-float-show-' + $this.options.php.module);
					}
				});
			}

	    /* Option description show/hide */
			this.options.element.desription.live({
				mouseover: function(){
					var option = $(this).parents('.ocfilter-option');

					$this.delayShow($('.option-description', option), 'option-description-show-' + option.index(), 250);

	    		$this.clearTimeout('option-description-hide-' + option.index());
				},
				mouseout: function() {
					var option = $(this).parents('.ocfilter-option');

					$this.delayHide($('.option-description', option), 'option-description-hide-' + option.index(), 500);

	        $this.clearTimeout('option-description-show-' + option.index());
				}
			});

		},
	  update: function() {
			var $this = this, data = {
				path: this.options.php.path,
				module: this.options.php.module
			};

			if (this.options.php.params) {
				data[this.options.php.index] = this.options.php.params;
			}

			this.preload();

		  $.get('index.php?route=module/ocfilter/callback', data, function(json){
				/* Start update */
				for (var i in json.values) {
		      var value = json.values[i], target = $this.values['v-' + i], total = value.t, selected = value.s, params = value.p;

					if (target !== undefined) {
			      if (target.is('label')) {
			        if (total === 0 && !selected) {
								target.addClass('disabled').removeClass('selected').find('input').attr('disabled', true).prop('checked', false);
							} else {
								target.removeClass('disabled').find('input').removeAttr('disabled');
							}

			        $('input', target).val(params);

			        if ($this.options.php.showCounter) $('small', target).text(total);
			      } else {
			        if (total === 0) {
								target.attr('disabled', true);
							} else {
								target.removeAttr('disabled');
							}

			        target.val(params);
			      }
					}
	      }

		    if (json.total === 0) {
		      $this.options.element.submitButton.removeAttr('href').text($this.options.text.select);

		    	$this.options.element.scales.addClass('disabled');
		    } else {
		      $this.options.element.submitButton.attr('href', $this.options.php.link + ($this.options.php.params ? '&' + $this.options.php.index + '=' + $this.options.php.params : '')).removeClass('disabled').text(json.text_total);

					$this.options.element.scales.removeClass('disabled');
		    }

	      $this.options.element.fields.filter('.enabled').removeAttr('disabled');
		    /* End update */
		  }, 'json');
	  },
		params: {
			decode: function() {
				var params = {};

		 		if (this.options.php.params) {
					var matches = this.options.php.params.split(';');

					for (var i = 0; i < matches.length; i++) {
						var parts = matches[i].split(':');

						params[parts[0]] = typeof parts[1] != 'undefined' ? parts[1].split(',') : [];
					}
				}

				this.options.php.params = params;
			},
			encode: function() {
				var params = [];

		 		if (this.options.php.params) {
					for (i in this.options.php.params) {
						params.push(i + ':' + (typeof this.options.php.params[i] == 'object' ? this.options.php.params[i].join(',') : this.options.php.params[i]));
					}
				}

				this.options.php.params = params.join(';');
			},
			set: function(option_id, value_id) {
				this.params.decode.call(this);

				if (typeof this.options.php.params[option_id] != 'undefined') {
					this.options.php.params[option_id].push(value_id);
				} else {
					this.options.php.params[option_id] = [value_id];
	   		}

				this.params.encode.call(this);
			},
			remove: function(option_id, value_id) {
				this.params.decode.call(this);

				if (typeof this.options.php.params[option_id] != 'undefined') {
					if (this.options.php.params[option_id].length === 1 || !value_id) {
						delete this.options.php.params[option_id];
					} else {
						this.options.php.params[option_id].splice(ocfilter.options.php.params[option_id].indexOf(value_id), 1);
					}
				}

				this.params.encode.call(this);
			}
		},
	  preload: function() {
		  this.options.element.fields.attr('disabled', true);
		  this.options.element.labels.addClass('disabled').find('small').text('0');
		  this.options.element.submitButton.addClass('disabled').removeAttr('href').text(this.options.text.load);
		},
		scroll: function(target) {
	    this.options.element.floatButton.css('top', target.offset().top - this.offset.top - (this.options.element.floatButton.outerHeight() - target.outerHeight()) / 2);
		},
		delayShow: function(element, key, delay) {
			if (!key) return false;

			this.timers[key] = setTimeout(function(){
				if (!element.hasClass('active')) {
	      	element.addClass('active');
				}
			}, delay || 350);
		},
		delayHide: function(element, key, delay) {
			if (!key) return false;

			this.timers[key] = setTimeout(function(){
				if (!element.hasClass('hover')) {
	      	element.removeClass('active');
				}
			}, delay || 350);
		},
		clearTimeout: function(key) {
			if (key && this.timers[key]) return clearTimeout(this.timers[key]);
		}
  };

	/* IE6+ */
	if (Object.create === undefined) {
    Object.create = function(object) {
      function f(){};
      f.prototype = object;
      return new f();
    };
  }

  $.fn.ocfilter = function(options) {
    return this.each(function(){
      var $this = $(this);

      if ($this.data('ocfilter')) {
        return $this.data('ocfilter');
      }

      $this.data('ocfilter', Object.create(ocfilter).init(options, $this));
    });
  };
})(jQuery);