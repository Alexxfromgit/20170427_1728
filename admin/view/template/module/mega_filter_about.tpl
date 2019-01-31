<?php require DIR_TEMPLATE . 'module/' . $_name . '-header.tpl'; ?>

<table class="form">
	<tr>
		<td><?php echo $entry_ext_version; ?></td>
		<td><?php echo $ext_version; ?></td>
	</tr>
	<?php if( ! empty( $plus_version ) ) { ?>
		<tr>
			<td><?php echo $entry_plus_version; ?></td>
			<td>
				<?php echo $plus_version; ?>
				-
				<a href="<?php echo $action_rebuild_index; ?>" class="btn btn-xs btn-info"><i class="glyphicon glyphicon-retweet"></i> <?php echo $text_rebuild_index; ?></a>
			</td>
		</tr>
	<?php } ?>
	<tr>
		<td><?php echo $entry_author; ?></td>
		<td><?php echo $ext_author; ?></td>
	</tr>
</table>
			
<?php require DIR_TEMPLATE . 'module/' . $_name . '-footer.tpl'; ?>