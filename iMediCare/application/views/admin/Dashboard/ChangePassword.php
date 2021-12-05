<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		
        <?php require_once(APPPATH . "views/admin/header.php"); ?>
		
    </head>
    <body id="top" data-spy="scroll" data-target=".navbar-collapse" data-offset="50">
		
	
	<form action="<?php echo base_url('password-submit') ?>" method="post" id="submitForm" name="submitForm">
      
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
									<h2 class="admin_topic">Change password</h2>
								</div>
						  </div>
						  <div class="row mt-px-30">
							   <div class="col-md-3 col-sm-3">
							   </div>
							   <div class="col-md-6 col-sm-6">
								   <div class="form-items <?php echo form_error("password") ? 'error' : "" ?>">
										<label for="password">Password</label>
										<input type="password" class="form-control" id="password" name="password" placeholder="Password" value="<?php echo $password ?>">
										<?php echo form_error("password") ? form_error("password") : "" ?>
									</div>
								   <div class="form-items <?php echo form_error("confirmPassword") ? 'error' : "" ?>">
										<label for="confirmPassword">Confirm Password</label>
										<input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password">
										<?php echo form_error("confirmPassword") ? form_error("confirmPassword") : "" ?>
									</div>
								   <button type="submit" class="form-control admin_btn mt-px-30" id="New">SUBMIT NEW PASSWORD</button>
							   </div>
						  </div>
                    </div>
               </div>
          </div>
     </section>
		
	</form>
		
		<?php require_once(APPPATH . "views/admin/footer.php"); ?> 
		
    </body>
</html>











