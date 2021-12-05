<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		
        <?php require_once(APPPATH . "views/admin/header.php"); ?>
		
    </head>
    <body id="top" data-spy="scroll" data-target=".navbar-collapse" data-offset="50">
		
	
	<form action="<?php echo base_url('appointment-sumbit') ?>" method="post" id="submitForm" name="submitForm">
      
		<?php require_once(APPPATH . "views/admin/navBar.php"); ?>
		


     <!-- MAKE AN APPOINTMENT -->
     <section id="appointment" data-stellar-background-ratio="3">
          <div class="container">
               <div class="row">
                    <div class="col-md-3 col-sm-3">
						<?php require_once(APPPATH . "views/admin/Dashboard/SideMenu.php"); ?>
                    </div>
                    <div class="col-md-8 col-sm-8">
						  <div class="row">
								<div class="col-md-12 col-sm-12">
									<h2 class="admin_topic">Dashboard</h2>
								</div>
						  </div>
						<?php if($user_type == 'user'){ ?> 
							<div class="row">
								<div class="col-md-6 col-sm-6">
									<div class="dash-widget">
										<span class="dash-widget-bg1"><i class="fa fa-stethoscope" aria-hidden="true"></i></span>
										<div class="dash-widget-info text-right">
											<h3><?php echo $doctor_count ?></h3>
											<span class="widget-title1">Doctors <i class="fa fa-check" aria-hidden="true"></i></span>
										</div>
									</div>
								</div>
								<div class="col-md-6 col-sm-6">
									<div class="dash-widget">
										<span class="dash-widget-bg2"><i class="fa fa-user-o" aria-hidden="true"></i></span>
										<div class="dash-widget-info text-right">
											<h3><?php echo $patient_count ?></h3>
											<span class="widget-title2">Patients <i class="fa fa-check" aria-hidden="true"></i></span>
										</div>
									</div>
								</div>
							</div>
						
						<hr>
						
							<div class="row mb-px-20">
								<div class="col-md-4 col-sm-4">
									<div class="form-items">
										<label for="src_app_date">Date</label>
										<input type="date" class="form-control" id="scr_date" name="scr_date" value="<?php echo $scr_date ?>" placeholder="">
									</div>
								</div>
								<div class="col-md-8 col-sm-8"></div>
							</div>
						
							<div class="row mb-px-20">
								<?php foreach ($dashboardDetails->result() as $value) { ?>
							  <div class="col-md-4 col-sm-4 mb-px-10">
										
								  <div class="team-thumb wow fadeInUp animated pt-px-30" data-wow-delay="0.2s" style="visibility: visible; animation-delay: 0.2s; animation-name: fadeInUp;">
									  	<div class="latest-stories" style="display: flex;justify-content: center;">
										   <div class="stories-image">
												<a href="#"><img src="<?php echo base_url($value->image_path) ?>" class="img-responsive" alt=""></a>
										   </div>
										</div>

									   <div class="team-info p-px-8">
											<h3 class="m-0 text-center"><?php echo $value->name ?></h3>
											<p class="text-center"><?php echo $value->specialty ?></p>
											<p class="text-center"><?php echo $value->channelling_time ?></p>
											<div class="team-contact-info">
												<p class="text-center">
													<span class="grid_span btn-warning">Appointment - <?php echo $value->totalCount ?></span>
												</p>
												<p class="text-center">
													<span class="grid_span btn-success">Confirm - <?php echo $value->confirmedCount ?></span>
												</p>
												<p class="text-center">
													<span class="grid_span btn-danger">Cancel - <?php echo $value->cancelCount ?></span>
												</p>
												<p class="text-center">
													<span class="grid_span btn-info">Pending - <?php echo $value->newCount ?></span>
												</p>
											</div>
									   </div>

								 </div>
							  </div>
								 <?php } ?>
							</div>
						<?php } if($user_type == 'doctor'){ ?> 
							<div class="row mb-px-70">
								<?php foreach ($dashboardDetails->result() as $value) { ?>
							  <div class="col-md-12 col-sm-12 mb-px-10">
										
								  <div class="team-thumb wow fadeInUp animated pr-px-20 pl-px-20" data-wow-delay="0.2s" style="visibility: visible; animation-delay: 0.2s; animation-name: fadeInUp;border-radius: 10px;">
									  <div class="row">
										  <div class="col-md-7 col-sm-7 pt-px-20 pb-px-20">
											  <?php echo date("Y-F-d", strtotime(date($value->channel_date))) ?>
											  <?php echo date("l", strtotime(date($value->channel_date))) ?>
										  </div>
										  <div class="col-md-5 col-sm-5 pt-px-20 pb-px-20 text-right">
											  <a class="grid_span btn-success">Appointment - <?php echo $value->confirmedCount ?></a>
										  </div>
									  </div>
								 </div>
							  </div>
								 <?php } ?>
							</div>
						<?php } if($user_type == 'patient'){ ?> 
						
							<div class="row">
								<?php foreach ($dashboardDetails->result() as $value) { ?>
							  <div class="col-md-4 col-sm-4 mb-px-10">
										
								  <div class="team-thumb wow fadeInUp animated pt-px-30" data-wow-delay="0.2s" style="visibility: visible; animation-delay: 0.2s; animation-name: fadeInUp;">
									  	<div class="latest-stories" style="display: flex;justify-content: center;">
										   <div class="stories-image">
												<a href="#"><img src="<?php echo base_url($value->image_path) ?>" class="img-responsive" alt=""></a>
										   </div>
										</div>
									  
									  <div class="team-info p-px-8">
											<h3 class="m-0 text-center">Dr. <?php echo $value->name ?></h3>
											<p class="text-center"><?php echo $value->specialty ?></p>
											<p class="text-center"><?php echo $value->channelling_time ?></p>
											<div class="team-contact-info pt-px-8">
												<p class="text-center">
													<?php if($value->token != 'N/A'){ ?>
														<span class="grid_span btn-info">Token - <?php echo $value->token ?></span>
													 <?php } ?>
													<span class="grid_span 
																	 <?php echo $value->status == 'New' ? 'btn-info' : '' ?>
																	 <?php echo $value->status == 'Cancelled' ? 'btn-danger' : '' ?>
																	 <?php echo $value->status == 'Confirmed' ? 'btn-success' : '' ?>
																	 <?php echo $value->status == 'Doctor checked' ? 'btn-warning' : '' ?>
																	 "><?php echo $value->status ?></span>
												</p>
											</div>
									   </div>

								 </div>
							  </div>
								 <?php } ?>
							</div>
						<?php } ?>
                    </div>
               </div>
          </div>
     </section>
		
		
			
	</form>
		
		<?php require_once(APPPATH . "views/admin/footer.php"); ?> 
		
    </body>
</html>



<script>
	
	$("#scr_date").change(function(){
		
	  	document.getElementById('submitForm').action = '<?php echo base_url('administration') ?>';
		document.getElementById('submitForm').target= '_parent'; 
		document.getElementById('submitForm').submit(); return false;
		
	});
	
</script>










