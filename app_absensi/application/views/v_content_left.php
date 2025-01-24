<div class="left_col scroll-view">
	<br />
	<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
		<div class="col-lg-12" style="width:230px;"></div>
		<div class="menu_section">
			<br />
			<br />
			<ul class="nav side-menu">
				<li <?php if ($pages == "pengajuan")
					echo 'class="active"'; ?>><a
						href="<?php echo site_url(); ?>/pengajuan"><i class="fa fa-edit"></i> Absensi Kehadiran </a>
				</li>
				<li <?php if ($pages == "history")
					echo 'class="active"'; ?>><a href="<?php echo site_url(); ?>/history"><i
							class="fa fa-list"></i> History Kehadiran </a></li>
				<?php if ($this->session->userdata('userrole') == 'approval' || $this->session->userdata('userrole') == 'verifikator' || $this->session->userdata('pegawaiid') == 853): ?>
					<li <?php if ($pages == "listapprove")
						echo 'class="active"'; ?>><a
							href="<?php echo site_url(); ?>/listapprove"><i class="fa fa-check-square-o"></i> Approval
							Kehadiran </a></li>
				<?php endif; ?>
				<li><a href="<?= base_url('/portal.php'); ?>"><i class="fa fa-sign-out"></i> Kembali </a></li>
			</ul>
		</div>
	</div>
</div>