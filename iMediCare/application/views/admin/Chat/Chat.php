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
     <section id="appointment" data-stellar-background-ratio="3" style="padding-top: 20px;">
          <div class="container">
               <div class="row">
                    <div class="col-md-4 col-sm-4">
						<?php require_once(APPPATH . "views/admin/Chat/SideMenu.php"); ?>
                    </div>
                    <div class="col-md-8 col-sm-8">
						  <div class="row">
								<div class="col-md-12 col-sm-12">
									<div class="chatList">
										<div class="item act">
											<div class="clicker"></div>
											<div class="image">
												<img src="<?php echo $user_image ?>">
											</div>
											<div class="details">
												<div class="_details">
													<h1>Mahesh madushanka</h1>
													<h2>Hi mahesh..</h2>
												</div>
											</div>
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
	
	$('#search_ddl').select2({
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








