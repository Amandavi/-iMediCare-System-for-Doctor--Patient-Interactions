	<!-- PRE LOADER -->
     <section class="preloader">
          <div class="spinner">

               <span class="spinner-rotate"></span>
               
          </div>
     </section>


     <!-- HEADER -->
     <header>
          <div class="container">
               <div class="row">

                    <div class="col-md-4 col-sm-5">
                         <p>Welcome to a Professional Health Care</p>
                    </div>
                         
                    <div class="col-md-8 col-sm-7 text-align-right">
                         <span class="phone-icon"><i class="fa fa-phone"></i> +94 112 252 252 2</span>
                         <span class="email-icon"><i class="fa fa-envelope-o"></i> <a href="#">info@imedicare.com</a></span>
                    </div>

               </div>
          </div>
     </header>


     <!-- MENU -->
     <section class="navbar navbar-default navbar-static-top" role="navigation">
          <div class="container">

               <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                         <span class="icon icon-bar"></span>
                         <span class="icon icon-bar"></span>
                         <span class="icon icon-bar"></span>
                    </button>

                    <!-- lOGO TEXT HERE -->
                    <a href="#" class="navbar-brand" style="padding: 5px 15px;">
						<img src="<?php echo base_url('Images/logo.png') ?>" class="img-responsive" alt="" style="height: 35px;">
				   </a>
               </div>

               <!-- MENU LINKS -->
               <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">
						<?php if($is_login == 0 ){ ?> 
                         	<li><a href="<?php echo base_url('login') ?>" class="smoothScroll <?php echo $page == 'login' ? 'navAct' : '' ?>">Login</a></li>
                         	<li><a href="<?php echo base_url('register') ?>" class="smoothScroll <?php echo $page == 'register' ? 'navAct' : '' ?>">Register</a></li>
						<?php } ?>
						
						<?php if($user_type != 'doctor' ){ ?> 
						<li class="appointment-btn"><a href="<?php echo base_url('appointment') ?>">Make an appointment</a></li>
						<?php } ?>
						
						<?php if($is_login == 1){ ?>
						<li><a href="<?php echo base_url('administration') ?>" class="smoothScroll <?php echo $page == 'administration' ? 'navAct' : '' ?>">Administration</a></li>
                         <li>
							 <a href="<?php echo base_url('profile') ?>" style="padding-right: 0px;padding-left: 0px;">
								 <img src="<?php echo base_url($user_image) ?>" style="height: 21px;width: 21px;border-radius: 20px;margin-right: 5px;">
								 <?php echo substr($user_name, 0, 12); echo strlen($user_name) > 12 ? '...' : ''  ?>
							 </a>
						 </li>
						<li><a href="<?php echo base_url('login-out') ?>"><i class="fa fa-power-off mr-px-8"></i>Log-out</a></li>
						<!--<a href="<?php echo base_url('chat') ?>" class="navbar-brand" style="padding: 15px 15px;">
							<i class="fa fa-comments" style="font-size: 20px;color: #4792F7;"> 
								<?php if($unreadMsg > 0){ ?> 
									<span style="font-size: 10px;padding: 6px;background-color: red;color: white;border-radius: 100%;"><?php echo $unreadMsg < 10 ? $unreadMsg : '+9' ?></span>
								<?php } ?>
							</i>
					   	</a>-->
						<?php } ?>
                    </ul>
               </div>

          </div>
     </section>