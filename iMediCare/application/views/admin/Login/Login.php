<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		
        <?php require_once(APPPATH . "views/admin/header.php"); ?>
		
    </head>
    <body id="top" data-spy="scroll" data-target=".navbar-collapse" data-offset="50">
		
	
	<form action="<?php echo base_url('login-sumbit') ?>" method="post" id="submitForm" name="submitForm">
      
		<?php require_once(APPPATH . "views/admin/navBar.php"); ?>
		


     <!-- MAKE AN APPOINTMENT -->
     <section id="appointment" data-stellar-background-ratio="3">
          <div class="container">
               <div class="row">

                    <div class="col-md-6 col-sm-6">
                         <img src="<?php echo base_url('Images/login.png') ?>" class="img-responsive" alt="">
                    </div>
                    <div class="col-md-1 col-sm-1"></div>
                    <div class="col-md-4 col-sm-4">
                         <!-- CONTACT FORM HERE -->
                         <form id="appointment-form" role="form" method="post" action="#">

                              <!-- SECTION TITLE -->
                              <div class="section-title wow fadeInUp" data-wow-delay="0.4s">
								  	<div class="col-md-12 col-sm-12">
                                   		<h2>Login</h2>
									</div>
                              </div>

                              <div class="wow fadeInUp" data-wow-delay="0.8s">
                                   <div class="col-md-12 col-sm-12">
									   <div class="form-items <?php echo form_error("email") ? 'error' : "" ?>">
                                        	<label for="email">Email</label>
                                        	<input type="email" class="form-control" id="email" name="email" value="<?php echo $email ?>" placeholder="Your Email">
									   		<?php echo form_error("email") ? form_error("email") : "" ?>
										</div>
                                   </div>

                                   <div class="col-md-12 col-sm-12">
									   <div class="form-items <?php echo form_error("password") ? 'error' : "" ?>">
                                        	<label for="password">Password</label>
                                        	<input type="password" class="form-control" id="password" name="password" placeholder="Password">
									   		<?php echo form_error("password") ? form_error("password") : "" ?>
										</div>
                                   </div>

                                   <div class="col-md-12 col-sm-12">
									   <br>
                                        <button type="submit" class="form-control" id="cf-submit" name="submit">LOGIN</button>
									   <br><br><br>
                                   </div>
                              </div>
                        </form>
                    </div>

               </div>
          </div>
     </section>
		
		
			
	</form>
		
		<?php require_once(APPPATH . "views/admin/footer.php"); ?> 
		
    </body>
</html>
