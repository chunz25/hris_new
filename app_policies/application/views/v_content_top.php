<div class="nav_menu">
	<nav>
		<div class="nav toggle">
			<a id="menu_toggle"><i class="fa fa-bars"></i></a>
		</div>
		<ul class="nav navbar-nav navbar-right">		
			<li class="">
				<a href="javascript:;" class="user-profile">
					<?php echo $this->session->userdata('nik'); ?> | <?php echo $this->session->userdata('nama'); ?>
				</a>
			</li>
		</ul>
	</nav>
</div>
<script type="text/javascript">
$(function(){
	$.fn.detailApp = function() {
		window.location = SITE_URL + '/listapprove/';
	};
	$.fn.detailHist = function() {
		window.location = SITE_URL + '/history/';
	};
	$.fn.lihatsemua = function() {
		window.location = SITE_URL + '/notifikasi/';
	};
});
</script>
