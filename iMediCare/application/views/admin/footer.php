
	<script src="<?php echo base_url('medicare/js/jquery.js') ?>" type="text/javascript"></script>
	<script src="<?php echo base_url('medicare/js/bootstrap.min.js') ?>" type="text/javascript"></script>
	<script src="<?php echo base_url('medicare/js/jquery.sticky.js') ?>" type="text/javascript"></script>
	<script src="<?php echo base_url('medicare/js/jquery.stellar.min.js') ?>" type="text/javascript"></script>
	<script src="<?php echo base_url('medicare/js/wow.min.js') ?>" type="text/javascript"></script>
	<script src="<?php echo base_url('medicare/js/smoothscroll.js') ?>" type="text/javascript"></script>
	<script src="<?php echo base_url('medicare/js/owl.carousel.min.js') ?>" type="text/javascript"></script>
	<script src="<?php echo base_url('medicare/js/custom.js') ?>" type="text/javascript"></script>



	<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>


<script type="text/javascript">
	$(document).ready(function () {
		
		$(document).on('click', '[data-control=_menu]', function(e) {
			
			var _action = e.target.id;
			
			document.getElementById('submitForm').action = '<?php echo base_url() ?>'+_action;
			document.getElementById('submitForm').target= '_parent'; 
			document.getElementById('submitForm').submit(); return false;
			
		});
		
		$(document).on('click', '[data-control=_mobmenu]', function(e) {
			$('._sideMenu').toggleClass('display-block');
		});
		
	});
</script>




