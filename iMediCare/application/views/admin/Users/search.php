
		<!--search section-->
		<div class="box box-solid" style="margin-bottom: 5px;">
			<div class="box-body">
				<div class="row" >
					<div class="col-md-4">
						<div class="form-group">
							<label>User name</label>
							<input id="src_userName" name="src_userName" value="<?php echo $src_userName ?>" type="text" class="form-control" />
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label>User group</label>
							<select class="form-control selectpicker" id="src_userGroupId" name="src_userGroupId" data-live-search="true">
								<option value="0" <?php echo $src_userGroupId == 0 ? 'selected' : '' ?> >All groups</option>
								<?php foreach ($user_groups->result() as $value) { ?>
									<option value="<?php echo $value->user_group_Id ?>" <?php echo $src_userGroupId == $value->user_group_Id ? 'selected' : '' ?>><?php echo $value->user_group_name ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="col-md-4 text-right">
						<div class="form-group" style="padding-top: 25px;">
							<a data-control="searchClick" class="btn btn-info">Search details</a>
						</div>
					</div>
				</div>
			</div>
		</div>

<script>
	
	$('.selectpicker').selectpicker('refresh');
	

</script>

















