
	<section class="content-header">
		<h1>
		   User
			<small> it all starts here </small>
		</h1>
		<?php require_once(APPPATH . "views/admin/breadcrumb.php"); ?>
	</section>

	<section class="content">

		<!--buttons section-->
		<div class="box box-solid" style="margin-bottom: 10px;">
			<div class="box-body">
				<div class="row" >
					<div class="col-md-6" id="errorMsg"> </div>
					<div class="col-md-6 text-right">
						<div class=" btn-group btn-group-justified">
							<a href="#" class="btn btn-default" data-control="allCheck"> <i class="fa fa-check"></i> All</a>
							<a href="#" class="btn btn-default" data-control="allUnCheck"> <i class="fa fa-close"></i> None</a>
							<a href="#" class="btn btn-default" data-control="generate_exel"> <i class="fa fa-file-excel-o"></i>Exel</a>
							<a href="#" class="btn btn-default" data-control="generate_pdf"> <i class="fa fa-save"></i> Print </a>

							<?php if($is_createAvailable == 1){ ?> 
							<a data-control="createNew" class="btn btn-primary" id="new" >Create New </a>
							<?php } ?>

						</div>
					</div>
				</div>
			</div>
		</div>

		<!--search section-->
		<?php require_once(APPPATH . "views/admin/".$falder_name."/search.php"); ?>

		<!--grid section-->
		<div class="box box-info">
			<div class="box-body scrollGridDv" id="gridHtml">
				<?php require_once(APPPATH . "views/admin/".$falder_name."/grid.php"); ?>
			</div>
		</div>

	</section>	

	<input  type="hidden" id="formName" name="formName" value="<?php echo $route_common ?>">
	<input  type="hidden" id="selectedRows" name="selectedRows" value="">


<!--don't delete or change-->
<script>

	setTimeout(function(){
		
		$('#selected_id').val('<?php echo $selected_id ?>');
		
		var menu_ids = $('#menu_ids').val();
		var selected_id = $('#selected_id').val();
		
		var newurl = '<?php echo base_url($route_common.'-refresh/').$route_common.'-list/' ?>'+menu_ids+'/'+selected_id;
		window.history.pushState({path:newurl},'',newurl);
		
	},100);
		

</script>







