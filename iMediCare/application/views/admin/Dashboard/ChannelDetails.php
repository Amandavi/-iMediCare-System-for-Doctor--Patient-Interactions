<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		
        <?php require_once(APPPATH . "views/admin/header.php"); ?>
		
    </head>
    <body id="top" data-spy="scroll" data-target=".navbar-collapse" data-offset="50">
		
	
	<form action="<?php echo base_url('appointment-sumbit') ?>" method="post" id="submitForm" name="submitForm" enctype="multipart/form-data">
      
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
									<h2 class="admin_topic">Channelling details</h2>
								</div>
						  </div>
						
						<div class="row mb-px-100">
							<div class="col-md-8 col-sm-8">
								
								<?php if($user_type == 'user') { ?> 
								
								<div class="row mb-px-20">
									<div class="col-md-12 col-sm-12">
										<div class="form-items <?php echo form_error("document_type") ? 'error' : "" ?>">
											<label for="document_type">Document</label>
											<input type="text" class="form-control" id="document_type" name="document_type" value="" placeholder="Document">
											<p class="error_document" id="error_document"></p>
										</div>
									</div>
									<div class="col-md-12 col-sm-12">
										<div class="form-items <?php echo form_error("note") ? 'error' : "" ?>">
											<label for="note">Note</label>
											<textarea class="form-control" rows="3" id="note" name="note" placeholder="Note"></textarea>
											<p class="error_note" id="error_note"></p>
										</div>
									</div>
									<div class="col-md-4 col-sm-4">
										<button type="button" data-control="submitDocument" class="form-control admin_btn" id="cf-submit" > UPLOAD DOCUMENT</button>
									</div>
									<div class="col-md-8 col-sm-8 pt-px-3">
										<label class="file-control" style="float: left;margin-right: 5px;">

											<input class="hide" id="_modalDocuments" type="file" name="_modalDocuments" data-control="modalFileUploader" size="60" accept=".xls,.xlsx,.pdf,.ppt,.pptx,.doc,.docx,.png,.jpeg,.jpg" >
											<a class="btn btn-info" ><i class="fa fa-clipboard" style="margin-right: 5px;"></i>Select attachment</a>

										</label>
										<a id="docError" class="_modalDocuments docError" style="margin-top: 5px;float: left;"></a>
										<input type="hidden" id="is_modalDocSelected" name="is_modalDocSelected" value="false" >
									</div>
								</div>
								
								<hr>
								
								<?php } ?>
								
								
								
								<p><strong>Hi</strong> <?php echo $patient_name ?></p>
								
								
								<?php if($user_type == 'doctor') { ?> 
								
								<div class="row mb-px-20">
									<div class="col-md-12 col-sm-12">
										<div class="form-items <?php echo form_error("doctor_recommend") ? 'error' : "" ?>">
											<!--<label for="doctor_recommend">Doctor recommends</label>-->
											<textarea class="form-control" rows="18" id="doctor_recommend" name="doctor_recommend"><?php echo $note ?></textarea>
											<p class="error_recommend" id="error_recommend"></p>
										</div>
									</div>
									<div class="col-md-4 col-sm-4">
										<button type="button" data-control="submitDoctorRecommends" class="form-control admin_btn" id="cf-submit" > SUBMIT</button>
									</div>
									<div class="col-md-3 col-sm-3">
										<button type="button" data-control="patientHistory" class="form-control admin_btn" id="cf-submit" > HISTORY</button>
									</div>
								</div>
								
								<!--message modal-->
								<div class="modal fade " id="patientHistory" role="dialog">
									<div class="modal-dialog modal-lg">
										<div class="modal-content" align="left">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal">&times;</button>
												<h4 class="modal-title" style="font-size: 20px;font-weight: 100;">Channeling history</h4>
											</div>
											<div class="modal-body" id="htmlMessage">
												<div class="panel-group" id="accordion">
													<?php $i=0; foreach ($patient_channellingHistory->result() as $value) { $i++; ?> 
												  <div class="panel panel-default">
													<div class="panel-heading">
													  <h4 class="panel-title">
														<a style="font-weight: 100;color: black !important;" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $i ?>">
														Dr. <?php echo $value->doctor_name.' ('.$value->channel_date.')' ?></a>
													  </h4>
													</div>
													<div id="collapse<?php echo $i ?>" class="panel-collapse collapse <?php echo $i==1 ? 'in' : '' ?>">
													  <div class="panel-body"><?php echo $value->doctor_notes ?></div>
													</div>
												  </div>
													<?php } ?>
												</div>
											</div>
											<div class="modal-footer" id="htmlDeleteBtn">

											</div>
										</div>
									</div>
								</div>
								
								<?php }else { ?>
									<p><?php echo $note ?></p>
								<?php } ?>
								
								<?php if($channelling_docs->num_rows() > 0) { ?> 
								<div class="_tableOverFlowDiv mt-px-30 mb-px-10">
									<div class="_overFlowDiv" style="min-width: 500px;">
										<table class="">
											<thead>
												<tr>
													<th class="w-40">Document</th>
													<th class="w-40">Note</th>
													<th class="w-20"></th>
												</tr>
											</thead>
											<tbody>
												<?php foreach ($channelling_docs->result() as $value) { ?> 
												<tr>
													<td class="w-40"><?php echo $value->doc ?></td>
													<td class="w-40"><?php echo $value->note ?></td>
													<td class="w-20">
														<a class="btn btn-success" href="<?php echo base_url($value->doc_path) ?>" target="_blank"><i class="fa fa-eye"></i></a>

														<?php if($user_type == 'user' ){ ?> 
															<a class="btn btn-danger" data-control="deleteChannellingDetails" id="<?php echo $value->id ?>"><i id="<?php echo $value->id ?>" class="fa fa-trash"></i></a>
														<?php } ?>

													</td>
												</tr>
												<?php } ?>
											</tbody>
										</table>
									</div>
								</div>
								<?php } ?>
								
							</div>
							<div class="col-md-4 col-sm-4">
								<?php if($user_type == 'user' || $user_type == 'doctor'){ ?> 
								<div class="team-thumb wow fadeInUp animated" data-wow-delay="0.2s" style="visibility: visible; animation-delay: 0.2s; animation-name: fadeInUp;">
								  <img src="<?php echo base_url($patient_image) ?>" class="img-responsive w-100" alt="">

									   <div class="team-info">
											<h3 class="mt-0"><?php echo $patient_name ?></h3>
											<div class="team-contact-info pb-px-15">
												 <p><i class="fa fa-phone"></i><?php echo $patient_phone_no ?></p>
												 <p><i class="fa fa-envelope-o"></i> <a href="#"><?php echo $patient_email ?></a></p>
											</div>
									   </div>

								</div>
								<?php } else { ?>
								<div class="team-thumb wow fadeInUp animated" data-wow-delay="0.2s" style="visibility: visible; animation-delay: 0.2s; animation-name: fadeInUp;">
								  <img src="<?php echo base_url($doc_image) ?>" class="img-responsive w-100" alt="">

									   <div class="team-info">
											<h3 class="mt-0"><?php echo $doctor_name ?></h3>
											<p><?php echo $specialty ?></p>
											<div class="team-contact-info pb-px-15">
												 <p><i class="fa fa-phone"></i><?php echo $doc_phoneNo ?></p>
												 <p><i class="fa fa-envelope-o"></i> <a href="#"><?php echo $doc_email ?></a></p>
											</div>
									   </div>

								</div>
								<?php } ?>
								
							</div>
						</div>
                    </div>
               </div>
          </div>
     </section>
		
		<input id="selected_id" name="selected_id" value="0" type="hidden">			
		<input type="hidden" id="patient_id" name="patient_id" value="<?php echo $patient_id ?>">
		<input type="hidden" id="channeling_id" name="channeling_id" value="<?php echo $channeling_id ?>">
		
	</form>
		
		<?php require_once(APPPATH . "views/admin/footer.php"); ?> 
		
    </body>
</html>



<script>
	$(document).ready(function () {
		
		
		
		$(document).on('change', '[data-control=modalFileUploader]', function(e){
			
			var id = e.target.id;
			
			if (this.files && this.files[0]) {
				
				var reader = new FileReader();
				
				reader.onload = function (e) {

					var selectedImage = e.target.result;

					$('.'+id).text('New file selected.');
					$(".docError").css("color", "black");
					$('#is_modalDocSelected').val('true');

				}
				
				reader.readAsDataURL(this.files[0]);
				
			}
			
		});
		
		$(document).on('click', '[data-control=submitDocument]', function(e) {
			
			var is_selected = $('#is_modalDocSelected').val();
			var note = $('#note').val();
			var _document = $('#document_type').val();
			var is_error = false;
			
			if(is_selected == 'false' || is_selected == '' || is_selected == null){
				
				$("#docError").text('Please select document.');
				$(".docError").css("color", "red");
				
				is_error = true;
			   
		   	}
			
			if(_document.trim().length == 0) {
				
				$("#error_document").text('Please enter document.');
				$(".error_document").css("color", "red");
				
				is_error = true;
			   
		   	} else {
				$("#error_document").text('');
			}
			
			if(is_error == true){
				return;
			}
			
			
			
			document.getElementById('submitForm').action = '<?php echo base_url('channelling-doc') ?>';
			document.getElementById('submitForm').target= '_parent'; 
			document.getElementById('submitForm').submit(); return false;
			
		});
		
		$(document).on('click', '[data-control=deleteChannellingDetails]', function(e) {
			
			var selected_id = e.target.id;
			$('#selected_id').val(selected_id);
			
			document.getElementById('submitForm').action = '<?php echo base_url('channelling-doc-delete') ?>';
			document.getElementById('submitForm').target= '_parent'; 
			document.getElementById('submitForm').submit(); return false;
			
		});
		
		$(document).on('click', '[data-control=submitDoctorRecommends]', function(e) {
			
			var note = $('#doctor_recommend').val();
			var is_error = false;
			
			if(note.trim().length == 0) {
				
				$("#error_recommend").text('Please enter your recommend.');
				$(".error_recommend").css("color", "red");
				
				is_error = true;
			   
		   	} else {
				$("#error_recommend").text('');
			}
			
			if(is_error == true){
				return;
			}
			
			
			
			document.getElementById('submitForm').action = '<?php echo base_url('doctor-recommends') ?>';
			document.getElementById('submitForm').target= '_parent'; 
			document.getElementById('submitForm').submit(); return false;
			
		});
		
		$(document).on('click', '[data-control=patientHistory]', function(e) {
			$('#patientHistory').modal('show');
		});
		
	});
</script>










