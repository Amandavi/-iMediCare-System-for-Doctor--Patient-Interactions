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
                    <div class="col-md-1 col-sm-1"></div>
                    <div class="col-md-4 col-sm-4">
						  <!-- SECTION TITLE -->
						  <div class="section-title wow row" data-wow-delay="0.4s">
								<div class="col-md-12 col-sm-12">
									<h2 style="text-align: center;"><?php echo $header ?></h2>
								</div>
						  </div>
						  <div class="wow row" data-wow-delay="0.8s">
							   <p style="text-align: center;text-align: justify-all;"><?php echo $msg1 ?></p>
							   <p style="text-align: center;text-align: justify-all;"><?php echo $msg2 ?></p>
						  </div>
                    </div>
                    <div class="col-md-1 col-sm-1"></div>
                    <div class="col-md-6 col-sm-6">
                         <img src="<?php echo base_url($image) ?>" class="img-responsive" alt="" style="width: 70%;">
                    </div>
               </div>
          </div>
     </section>
		
		
			
	</form>
		
		<?php require_once(APPPATH . "views/admin/footer.php"); ?> 
		
    </body>
</html>



<script>
	//$('.selectpicker').selectpicker('refresh');
	
	$('#specialty_id').select2({
			selectOnClose: true,
			allowClear: true,
			placeholder: "Select specialty",
			minimumInputLength: 0,
			ajax: {
				type: "post",
				url:  function (params) {
				  return "<?php echo base_url(); ?>" + "index.php/admin/Appointment/AppointmentController/specialty";
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
								id: item.specialty ,
								text: item.specialty,
							};

						}),
						pagination: {
							more: data.length === 10
						}
					};
				},
				error: function (error) {
					alert(JSON.stringify(error));
				},
			}
		}).on("select2:select", function (e) {

			var data = e.params.data;
			var id = data.id;
			var name = data.text;

			$("#specialty_id").val(id);
			$("#specialty").val(name);
		
			clearDoctor();

		}).on("select2:unselect", function (e) {

			$("#specialty_id").val('');
			$("#specialty").val('');
		
			clearDoctor();

		});
	
	$('#doctor_id').select2({
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
						specialty : $('#specialty_id').val(),
						channelling_date : $('#app_date').val(),
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
								text: 'Dr. '+item.doctor_name,
								_time: item.channelling_time ,
								_fee: item.channelling_fee ,
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

			$("#doctor_id").val(id);
			$("#doctor").val(name);
		
			$("#app_time").val(data._time);
			$("#fee").val(data._fee);

		}).on("select2:unselect", function (e) {

			clearDoctor();

		});
	
	function clearDoctor(){
		
		var ddl = $("#doctor_id");
		var option = new Option('Select doctor', 0, true, true);
		ddl.append(option).trigger('change');

		$("#doctor_id").val(0);
		$("#doctor").val('');
		
		$("#app_time").val('');
		$("#fee").val('');
		
	}
	
</script>










