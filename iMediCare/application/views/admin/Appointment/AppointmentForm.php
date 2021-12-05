
						<?php if($page == 'administration' && $appointment_id > 0){ ?> 
							<div class="row mb-px-20">
							   <div class="col-md-12 col-sm-12">
								   <span style="float: right;" 
										  class="grid_span 
												 <?php echo $status == 'New' ? 'btn-info' : '' ?>
												 <?php echo $status == 'Cancelled' ? 'btn-danger' : '' ?>
												 <?php echo $status == 'Confirmed' ? 'btn-success' : '' ?>
												 <?php echo $status == 'Doctor checked' ? 'btn-warning' : '' ?>
												 <?php echo $app_date < date('Y-m-d') && $status != 'Doctor checked' ? 'btn-danger' : '' ?>
												 ">
										<?php echo $app_date < date('Y-m-d') && $status != 'Doctor checked' ? 'Expired' : $status ?></span>
							   </div>
						  </div>
						<?php } ?>

						
						<?php if($user_type == 'user'){?>
							<div class="row <?php echo $patient == 'derect_channel' ? 'hide' : '' ?>">
								<div class="col-md-6 col-sm-6">
								   <div class="form-items <?php echo form_error("patient") ? 'error' : "" ?>">
										<label for="patient_id">Patient</label>
										<select class="form-control selectpicker" id="patient_id" name="patient_id">
											
											<?php if($patient_id != '0') { ?> 
												<option value="<?php echo $patient_id ?>" selected><?php echo $patient ?></option>
											<?php }  ?>
											
										</select>
									   	<input type="hidden" id="patient" name="patient" value="<?php echo $patient ?>">
										<?php echo form_error("patient") ? form_error("patient") : "" ?>
									</div>
							   </div>
							</div>
						<?php } ?>


						  <div class="row">
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
						  <div class="row">
							   <div class="col-md-6 col-sm-6">
								   <div class="form-items <?php echo form_error("email") ? 'error' : "" ?>">
										<label for="email">Email</label>
										<input type="email" class="form-control" id="email" name="email" value="<?php echo $email ?>" placeholder="Email">
										<?php echo form_error("email") ? form_error("email") : "" ?>
									</div>
							   </div>
							   <div class="col-md-6 col-sm-6">
								   <div class="form-items <?php echo form_error("app_date") ? 'error' : "" ?>">
										<label for="app_date">Date</label>
										<input type="date" class="form-control" id="app_date" name="app_date" value="<?php echo $app_date ?>" placeholder="Phone no">
										<?php echo form_error("app_date") ? form_error("app_date") : "" ?>
									</div>
							   </div>
						  </div>
						  <div class="row">
							   <div class="col-md-6 col-sm-6">
								   <div class="form-items <?php echo form_error("specialty_id") ? 'error' : "" ?>">
										<label for="specialty_id">Specialty</label>
										<select class="form-control selectpicker" id="specialty_id" name="specialty_id">
											
											<option value="0" selected>All specialty</option>
											
											<?php if($specialty_id != '0') { ?> 
												<option value="<?php echo $specialty_id ?>" selected><?php echo $specialty ?></option>
											<?php }  ?>
										</select>
									   	<input type="hidden" id="specialty" name="specialty" value="<?php echo $specialty ?>">
										<?php echo form_error("specialty_id") ? form_error("specialty_id") : "" ?>
									</div>
							   </div>
							   <div class="col-md-6 col-sm-6">
								   <div class="form-items <?php echo form_error("doctor") ? 'error' : "" ?>">
										<label for="doctor_id">Doctor</label>
										<select class="form-control selectpicker" id="doctor_id" name="doctor_id">
											<?php if($doctor_id > 0) { ?> 
												<option value="<?php echo $doctor_id ?>" selected><?php echo $doctor ?></option>
											<?php } ?>
										</select>
									   	<input type="hidden" id="doctor" name="doctor" value="<?php echo $doctor ?>">
										<?php echo form_error("doctor") ? form_error("doctor") : "" ?>
									</div>
							   </div>
						  </div>
							<div class="row">
							   <div class="col-md-6 col-sm-6">
								   <div class="form-items <?php echo form_error("fee") ? 'error' : "" ?>">
										<label for="fee">Fee (LKR)</label>
										<input readonly type="text" class="form-control" id="fee" name="fee" value="<?php echo $fee ?>" placeholder="0.00">
										<?php echo form_error("fee") ? form_error("fee") : "" ?>
									</div>
							   </div>
							   <div class="col-md-6 col-sm-6">
								   <div class="form-items <?php echo form_error("app_time") ? 'error' : "" ?>">
										<label for="app_time">Time</label>
										<input readonly type="text" class="form-control" id="app_time" name="app_time" value="<?php echo $app_time ?>" placeholder="">
										<?php echo form_error("app_time") ? form_error("app_time") : "" ?>
									</div>
							   </div>
						  </div>
						  <div class="row mb-px-20">
							   <div class="col-md-12 col-sm-12">
								   <div class="form-items <?php echo form_error("message") ? 'error' : "" ?>">
										<label for="message">Additional Message</label>
										<textarea class="form-control" rows="5" id="message" name="message" placeholder="Message"><?php echo $message ?></textarea>
										<?php echo form_error("message") ? form_error("message") : "" ?>
									</div>
							   </div>
								<?php if($page != 'administration'){ ?> 
								   <div class="col-md-12 col-sm-12">
									   <br>
										<button type="submit" class="form-control" id="cf-submit">SUBMIT APPOINTMENT</button>
									   <br><br><br>
								   </div>
							  	<?php } ?>
						  </div>


							<?php if($page == 'administration'){ ?> 

							<?php if($status != 'Doctor checked' && $app_date >= date('Y-m-d')){ ?> 
						  	<div class="row mb-px-50">
								<div class="col-md-3 col-sm-3">
									<button type="button" data-control="updateAppointment" class="form-control admin_btn" id="New"><?php echo $appointment_id == 0 ? 'SUBMIT' : 'UPDATE' ?></button>
								</div>
								<?php if($appointment_id > 0 && $status != 'Cancelled' ){ ?> 
								<div class="col-md-3 col-sm-3">
									<button type="button" data-control="updateAppointment" class="form-control admin_btn cancel" id="Cancelled">CANCEL</button>
								</div>
								<?php } ?>
								<?php if($user_type == 'user' && $status == 'New' ){ ?> 
								<div class="col-md-3 col-sm-3">
									<button type="button" data-control="updateAppointment" class="form-control admin_btn confirm" id="Confirmed">CONFIRM</button>
								</div>
								<?php } ?>
								
								
							</div>
							<?php } ?>

							

							<?php } ?>







