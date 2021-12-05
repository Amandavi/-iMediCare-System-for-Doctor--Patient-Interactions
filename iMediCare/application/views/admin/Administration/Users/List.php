<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		
        <?php require_once(APPPATH . "views/admin/header.php"); ?>
		
    </head>
    <body id="top" data-spy="scroll" data-target=".navbar-collapse" data-offset="50">
		
	
	<form action="<?php echo base_url('save-doctor') ?>" method="post" id="submitForm" name="submitForm">
      
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
									<h2 class="admin_topic">Users</h2>
								</div>
						  </div>
						
						  	<div class="row">
							  <div class="col-md-9 col-sm-9">
								  
							  </div>
							  <div class="col-md-3 col-sm-3">
								  <button type="button" data-control="createNew" class="form-control admin_btn" id="cf-submit" >NEW USER</button>
							  </div>
							</div>
						
						<div class="row mb-px-100">
							<div class="col-md-12 col-sm-12">
								<div class="_tableOverFlowDiv">
									<div class="_overFlowDiv">
								<table class="mt-px-30">
									<thead>
										<tr>
											<th class="w-35">User name</th>
											<th class="w-30">Email</th>
											<th class="w-25">Phone no</th>
											<th class="w-10"></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($list->result() as $value) { $encryption_id = urlencode(base64_encode($value->user_id)); ?> 
										<tr>
											<td class="w-35"><?php echo $value->name ?></td>
											<td class="w-30"><?php echo $value->email ?></td>
											<td class="w-25"><?php echo $value->phone_no ?></td>
											<td class="w-10">
												<a class="btn btn-info" data-control="edit" id="<?php echo $encryption_id ?>"><i id="<?php echo $encryption_id ?>" class="fa fa-edit"></i></a>
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
		
		$(document).on('click', '[data-control=createNew]', function(e) {
			
			document.getElementById('submitForm').action = '<?php echo base_url('new-user') ?>';
			document.getElementById('submitForm').target= '_parent'; 
			document.getElementById('submitForm').submit(); return false;
			
		});
		
		$(document).on('click', '[data-control=edit]', function(e) {
			
			var selected_id = e.target.id;
			$('#selected_id').val(selected_id);
			
			document.getElementById('submitForm').action = '<?php echo base_url('edit-user') ?>';
			document.getElementById('submitForm').target= '_parent'; 
			document.getElementById('submitForm').submit(); return false;
			
		});
		
	});
</script>








