(function($){
	var trackbar = {
		/* Initialization */
		init: function(options, scale) {
      if (scale.is(':hidden')) {
        scale.parents('.hidden-options').css({position: 'absolute', visibility: 'hidden', display: 'block'});
      }

      /* Set defaults */
      this.scale     = scale;
      this.callback  = options.callback;
      this.dual      = options.dual || false;
			this.min       = options.min || 0;
			this.max       = options.max || 100;
			this.minVal    = options.minVal || this.min;
			this.maxVal    = this.dual ? options.maxVal || this.max : this.minVal;
			this.round     = options.round || 0;
			this.fixed     = options.fixed || 0;
      this.onLoad    = options.onLoad || function(){};
      this.onMove    = options.onMove || function(){};
      this.onChange  = options.onChange || function(){};

			/* JQ Elements */
			this.trackbar      = $('.trackbar', scale);
			this.handler       = $('.trackbar-handler', scale);
			this.leftBlock     = $('.left-block', scale);
      this.rightBlock    = $('.right-block', scale);

			/* Flags */
			this.moving = false;

			/* Get Widths */
      this.width        = this.trackbar.outerWidth();
      this.handlerWidth = this.handler.outerWidth();

  		this.valueInterval = this.max - this.min;

      /* Set current state */
			this.setState();

			/* Events delegation */
			var $this = this;

      this.handler.on('mousedown', function(e){ $this.start(e); });
      $(document).on('mousemove', function(e){ if ($this.moving) $this.move(e); });
      $(document).on('mouseup', function(){ if ($this.moving) $this.end(); });

      if (typeof this.onLoad == 'function') {
    		this.onLoad.call(this);
    	}

      this.scale.parents('.hidden-options').removeAttr('style');
		},
		position: function(event) {
			if (!document.attachEvent && document.addEventListener) return event.clientX + window.scrollX;
			if (document.attachEvent != null) return window.event.clientX + document.documentElement.scrollLeft + document.body.scrollLeft;
		},
		/* On handler mousedown fn */
		start: function(e) {
			this.moving = this.handler.index(e.target) + 1;

			this.hx = this.position(e);
			this.bx = this.moving == 2 ? this.rightWidth : this.leftWidth;

      this._minVal = this.minVal;
      this._maxVal = this.maxVal;
      this._onChange = this.onChange;

      return this;
		},
		/* On document mousemove fn */
		move: function(e) {
			if (this.moving == 1) {
				this.leftWidth = this.bx + this.position(e) - this.hx;
			}

			if (this.moving == 2) {
				this.rightWidth = this.bx + this.hx - this.position(e);
			}

			this.calculate();

      return this;
		},
		/* On document mouseup fn */
		end: function() {
			if (this._minVal != this.minVal || this._maxVal != this.maxVal) {
        if (typeof this._onChange == 'function') {
      		this._onChange.call(this);
      	}

        this._onChange = function(){};
      }

			this.moving = false;

      return this;
		},
		setState: function() {
			this.leftWidth = parseInt((this.minVal - this.min) / this.valueInterval * this.width);
			this.rightWidth = this.width - parseInt((this.maxVal - this.min) / this.valueInterval * this.width);

      this.calculate();

      this._onChange = this.onChange;

      return this;
		},
		calculate: function() {
     	if (this.leftWidth < 0) this.leftWidth = 0;
			if (this.rightWidth < 0) this.rightWidth = 0;

			if (this.moving === 1) {
				if (this.dual) {
					this.leftWidth = this.leftWidth > this.width - this.rightWidth - this.handlerWidth ? this.width - this.rightWidth - this.handlerWidth : this.leftWidth;
				} else {
					this.leftWidth = this.leftWidth > this.width ? this.width : this.leftWidth;
		      this.rightWidth = this.width - this.leftWidth;
				}

        this.minVal = parseFloat(this.min + this.leftWidth / this.width * this.valueInterval).toFixed(this.fixed);

				if (!this.dual) this.maxVal = this.minVal;
				if (this.round) this.minVal = parseInt(this.minVal / this.round) * this.round;
				if (this.leftWidth + this.rightWidth >= this.width) this.minVal = this.maxVal;
			}

			if (this.moving == 2) {
				if (this.dual) {
					this.rightWidth = this.rightWidth > this.width - this.leftWidth - this.handlerWidth ? this.width - this.leftWidth - this.handlerWidth : this.rightWidth;
				} else {
					this.rightWidth = this.rightWidth > this.width ? this.width : this.rightWidth;
		      this.leftWidth = this.width - this.rightWidth;
				}

				this.maxVal = parseFloat(this.min + this.valueInterval - this.rightWidth / this.width * this.valueInterval).toFixed(this.fixed);

				if (!this.dual) this.minVal = this.maxVal;
				if (this.round) this.maxVal = parseInt(this.maxVal / this.round) * this.round;
				if (this.leftWidth + this.rightWidth >= this.width) this.maxVal = this.minVal;
			}

      this.leftBlock.width(this.leftWidth);
      this.rightBlock.width(this.rightWidth);

      if (typeof this.onMove == 'function') {
    		this.onMove.call(this);
    	}

      return this;
		},
		/* Console logging */
		log: function(message) {
      console.log('Trackbar log: ');
			console.log(message);
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

  $.fn.trackbar = function(options) {
    return this.each(function(){
      var $this = $(this);

      if ($this.data('trackbar')) {
        return $this.data('trackbar');
      }

      $this.data('trackbar', Object.create(trackbar).init(options, $this));
    });
  };
})(jQuery);