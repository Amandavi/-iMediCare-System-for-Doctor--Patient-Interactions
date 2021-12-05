<div style="width: <?php echo $gridWidth ?>">
	<table id="master_grid" class="table table-bordered table-striped">
		<thead>
			<tr class="<?php echo $this->config->item('grid_header'); ?>">
				<th style="width: 15%"></th>
				<?php $i=0;  
					foreach ($dataList->list_fields() as $field) {if($field != 'id' && $field != 'is_active'){$i++;}} 
					$thWidth = (100-15)/$i; ?>
					<?php  foreach ($dataList->list_fields() as $field) { if($field != 'id' && $field != 'is_active'){ ?>
						<th style="width: <?php echo $thWidth.'%' ?>" >
							<?php echo str_replace("_"," ",$field) ?>
						</th>
					<?php }} ?>
			</tr>
		</thead>
		<tbody>
		<?php   foreach ($dataList->result_array() as $value) { ?>
			<?php $id = $value['id']; $encryption_id = nbit_encode($id); $key = urlencode(base64_encode($route_common));  ?>
			<tr>
				<td class="text-right">
					<div class="center">
						<?php require(APPPATH . "views/admin/Z_Pages/gridButtons.php"); ?>
					</div>
				</td>
				
				<?php foreach ($dataList->list_fields() as $field) { if($field != 'id' && $field != 'is_active'){ ?>
					<td style="width: <?php echo $thWidth.'%' ?>" >
						<?php echo $value[$field] ?>
					</td>
				<?php }} ?>
			</tr>			
		<?php  } ?>		
		</tbody>
	</table>
</div>

<?php 

	function nbit_encode($string) {
		
		$string_new = '!g'.$string.'g!'; 
	
		$entities = array('%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F',  '%23', '%5B', '%5D');
		$replacements = array('!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",", "/", "?",  "#", "[", "]");
		return str_replace($replacements,$entities,urlencode(base64_encode($string_new))) ; //str_replace($entities, $replacements,
	}

?>

<script>
	$('[data-toggle="tooltip"]').tooltip();
</script>







