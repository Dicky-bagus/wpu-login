<!-- Sidebar -->

<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

	<!-- Sidebar - Brand -->
	<a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
		<div class="sidebar-brand-icon ">
			<i class="fas fa-laptop-code"></i>
		</div>
		<div class="sidebar-brand-text mx-3">WPU Admin </div>
	</a>

	<!-- Divider -->
	<hr class="sidebar-divider">


	<?php
	$roleId = $this->session->userdata('role_id');
	$queryMenu = "select user_menu.*,user_access_menu.menu_id from user_menu join user_access_menu on user_menu.id = user_access_menu.menu_id where user_access_menu.role_id = '$roleId' order by user_access_menu.menu_id ";
	$menu = $this->db->query($queryMenu)->result_array();
	?>
	<?php foreach ($menu as $m) : ?>
		<!-- Heading -->
		<div class="sidebar-heading">
			<?= $m['menu']; ?>
		</div>
		<!-- SIAPKAN SUB MENU SESUAI MENU  -->
		<?php
		$menuId = $m['id'];
		$querySubMenu = "select *,user_menu.id from user_sub_menu join user_menu on user_menu.id = user_sub_menu.menu_id where user_sub_menu.menu_id = '$menuId' and user_sub_menu.is_active='1' ";
		$subMenu = $this->db->query($querySubMenu)->result_array();
		?>

		<?php foreach ($subMenu as $sm) : ?>
			<!-- Nav Item - Dashboard -->
			<?php if ($title == $sm['title']) : ?>
				<li class="nav-item active">
				<?php else : ?>
				<li class="nav-item">
				<?php endif; ?>
				<a class="nav-link pb-0" href="<?= base_url() . $sm['url']; ?>">
					<i class="<?= $sm['icon'] ?>"></i>
					<span><?= $sm['title']; ?></span>
				</a>
				</li>

			<?php endforeach; ?>
			<hr class="sidebar-divider mt-3">
		<?php endforeach; ?>


		<li class="nav-item">
			<a class="nav-link" href="#" data-toggle="modal" data-target="#logoutModal">
				<i class="fas fa-fw fa-sign-out-alt "></i>
				<span>Logout</span>
			</a>
		</li>

		<!-- Divider -->
		<hr class="sidebar-divider d-none d-md-block">

		<!-- Sidebar Toggler (Sidebar) -->
		<div class="text-center d-none d-md-inline">
			<button class="rounded-circle border-0" id="sidebarToggle"></button>
		</div>

</ul>
<!-- End of Sidebar -->
