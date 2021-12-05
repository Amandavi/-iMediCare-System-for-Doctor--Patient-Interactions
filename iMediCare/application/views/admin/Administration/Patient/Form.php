<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		
        <?php require_once(APPPATH . "views/admin/header.php"); ?>
		
    </head>
    <body id="top" data-spy="scroll" data-target=".navbar-collapse" data-offset="50">
		
	
	<form action="<?php echo base_url('save-patient') ?>" method="post" id="submitForm" name="submitForm">
      
		<?php require_once(APPPATH . "views/admin/navBar.php"); ?>
		


     <!-- MAKE AN APPOINTMENT -->
     <section id="appointment" data-stellar-background-ratio="3">
          <div class="container">
               <div class="row">
                    <div class="col-md-3 col-sm-3">
						<?php require_once(APPPATH . "views/admin/Dashboard/SideMenu.php"); ?>
                    </div>
                    <div class="col-md-9 col-sm-9 mb-px-50">
						  <div class="row">
								<div class="col-md-12 col-sm-12">
									<h2 class="admin_topic">Patient</h2>
								</div>
						  </div>
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
						<?php if($_id == 0){ ?> 
						  <div class="row">
							   <div class="col-md-6 col-sm-6">
								   <div class="form-items <?php echo form_error("password") ? 'error' : "" ?>">
										<label for="password">Password</label>
										<input type="password" class="form-control" id="password" name="password" placeholder="Password" value="<?php echo $password ?>">
										<?php echo form_error("password") ? form_error("password") : "" ?>
									</div>
							   </div>
							   <div class="col-md-6 col-sm-6">
								   <div class="form-items <?php echo form_error("confirmPassword") ? 'error' : "" ?>">
										<label for="confirmPassword">Confirm Password</label>
										<input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password">
										<?php echo form_error("confirmPassword") ? form_error("confirmPassword") : "" ?>
									</div>
							   </div>
						  </div>
						<?php }  ?>
							<div class="row mt-px-30">
								<div class="col-md-9 col-sm-9"></div>
								<div class="col-md-3 col-sm-3">
									<button type="submit" class="form-control admin_btn" id="New"><?php echo $_id == 0 ? 'SUBMIT' : 'UPDATE' ?></button>
								</div>
							</div>
                    </div>
               </div>
          </div>
     </section>
		
		<input type="hidden" id="_id" name="_id" value="<?php echo $_id ?>">
			
	</form>
		
		<?php require_once(APPPATH . "views/admin/footer.php"); ?> 
		
    </body>
</html>











