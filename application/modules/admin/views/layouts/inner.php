<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$controller=$this->router->fetch_class();
$method=$this->router->fetch_method();

//echo $method; 


?>
<!DOCTYPE html>
<html lang="en">
  <head>
    
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
     
    <title>Government Webcast Management System</title>
    <!-- Main CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/admin/css/main.css">
  
    <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/error.css">
    
    <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/prettyPhoto.css">
   <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
      <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/admin/css/jquery.datetimepicker.min.css">
      <link href="<?php echo base_url(); ?>assets/admin/css/select2.min.css" rel="stylesheet" />
      <style>
          .select2-container--default .select2-selection--multiple .select2-selection__choice{
              background-color: #009688;
              border: 1px solid #009688;
          }
          .select2-container--default .select2-selection--multiple .select2-selection__choice__remove{
              color: #fff;
          }
          .select2-container--default .select2-results__option--highlighted[aria-selected]{
              background-color: #009688;
          }
          .select2-container .select2-selection--single{
              height:auto;
          }
          .select2-container--default .select2-selection--single .select2-selection__rendered,.select2-container--default .select2-selection--multiple{
              padding: 0.375rem 0.75rem;
          }
          .select2-container--default .select2-selection--single .select2-selection__arrow {
              top: 8px;
              right: 6px;
          }
          .select2-container--default .select2-selection--single .select2-selection__clear{
              right: 15px;
          }
      </style>
      <!-- Essential javascripts for application to work-->
      <script src="<?php echo base_url(); ?>assets/admin/js/jquery-3.2.1.min.js"></script>
      <script src="<?php echo base_url(); ?>assets/admin/js/popper.min.js"></script>
      <script src="<?php echo base_url(); ?>assets/admin/js/bootstrap.min.js"></script>
      <script src="<?php echo base_url(); ?>assets/admin/js/bootstrap3-typeahead.js"></script>

      <!-- The javascript plugin to display page loading on top-->
      <script src="<?php echo base_url(); ?>assets/admin/js/plugins/pace.min.js"></script>

      <script src="<?php echo base_url(); ?>assets/admin/js/select2.min.js"></script>
      <script src="<?php echo base_url(); ?>assets/admin/js/jquery.validate.min.js"></script>
      <script src="<?php echo base_url(); ?>assets/admin/js/additional-methods.js"></script>
      <script src="<?php echo base_url(); ?>assets/admin/js/jquery.form.js"></script>
      <script src="<?php echo base_url(); ?>assets/admin/js/sha256.js"></script>
      <script src="<?php echo base_url(); ?>assets/admin/js/common.js"></script>
      <script src="<?php echo base_url(); ?>assets/admin/js/jquery.datetimepicker.full.min.js"></script>


      <script type="text/javascript" src="<?php echo base_url(); ?>assets/admin/js/jquery.prettyPhoto.js" charset="utf-8"></script>


      <script type="text/javascript">
          $(document).ready(function() {
              $("a[rel^='galleryPhoto']").prettyPhoto({
                  social_tools: '',
                  deeplinking: false,
                  theme: 'pp_default',
                  /* light_rounded / dark_rounded / light_square / dark_square / facebook */
              });
          });
          /*** for screenshots ***/
          $(document).ready(function() {
              $("a[rel^='prettyPhoto']").prettyPhoto({
                  social_tools: false
              });
          });

          function burstCache() {

              if (!navigator.onLine) {
                  document.body.innerHTML = 'Not Available';
              }
          }
      </script>
  </head>
  <body onLoad="burstCache();" class="app sidebar-mini rtl">
    <!-- Navbar-->
    <header class="app-header"><a class="app-header__logo" href="<?php echo base_url(); ?>admin">Home</a>
      <!-- Sidebar toggle button--><a class="app-sidebar__toggle" href="#" data-toggle="sidebar" aria-label="Hide Sidebar"></a>
      <!-- Navbar Right Menu-->
      <ul class="app-nav">
      
        <?php
		if($this->admin->is_user_loggedin())  // function defined in Admin's View Helper
		{
			$usrRec=$this->admin->get_loggedin_user();
			
			//print_r($admRec);
		}
		?>	
        <!-- User Menu-->
        <li class="dropdown"><a class="app-nav__item" href="#" data-toggle="dropdown" aria-label="Open Profile Menu"><i class="fa fa-user fa-lg"></i></a>
          <ul class="dropdown-menu settings-menu dropdown-menu-right">
          	<li><a class="dropdown-item" href="<?php echo base_url().'admin/home/view_profile'; ?>"><i class="fa fa-user fa-lg"></i> Profile</a></li>
            <li><a class="dropdown-item" href="<?php echo base_url().'admin/home/change_password'; ?>"><i class="fa fa-cog fa-lg"></i> Settings</a></li>          
            <li><a class="dropdown-item" href="<?php echo base_url().'admin/home/logout'; ?>?csrf_wc_token=<?php  echo md5($this->security->get_csrf_hash().$this->session->csrf_salt); ?>"><i class="fa fa-sign-out fa-lg"></i> Logout</a></li>
          </ul>
        </li>
      </ul>
    </header>
    <!-- Sidebar menu-->
    <div class="app-sidebar__overlay" data-toggle="sidebar"></div>
    <aside class="app-sidebar">
      <div class="app-sidebar__user">
      
      
     
     <?php
	$photo_url=base_url().'uploads/userprofile/thumbs/'.$this->validation->xssSafe($usrRec->photo);
	$photo_path=base_url().'uploads/userprofile/thumbs/'.$this->validation->xssSafe($usrRec->photo);
	
	if(is_file($photo_path))
	{
	?>
	<img class="app-sidebar__user-avatar" src="<?php echo $photo_url; ?>" alt="" width="48" />
	<?php
	}
	else
	{
	?>
	<img class="app-sidebar__user-avatar" src="<?php echo base_url(); ?>assets/admin/images/profile-pic2.png" alt="" class="img-responsive img-circle" />
	<?php
	}
	?>
      
      
      
      
        <div>
          <p class="app-sidebar__user-name"><?php echo $this->validation->xssSafe($usrRec->fname.' '.$usrRec->lname); ?></p>
          <p class="app-sidebar__user-designation"><?php echo $this->validation->xssSafe($usrRec->designation); ?></p>
        </div>
      </div>
      <ul class="app-menu">
        <li><a class="app-menu__item active" href="<?php echo base_url().'admin/dashboard/index'; ?>"><i class="app-menu__icon fa fa-dashboard"></i><span class="app-menu__label">Dashboard</span></a></li>
          <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-calendar"></i><span class="app-menu__label">Events </span><i class="treeview-indicator fa fa-angle-right"></i></a>
              <ul class="treeview-menu">
                  <li><a class="treeview-item" href="<?php echo base_url().'admin/events/index'; ?>"><i class="icon fa fa-bars"></i>List</a></li>
                  <li><a class="treeview-item" href="<?php echo base_url().'admin/events/add'; ?>"><i class="icon fa fa-plus-circle"></i>Add</a></li>
              </ul>
          </li>
          <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-clock-o"></i><span class="app-menu__label">Event Sessions </span><i class="treeview-indicator fa fa-angle-right"></i></a>
              <ul class="treeview-menu">
                  <li><a class="treeview-item" href="<?php echo base_url().'admin/event_sessions/index'; ?>"><i class="icon fa fa-bars"></i>List</a></li>
                  <li><a class="treeview-item" href="<?php echo base_url().'admin/event_sessions/add'; ?>"><i class="icon fa fa-plus-circle"></i>Add</a></li>
              </ul>
          </li>

          <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-picture-o"></i><span class="app-menu__label">Banners </span><i class="treeview-indicator fa fa-angle-right"></i></a>
              <ul class="treeview-menu">
                  <li><a class="treeview-item" href="<?php echo base_url().'admin/banners/index'; ?>"><i class="icon fa fa-bars"></i>List</a></li>
                  <li><a class="treeview-item" href="<?php echo base_url().'admin/banners/add'; ?>"><i class="icon fa fa-plus-circle"></i>Add</a></li>
              </ul>
          </li>
          <?php if($usrRec->role=='admin'){?>
              <li class="treeview"><a class="app-menu__item" href="<?php echo base_url().'admin/state/index'; ?>"><i class="app-menu__icon fa fa-picture-o"></i><span class="app-menu__label">States</span></a></li>
              <li class="treeview"><a class="app-menu__item" href="<?php echo base_url().'admin/organization/index'; ?>"><i class="app-menu__icon fa fa-picture-o"></i><span class="app-menu__label">Organizations</span></a></li>
              <li class="treeview"><a class="app-menu__item" href="<?php echo base_url().'admin/state_department/index'; ?>"><i class="app-menu__icon fa fa-picture-o"></i><span class="app-menu__label">State Departments</span></a></li>
              <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-th-list"></i><span class="app-menu__label">Categories</span><i class="treeview-indicator fa fa-angle-right"></i></a>
                  <ul class="treeview-menu">
                      <li><a class="treeview-item" href="<?php echo base_url().'admin/category/index'; ?>"><i class="icon fa fa-bars"></i>List</a></li>
                      <li><a class="treeview-item" href="<?php echo base_url().'admin/category/add'; ?>"><i class="icon fa fa-plus-circle"></i>Add</a></li>
                  </ul>
              </li>
              <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-users"></i><span class="app-menu__label">Coordinators</span><i class="treeview-indicator fa fa-angle-right"></i></a>
                  <ul class="treeview-menu">
                      <li><a class="treeview-item" href="<?php echo base_url().'admin/coordinator/index'; ?>"><i class="icon fa fa-bars"></i>List</a></li>
                      <li><a class="treeview-item" href="<?php echo base_url().'admin/coordinator/add'; ?>"><i class="icon fa fa-plus-circle"></i>Add</a></li>
                  </ul>
              </li>
              <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-user-circle-o"></i><span class="app-menu__label">CMS Users</span><i class="treeview-indicator fa fa-angle-right"></i></a>
                  <ul class="treeview-menu">
                      <li><a class="treeview-item" href="<?php echo base_url().'admin/admin_user/index'; ?>"><i class="icon fa fa-bars"></i>List</a></li>
                      <li><a class="treeview-item" href="<?php echo base_url().'admin/admin_user/add'; ?>"><i class="icon fa fa-plus-circle"></i>Add</a></li>
                  </ul>
              </li>

              <li class="treeview"><a class="app-menu__item" href="<?php echo base_url().'admin/logs/index'; ?>"><i class="app-menu__icon fa fa-list"></i><span class="app-menu__label">Logs</span></a></li>

          <?php } ?>
      </ul>
    </aside>
    
    
    <?php
    echo $body
	?>

    <script src="<?php echo base_url(); ?>assets/admin/js/main.js"></script>

  </body>
</html>