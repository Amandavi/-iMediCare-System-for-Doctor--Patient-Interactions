<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		
        <?php require_once(APPPATH . "views/admin/header.php"); ?>
		
    </head>
    <body id="top" data-spy="scroll" data-target=".navbar-collapse" data-offset="50">
		
	
	<form action="<?php echo base_url('save-specialty') ?>" method="post" id="submitForm" name="submitForm">
      
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
									<h2 class="admin_topic">Doctor's specialty</h2>
								</div>
						  </div>
							<div class="row">
							   <div class="col-md-6 col-sm-6">
								   <div class="form-items <?php echo form_error("specialty") ? 'error' : "" ?>">
										<label for="specialty">Specialty</label>
										<input type="text" class="form-control" id="specialty" name="specialty" value="<?php echo $specialty ?>" placeholder="Specialty">
										<?php echo form_error("specialty") ? form_error("specialty") : "" ?>
									</div>
							   </div>
								<div class="col-md-3 col-sm-3"></div>
								<div class="col-md-3 col-sm-3">
									<button type="submit" class="form-control admin_btn  mt-px-26" id="New"><?php echo $_id == 0 ? 'SUBMIT' : 'UPDATE' ?></button>
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











