<div class="_menu">
	<div class="mobMenu">
		<i data-control="_mobmenu" class="fa fa-bars"></i> 
	</div>
	<div class="_sideMenu mb-px-100">
		
			
		<div class="_menuItem _menuItem_act">
			<div class="stories-image pt-px-10">
				<div style="display: flex;justify-content: center;">
					<a href="#"><img src="<?php echo base_url($user_image) ?>" class="img-responsive" alt=""></a>
				</div>
				
				<div style="font-size: 12px;text-transform: none;text-align: center;margin-top: 10px;">
					<?php echo $user_name ?>
					<br>I'm <?php echo $user_type ?>
				</div>
				
		   </div>
		</div>
		
		<div data-control="_menu" id="administration" class="_menuItem <?php echo $act_menu == 'dashboard' ? '_menuItem_act' : '' ?>">
			dashboard
		</div>
		<div data-control="_menu" id="appointment-list" class="_menuItem  <?php echo $act_menu == 'appointments_list' ? '_menuItem_act' : '' ?>">
			appointment
		</div>
		
		<?php if($user_type != 'doctor'){ ?>
		<div data-control="_menu" id="medical-reports" class="_menuItem  <?php echo $act_menu == 'medical_reports' ? '_menuItem_act' : '' ?>">
			medical reports
		</div>
		<?php } ?>
		
		<?php if($user_type == 'user'){ ?>
		
			<div data-control="_menu" id="doctors" class="_menuItem  <?php echo $act_menu == 'doctors' ? '_menuItem_act' : '' ?>">
				Doctors
			</div>

			<div data-control="_menu" id="patients" class="_menuItem  <?php echo $act_menu == 'patient' ? '_menuItem_act' : '' ?>">
				Patient
			</div>

			<div data-control="_menu" id="specialty" class="_menuItem  <?php echo $act_menu == 'specialty' ? '_menuItem_act' : '' ?>">
				Specialty
			</div>

			<div data-control="_menu" id="user" class="_menuItem  <?php echo $act_menu == 'users' ? '_menuItem_act' : '' ?>">
				Users
			</div>
		
		<?php } ?>
		
		<div data-control="_menu" id="change-password" class="_menuItem mt-15  <?php echo $act_menu == 'changePassword' ? '_menuItem_act' : '' ?>">
			Change password
		</div>
		
		<div data-control="_menu" id="login-out" class="_menuItem">
			log out
		</div>
	</div>
</div>










