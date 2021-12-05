
			
            <section class="content-header">
                <h1>
                   User
                    <small> 
						<?php echo $_id > 0 ? 'Update' : '' ?> 
						<?php echo $_id == 0 ? 'Create new' : '' ?>
					</small>
                </h1>
                <?php require_once(APPPATH . "views/admin/breadcrumb.php"); ?>
            </section>

            <section class="content">
				<div class="row">
					<div class="col-xs-12">
						<div class="box box-solid" style="margin-bottom: 5px;">
							<div class="box-body">
								<div class="row"> <!-- Form Buttons   -->
									<div class="col-md-1">
										<a data-control="backButtonClick" class=" btn btn-info"><i class="fa fa-angle-left"></i> Go back</a>
									</div>  
									<div class="col-md-8">
										<?php if (validation_errors()) { ?>
											<div class="callout <?php echo $this->config->item('msg_error'); ?> error-msg">
												<div>

													<p><i class="icon fa fa-warning"></i>There is a problem that we need to fix.</p>

												</div>
											</div>
										<?php } ?>
									</div>
									<div class="col-md-3 text-right">

										<a href="#" data-control="saveButtonClick" class=" btn btn-success"><?php echo $_id >0 ? 'Update details' : 'Save details' ?></a>

									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				 
				
				
				<div class="row">
                        
                        <div class="col-xs-12">
                            <div class="box box-info">
                                <div class="box-header">
                                    <h3 class="box-title">General Informations</h3>
                                </div><!-- /.box-header -->
                                <div class="row">
                                    <div class="col-md-1"></div>
                                    <div class="col-md-5">

                                        <div class="box-body">
                                            <!-- text input -->
                                            <div class="form-group <?php echo form_error("UserName") ? "has-error" : "" ?>">
                                                <label>User Name</label>
                                                <input id="UserName" name="UserName" type="text"   value="<?php echo $user_name_val; ?>" type="text" class="form-control" placeholder="Enter ..."/>
                                                <label  ><small><?php echo form_error("UserName"); ?></small></label>
                                            </div>
                                            <div class="form-group <?php echo form_error("FullName") ? "has-error" : "" ?>">
                                                <label>Full name</label>
                                                <input id="FullName" name="FullName" type="text"   value="<?php echo $full_name_val; ?>"type="text" class="form-control" placeholder="Enter ..."/>
                                                <label  ><small><?php echo form_error("FullName"); ?></small></label>
                                            </div>
                                            <div class="form-group <?php echo form_error("Email") ? "has-error" : "" ?>">
                                                <label>Email</label>
                                                <input id="Email" name="Email" type="text"   value="<?php echo $email_val; ?>" type="email" class="form-control" placeholder="Enter ..."/>
                                                <label  ><small><?php echo form_error("Email"); ?></small></label>
                                            </div>
                                            <div class="form-group <?php echo form_error("PhoneNo") ? "has-error" : "" ?>">
                                                <label>Phone number</label>
                                                <input id="PhoneNo" name="PhoneNo" type="text"  value="<?php echo $phone_no_val; ?>" type="number" class="form-control" placeholder="Enter ..."/>
                                                <label  ><small><?php echo form_error("PhoneNo"); ?></small></label>
                                            </div>

                                            <!-- textarea -->
                                            <div class="form-group <?php echo form_error("Description") ? "has-error" : "" ?>">
                                                <label>Description</label>
                                                <textarea id="Description" name="Description" type="text"   class="form-control" rows="3" placeholder="Enter ..."><?php echo $description_val; ?></textarea>
                                                <label  ><small><?php echo form_error("Description"); ?></small></label>
                                            </div>
                                        </div>


                                    </div>
                                    <div class="col-md-5">

                                        <div class="box-body">


                                            <!-- text input -->
                                            <?php if ($user_id_val == 0) { ?>
                                                <div class="form-group <?php echo form_error("Password") ? "has-error" : "" ?>">
                                                    <label>Password</label>
                                                    <input id="Password" name="Password" type="password"    type="text" class="form-control" placeholder="Enter ..."/>
                                                    <label  ><small><?php echo form_error("Password"); ?></small></label>
                                                </div>
                                                <div class="form-group <?php echo form_error("ConfirmPassword") ? "has-error" : "" ?>">
                                                    <label>Confirm Password</label>
                                                    <input id="ConfirmPassword" name="ConfirmPassword" type="password"  type="text" class="form-control" placeholder="Enter ..."/>
                                                    <label  ><small><?php echo form_error("ConfirmPassword"); ?></small></label>
                                                </div>
                                            <?php } ?>
                                            <div class="form-group <?php echo form_error("UserGroupId") ? "has-error" : "" ?>">
                                                <label>User group</label>
                                                <select class="form-control selectpicker" id="UserGroupId" name="UserGroupId" data-live-search="true">
													
													<option value="0" <?php echo $user_group_Id_val == 0 ? 'selected' : '' ?> >Select group</option>
													<?php foreach ($user_groups->result() as $value) { ?>
														<option value="<?php echo $value->user_group_Id ?>" <?php echo $user_group_Id_val == $value->user_group_Id ? 'selected' : '' ?>><?php echo $value->user_group_name ?></option>
													<?php } ?>
													
                                                </select>
                                                <label  ><small><?php echo form_error("UserGroupId"); ?></small></label>
                                            </div>
											<div class="form-group generic <?php echo form_error("branch_id") ? "has-error" : "" ?>">
												<label>Branch</label>

												<select class="form-control txtBx_md " id="branch_id" name="branch_id[]" multiple="branch_id"  data-live-search="true"> 

													<?php foreach ($branches->result() as $value) { ?>

														<option  value="<?php echo $value->branch_id ?>" 

														 <?php if($branch_id != null && in_array($value->branch_id, $branch_id)){ echo "selected"; } ?>

															><?php echo $value->Name ?></option>

													<?php } ?>

												</select>

												<label><small><?php echo form_error("branch_id"); ?></small></label>
											</div>
											
                                            <input  type="hidden" runat="server"  id="user_Id" name="user_Id" value=" <?php echo $user_id_val; ?> ">

                                        </div>
                                    </div>
                                </div>
                            </div>
							
                            <div class="box box-info">
                                <div class="box-header">
                                    <h3 class="box-title">Image</h3>
                                </div><!-- /.box-header -->
                                <div class="row">
                                    <div class="col-md-1"></div>
                                    <div class="col-md-4">
                                        <div class="box-body">
                                        	<div class="form-group">
											  	<label class="file-control">
													<input data-control="logoUploader" type="file" id="fileUploader" name="fileUploader" size="60" >
													<div class="dv-image">
														<img class="result-image" id="result" name="result" src="
														<?php echo ($user_id_val > 0 ? $image_path_val : base_url('dist/img/user.jpg') ) ?>">
														<div class="dv-hover center">
															<i class="fa fa-image"></i>
														</div>
													</div>
												</label> 
											</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div id="crop-image" class="box-body"></div>
                                       <input hidden="hidden" type="text" runat="server"  id="image_path" name="image_path" value=" <?php echo $image_path_val; ?> " class="txtBx">
                                    </div>
                                </div>
                            </div>
                        </div>                      
                        
                    </div>
				 
            </section>

			<input  type="hidden" id="_id" name="_id" value="<?php echo $_id ?>">

			<!--search details-->
			<div class="hide">
				<?php require_once(APPPATH . "views/admin/".$falder_name."/search.php"); ?>
			</div>
			
<!--don't delete or change-->
<script type="text/javascript">

    $(document).ready(function () {
				
		$('#branch_id').select2({
		  placeholder: 'All generics',
		  allowClear: true
		});
		
		$('#selected_id').val('<?php echo $selected_id ?>');
		
    });
	
</script>









