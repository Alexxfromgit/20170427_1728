<?php echo $header; ?>
<div id="content">
<!-- breadcrumb -->
	<ul class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<?php echo $breadcrumb['separator']; ?><li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
		<?php } ?>
	</ul>
<!-- warning -->
	<?php if ($success) { ?>
	<div class="success"><?php echo $success; ?></div>
	<?php } ?>
	<?php if ($error_warning) { ?>
	<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>
<!-- settings box -->
	<div class="box">
<!-- heading -->
		<div class="hero-unit">
			<div class="row-fluid">	
			<div class="gears"></div>
			<h2><?php echo $common_title; ?></h2>
			<p><?php echo $common_description; ?></p>
			<div class="btn-group" data-toggle="buttons-checkbox" id="save">
				<a onclick="$('#form').submit();" class="btn btn-sample"><span><?php echo $button_save; ?></span></a>
				<a onclick="location = '<?php echo $cancel; ?>';" class="btn btn-sample"><span><?php echo $button_cancel; ?></span></a>
				<a onclick="apply()" class="btn btn-sample apply"><span><?php echo $button_apply; ?></span></a>
			</div>
			<div class="clearfix"></div>
			</div>
		</div>
<!-- content -->
		<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
			<div id="toos">
				<ul class="nav nav-tabs" id="myTab">
					<li class="active"><a href="#main"><?php echo $tools_main; ?></a></li>
					<li><a href="#popup"><?php echo $tools_popup; ?></a></li>
					<li><a href="#fly"><?php echo $tools_fly; ?></a></li>
					<li><a href="#module_tools"><?php echo $module_tools; ?></a></li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="main">
						<div class="row-fluid">	
							<ul class="nav nav-tabs" id="myTab1">
								<li class="active"><a href="#1"><?php echo $tools_typepres; ?></a></li>
								<li><a href="#2"><?php echo $tools_position; ?></a></li>
								<li><a href="#3"><?php echo $tools_design; ?></a></li>
							</ul>
<!-- general -->
							<div class="tab-content">	
								<div class="tab-pane active" id="1">
									<div class="control-group">
										<label class="control-label"><span class="tip icon-question-sign help" title="<?php echo $tools_tpreseth; ?>"></span><?php echo $tools_tpreset; ?>:</label>
										<div class="controls" id="preset">	
											<div class="select_box"><select name="kw_flycart_preset">
														<option disabled><?php echo $tools_choose; ?></option>
												<?php foreach ($cart_preset as $preset) { ?>
													<?php if ($kw_flycart_preset == $preset['name']) { ?>
														<option value="<?php echo $preset['name']; ?>" selected="selected"><?php echo $preset['title']; ?></option>
													<?php } else { ?>
														<option value="<?php echo $preset['name']; ?>"><?php echo $preset['title']; ?></option>
													<?php } ?>
												<?php } ?>
											</select></div>
										</div>
										<div class="clearfix"></div>
										<div id="pre_img" class="pat_pre">
											<div class="patern-button" style="background-image:url(/image/cart/<?php echo isset(${'kw_flycart_image'}) ? ${'kw_flycart_image'} : 'Right-With-Shadow.png'; ?>)"></div>
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label"><span class="tip icon-question-sign help" title="<?php echo $tools_tselech; ?>"></span><?php echo $tools_tselect; ?>:</label>
										<div class="controls" id="type">	
											<div class="select_box"><select name="kw_flycart_type">
														<option disabled><?php echo $tools_choose; ?></option>
												<?php foreach ($cart_type as $type) { ?>
													<?php if ($kw_flycart_type == $type['name']) { ?>
														<option value="<?php echo $type['name']; ?>" selected="selected"><?php echo $type['title']; ?></option>
													<?php } else { ?>
														<option value="<?php echo $type['name']; ?>"><?php echo $type['title']; ?></option>
													<?php } ?>
												<?php } ?>
											</select></div>
											
										</div>
										<div class="clearfix"></div>
									</div>
									
									<div class="control-group">
										<label class="control-label"><span class="tip icon-question-sign help" title="<?php echo $tools_optionsh; ?>"></span><?php echo $tools_options; ?>:</label>
										<div class="controls" id="topions">	
											<select name="kw_flycart_topions">
														<option disabled><?php echo $tools_choose; ?></option>
												<?php foreach ($popup_topions as $topions) { ?>
													<?php if ($kw_flycart_topions == $topions['name']) { ?>
														<option value="<?php echo $topions['name']; ?>" selected="selected"><?php echo $topions['title']; ?></option>
													<?php } else { ?>
														<option value="<?php echo $topions['name']; ?>"><?php echo $topions['title']; ?></option>
													<?php } ?>
												<?php } ?>
											</select>
										</div>
										<div class="clearfix"></div>
									</div>				
								</div>
								
								<div class="tab-pane" id="2">	
									<div class="control-group">
										<label class="control-label"><span class="tip icon-question-sign help" title="<?php echo $tools_flyposh; ?>"></span><?php echo $tools_flypos; ?>:</label>
										<div class="controls" id="pos_group">	
											<select name="kw_flycart_position">
														<option disabled><?php echo $tools_choose; ?></option>
												<?php foreach ($cart_position as $position) { ?>
													<?php if ($kw_flycart_position == $position['name']) { ?>
														<option value="<?php echo $position['name']; ?>" selected="selected"><?php echo $position['title']; ?></option>
													<?php } else { ?>
														<option value="<?php echo $position['name']; ?>"><?php echo $position['title']; ?></option>
													<?php } ?>
												<?php } ?>
											</select>
										</div>
										<div class="clearfix"></div>
									</div>	

									<div class="control-group">
										<label class="control-label"><span class="tip icon-question-sign help" title="<?php echo $tools_positionh; ?>"></span><?php echo $tools_position; ?>:</label>
										<div class="controls" id="pos_type">	
											<select name="kw_flycart_postype">
														<option disabled><?php echo $tools_choose; ?></option>
												<?php foreach ($cart_postype as $postype) { ?>
													<?php if ($kw_flycart_postype == $postype['name']) { ?>
														<option value="<?php echo $postype['name']; ?>" selected="selected"><?php echo $postype['title']; ?></option>
													<?php } else { ?>
														<option value="<?php echo $postype['name']; ?>"><?php echo $postype['title']; ?></option>
													<?php } ?>
												<?php } ?>
											</select>
										</div>
										<div class="clearfix"></div>
									</div>		
									
									<div class="page-header"><strong><?php echo $tools_offset; ?></strong></div>
									<div class="control-group" id="offsetx">
										<label class="control-label"><span class="tip icon-question-sign help" title="<?php echo $tools_horizontalh; ?>"></span><?php echo $tools_horizontal; ?>:</label>
										<div class="controls">
											<input type="text" name="kw_flycart_offset_x" value="<?php echo isset(${'kw_flycart_offset_x'}) ? ${'kw_flycart_offset_x'} : '0px'; ?>" />
										</div>
										<div class="clearfix"></div>
									</div>
									<div class="control-group" id="offsety">
										<label class="control-label"><span class="tip icon-question-sign help" title="<?php echo $tools_verticalh; ?>"></span><?php echo $tools_vertical; ?>:</label>
										<div class="controls">
											<input type="text" name="kw_flycart_offset_y" value="<?php echo isset(${'kw_flycart_offset_y'}) ? ${'kw_flycart_offset_y'} : '100px'; ?>" />
										</div>
										<div class="clearfix"></div>
									</div>						
								</div>
								
								<div class="tab-pane" id="3">
									<div class="page-header"><strong><?php echo $tools_size; ?></strong></div>
									<div class="control-group" id="vheight">
										<label class="control-label"><span class="tip icon-question-sign help" title="<?php echo $tools_heighth; ?>"></span><?php echo $tools_height; ?>:</label>
										<div class="controls">
											<input type="text" name="kw_flycart_fheight" value="<?php echo isset(${'kw_flycart_fheight'}) ? ${'kw_flycart_fheight'} : '56px'; ?>" />
										</div>
										<div class="clearfix"></div>
									</div>
									<div class="control-group" id="vwidth">
										<label class="control-label"><span class="tip icon-question-sign help" title="<?php echo $tools_widthh; ?>"></span><?php echo $tools_width; ?>:</label>
										<div class="controls">
											<input type="text" name="kw_flycart_fwidth" value="<?php echo isset(${'kw_flycart_fwidth'}) ? ${'kw_flycart_fwidth'} : '71px'; ?>" />
										</div>
										<div class="clearfix"></div>
									</div>	
								
									<div id="cart_image">
										<div class="control-group pat">
											<label class="control-label"><span class="tip icon-question-sign help" title="<?php echo $tools_vimgh; ?>"></span><?php echo $tools_vimg; ?>:</label>
											<div class="controls">	
												<div class="size_pre">
													<a class="opener"><?php echo $tools_open; ?>  <i class="icon-arrow-down"></i></a>
													<a class="closer"><?php echo $tools_close; ?>  <i class="icon-arrow-up"></i></a>
													<?php echo $tools_height; ?>:<span class="imheight"></span><br /><?php echo $tools_width; ?>:<span class="imwidth"></span>
												</div>
												
												<div class="pat_pre">
													<div class="patern-button" style="background-image:url(/image/cart/<?php echo isset(${'kw_flycart_image'}) ? ${'kw_flycart_image'} : 'Right-With-Shadow.png'; ?>)"></div>
												</div>
											</div>
											<div class="clearfix"></div>
										</div>
									
										<div class="pattern-container">
											<table class="table table-bordered">
												<tr>
													<td>
														<div class="pattern-body"><ul class="pattern"></ul></div>
														<input class="pattern_bg" name="kw_flycart_image" type="hidden" value="<?php echo isset(${'kw_flycart_image'}) ? ${'kw_flycart_image'} : 'Right-With-Shadow.png'; ?>" />
													</td>
												</tr>
												<tr>
													<td>
														<div class="btn-group">
															<a class="upload btn btn-sample"><?php echo $tools_upload; ?>  <i class="icon-download icon-white"></i></a>
															<a class="delete btn btn-sample"><?php echo $tools_delete; ?>  <i class="icon-remove-circle icon-white"></i></a>
														</div>
													</td>
												</tr>			
											</table>
											<div class="clearfix"></div>
										</div>
									</div>
								
									<div class="page-header"><strong><?php echo $tools_vtext; ?></strong></div>						
									<div class="control-group color" id="total_color">
										<label class="control-label"><span class="tip icon-question-sign help" title="<?php echo $tools_vcolorh; ?>"></span><?php echo $tools_vcolor; ?>:</label>
										<div class="controls">
											<input class="t_border_color" name="kw_flycart_tcolor" type="text" value="<?php echo isset(${'kw_flycart_tcolor'}) ? ${'kw_flycart_tcolor'} : 'FFFFFF'; ?>" maxlength="6" />
											<div class="top_bg_sel">
												<div style="background-color:#<?php echo isset(${'kw_flycart_tcolor'}) ? ${'kw_flycart_tcolor'} : 'FFFFFF'; ?>"></div>
											</div>
										</div>
										<div class="clearfix"></div>
									</div>	
									<div class="control-group" id="total_size">
										<label class="control-label"><span class="tip icon-question-sign help" title="<?php echo $tools_vsizeh; ?>"></span><?php echo $tools_vsize; ?>:</label>
										<div class="controls">
											<input type="text" name="kw_flycart_tsize" value="<?php echo isset(${'kw_flycart_tsize'}) ? ${'kw_flycart_tsize'} : '17px'; ?>" />
										</div>
										<div class="clearfix"></div>
									</div>				

									<div id="margin_total" class="margin form-inline">
										<div class="page-header"><strong><?php echo $tools_vmargin; ?></strong><span class="tip icon-question-sign help" title="<?php echo $tools_vmarginh; ?>"></span></div>	
											<label class="control-label" id="total_tmbottom"><span>top:</span>
												<input type="text" class="input-small" name="kw_flycart_tmbottom" value="<?php echo isset(${'kw_flycart_tmbottom'}) ? ${'kw_flycart_tmbottom'} : '0px'; ?>" />
											</label>							
											<label class="control-label" id="total_tmleft"><span>right:</span>
												<input type="text" class="input-small" name="kw_flycart_tmleft" value="<?php echo isset(${'kw_flycart_tmleft'}) ? ${'kw_flycart_tmleft'} : '24px'; ?>" />
											</label>							
											<label class="control-label" id="total_tmtop"><span>bottom:</span>
												<input type="text" class="input-small" name="kw_flycart_tmtop" value="<?php echo isset(${'kw_flycart_tmtop'}) ? ${'kw_flycart_tmtop'} : '12px'; ?>" />
											</label>							
											<label class="control-label" id="total_tmright"><span>left:</span>
												<input type="text" class="input-small" name="kw_flycart_tmright" value="<?php echo isset(${'kw_flycart_tmright'}) ? ${'kw_flycart_tmright'} : '0px'; ?>" />
											</label>							
										<div class="clearfix"></div>
									</div>
								</div>
							</div>
							<div class="clearfix"></div>
						</div>
					</div>	
<!-- popup -->					
					<div class="tab-pane" id="popup">
						<div class="row-fluid">	
							<ul class="nav nav-tabs" id="myTab2">
								<li class="active"><a href="#a1"><?php echo $tools_pgeneral; ?></a></li>
								<li><a href="#a2"><?php echo $tools_pheader; ?></a></li>
								<li><a href="#a3"><?php echo $tools_pcont; ?></a></li>
								<li><a href="#a4"><?php echo $tools_pfooter; ?></a></li>
							</ul>
							<div class="tab-content">	
								<div class="tab-pane active" id="a1">					
									<div class="control-group">
										<label class="control-label"><span class="tip icon-question-sign help" title="<?php echo $tools_plbh; ?>"></span><?php echo $tools_plb; ?>:</label>
										<div class="controls" id="pselect">	
											<select name="kw_flycart_pselect">
														<option disabled><?php echo $tools_choose; ?></option>
												<?php foreach ($popup_select as $select) { ?>
													<?php if ($kw_flycart_pselect == $select['name']) { ?>
														<option value="<?php echo $select['name']; ?>" selected="selected"><?php echo $select['title']; ?></option>
													<?php } else { ?>
														<option value="<?php echo $select['name']; ?>"><?php echo $select['title']; ?></option>
													<?php } ?>
												<?php } ?>
											</select>
										</div>
										<div class="clearfix"></div>
									</div>							
									
									<div class="control-group">
										<label class="control-label"><span class="tip icon-question-sign help" title="<?php echo $tools_pcfh; ?>"></span><?php echo $tools_pcf; ?>:</label>
										<div class="controls" id="bselect">	
											<select name="kw_flycart_bselect">
														<option disabled><?php echo $tools_choose; ?></option>
												<?php foreach ($popup_bselect as $bselect) { ?>
													<?php if ($kw_flycart_bselect == $bselect['name']) { ?>
														<option value="<?php echo $bselect['name']; ?>" selected="selected"><?php echo $bselect['title']; ?></option>
													<?php } else { ?>
														<option value="<?php echo $bselect['name']; ?>"><?php echo $bselect['title']; ?></option>
													<?php } ?>
												<?php } ?>
											</select>
										</div>
										<div class="clearfix"></div>
									</div>				
									
									<div class="control-group color" id="overlay">
										<label class="control-label"><span class="tip icon-question-sign help" title="<?php echo $tools_pcoh; ?>"></span><?php echo $tools_pco; ?>:</label>
										<div class="controls">
											<input class="top_bg" name="kw_flycart_overlay" type="text" value="<?php echo isset(${'kw_flycart_overlay'}) ? ${'kw_flycart_head_bgp'} : 'ffffff'; ?>" maxlength="6" />
											<div class="top_bg_sel">
												<div style="background-color:#<?php echo isset(${'kw_flycart_overlay'}) ? ${'kw_flycart_overlay'} : 'ffffff'; ?>"></div>
											</div>
										</div>
										<div class="clearfix"></div>
									</div>
								</div>
								
								<div class="tab-pane" id="a2">
									<div class="control-group color" id="head_bgp">
										<label class="control-label"><span class="tip icon-question-sign help" title="<?php echo $tools_pbgh; ?>"></span><?php echo $tools_pbg; ?>:</label>
										<div class="controls">
											<input class="top_bg" name="kw_flycart_head_bgp" type="text" value="<?php echo isset(${'kw_flycart_head_bgp'}) ? ${'kw_flycart_head_bgp'} : 'ffffff'; ?>" maxlength="6" />
											<div class="top_bg_sel">
												<div style="background-color:#<?php echo isset(${'kw_flycart_head_bgp'}) ? ${'kw_flycart_head_bgp'} : 'ffffff'; ?>"></div>
											</div>
										</div>
										<div class="clearfix"></div>
									</div>				
									
									<div class="control-group color" id="bhead_bgp">
										<label class="control-label"><span class="tip icon-question-sign help" title="<?php echo $tools_pcbh; ?>"></span><?php echo $tools_pcb; ?>:</label>
										<div class="controls">
											<input class="top_bg" name="kw_flycart_bhead_bgp" type="text" value="<?php echo isset(${'kw_flycart_bhead_bgp'}) ? ${'kw_flycart_bhead_bgp'} : 'eeeeee'; ?>" maxlength="6" />
											<div class="top_bg_sel">
												<div style="background-color:#<?php echo isset(${'kw_flycart_bhead_bgp'}) ? ${'kw_flycart_bhead_bgp'} : 'eeeeee'; ?>"></div>
											</div>
										</div>
										<div class="clearfix"></div>
									</div>
									
									<div class="control-group color" id="chead_bgp">
										<label class="control-label"><span class="tip icon-question-sign help" title="<?php echo $tools_pcth; ?>"></span><?php echo $tools_pct; ?>:</label>
										<div class="controls">
											<input class="top_bg" name="kw_flycart_chead_bgp" type="text" value="<?php echo isset(${'kw_flycart_chead_bgp'}) ? ${'kw_flycart_chead_bgp'} : '000000'; ?>" maxlength="6" />
											<div class="top_bg_sel">
												<div style="background-color:#<?php echo isset(${'kw_flycart_chead_bgp'}) ? ${'kw_flycart_chead_bgp'} : '000000'; ?>"></div>
											</div>
										</div>
										<div class="clearfix"></div>
									</div>				
									
									<div class="control-group color" id="close_bg">
										<label class="control-label"><span class="tip icon-question-sign help" title="<?php echo $tools_pccth; ?>"></span><?php echo $tools_pcct; ?>:</label>
										<div class="controls">
											<input class="top_bg" name="kw_flycart_close_bg" type="text" value="<?php echo isset(${'kw_flycart_close_bg'}) ? ${'kw_flycart_close_bg'} : '000000'; ?>" maxlength="6" />
											<div class="top_bg_sel">
												<div style="background-color:#<?php echo isset(${'kw_flycart_close_bg'}) ? ${'kw_flycart_close_bg'} : '000000'; ?>"></div>
											</div>
										</div>
										<div class="clearfix"></div>
									</div>					
								</div>
								
								<div class="tab-pane" id="a3">
									<div class="control-group color" id="color_bgp">
										<label class="control-label"><span class="tip icon-question-sign help" title="<?php echo $tools_pbgh; ?>"></span><?php echo $tools_pbg; ?>:</label>
										<div class="controls">
											<input class="top_bg" name="kw_flycart_color_bgp" type="text" value="<?php echo isset(${'kw_flycart_color_bgp'}) ? ${'kw_flycart_color_bgp'} : 'ffffff'; ?>" maxlength="6" />
											<div class="top_bg_sel">
												<div style="background-color:#<?php echo isset(${'kw_flycart_color_bgp'}) ? ${'kw_flycart_color_bgp'} : 'ffffff'; ?>"></div>
											</div>
										</div>
										<div class="clearfix"></div>
									</div>					
									
									<div class="control-group color" id="color_a">
										<label class="control-label"><span class="tip icon-question-sign help" title="<?php echo $tools_pclh; ?>"></span><?php echo $tools_pcl; ?>:</label>
										<div class="controls">
											<input class="top_bg" name="kw_flycart_color_a" type="text" value="<?php echo isset(${'kw_flycart_color_a'}) ? ${'kw_flycart_color_a'} : '38B0E3'; ?>" maxlength="6" />
											<div class="top_bg_sel">
												<div style="background-color:#<?php echo isset(${'kw_flycart_color_a'}) ? ${'kw_flycart_color_a'} : '38B0E3'; ?>"></div>
											</div>
										</div>
										<div class="clearfix"></div>
									</div>					
									
									<div class="control-group color" id="color_color">
										<label class="control-label"><span class="tip icon-question-sign help" title="<?php echo $tools_pcth; ?>"></span><?php echo $tools_pct; ?>:</label>
										<div class="controls">
											<input class="top_bg" name="kw_flycart_color" type="text" value="<?php echo isset(${'kw_flycart_color'}) ? ${'kw_flycart_color'} : '000000'; ?>" maxlength="6" />
											<div class="top_bg_sel">
												<div style="background-color:#<?php echo isset(${'kw_flycart_color'}) ? ${'kw_flycart_color'} : '000000'; ?>"></div>
											</div>
										</div>
										<div class="clearfix"></div>
									</div>		

									<div class="control-group color" id="remove_bg">
										<label class="control-label"><span class="tip icon-question-sign help" title="<?php echo $tools_pcrth; ?>"></span><?php echo $tools_pcrt; ?>:</label>
										<div class="controls">
											<input class="top_bg" name="kw_flycart_remove_bg" type="text" value="<?php echo isset(${'kw_flycart_remove_bg'}) ? ${'kw_flycart_remove_bg'} : '000000'; ?>" maxlength="6" />
											<div class="top_bg_sel">
												<div style="background-color:#<?php echo isset(${'kw_flycart_remove_bg'}) ? ${'kw_flycart_remove_bg'} : '000000'; ?>"></div>
											</div>
										</div>
										<div class="clearfix"></div>
									</div>				
									
									<div class="control-group color" id="color_border">
										<label class="control-label"><span class="tip icon-question-sign help" title="<?php echo $tools_pcbhb; ?>"></span><?php echo $tools_pcb; ?>:</label>
										<div class="controls">
											<input class="top_bg" name="kw_flycart_border" type="text" value="<?php echo isset(${'kw_flycart_border'}) ? ${'kw_flycart_border'} : 'eeeeee'; ?>" maxlength="6" />
											<div class="top_bg_sel">
												<div style="background-color:#<?php echo isset(${'kw_flycart_border'}) ? ${'kw_flycart_border'} : 'eeeeee'; ?>"></div>
											</div>
										</div>
										<div class="clearfix"></div>
									</div>				
									
									<div class="control-group color" id="scroll">
										<label class="control-label"><span class="tip icon-question-sign help" title="<?php echo $tools_pcsрh; ?>"></span><?php echo $tools_pcs; ?>:</label>
										<div class="controls">
											<input class="top_bg" name="kw_flycart_scroll" type="text" value="<?php echo isset(${'kw_flycart_scroll'}) ? ${'kw_flycart_scroll'} : '000000'; ?>" maxlength="6" />
											<div class="top_bg_sel">
												<div style="background-color:#<?php echo isset(${'kw_flycart_scroll'}) ? ${'kw_flycart_scroll'} : '000000'; ?>"></div>
											</div>
										</div>
										<div class="clearfix"></div>
									</div>					
									
									<div class="control-group color" id="empty">
										<label class="control-label"><span class="tip icon-question-sign help" title="<?php echo $tools_pceh; ?>"></span><?php echo $tools_pce; ?>:</label>
										<div class="controls">
											<input class="top_bg" name="kw_flycart_empty" type="text" value="<?php echo isset(${'kw_flycart_empty'}) ? ${'kw_flycart_empty'} : '7c7c7c'; ?>" maxlength="6" />
											<div class="top_bg_sel">
												<div style="background-color:#<?php echo isset(${'kw_flycart_empty'}) ? ${'kw_flycart_empty'} : '7c7c7c'; ?>"></div>
											</div>
										</div>
										<div class="clearfix"></div>
									</div>	
								</div>
								
								<div class="tab-pane" id="a4">
									<div class="control-group color" id="color_fgp">
										<label class="control-label"><span class="tip icon-question-sign help" title="<?php echo $tools_pbgh; ?>"></span><?php echo $tools_pbg; ?>:</label>
										<div class="controls">
											<input class="top_bg" name="kw_flycart_color_fgp" type="text" value="<?php echo isset(${'kw_flycart_color_fgp'}) ? ${'kw_flycart_color_fgp'} : 'F5F5F5'; ?>" maxlength="6" />
											<div class="top_bg_sel">
												<div style="background-color:#<?php echo isset(${'kw_flycart_color_fgp'}) ? ${'kw_flycart_color_fgp'} : 'F5F5F5'; ?>"></div>
											</div>
										</div>
										<div class="clearfix"></div>
									</div>					
									
									<div class="control-group color" id="color_fbgp">
										<label class="control-label"><span class="tip icon-question-sign help" title="<?php echo $tools_pcbth; ?>"></span><?php echo $tools_pcb; ?>:</label>
										<div class="controls">
											<input class="top_bg" name="kw_flycart_color_fbgp" type="text" value="<?php echo isset(${'kw_flycart_color_fbgp'}) ? ${'kw_flycart_color_fbgp'} : 'DDDDDD'; ?>" maxlength="6" />
											<div class="top_bg_sel">
												<div style="background-color:#<?php echo isset(${'kw_flycart_color_fbgp'}) ? ${'kw_flycart_color_fbgp'} : 'DDDDDD'; ?>"></div>
											</div>
										</div>
										<div class="clearfix"></div>
									</div>	
									
									<div class="control-group">
										<label class="control-label"><span class="tip icon-question-sign help" title="<?php echo $tools_butcolorp; ?>"></span><?php echo $tools_butcolor; ?>:</label>
										<div class="controls" id="pbutton">	
											<select name="kw_flycart_pbutton">
														<option disabled><?php echo $tools_choose; ?></option>
												<?php foreach ($popup_button as $pbutton) { ?>
													<?php if ($kw_flycart_pbutton == $pbutton['name']) { ?>
														<option value="<?php echo $pbutton['name']; ?>" selected="selected"><?php echo $pbutton['title']; ?></option>
													<?php } else { ?>
														<option value="<?php echo $pbutton['name']; ?>"><?php echo $pbutton['title']; ?></option>
													<?php } ?>
												<?php } ?>
											</select>
										</div>
										<div class="clearfix"></div>
									</div>		
								</div>	
							</div>
						</div>
					</div>
<!-- fly -->					
					<div class="tab-pane" id="fly">
						<div class="row-fluid">	
							<div class="control-group">
								<label class="control-label"><span class="tip icon-question-sign help" title="<?php echo $tools_tselecth; ?>"></span><?php echo $tools_tselect; ?>:</label>
								<div class="controls" id="flytype">	
									<select name="kw_flycart_flytype">
											<option disabled><?php echo $tools_choose; ?></option>
										<?php foreach ($cart_flytype as $flytype) { ?>
										<?php if ($kw_flycart_flytype == $flytype['name']) { ?>
											<option value="<?php echo $flytype['name']; ?>" selected="selected"><?php echo $flytype['title']; ?></option>
											<?php } else { ?>
											<option value="<?php echo $flytype['name']; ?>"><?php echo $flytype['title']; ?></option>
										<?php } ?>
										<?php } ?>
									</select>
								</div>
								<div class="clearfix"></div>
							</div>
							
							<div id="fly_image">
								<div class="control-group pat">
									<label class="control-label"><span class="tip icon-question-sign help" title="<?php echo $tools_vimgh; ?>"></span><?php echo $tools_img; ?>:</label>
									<div class="controls">
										<div class="size_pre">
											<a class="opener"><?php echo $tools_open; ?>  <i class="icon-arrow-down"></i></a>
											<a class="closer"><?php echo $tools_close; ?>  <i class="icon-arrow-up"></i></a>
										</div>							
										<div class="pat_pre">
											<div class="patern-button" style="background-image:url(/image/cart/<?php echo isset(${'kw_flycart_flyimage'}) ? ${'kw_flycart_flyimage'} : ''; ?>)"></div>
										</div>
									</div>
									<div class="clearfix"></div>
								</div>
									
								<div class="pattern-container">
									<table class="table table-bordered">
										<tr>
											<td>
												<div class="pattern-body"><ul class="pattern"></ul></div>
												<input class="pattern_bg" name="kw_flycart_flyimage" type="hidden" value="<?php echo isset(${'kw_flycart_flyimage'}) ? ${'kw_flycart_flyimage'} : ''; ?>" />
											</td>
											<tr>
												<td>
													<div class="btn-group">
														<a class="upload btn btn-sample"><?php echo $tools_upload; ?>  <i class="icon-download icon-white"></i></a>
														<a class="delete btn btn-sample"><?php echo $tools_delete; ?>  <i class="icon-remove-circle icon-white"></i></a>
													</div>
												</td>
											</tr>
										</tr>			
									</table>
									<div class="clearfix"></div>
								</div>
							</div>	

							<div class="control-group">
								<label class="control-label"><span class="tip icon-question-sign help" title="<?php echo $tools_fsh; ?>"></span><?php echo $tools_fs; ?>:</label>
								<div class="controls" id="frselect">	
									<select name="kw_flycart_frselect">
											<option disabled><?php echo $tools_choose; ?></option>
										<?php foreach ($cart_frselect as $frselect) { ?>
										<?php if ($kw_flycart_frselect == $frselect['name']) { ?>
											<option value="<?php echo $frselect['name']; ?>" selected="selected"><?php echo $frselect['title']; ?></option>
											<?php } else { ?>
											<option value="<?php echo $frselect['name']; ?>"><?php echo $frselect['title']; ?></option>
										<?php } ?>
										<?php } ?>
									</select>
								</div>
								<div class="clearfix"></div>
							</div>
									
							<div class="control-group color" id="color_f">
								<label class="control-label"><span class="tip icon-question-sign help" title="<?php echo $tools_fch; ?>"></span><?php echo $tools_fc; ?>:</label>
								<div class="controls">
									<input class="top_bg" name="kw_flycart_color_f" type="text" value="<?php echo isset(${'kw_flycart_color_f'}) ? ${'kw_flycart_color_f'} : '606060'; ?>" maxlength="6" />
									<div class="top_bg_sel">
										<div style="background-color:#<?php echo isset(${'kw_flycart_color_f'}) ? ${'kw_flycart_color_f'} : '606060'; ?>"></div>
									</div>
								</div>
								<div class="clearfix"></div>
							</div>	

							<div class="control-group" id="size_f">
								<label class="control-label"><span class="tip icon-question-sign help" title="<?php echo $tools_fsizeh; ?>"></span><?php echo $tools_fsize; ?>:</label>
								<div class="controls">
									<input type="text" name="kw_flycart_size_f" value="<?php echo isset(${'kw_flycart_size_f'}) ? ${'kw_flycart_size_f'} : '3'; ?>" />
								</div>
								<div class="clearfix"></div>
							</div>					
									
							<div class="control-group" id="speed">
								<label class="control-label"><span class="tip icon-question-sign help" title="<?php echo $tools_animh; ?>"></span><?php echo $tools_anim; ?>:</label>
								<div class="controls">
									<input type="text" name="kw_flycart_speed" value="<?php echo isset(${'kw_flycart_speed'}) ? ${'kw_flycart_speed'} : '700'; ?>" />
								</div>
								<div class="clearfix"></div>
							</div>
					
							<div class="control-group">
								<label class="control-label"><span class="tip icon-question-sign help" title="<?php echo $tools_rotateh; ?>"></span><?php echo $tools_rotate; ?>:</label>
								<div class="controls" id="rtselect">	
									<select name="kw_flycart_rtselect">
											<option disabled><?php echo $tools_choose; ?></option>
										<?php foreach ($cart_rtselect as $rtselect) { ?>
										<?php if ($kw_flycart_rtselect == $rtselect['name']) { ?>
											<option value="<?php echo $rtselect['name']; ?>" selected="selected"><?php echo $rtselect['title']; ?></option>
											<?php } else { ?>
											<option value="<?php echo $rtselect['name']; ?>"><?php echo $rtselect['title']; ?></option>
										<?php } ?>
										<?php } ?>
									</select>
								</div>
								<div class="clearfix"></div>
							</div>

							<div class="control-group" id="radius">
								<label class="control-label"><span class="tip icon-question-sign help" title="<?php echo $tools_radiush; ?>"></span><?php echo $tools_radius; ?>:</label>
								<div class="controls">
									<input type="text" name="kw_flycart_radius" value="<?php echo isset(${'kw_flycart_radius'}) ? ${'kw_flycart_radius'} : '5px'; ?>" />
								</div>
								<div class="clearfix"></div>
							</div>
						</div>
					</div>
<!-- module -->					
					<div class="tab-pane" id="module_tools">
						<table id="module" class="table table-bordered">
							<thead>
								<tr>
									<th class="left"><?php echo $entry_layout; ?></th>
									<th class="left"><?php echo $entry_position; ?></th>
									<th class="left"><?php echo $entry_status; ?></th>
									<th class="right"><?php echo $entry_sort_order; ?></th>
									<th></th>
								</tr>
							</thead>
							<?php $module_row = 0; ?>
							<?php foreach ($modules as $module) { ?>
							<tbody id="module-row<?php echo $module_row; ?>">
								<tr>
									<td class="left">
										<select name="kw_flycart_module[<?php echo $module_row; ?>][layout_id]">
											<option disabled>Выбрать</option>
											<?php foreach ($layouts as $layout) { ?>
											<?php if ($layout['layout_id'] == $module['layout_id']) { ?>
											<option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
											<?php } else { ?>
											<option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
											<?php } ?>
											<?php } ?>
										</select>
									</td>
									<td class="left">
										<select name="kw_flycart_module[<?php echo $module_row; ?>][position]">
											<option disabled>Выбрать</option>
											<?php if ($module['position'] == 'content_top') { ?>
											<option value="content_top" selected="selected"><?php echo $text_content_top; ?></option>
											<?php } else { ?>
											<option value="content_top"><?php echo $text_content_top; ?></option>
											<?php } ?>
											<?php if ($module['position'] == 'content_bottom') { ?>
											<option value="content_bottom" selected="selected"><?php echo $text_content_bottom; ?></option>
											<?php } else { ?>
											<option value="content_bottom"><?php echo $text_content_bottom; ?></option>
											<?php } ?>
											<?php if ($module['position'] == 'column_left') { ?>
											<option value="column_left" selected="selected"><?php echo $text_column_left; ?></option>
											<?php } else { ?>
											<option value="column_left"><?php echo $text_column_left; ?></option>
											<?php } ?>
											<?php if ($module['position'] == 'column_right') { ?>
											<option value="column_right" selected="selected"><?php echo $text_column_right; ?></option>
											<?php } else { ?>
											<option value="column_right"><?php echo $text_column_right; ?></option>
											<?php } ?>
										</select>
									</td>
									<td class="left">
										<select name="kw_flycart_module[<?php echo $module_row; ?>][status]">
											<option disabled>Выбрать</option>
											<?php if ($module['status']) { ?>
											<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
											<option value="0"><?php echo $text_disabled; ?></option>
											<?php } else { ?>
											<option value="1"><?php echo $text_enabled; ?></option>
											<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
											<?php } ?>
										</select>
									</td>
									<td class="right"><input type="text" name="kw_flycart_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" /></td>
									<td class="left"><a onclick="$('#module-row<?php echo $module_row; ?>').remove();" class="btn btn-sample"><?php echo $button_remove; ?></a></td>
								</tr>
							</tbody>
							<?php $module_row++; ?>
							<?php } ?>
							<tfoot>
								<tr>
									<td colspan="4"></td>
									<td class="left"><a onclick="addModule();" class="btn btn-sample"><?php echo $button_add_module; ?></a></td>
								</tr>
							</tfoot>
						</table>	
					</div>
				</div>
			</div>	
		</form>	
	</div>
	<div class="clearfix"></div>
	<div class="foot"></div>
</div>
<script>
    $('#myTab a,#myTab1 a,#myTab2 a').click(function(e){
		e.preventDefault();
		$(this).tab('show');
    });

	$('.apply').click(function() {
		$(this).addClass('active');
	});		
	
	function apply(){
		$.ajax({
			type: 'POST',
			url: $('#form').attr('action'),
			data: $('#form').serialize(),
			dataType: 'text',
			success: function(data){
				if($(data).find('.error').length > 0){
				    $("#form").submit();
				}else{
				    $('.success,.warning').remove();
					$('.apply').removeClass('active');
				    $(data).find('.success,.warning').clone().appendTo('div.hero-unit').css('margin-top','100px');
				        setTimeout(function(){$('.success,.warning').remove()},5000);
				}
			}
		});
	};
</script>

<script type="text/javascript"><!--
var module_row = <?php echo $module_row; ?>;

function addModule() {	
	html  = '<tbody id="module-row' + module_row + '">';
	html += '  <tr>';
	html += '    <td class="left"><select name="kw_flycart_module[' + module_row + '][layout_id]">';
	<?php foreach ($layouts as $layout) { ?>
	html += '      <option value="<?php echo $layout['layout_id']; ?>"><?php echo addslashes($layout['name']); ?></option>';
	<?php } ?>
	html += '    </select></td>';
	html += '    <td class="left"><select name="kw_flycart_module[' + module_row + '][position]">';
	html += '      <option value="content_top"><?php echo $text_content_top; ?></option>';
	html += '      <option value="content_bottom"><?php echo $text_content_bottom; ?></option>';
	html += '      <option value="column_left"><?php echo $text_column_left; ?></option>';
	html += '      <option value="column_right"><?php echo $text_column_right; ?></option>';
	html += '    </select></td>';
	html += '    <td class="left"><select name="kw_flycart_module[' + module_row + '][status]">';
    html += '      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
    html += '      <option value="0"><?php echo $text_disabled; ?></option>';
    html += '    </select></td>';
	html += '    <td class="right"><input type="text" name="kw_flycart_module[' + module_row + '][sort_order]" value="" size="3" /></td>';
	html += '    <td class="left"><a onclick="$(\'#module-row' + module_row + '\').remove();" class="btn btn-sample"><?php echo $button_remove; ?></a></td>';
	html += '  </tr>';
	html += '</tbody>';
	
	$('#module tfoot').before(html);
	$(".tip").tooltip();
	$('select').selectbox();
	
	module_row++;
}
//--></script> 

<?php echo $footer; ?>