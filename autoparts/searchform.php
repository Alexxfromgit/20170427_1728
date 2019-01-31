<?=Lng('Part_number',1,0)?>: 
<input type="text" id="artnum" value="<?=$_REQUEST['article']?>" maxlength="40" class="tdsform" placeholder="<?=Lng('Example',2,0)?>: CT637"> 
<input type="submit" value="<?=Lng('Search_button',0,0)?>" class="tdsbut" onclick="TDMArtSearch()">
<script type="text/javascript">
function TDMArtSearch(){
	var art = $('#artnum').val();
	if(art!=''){
		art = art.replace(/[^a-zA-Z0-9.-]+/g, '');
		location = '/<?=TDM_ROOT_DIR?>/search/'+art+'/';
	}
}
$('#artnum').keypress(function (e){
  if(e.which == 13){ TDMArtSearch(); return false;}
});
</script>