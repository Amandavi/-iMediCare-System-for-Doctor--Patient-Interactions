<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		
        <?php require_once(APPPATH . "views/admin/header.php"); ?>
		
    </head>
    <body id="top" data-spy="scroll" data-target=".navbar-collapse" data-offset="50">
		
	
	<form action="<?php echo base_url('profile-sumbit') ?>" method="post" id="submitForm" name="submitForm">
      
		<?php require_once(APPPATH . "views/admin/navBar.php"); ?>
		


     <!-- MAKE AN APPOINTMENT -->
     <section id="appointment" data-stellar-background-ratio="3">
          <div class="container">
               <div class="row">
                    <div class="col-md-1 col-sm-1"></div>
                    <div class="col-md-3 col-sm-3">
                         <!--<img src="<?php echo base_url('Images/appointment.png') ?>" class="img-responsive" alt="" style="margin-left: 70px;width: 70%;">-->
						
						<div class="team-thumb">
							<div id="profileImage">
								<img src="<?php echo $is_new_image == 1 ? $image_path : base_url($image_path) ?>" class="img-responsive w-100" alt="">
							</div>
						  

							   <div class="team-info mb-px-15">
									<label class="file-control" style="width: 100%;text-align: center;">

										<input class="hide" id="_modalDocuments" type="file" name="_modalDocuments" data-control="modalFileUploader" size="60" accept=".png,.jpeg,.jpg" >
										<a class="btn btn-info" ><i class="fa fa-picture-o mr-px-10"></i>Edit image</a>

									</label>
							   </div>

						</div>
							
							<div>
								   <br>
									<button type="submit" class="form-control" id="cf-submit" name="submit">UPDATE DETAILS</button>
								   <br><br><br>
							   </div>
						
                    </div>
                    <div class="col-md-7 col-sm-7 mb-px-100">
						  <!-- SECTION TITLE -->
						  <div class="section-title wow row" data-wow-delay="0.4s">
								<div class="col-md-12 col-sm-12">
									<h2>Profile</h2>
								</div>
						  </div>
						<?php if($user_type == 'user'){ ?> 
						  <div class="wow row" data-wow-delay="0.8s">
							   <div class="col-md-6 col-sm-6">
								   <div class="form-items <?php echo form_error("name") ? 'error' : "" ?>">
										<label for="name">Name</label>
										<input type="text" class="form-control" id="name" name="name" value="<?php echo $name ?>" placeholder="Name">
										<?php echo form_error("name") ? form_error("name") : "" ?>
									</div>
							   </div>
							   <div class="col-md-6 col-sm-6">
								   <div class="form-items <?php echo form_error("phone_no") ? 'error' : "" ?>">
										<label for="phone_no">Phone no</label>
										<input type="text" class="form-control" id="phone_no" name="phone_no" value="<?php echo $phone_no ?>" placeholder="Phone no">
										<?php echo form_error("phone_no") ? form_error("phone_no") : "" ?>
									</div>
							   </div>
						  </div>
						  <div class="wow row" data-wow-delay="0.8s">
							   <div class="col-md-6 col-sm-6">
								   <div class="form-items <?php echo form_error("email") ? 'error' : "" ?>">
										<label for="email">Email</label>
										<input type="email" class="form-control" id="email" name="email" value="<?php echo $email ?>" placeholder="Email">
										<?php echo form_error("email") ? form_error("email") : "" ?>
									</div>
							   </div>
						  </div>
						
						<?php } if($user_type == 'patient'){ ?> 
							<div class="row">
							   <div class="col-md-3 col-sm-3">
								   <div class="form-items ddl <?php echo form_error("title") ? 'error' : "" ?>">
										<label for="title">Title</label>
										<select class="form-control selectpicker" id="title" name="title">
									   		<option value="Mr." <?php echo $title == 'Mr.' ? 'selected' : '' ?>>Mr.</option>
									   		<option value="Mrs." <?php echo $title == 'Mrs.' ? 'selected' : '' ?>>Mrs.</option>
									   		<option value="Miss" <?php echo $title == 'Miss' ? 'selected' : '' ?>>Miss</option>
										</select>
										<?php echo form_error("title") ? form_error("title") : "" ?>
									</div>
							   </div>
							   <div class="col-md-3 col-sm-3">
								   <div class="form-items ddl <?php echo form_error("gender") ? 'error' : "" ?>">
										<label for="gender">Gender</label>
										<select class="form-control selectpicker" id="gender" name="gender">
									   		<option value="Male" <?php echo $gender == 'Male' ? 'selected' : '' ?>>Male</option>
									   		<option value="Female" <?php echo $gender == 'Female' ? 'selected' : '' ?>>Female</option>
									   		<option value="Other" <?php echo $gender == 'Other' ? 'selected' : '' ?>>Other</option>
										</select>
										<?php echo form_error("gender") ? form_error("gender") : "" ?>
									</div>
							   </div>
							   <div class="col-md-6 col-sm-6">
								   <div class="form-items <?php echo form_error("name") ? 'error' : "" ?>">
										<label for="name">Name</label>
										<input type="text" class="form-control" id="name" name="name" value="<?php echo $name ?>" placeholder="Name">
										<?php echo form_error("name") ? form_error("name") : "" ?>
									</div>
							   </div>
						  </div>
						  <div class="row">
							   <div class="col-md-6 col-sm-6">
								   <div class="form-items <?php echo form_error("email") ? 'error' : "" ?>">
										<label for="email">Email</label>
										<input type="email" class="form-control" id="email" name="email" value="<?php echo $email ?>" placeholder="Email">
										<?php echo form_error("email") ? form_error("email") : "" ?>
									</div>
							   </div>
							   <div class="col-md-6 col-sm-6">
								   <div class="form-items <?php echo form_error("phone_no") ? 'error' : "" ?>">
										<label for="phone_no">Phone no</label>
										<input type="text" class="form-control" id="phone_no" name="phone_no" value="<?php echo $phone_no ?>" placeholder="Phone no">
										<?php echo form_error("phone_no") ? form_error("phone_no") : "" ?>
									</div>
							   </div>
						  </div>
						  <div class="row">
							   <div class="col-md-6 col-sm-6">
								   <div class="form-items <?php echo form_error("nic") ? 'error' : "" ?>">
										<label for="nic">NIC no</label>
										<input type="nic" class="form-control" id="nic" name="nic" value="<?php echo $nic ?>" placeholder="NIC">
										<?php echo form_error("nic") ? form_error("nic") : "" ?>
									</div>
							   </div>
							   <div class="col-md-6 col-sm-6">
								   <div class="form-items <?php echo form_error("address") ? 'error' : "" ?>">
										<label for="address">Address</label>
										<textarea class="form-control" rows="2" id="address" name="address" placeholder="Address"><?php echo $address ?></textarea>
										<?php echo form_error("address") ? form_error("address") : "" ?>
									</div>
							   </div>
						  </div>
						  <div class="row">
							  <div class="col-md-6 col-sm-6">
								   <div class="form-items <?php echo form_error("birth_day") ? 'error' : "" ?>">
										<label for="birth_day">Birth day</label>
										<input type="date" class="form-control" id="birth_day" name="birth_day" value="<?php echo $birth_day ?>" placeholder="Phone no">
										<?php echo form_error("birth_day") ? form_error("birth_day") : "" ?>
									</div>
								</div>
						  </div>
						
						<?php } if($user_type == 'doctor'){ ?> 
							<div class="row">
							   <div class="col-md-3 col-sm-3">
								   <div class="form-items ddl <?php echo form_error("title") ? 'error' : "" ?>">
										<label for="title">Title</label>
										<select class="form-control selectpicker" id="title" name="title">
									   		<option value="Mr." <?php echo $title == 'Mr.' ? 'selected' : '' ?>>Mr.</option>
									   		<option value="Mrs." <?php echo $title == 'Mrs.' ? 'selected' : '' ?>>Mrs.</option>
									   		<option value="Miss" <?php echo $title == 'Miss' ? 'selected' : '' ?>>Miss</option>
										</select>
										<?php echo form_error("title") ? form_error("title") : "" ?>
									</div>
							   </div>
							   <div class="col-md-3 col-sm-3">
								   <div class="form-items ddl <?php echo form_error("gender") ? 'error' : "" ?>">
										<label for="gender">Gender</label>
										<select class="form-control selectpicker" id="gender" name="gender">
									   		<option value="Male" <?php echo $gender == 'Male' ? 'selected' : '' ?>>Male</option>
									   		<option value="Female" <?php echo $gender == 'Female' ? 'selected' : '' ?>>Female</option>
									   		<option value="Other" <?php echo $gender == 'Other' ? 'selected' : '' ?>>Other</option>
										</select>
										<?php echo form_error("gender") ? form_error("gender") : "" ?>
									</div>
							   </div>
							   <div class="col-md-6 col-sm-6">
								   <div class="form-items <?php echo form_error("name") ? 'error' : "" ?>">
										<label for="name">Name</label>
										<input type="text" class="form-control" id="name" name="name" value="<?php echo $name ?>" placeholder="Name">
										<?php echo form_error("name") ? form_error("name") : "" ?>
									</div>
							   </div>
						  </div>
						  <div class="row">
							   <div class="col-md-6 col-sm-6">
								   <div class="form-items <?php echo form_error("email") ? 'error' : "" ?>">
										<label for="email">Email</label>
										<input type="email" class="form-control" id="email" name="email" value="<?php echo $email ?>" placeholder="Email">
										<?php echo form_error("email") ? form_error("email") : "" ?>
									</div>
							   </div>
							   <div class="col-md-6 col-sm-6">
								   <div class="form-items <?php echo form_error("phone_no") ? 'error' : "" ?>">
										<label for="phone_no">Phone no</label>
										<input type="text" class="form-control" id="phone_no" name="phone_no" value="<?php echo $phone_no ?>" placeholder="Phone no">
										<?php echo form_error("phone_no") ? form_error("phone_no") : "" ?>
									</div>
							   </div>
						  </div>
						  <div class="row">
							   <div class="col-md-6 col-sm-6">
								   <div class="form-items <?php echo form_error("nic") ? 'error' : "" ?>">
										<label for="nic">NIC no</label>
										<input type="nic" class="form-control" id="nic" name="nic" value="<?php echo $nic ?>" placeholder="NIC">
										<?php echo form_error("nic") ? form_error("nic") : "" ?>
									</div>
							   </div>
							   <div class="col-md-6 col-sm-6">
								   <div class="form-items <?php echo form_error("address") ? 'error' : "" ?>">
										<label for="address">Address</label>
										<textarea class="form-control" rows="2" id="address" name="address" placeholder="Address"><?php echo $address ?></textarea>
										<?php echo form_error("address") ? form_error("address") : "" ?>
									</div>
							   </div>
						  </div>
						  <div class="row">
							   <div class="col-md-6 col-sm-6">
								   <div class="form-items ddl <?php echo form_error("specialty_id") ? 'error' : "" ?>">
										<label for="specialty_id">Doctor specialty</label>
										<select class="form-control selectpicker" id="specialty_id" name="specialty_id" data-live-search="true">
									   		<option value="0" <?php echo $specialty_id == '0' ? 'selected' : '' ?>>Select</option>
											<?php foreach ($specialty->result() as $value) { ?>
												<option value="<?php echo $value->specialty ?>" <?php echo $specialty_id == $value->specialty ? 'selected' : '' ?>><?php echo $value->specialty ?></option>
											<?php } ?>
										</select>
										<?php echo form_error("specialty_id") ? form_error("specialty_id") : "" ?>
									</div>
							   </div>
							   <div class="col-md-6 col-sm-6">
								   <div class="form-items <?php echo form_error("fee") ? 'error' : "" ?>">
										<label for="fee">Channelling fee (LKR)</label>
										<input type="fee" class="form-control" id="fee" name="fee" value="<?php echo $channelling_fee ?>" placeholder="0.00">
										<?php echo form_error("fee") ? form_error("fee") : "" ?>
									</div>
							   </div>
						  </div>
						  <div class="row mt-px-30">
								<div class="col-md-12 col-sm-12">
									<h2 class="admin_topic" style="font-size: 23px;">Doctor availability</h2>
								</div>
						  </div>
						  <div class="row">
							   <div class="col-md-3 col-sm-3">
								   <div class="form-items <?php echo form_error("avlMonday") ? 'error' : "" ?>">
										<label ><input type="checkbox" style="height: auto;margin-right: 5px;" id="chkMonday" name="chkMonday" value="1" <?php echo $chkMonday == 1 ? 'checked' : '' ?>> Monday</label>
										<input type="text" class="form-control mb-px-10" id="avlMonday" name="avlMonday" value="<?php echo $avlMonday ?>" placeholder="04:00PM to 06:00PM">
										<?php echo form_error("avlMonday") ? form_error("avlMonday") : "" ?>
									</div>
							   </div>
							   <div class="col-md-3 col-sm-3">
								   <div class="form-items <?php echo form_error("avlTuesday") ? 'error' : "" ?>">
										<label ><input type="checkbox" style="height: auto;margin-right: 5px;" id="chkTuesday" name="chkTuesday" value="1" <?php echo $chkTuesday == 1 ? 'checked' : '' ?>> Tuesday</label>
										<input type="text" class="form-control mb-px-10" id="avlTuesday" name="avlTuesday" value="<?php echo $avlTuesday ?>" placeholder="04:00PM to 06:00PM">
										<?php echo form_error("avlTuesday") ? form_error("avlTuesday") : "" ?>
									</div>
							   </div>
							   <div class="col-md-3 col-sm-3">
								   <div class="form-items <?php echo form_error("avlWednesday") ? 'error' : "" ?>">
										<label ><input type="checkbox" style="height: auto;margin-right: 5px;" id="chkWednesday" name="chkWednesday" value="1" <?php echo $chkWednesday == 1 ? 'checked' : '' ?>> Wednesday</label>
										<input type="text" class="form-control mb-px-10" id="avlWednesday" name="avlWednesday" value="<?php echo $avlWednesday ?>" placeholder="04:00PM to 06:00PM">
										<?php echo form_error("avlWednesday") ? form_error("avlWednesday") : "" ?>
									</div>
							   </div>
							   <div class="col-md-3 col-sm-3">
								   <div class="form-items <?php echo form_error("avlThursday") ? 'error' : "" ?>">
										<label ><input type="checkbox" style="height: auto;margin-right: 5px;" id="chkThursday" name="chkThursday" value="1" <?php echo $chkThursday == 1 ? 'checked' : '' ?>> Thursday</label>
										<input type="text" class="form-control mb-px-10" id="avlThursday" name="avlThursday" value="<?php echo $avlThursday ?>" placeholder="04:00PM to 06:00PM">
										<?php echo form_error("avlThursday") ? form_error("avlThursday") : "" ?>
									</div>
							   </div>
						  </div>
						  <div class="row">
							   <div class="col-md-3 col-sm-3">
								   <div class="form-items <?php echo form_error("avlFriday") ? 'error' : "" ?>">
										<label ><input type="checkbox" style="height: auto;margin-right: 5px;" id="chkFriday" name="chkFriday" value="1" <?php echo $chkFriday == 1 ? 'checked' : '' ?>> Friday</label>
										<input type="text" class="form-control mb-px-10" id="avlFriday" name="avlFriday" value="<?php echo $avlFriday ?>" placeholder="04:00PM to 06:00PM">
										<?php echo form_error("avlFriday") ? form_error("avlFriday") : "" ?>
									</div>
							   </div>
							   <div class="col-md-3 col-sm-3">
								   <div class="form-items <?php echo form_error("avlSaturday") ? 'error' : "" ?>">
										<label ><input type="checkbox" style="height: auto;margin-right: 5px;" id="chkSaturday" name="chkSaturday" value="1" <?php echo $chkSaturday == 1 ? 'checked' : '' ?>> Saturday</label>
										<input type="text" class="form-control mb-px-10" id="avlSaturday" name="avlSaturday" value="<?php echo $avlSaturday ?>" placeholder="04:00PM to 06:00PM">
										<?php echo form_error("avlSaturday") ? form_error("avlSaturday") : "" ?>
									</div>
							   </div>
							   <div class="col-md-3 col-sm-3">
								   <div class="form-items <?php echo form_error("avlSunday") ? 'error' : "" ?>">
										<label ><input type="checkbox" style="height: auto;margin-right: 5px;" id="chkSunday" name="chkSunday" value="1" <?php echo $chkSunday == 1 ? 'checked' : '' ?>> Sunday</label>
										<input type="text" class="form-control mb-px-10" id="avlSunday" name="avlSunday" value="<?php echo $avlSunday ?>" placeholder="04:00PM to 06:00PM">
										<?php echo form_error("avlSunday") ? form_error("avlSunday") : "" ?>
									</div>
							   </div>
						  </div>
						<?php } ?>
                    </div>
               </div>
          </div>
     </section>
		
		<input type="hidden" id="is_new_image" name="is_new_image" value="<?php echo $is_new_image ?>">
		<input type="hidden" id="image_path" name="image_path" value="<?php echo $image_path ?>">
			
	</form>
		
		<?php require_once(APPPATH . "views/admin/footer.php"); ?> 
		
    </body>
</html>



<script>
	
	$(document).on('change', '[data-control=modalFileUploader]', function(e){
			
		var id = e.target.id;

		if (this.files && this.files[0]) {

			var reader = new FileReader();

			reader.onload = function (e) {

				var selectedImage = e.target.result;
				$('#is_new_image').val(1);
				$('#image_path').val(selectedImage);
				
				$('#profileImage').html('<img src="'+selectedImage+'" class="img-responsive w-100" alt="">');
				

			}

			reader.readAsDataURL(this.files[0]);

		}

	});
	
</script>










