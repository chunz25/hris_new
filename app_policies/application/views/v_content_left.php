<div class="left_col scroll-view">
	<br />
	<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
		<div class="col-lg-12" style="width:230px;"></div>
		<div class="menu_section">
			<br />
			<br />	
			<ul class="nav side-menu">
				<?php if($this->session->userdata('admin') == true) : ?>
					<li <?php if($pages == "policies") echo 'class="active"';?>><a href="<?php echo site_url(); ?>/policies"><i class="fa fa-edit"></i>Form Input Informasi</a></li>				
				<?php endif; ?>
				<li <?php if($pages == "history") echo 'class="active"';?>><a href="<?php echo site_url(); ?>/history"><i class="fa fa-list"></i>Kebijakan & Peraturan Perusahaan </a></li>
				<li <?php if($pages == "template") echo 'class="active"';?>><a href="<?php echo site_url(); ?>/template"><i class="fa fa-file-text-o"></i>Template Form </a></li>
				<li><a href="<?php echo base_url('/portal.php'); ?>"><i class="fa fa-sign-out"></i> Kembali </a></li>
			</ul>
		</div>						
	</div>
</div>
