$.fn.hashTabs = function(options) {
	var _hash = {
	  set: function(data) {
	    var hash = location.hash.substr(1).split(';'), params = {};

	    if (hash[0]) for (var i in hash) { var matches = hash[i].split(':'); params[matches[0]] = matches[1]; }

	    params[data.key] = data.value;

	    window.location.hash = _hash.encode(params);
	  },
	  get: function(key) {
	    var hash = location.hash.substr(1).split(';'), params = {};

	    for (var i in hash) { var matches = hash[i].split(':'); params[matches[0]] = matches[1]; }

	    return params[key];
	  },
	  encode: function(params) {
	    var vars = [];

	    for (var i in params) { vars.push([i, params[i]].join(':')); }

	    return '#' + vars.join(';');
	  }
	}, selector = $(this), key = selector.parent().attr('id'), hash = _hash.get(key);

	selector.click(function() {
		$(selector).removeClass('selected').each(function(i, element) {
			$($(element).attr('href')).removeClass('selected');
		});

		$(this).addClass('selected');

		$($(this).attr('href')).addClass('selected');

	  _hash.set({key: key, value: this.hash.substr(1)});

		return false;
	});

  if (hash && $(this).filter('[href=\'#' + hash + '\']').length) {
    $(this).filter('[href=\'#' + hash + '\']').click();
	} else {
		$(this).first().click();
	}
};

var ocfilter = {
	/**
	   	URL GET Variables
	**/
	url: {},
	/**
	   	PHP Data (languages, filter params..)
	**/
	php: {},
	/**
	   	Initialization
	**/
	init: function() {
		this.helper.setURLVars();

	  /* OCFilter option values switcher */
	  $(document).on('click', '.switcher .selected', function(e) {
			var $this = $(this).parent('.switcher');

	    if (!$this.hasClass('active')) {
	      $('.switcher').removeClass('active');
	      $this.addClass('active');
	    } else {
	      $this.removeClass('active');
	    }
	  });

	  $(document).on('change', '.switcher input[type=\'checkbox\']', function() {
	    var $this = $(this), selected = $this.parents('.switcher').find('.selected'), text = $this.parent('label').text(), length = selected.find('span').length, counter = selected.find('strong').length;

			if (counter) {
	      selected.find('strong').text($this.parents('.switcher').find('.values input[name*=\'[options_id][]\']:checked').length);
			} else {
		    if ($this.attr('checked')) {
	        selected.append('<span id="v-' + this.value + '">' + text + '</span>').find('b').remove();
		    } else {
		      if (length === 1) {
		        $('#v-' + this.value).replaceWith('<b>' + ocfilter.php.text_select + '</b>');
		      } else {
		        $('#v-' + this.value).remove();
		      }
		    }
			}
	  });

	  $(document).click(function(e){
	    if (!$(e.target).parents('.switcher').length) $('.switcher.active').removeClass('active');
	    if (!$(e.target).parents('#colorbox').length) {
				$('#colorbox').remove();
	      $('a.color-handler').removeClass('active');
			}
	  });

		/* Floating Actions list */
		var actions = $('#list-actions'), scrolled = false, timeout = null;

		$(window).on('scroll', function() {
			if (window.pageYOffset > 35 && !scrolled) {
				scrolled = true;

				actions.addClass('scrolled');
			} else if (window.pageYOffset < 35 && scrolled) {
				scrolled = false;

				actions.removeClass('scrolled');
			}
		});

		$('.list input[name*=\'selected\'], .list thead input:first').on('change', function() {
			clearTimeout(timeout);

			actions.addClass('change');

			timeout = setTimeout(function(){ actions.removeClass('change'); }, 230);

		  $('strong > span', actions).text($('input[name*=\'selected\']:checked').length);
		});

		/* Subfields */

		$('.with-subfield').on('change', function(e){
			var $this = $(this), tag = e.target.nodeName.toLowerCase(), subfield = $this.attr('data-subfield');

			if (tag == 'input') {
				if (this.checked) {
	        $('.sf-' + subfield).addClass('visible');
				} else {
	        $('.sf-' + subfield).removeClass('visible');
				}
			} else if (tag == 'select') {
				$('.' + subfield).removeClass('visible');

				if (this.value) {
	        $('.sf-' + this.value.replace(/\_/g, '-')).addClass('visible');
				}
			}
		});
	},
	/**
	   	OCFilter List
	**/
	list: {
		init: function () {
			$('tr.filter input').keydown(function(e) {
				if (e.keyCode == 13) ocfilter.list.filter();
			});

			$('table input.edit, table select.edit').on('change', function(){
				var e = $(this), type = e.attr('type'), post = {
					field: encodeURIComponent(e.attr('name')),
					value: (type == 'checkbox' ? (this.checked ? 1 : 0) : encodeURIComponent(this.value)),
					option_id: e.attr('for')
				};

				e.fadeTo(250, .3);

				$.post('index.php?route=catalog/ocfilter/edit&token=' + ocfilter.url['token'], post, function(json){
					if (json['status'] === true) {
						e.fadeTo(250, 1).css('border', '1px solid #4BB349');

						if (type == 'checkbox') {
							var span = e.next('span');

							span.text(span.text() == ocfilter.php.text_enabled ? ocfilter.php.text_disabled : ocfilter.php.text_enabled);
						}
					} else {
						e.fadeTo(250, 1).css('border', '1px solid #E2302F');
					}
			  }, 'json');
			});
		},
		filter: function () {
			var $this = ocfilter, url = 'index.php?route=catalog/ocfilter&token=' + $this.url['token'], params = {};

			for (var i = 0; i < $this.php.filter_get.length; i++) {
				var key = $this.php.filter_get[i];

				params[key] = $('[name=\'' + key + '\']').val();

			  if (params[key] != '') {
					url += '&' + key + '=' + encodeURIComponent(params[key]);
				}
			}

			window.location = url;
		}
	},
	/**
	   	OCFilter Form
	**/
	form: {
		init: function() {
			$('#sortable').sortable({
			  placeholder: 'empty',
			  handle: '.handler',
	      revert: 150,
			  start: function (event, ui) { ui.item.addClass('start'); $('.empty').height($('#sortable li:first').height()); },
			  stop: function (event, ui) { ui.item.removeClass('start'); },
			  update: function(event, ui) { ocfilter.form.updateValues(); }
			});

      $('#tabs a').hashTabs();

			/* Color picker by SooR. 12-07-2013 */

			var colorbox = [], colors = ['f00', 'ff0', '0f0', '0ff', '00f', 'f0f', 'fff', 'ebebeb', 'e1e1e1', 'd7d7d7', 'cccccc', 'c2c2c2', 'b7b7b7', 'acacac', 'a0a0a0', '959595', 'ee1d24', 'fff100', '00a650', '00aeef', '2f3192', 'ed008c', '898989', '7d7d7d', '707070', '626262', '555', '464646', '363636', '262626', '111', '000', 'f7977a', 'fbad82', 'fdc68c', 'fff799', 'c6df9c', 'a4d49d', '81ca9d', '7bcdc9', '6ccff7', '7ca6d8', '8293ca', '8881be', 'a286bd', 'bc8cbf', 'f49bc1', 'f5999d', 'f16c4d', 'f68e54', 'fbaf5a', 'fff467', 'acd372', '7dc473', '39b778', '16bcb4', '00bff3', '438ccb', '5573b7', '5e5ca7', '855fa8', 'a763a9', 'ef6ea8', 'f16d7e', 'ee1d24', 'f16522', 'f7941d', 'fff100', '8fc63d', '37b44a', '00a650', '00a99e', '00aeef', '0072bc', '0054a5', '2f3192', '652c91', '91278f', 'ed008c', 'ee105a', '9d0a0f', 'a1410d', 'a36209', 'aba000', '588528', '197b30', '007236', '00736a', '0076a4', '004a80', '003370', '1d1363', '450e61', '62055f', '9e005c', '9d0039', '790000', '7b3000', '7c4900', '827a00', '3e6617', '045f20', '005824', '005951', '005b7e', '003562', '002056', '0c004b', '30004a', '4b0048', '7a0045', '7a0026'];

	    colorbox.push('<div id="colorbox">');

			for (var i = 0; i < colors.length; i++) {
	      colorbox.push('<a href="#' + colors[i] + '" style="background-color: #' + colors[i] + ';"><i style="background-color: #' + colors[i] + ';"></i></a>');
			}

			colorbox.push('</div>');

	    colorbox = colorbox.join('');

			$(document).on('click', 'a.color-handler', function(){
				$('#colorbox').remove();
	      $('a.color-handler').not(this).removeClass('active');

				var $this = $(this);

				$this.toggleClass('active');

				if ($this.hasClass('active')) {
					$this.after(colorbox);
		 		}

				return false;
			});

	    $(document).on('click', '#colorbox a', function(){
				var $this = $(this), color = $this.attr('href').substr(1), value = $this.parents('li');

				value.find('input[name*=\'[color]\']').val(color);
	   		value.find('.color-handler').css('background', '#' + color);

				return false;
			});

			$(document).on('click', 'a.image-handler', function(){
				var $this = $(this), field = $this.parents('li').find('input[name*=\'[image]\']'), index = $this.parents('li').index();

				if ($this.hasClass('inserted')) {
          $('a.image-handler').removeClass('active');

					field.val('').removeAttr('id');

					$this.removeClass('inserted').html('<img src="view/image/banner.png" alt="" />');
				} else {
				  $('a.image-handler').not(this).removeClass('active');

					$this.toggleClass('active');

					if ($this.hasClass('active')) {
	          field.attr('id', 'image-' + index);

	     			ocfilter.form.imageUpload($this, 'image-' + index);
			 		}
				}

				return false;
			});

			/* Search category by SooR. UPD 12-07-2013 */
			$.expr[':'].icontains = function(a, i, m) { return $(a).text().toUpperCase().indexOf(m[3].toUpperCase()) >= 0; };

			var categories = $('#categories'), categoriesOffsetTop = categories.offset().top, categoriesHeight = categories.outerHeight();

			$('input[name=\'search\']').keyup(function(){
			  categories.scrollTop(0).find('label').removeAttr('style');

			  if (this.value) {
			    var find = categories.find('label:icontains(\'' + this.value + '\')');

			    if (find.length) {
			      find.css({'color': 'red', 'font-weight': 'bold'});

			      categories.scrollTop(find.offset().top - categoriesOffsetTop - categoriesHeight / 2);
			    }
			  }
			});

			/* Numeric values clone for another name fields */
		 	if (ocfilter.php.languages.length > 1) {
				$(document).on('keyup', '.value-name', function() {
					var $this = $(this), fields = $this.parents('.fields').find('input[name*=\'[name]\']').not(this), text = this.value, numeric = /^\d/g.test(text);

					if (numeric) fields.val(text);
				});
			}

			$(document).on('change', 'input[name=\'color\']', function(){
				if (this.checked) {
					$('a.color-handler').addClass('visible');
				} else {
          $('a.color-handler.visible').removeClass('visible');
				}

        ocfilter.php.color = this.checked;
      });

      $(document).on('change', 'input[name=\'image\']', function(){
    		if (this.checked) {
					$('a.image-handler').addClass('visible');
				} else {
          $('a.image-handler.visible').removeClass('visible');
				}

        ocfilter.php.image = this.checked;
			});
		},
		valueRow: $('#sortable li').length,
	  updateValues: function() {
	    $('#sortable input[name*=\'[sort_order]\']').each(function(i) {
	      this.value = i;
	    });
	  },
	  deleteValue: function(value) {
	    value.parent('li').remove();

			this.valueRow--;
	  },
	  addValue: function() {
			var html = [];

			html.push('<li>');
			html.push(' <div class="handler"></div>');
			html.push('	<a class="delete" onclick="ocfilter.form.deleteValue($(this));">Delete</a>');
			html.push('	<div class="fields">');
			html.push('		<input type="hidden" name="ocfilter_option_value[insert][' + this.valueRow + '][color]" value="" />');
			html.push('		<input type="hidden" name="ocfilter_option_value[insert][' + this.valueRow + '][image]" value="" />');
			html.push('		<input type="hidden" name="ocfilter_option_value[insert][' + this.valueRow + '][sort_order]" value="' + this.valueRow + '" />');

			for (var i = 0; i < ocfilter.php.languages.length; i++) {
				var language = ocfilter.php.languages[i];

				html.push('	<label><input type="text" class="value-name" name="ocfilter_option_value[insert][' + this.valueRow + '][language][' + language.language_id + '][name]" value="" placeholder="Значение опции #' + this.valueRow + '" />&nbsp;<img src="view/image/flags/' + language.image + '" title="' + language.name + '" /></label>');
			}

			html.push('	</div>');
			html.push('	<a href="#" class="color-handler' + (ocfilter.php.color ? ' visible' : '') + '" title="' + ocfilter.php.text_select_color + '"></a>');
			html.push('	<a href="#" class="image-handler' + (ocfilter.php.image ? ' visible' : '') + '" title="' + ocfilter.php.text_browse_image + '"><img src="view/image/banner.png" alt="" /></a>');
		  html.push('</li>');

      $('#sortable').append(html.join(''));

    	this.valueRow++;
	  },
		imageUpload: function(target, field) {
			$('#dialog').remove();

			$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=' + ocfilter.url['token'] + '&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');

			$('#dialog').dialog({
				title: ocfilter.php.text_image_manager,
				close: function (event, ui) {
					if ($('#' + field).attr('value')) {
						$.ajax({
							url: 'index.php?route=common/filemanager/image&token=' + ocfilter.url['token'] + '&image=' + encodeURIComponent($('#' + field).val()),
							dataType: 'text',
							success: function(data) {
								target.addClass('inserted').html('<img src="' + data + '" alt="" />');
							}
						});
					}

          $('a.image-handler').removeClass('active');
				},
				bgiframe: false,
				width: 800,
				height: 400,
				resizable: false,
				modal: false
			});
		}
	},
	/**
	   	OCFilter Setting Form
	**/
	setting: {
		init: function() {
			$('input[type=\'checkbox\']').change(function() {
		    $(this).parent('label').toggleClass('checked');
		  });

		  $('input[name=\'ocfilter_module[0][show_price]\']').change(function(){
		    if (!$(this).attr('checked')) {
		      $('a[href=\'#tab-price-filtering\']').addClass('disabled').animate({opacity: 0.3}, {duration: 300, queue: false});
		      $('#tab-price-filtering input').attr('disabled', true);
		    } else {
		      $('#tabs .disabled').removeClass('disabled').animate({opacity: 1}, {duration: 300, queue: false});
		      $('#tab-price-filtering input').attr('disabled', false);
		    }
		  });

      $('tr.install-package input').on('click', function() {
			  $(this).select();
			});

      $('input[name=\'package[]\']').on('change', function() {
				if (this.checked) {
				 	$('tr.install-package.package-' + this.value).show();
				} else {
				 	$('tr.install-package.package-' + this.value).hide();
				}
			});

	    $(document).on('click', '.position a', function() {
				var $this = $(this), position = $this.attr('class').split(' ')[0], block = $this.parent('.position'), input = block.find('input');

	      block.find('a').removeClass('selected');

				$this.addClass('selected');

				input.val(position);
			});

		  $('#copy-attributes').on('click', function() {
		    var $this = $(this), post = $('#tab-copy input, #tab-copy select');

		    $this.removeAttr('id').find('span').text(ocfilter.php.executed);

		    $.post('index.php?route=module/ocfilter/copyAttributes&token=' + ocfilter.url['token'], post, function(json){
		      if (json['message']) {
		        $('span', $this).text(json['message']);
		      }

		      setTimeout(function(){
						$this.attr('id', 'copy-attributes').find('span').text(ocfilter.php.copy);
					}, 3000);
		    }, 'json');
		  });

		  $('#add-module').on('click', function() {
				var html = [], key = $('#modules tr').length + 1;

				html.push('<tr>');
				html.push('	<td><input type="checkbox" name="ocfilter_module[' + key + '][status]" value="1" checked="checked" /></td>');
	      html.push('	<td class="left"><input type="text" name="ocfilter_module[' + key + '][heading_title]" value="" size="20" /></td>');
				html.push('	<td>');
				html.push('		<select name="ocfilter_module[' + key + '][layout_id]">');

				for (var i = 0; i < ocfilter.php.layouts.length; i++) {
		    	html.push('    <option value="' + ocfilter.php.layouts[i]['layout_id'] + '">' + ocfilter.php.layouts[i]['name'] + '</option>');
				}

				html.push('		</select>');
				html.push('	</td>');
				html.push('	<td><div class="position">');

				for (var i = 0; i < ocfilter.php.positions.length; i++) {
			  	html.push('		<a class="' + ocfilter.php.positions[i]['position'] + '" title="' + ocfilter.php.positions[i]['name'] + '">' + ocfilter.php.positions[i]['name'] + '</a>');
				}

	      html.push('		<input type="hidden" name="ocfilter_module[' + key + '][position]" value="column_left" />');
				html.push('	</div></td>');
				html.push('	<td>');
				html.push('		<select name="ocfilter_module[' + key + '][category_id]" class="ocfilter-categories" data-module-id="' + key + '">');
		    html.push('        <option value="">' + ocfilter.php.text_select + '</option>');

        for (var i = 0; i < ocfilter.php.categories.length; i++) {
		    	html.push('      <option value="' + ocfilter.php.categories[i]['category_id'] + '" class="level-' + ocfilter.php.categories[i]['level'] + '">' + ocfilter.php.categories[i]['name'] + '</option>');
				}

				html.push('		</select>');
				html.push('	</td>');
				html.push('	<td>');
	      html.push('		<div class="switcher">');
				html.push('			<div class="selected">' + ocfilter.php.text_selected + ' <strong>0</strong> ' + ocfilter.php.text_options + '</div>');
				html.push('			<div class="values"></div>');
				html.push('		</div>');
				html.push('	</td>');
	      html.push('	<td class="right"><input type="text" name="ocfilter_module[' + key + '][sort_order]" value="" size="3" /></td>');
				html.push('	<td><a class="button remove">' + ocfilter.php.button_remove + '</a></td>');
				html.push('</tr>');

	      html.push('<tr class="advanced-features">');
				html.push(' <td colspan="8">');
	      html.push('  <label><b>' + ocfilter.php.entry_show_diagram + '</b><input type="checkbox" name="ocfilter_module[' + key + '][show_diagram]" value="1" /></label>');
	      html.push('	 <label><b>' + ocfilter.php.entry_show_first_limit + '</b><input type="text" name="ocfilter_module[' + key + '][show_options_limit]" value="" size="4" />&nbsp;' + ocfilter.php.text_options + '</label>');
	      html.push('	 <label><b>' + ocfilter.php.entry_show_first_limit + '</b><input type="text" name="ocfilter_module[' + key + '][show_values_limit]" value="" size="4" />&nbsp;' + ocfilter.php.text_values + '</label>');
				html.push(' </td>');
				html.push('</tr>');

				$('#modules').append(html.join(''));
			});

			$(document).on('change', 'select.ocfilter-categories', function() {
				var $this = $(this), selectbox = $this.parents('tr').find('.switcher'), key = $this.attr('data-module-id');

				$.get('index.php?route=catalog/ocfilter/callback', {token: ocfilter.url['token'], category_id: this.value}, function(json) {
					var html = [], selecteds = 0;

	        html.push('<div class="base"><label><input type="checkbox" name="ocfilter_module[' + key + '][show_price]" value="1" checked="checked" />' + ocfilter.php.entry_show_price + '</label></div>');
				  html.push('<div class="base"><label><input type="checkbox" name="ocfilter_module[' + key + '][stock_status]" value="1" checked="checked" />' + ocfilter.php.entry_stock_status + '</label></div>');
				  html.push('<div class="base"><label><input type="checkbox" name="ocfilter_module[' + key + '][manufacturer]" value="1" checked="checked" />' + ocfilter.php.entry_manufacturer + '</label></div>');

					if (json['options']) {
						for (var i = 0; i < json['options'].length; i++) {
              var status = json['options'][i].status && json['options'][i].type != 'text';

              html.push('<div' + (!status ? ' class="disabled"' : '') + '><label><input type="checkbox" name="ocfilter_module[' + key + '][options_id][]" value="' + json['options'][i]['option_id'] + '"' + (status ? ' checked="checked"' : ' disabled="disabled"') + ' />' + json['options'][i]['name'] + '</label></div>');

							if (status) {
                selecteds++;
							}
						}
					}

	        selectbox.find('.values').html(html.join(''));
          selectbox.find('strong').text(selecteds);
				}, 'json');
			});

	    $(document).on('click', '#modules a.remove', function() {
				var tr = $(this).parents('tr');

				tr.next('tr').remove();
				tr.remove();
			});

	    $('#tabs a').hashTabs();

	    $('#form').submit(function(){
				$(this).attr('action', $(this).attr('action') + window.location.hash);
			});
		}
	},
	/**
	   	OCFilter Product Form
	**/
	productForm: {
		category_id: null,
		product_id: null,
		length: null,
		init: function() {
			$('a[href=\'#tab-links\']').after('<a href="#tab-ocfilter">' + ocfilter.php.tab_ocfilter + '</a>');
			$('#tab-links').after('<div id="tab-ocfilter"><h2>' + ocfilter.php.ocfilter_select_category + '</h2></div>');

      $('#tabs a').tabs();

			if (undefined !== ocfilter.url['product_id']) {
				this.product_id = ocfilter.url['product_id'];
			}

      this.length = $('#product-category div').length;

			if (this.length) {
		  	setInterval(function(){
					var length = $('#product-category div').length;

					if (ocfilter.productForm.length != length) {
						ocfilter.productForm.length = length;

						ocfilter.productForm.update();
					}
				}, 500);
			} else {
				$('div input[name=\'product_category[]\']').on('change', function() { ocfilter.productForm.update(); });
			}

      this.update();
		},
		update: function() {
			if (this.length) {
				this.category_id = $('#product-category input[name=\'product_category[]\']:last').val();
			} else {
				this.category_id = $('div input[name=\'product_category[]\']:checked:last').val();
			}

			var html = [], get = {
				token: ocfilter.url['token'],
				category_id: this.category_id
			};

			if (this.product_id) {
				get.product_id = this.product_id;
			}

      if (!get.category_id) {
      	$('#tab-ocfilter').html('<h2>' + ocfilter.php.ocfilter_select_category + '</h2>');

				return;
			}

      $.get('index.php?route=catalog/ocfilter/callback', get, function(json) {
        if (json.message) {
          $('#tab-ocfilter').html('<h2>' + json.message + '</h2>');

					return;
				}

				html.push('<table class="form product-ocfilter-values">');

				for (var i = 0; i < json.options.length; i++) {
          var option = json.options[i], values = [], selecteds = [];

          html.push('<tr' + (!option.status ? ' class="disabled"' : '') + '>');
          html.push('<td width="20%">' + option.name + '</td><td width="80%">');

          if (option.type == 'slide' || option.type == 'slide_dual') {
						html.push('<input type="hidden" name="ocfilter_product_option[' + option.option_id + '][values][0][selected]" value="1" />');
						html.push('<input type="text" name="ocfilter_product_option[' + option.option_id + '][values][0][slide_value_min]" value="' + option.slide_value_min + '" size="5" class="slide-value-min" />&nbsp;&mdash;&nbsp;<input type="text" name="ocfilter_product_option[' + option.option_id + '][values][0][slide_value_max]" value="' + option.slide_value_max + '" size="5" class="slide-value-max" />' + option.postfix + '');

            for (var l = 0; l < ocfilter.php.languages.length; l++) {
  						html.push('&nbsp;<input type="text" name="ocfilter_product_option[' + option.option_id + '][values][0][description][' + ocfilter.php.languages[l].language_id + '][description]" value="' + option.description[ocfilter.php.languages[l].language_id].description + '" size="30" class="description" style="background-image: url(\'view/image/flags/' + ocfilter.php.languages[l].image + '\');" />');
  					}
          } else if (option.type == 'text') {
						html.push('<input type="hidden" name="ocfilter_product_option[' + option.option_id + '][values][0][selected]" value="1" />');

						for (var l = 0; l < ocfilter.php.languages.length; l++) {
							html.push('<textarea name="ocfilter_product_option[' + option.option_id + '][values][0][description][' + ocfilter.php.languages[l].language_id + '][description]" rows="2" cols="40">' + option.description[ocfilter.php.languages[l].language_id].description + '</textarea>&nbsp;<img src="view/image/flags/' + ocfilter.php.languages[l].image + '" alt="' + ocfilter.php.languages[l].name + '" title="' + ocfilter.php.languages[l].name + '" /><br />');
						}
					} else {
						if (option.values) {
							for (var j in option.values) {
	              var value = option.values[j];

	              if (value.selected) {
	                selecteds.push('<span id="v-' + value.value_id + '">' + value.name + option.postfix + '</span>');
	              }

	              values.push('<div>');

                values.push(' <label><input type="checkbox" name="ocfilter_product_option[' + option.option_id + '][values][' + value.value_id + '][selected]" value="' + value.value_id + '"' + (value.selected ? ' checked="checked"' : '') + ' />' + value.name + option.postfix + '</label>');

                for (var l = 0; l < ocfilter.php.languages.length; l++) {
									values.push('&nbsp;<input type="text" name="ocfilter_product_option[' + option.option_id + '][values][' + value.value_id + '][description][' + ocfilter.php.languages[l].language_id + '][description]" value="' + value.description[ocfilter.php.languages[l].language_id].description + '" size="30" style="background-image: url(\'view/image/flags/' + ocfilter.php.languages[l].image + '\');" />');
								}

                values.push('</div>');
							}

	            if (!selecteds.length) selecteds = ['<b>' + ocfilter.php.text_select + '</b>'];

	            html.push('<div class="switcher"><div class="selected">' + selecteds.join('') + '</div><div class="values">' + values.join('') + '</div>');
	          } else {
	            html.push('<a href="index.php?route=catalog/ocfilter/update&token=' + ocfilter.url['token'] + '&option_id=' + option.option_id + '" target="_blank">' + ocfilter.php.entry_values + '</a>');
	          }
					}

          html.push('</td></tr>');
        }

        html.push('</table>');

      	$('#tab-ocfilter').html(html.join(''));
      }, 'json');
    }
	},
	/**
	   	OCFilter Product List
	**/
	productList: {
		products: [],
		init: function() {
			var $this = this;

			$('#manufacturer-id').on('change', function() {
			  var manufacturer_id = $(this).val();

			  $('input[name*=\'selected\']:checked').each(function() {
          $this.products.push({
            field: 'manufacturer_id',
						value: manufacturer_id,
						product_id: this.value
					});
			  });

				$this.update($(this));
			});

			$('table input.edit, table select.edit').on('change', function(){
        $this.products.push({
          field: encodeURIComponent($(this).attr('name')),
					value: ($(this).attr('type') == 'checkbox' ? (this.checked ? 1 : 0) : encodeURIComponent(this.value)),
					product_id: $(this).attr('for')
				});

				$this.update($(this));
			});
		},
		update: function(target) {
      target.fadeTo(250, .3);

			$.post('index.php?route=catalog/product/edit&token=' + ocfilter.url['token'], {products: this.products}, function(json){
				if (json['status'] === true) {
					target.fadeTo(250, 1).css('border', '1px solid #4BB349');

					if (target.attr('type') == 'checkbox') {
						var span = target.next('span');

						span.text(span.text() == ocfilter.php.text_enabled ? ocfilter.php.text_disabled : ocfilter.php.text_enabled);
					}
				} else {
					target.fadeTo(250, 1).css('border', '1px solid #E2302F');
				}

        ocfilter.productList.products = [];
		  }, 'json');
		}
	},
	/**
	   	Helpers
	**/
	helper: {
		setURLVars: function() {
			var vars = window.location.href.replace(window.location.hash, '').split('?')[1].split('&'), $this = ocfilter;

			for (var i = 0; i < vars.length; i++) {
				var parts = vars[i].split('=');

				ocfilter.url[parts[0]] = parts[1];
			}
		}
	}
};

$(function(){
  ocfilter.init();

	/**
	   	OCFilter List
	**/
  if (ocfilter.url['route'] == 'catalog/ocfilter') {
		ocfilter.list.init();
	}

	/**
	   	OCFilter Form
	**/
	if (ocfilter.url['route'] == 'catalog/ocfilter/insert' || ocfilter.url['route'] == 'catalog/ocfilter/update') {
		ocfilter.form.init();
	}

	/**
			OCFilter Product Form
	**/
  if (ocfilter.url['route'] == 'catalog/product/insert' || ocfilter.url['route'] == 'catalog/product/update') {
    ocfilter.productForm.init();
	}

	/**
			OCFilter Product List
	**/
  if (ocfilter.url['route'] == 'catalog/product') {
    ocfilter.productList.init();
	}

	/**
			OCFilter Module Setting
	**/
  if (ocfilter.url['route'] == 'module/ocfilter') {
		ocfilter.setting.init();
	}
});