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
									<h2 class="admin_topic">Appointments</h2>
								</div>
						  </div>
						
						<?php if($user_type != 'doctor' ){ ?> 
						  	<div class="row">
							  <div class="col-md-9 col-sm-9">
								  
							  </div>
							  <div class="col-md-3 col-sm-3">
								  <button type="button" data-control="newAppointment" class="form-control admin_btn" id="cf-submit" >NEW APPOINTMENT</button>
							  </div>
							</div>
						<?php } ?>
						
						<?php if($user_type == 'user' || $user_type == 'doctor' ){ ?> 
						  	<div class="row mb-px-20 mt-px-20">
							   <div class="col-md-6 col-sm-6">
								   <div class="form-items">
										<label for="src_app_date">Date</label>
										<input type="date" class="form-control" id="src_app_date" name="src_app_date" value="<?php echo $src_app_date ?>" placeholder="">
									</div>
							   </div>
							   <div class="col-md-6 col-sm-6 <?php echo $user_type == 'doctor' ? 'hide' : '' ?>">
								   <div class="form-items">
										<label for="src_doctor_id">Doctor</label>
										<select class="form-control selectpicker" id="src_doctor_id" name="src_doctor_id">
											<?php if($src_doctor_id > 0) { ?> 
												<option value="<?php echo $src_doctor_id ?>" selected><?php echo $src_doctor ?></option>
											<?php } ?>
										</select>
									   	<input type="hidden" id="src_doctor" name="src_doctor" value="<?php echo $src_doctor ?>">
									</div>
							   </div>
							   <div class="col-md-6 col-sm-6">
								   <div class="form-items">
										<label for="user_data">Patient (name/email/phone)</label>
										<input type="text" class="form-control" id="user_data" name="user_data" value="<?php echo $user_data ?>" placeholder="">
									</div>
							   </div>
							   <div class="col-md-4 col-sm-4">
								   <label for="src_status">Status</label>
									<select class="form-control selectpicker" id="src_status" name="src_status">
										<option value="All" <?php echo $src_status == 'All' ? 'selected' : '' ?>>All</option>
										<option value="New" <?php echo $src_status == 'New' ? 'selected' : '' ?>>New</option>
										<option value="Cancelled" <?php echo $src_status == 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
										<option value="Confirmed" <?php echo $src_status == 'Confirmed' ? 'selected' : '' ?>>Confirmed</option>
										<option value="Doctor checked" <?php echo $src_status == 'Doctor checked' ? 'selected' : '' ?>>Doctor checked</option>
									</select>
								</div>
							   <div class="col-md-2 col-sm-2">
								    <button type="button" data-control="searchAppointment" class="form-control admin_btn mt-px-26" id="cf-submit" > SEARCH</button>
							   </div>
							</div>
						<?php } ?>
						
						
						
						
						
						
						
						
						<div class="row mb-px-100 mt-px-30">
							<div class="col-md-12 col-sm-12">
								<div class="_tableOverFlowDiv">
									<div class="_overFlowDiv">
										<table class="">
											<thead>
												<tr>
													<th class="w-20">Doctor</th>
													<th class="w-20">Patient</th>
													<th class="w-20">Date</th>
													<th class="w-12">Token</th>
													<th class="w-15">Status</th>
													<th class="w-13"></th>
												</tr>
											</thead>
											<tbody>
												<?php foreach ($appointments->result() as $value) { $encryption_id = urlencode(base64_encode($value->id)); ?> 
												<tr>
													<td class="w-20">Dr. <?php echo $value->doctor_name ?></td>
													<td class="w-20"><?php echo $value->patient_name ?></td>
													<td class="w-20"><?php echo $value->channel_date ?></td>
													<td class="w-12"><?php echo $value->token ?></td>
													<td class="w-15">
														<span 
															  class="grid_span 
																	 <?php echo $value->status == 'New' ? 'btn-info' : '' ?>
																	 <?php echo $value->status == 'Cancelled' ? 'btn-danger' : '' ?>
																	 <?php echo $value->status == 'Confirmed' ? 'btn-success' : '' ?>
																	 <?php echo $value->status == 'Doctor checked' ? 'btn-warning' : '' ?>
																	 <?php echo $value->channel_date < date('Y-m-d') && $value->status != 'Doctor checked' ? 'btn-danger' : '' ?>
																	 ">
															<?php echo $value->channel_date < date('Y-m-d') && $value->status != 'Doctor checked' ? 'Expired' : $value->status ?></span>
													</td>
													<td class="w-13">
														<?php if($user_type != 'doctor'){ ?> 
															<a class="btn btn-info" data-control="editAppointment" id="<?php echo $encryption_id ?>"><i id="<?php echo $encryption_id ?>" class="fa fa-edit"></i></a>
														<?php } ?>

														<?php if(($value->status == 'Doctor checked' || $user_type == 'doctor') || ($user_type == 'user' && $value->status != 'Cancelled' && $value->status != 'New') ){ ?>
															<a class="btn btn-success" data-control="channellingDetails" id="<?php echo $encryption_id ?>"><i id="<?php echo $encryption_id ?>" class="fa fa-eye"></i></a>
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
		
		$(document).on('click', '[data-control=newAppointment]', function(e) {
			
			document.getElementById('submitForm').action = '<?php echo base_url('appointment-new') ?>';
			document.getElementById('submitForm').target= '_parent'; 
			document.getElementById('submitForm').submit(); return false;
			
		});
		
		$(document).on('click', '[data-control=editAppointment]', function(e) {
			
			var selected_id = e.target.id;
			$('#selected_id').val(selected_id);
			
			document.getElementById('submitForm').action = '<?php echo base_url('appointment-load') ?>';
			document.getElementById('submitForm').target= '_parent'; 
			document.getElementById('submitForm').submit(); return false;
			
		});
		
		$(document).on('click', '[data-control=channellingDetails]', function(e) {
			
			var selected_id = e.target.id;
			$('#selected_id').val(selected_id);
			
			document.getElementById('submitForm').action = '<?php echo base_url('channelling-details') ?>';
			document.getElementById('submitForm').target= '_parent'; 
			document.getElementById('submitForm').submit(); return false;
			
		});
		
		$(document).on('click', '[data-control=searchAppointment]', function(e) {
			
			document.getElementById('submitForm').action = '<?php echo base_url('appointment-search') ?>';
			document.getElementById('submitForm').target= '_parent'; 
			document.getElementById('submitForm').submit(); return false;
			
		});
		
	});
</script>


<script>
	
	
	$('#src_doctor_id').select2({
			selectOnClose: true,
			allowClear: true,
			placeholder: "Select doctor",
			minimumInputLength: 0,
			ajax: {
				type: "post",
				url:  function (params) {
				  return "<?php echo base_url(); ?>" + "index.php/admin/Appointment/AppointmentController/doctors";
				},
				dataType: "json",
				width: 'style',
				delay: 250,
				data: function (params) {
					
					var searchText = '';
					if (typeof(params.term) != "undefined"){
						searchText = params.term;
					}
					
					return {
						q: searchText,
						page: params.page,
						per_page: 10,
						specialty : 0,
						channelling_date : 0,
					};
				},

				success: function (response) {
					//alert(JSON.stringify(response));
					//alert($("#grade").val());
				},
				processResults: function (data, page) {
					return {
						results: data.map(function (item) {
							return {
								id: item.id ,
								text: 'Dr. '+item.doctor_name
							};

						}),
						pagination: {
							more: data.length === 10
						}
					};
				},
				error: function (error) {
					//alert(JSON.stringify(error));
				},
				// cache: true
			}
		}).on("select2:select", function (e) {

			var data = e.params.data;
			var id = data.id;
			var name = data.text;
		
			$("#src_doctor_id").val(id);
			$("#src_doctor").val(name);
		

		}).on("select2:unselect", function (e) {

			$("#src_doctor_id").val(0);
			$("#src_doctor").val('');

		});
	
</script>








