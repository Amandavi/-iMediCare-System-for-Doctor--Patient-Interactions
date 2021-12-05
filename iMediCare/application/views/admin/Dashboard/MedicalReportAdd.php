<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		
        <?php require_once(APPPATH . "views/admin/header.php"); ?>
		
    </head>
    <body id="top" data-spy="scroll" data-target=".navbar-collapse" data-offset="50">
		
	
	<form action="<?php echo base_url('medical-reports-save') ?>" method="post" id="submitForm" name="submitForm" enctype="multipart/form-data">
      
		<?php require_once(APPPATH . "views/admin/navBar.php"); ?>
		


     <!-- MAKE AN APPOINTMENT -->
     <section id="appointment" data-stellar-background-ratio="3">
          <div class="container">
               <div class="row">
                    <div class="col-md-3 col-sm-3">
						<?php require_once(APPPATH . "views/admin/Dashboard/SideMenu.php"); ?>
                    </div>
                    <div class="col-md-9 col-sm-9">
						  <div class="row mb-px-20">
								<div class="col-md-12 col-sm-12">
									<h2 class="admin_topic">Medical reports</h2>
								</div>
						  </div>
						<div class="row">
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
							<div class="col-md-6 col-sm-6">
								<div class="form-items <?php echo form_error("report_type") ? 'error' : "" ?>">
									<label for="report_type">Report type</label>
									<input type="text" class="form-control" id="report_type" name="report_type" value="<?php echo $report_type ?>" placeholder="Report type">
									<?php echo form_error("report_type") ? form_error("report_type") : "" ?>
								</div>
							</div>
						</div>
						<div class="row mb-px-20">
							<div class="col-md-12 col-sm-12">
								<div class="form-items <?php echo form_error("note") ? 'error' : "" ?>">
									<label for="note">Note</label>
									<textarea class="form-control" rows="5" id="note" name="note" placeholder="Note"><?php echo $note ?></textarea>
								</div>
							</div>
							<div class="col-md-8 col-sm-8 pt-px-5">
								<label class="file-control" style="float: left;margin-right: 5px;">

									<input class="hide" id="_modalDocuments" type="file" name="_modalDocuments" data-control="modalFileUploader" size="60" accept=".xls,.xlsx,.pdf,.ppt,.pptx,.doc,.docx,.png,.jpeg,.jpg" >
									<a class="btn btn-info" ><i class="fa fa-clipboard" style="margin-right: 5px;"></i>Select attachment</a>

								</label>
								<?php if(strlen(trim($doc_path)) > 0){ ?> 
								<a href="<?php echo base_url($doc_path) ?>" target="_blank" class="btn btn-info"><i class="fa fa-eye"></i></a>
								<?php } ?>
								<a id="docError" class="_modalDocuments docError" style="margin-top: 5px;margin-right: 5px;"></a>
								
							</div>
							<div class="col-md-4 col-sm-4">
								<button type="submit" class="form-control admin_btn" id="cf-submit" > SUBMIT REPORT</button>
							</div>
						</div>
						
                    </div>
               </div>
          </div>
     </section>
		
		<input type="hidden" id="_id" name="_id" value="<?php echo $_id ?>">
		<input type="hidden" id="is_new_doc" name="is_new_doc" value="<?php echo $is_new_doc ?>">
			
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

				$('.'+id).text('New file selected.');
				$('#is_new_doc').val(1);

			}

			reader.readAsDataURL(this.files[0]);

		}

	});
	
	$('#patient_id').select2({
			selectOnClose: true,
			allowClear: true,
			placeholder: "Select patient",
			minimumInputLength: 0,
			ajax: {
				type: "post",
				url:  function (params) {
				  return "<?php echo base_url(); ?>" + "index.php/admin/Appointment/AppointmentController/patient";
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
						per_page: 10
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
								text: item.patient_name,
								email: item.email,
								phone_no: item.phone_no
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
			}
		}).on("select2:select", function (e) {

			var data = e.params.data;
			var id = data.id;
			var name = data.text;

			$("#patient_id").val(id);
			$("#patient").val(name);
			
			$("#name").val(data.text);
			$("#email").val(data.email);
			$("#phone_no").val(data.phone_no);
		
		}).on("select2:unselect", function (e) {

			$("#patient_id").val(0);
			$("#patient").val('');
			
			$("#name").val('');
			$("#email").val('');
			$("#phone_no").val('');
		
		});
	
</script>










