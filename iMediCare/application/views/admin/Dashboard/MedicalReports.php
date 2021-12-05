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
                    <div class="col-md-9 col-sm-9">
						  <div class="row">
								<div class="col-md-12 col-sm-12">
									<h2 class="admin_topic">Medical reports</h2>
								</div>
						  </div>
						
						<?php if($user_type == 'user' ){ ?> 
						  	<div class="row">
							  <div class="col-md-9 col-sm-9">
								  
							  </div>
							  <div class="col-md-3 col-sm-3">
								  <button type="button" data-control="newReport" class="form-control admin_btn" id="cf-submit" >ADD NEW REPORT</button>
							  </div>
							</div>
						
							<div class="row mb-px-20 mt-px-40">
							   <div class="col-md-3 col-sm-3">
								   <div class="form-items">
										<label for="rpt_date">Date</label>
										<input type="date" class="form-control" id="rpt_date" name="rpt_date" value="<?php echo $rpt_date ?>" placeholder="">
									</div>
							   </div>
							   <div class="col-md-3 col-sm-3">
								   <div class="form-items">
										<label for="rpt_type">Report type</label>
										<input type="text" class="form-control" id="rpt_type" name="rpt_type" value="<?php echo $rpt_type ?>" placeholder="">
									</div>
							   </div>
							   <div class="col-md-4 col-sm-4">
								   <div class="form-items">
										<label for="user_data">Patient (name/email/phone)</label>
										<input type="text" class="form-control" id="user_data" name="user_data" value="<?php echo $user_data ?>" placeholder="">
									</div>
							   </div>
							   <div class="col-md-2 col-sm-2">
								    <button type="button" data-control="searchReport" class="form-control admin_btn mt-px-26" id="cf-submit" > SEARCH</button>
							   </div>
							</div>
						
						<?php } ?>
						
						<div class="row mt-px-30 mb-px-50">
							<div class="col-md-12 col-sm-12">
								<div class="_tableOverFlowDiv">
									<div class="_overFlowDiv">
										<table>
											<thead>
												<tr>
													<th class="w-30">Report</th>
													<th class="w-30">Note</th>
													<th class="w-20"></th>
													<th class="w-20"></th>
												</tr>
											</thead>
											<tbody>
												<?php foreach ($medical_reports->result() as $value) { $encryption_id = urlencode(base64_encode($value->id)); ?> 
												<tr>
													<td class="w-30"><?php echo $value->report ?></td>
													<td class="w-30"><?php echo $value->note ?></td>
													<td class="w-20"><?php echo $value->patient ?></td>
													<td class="w-20" style="text-align: right;">
														<?php if(strlen(trim($value->doc_path)) > 0){ ?> 
														<a class="btn btn-success" href="<?php echo base_url($value->doc_path) ?>" target="_blank"><i class="fa fa-eye"></i></a>
														<?php } ?>
														<?php if(strlen(trim($value->doc_path)) == 0){ ?>
															<span class="grid_span btn-warning">Pending</span>
														<?php } ?>
														<?php if($user_type == 'user'){ ?>
														<a class="btn btn-info" data-control="editReport" id="<?php echo $encryption_id ?>"><i id="<?php echo $encryption_id ?>" class="fa fa-edit"></i></a>
														<?php } ?>
													</td>
												</tr>
												<?php } ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
                    </div>
               </div>
          </div>
     </section>
		
		<input type="hidden" name="selected_id" id="selected_id" value="0">
			
	</form>
		
		<?php require_once(APPPATH . "views/admin/footer.php"); ?> 
		
    </body>
</html>


<script>
	$(document).ready(function () {
		
		$(document).on('click', '[data-control=searchReport]', function(e) {
			
			document.getElementById('submitForm').action = '<?php echo base_url('medical-reports-search') ?>';
			document.getElementById('submitForm').target= '_parent'; 
			document.getElementById('submitForm').submit(); return false;
			
		});
		
		$(document).on('click', '[data-control=newReport]', function(e) {
			
			document.getElementById('submitForm').action = '<?php echo base_url('medical-reports-new') ?>';
			document.getElementById('submitForm').target= '_parent'; 
			document.getElementById('submitForm').submit(); return false;
			
		});
		
		$(document).on('click', '[data-control=editReport]', function(e) {
			
			var selected_id = e.target.id;
			$('#selected_id').val(selected_id);
			
			document.getElementById('submitForm').action = '<?php echo base_url('medical-reports-edit') ?>';
			document.getElementById('submitForm').target= '_parent'; 
			document.getElementById('submitForm').submit(); return false;
			
		});
		
	});
</script>








