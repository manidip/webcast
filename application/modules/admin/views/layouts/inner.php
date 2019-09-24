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
    
    
    
        <!-- Essential javascripts for application to work-->
    <script src="<?php echo base_url(); ?>assets/admin/js/jquery-3.2.1.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/admin/js/popper.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/admin/js/bootstrap.min.js"></script>
     <script src="<?php echo base_url(); ?>assets/admin/js/bootstrap3-typeahead.js"></script>
    
    
    
   
    <!-- The javascript plugin to display page loading on top-->
    <script src="<?php echo base_url(); ?>assets/admin/js/plugins/pace.min.js"></script>
    
    <script src="<?php echo base_url(); ?>assets/admin/js/jquery.validate.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/admin/js/additional-methods.js"></script>
    <script src="<?php echo base_url(); ?>assets/admin/js/jquery.form.js"></script>
    <script src="<?php echo base_url(); ?>assets/admin/js/sha256.js"></script>
    <script src="<?php echo base_url(); ?>assets/admin/js/common.js"></script>
    
    
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/admin/js/jquery.prettyPhoto.js" charset="utf-8"></script>
    
    
    <script type="text/javascript">
	
	$(document).ready(function () {
		$("a[rel^='galleryPhoto']").prettyPhoto({ social_tools:'', deeplinking: false, theme: 'pp_default', /* light_rounded / dark_rounded / light_square / dark_square / facebook */ });
	});
	
	
	/*** for screenshots ***/
	$(document).ready(function(){
		$("a[rel^='prettyPhoto']").prettyPhoto({social_tools: false});
	});
	
	
	
	function burstCache() {
			
			if (!navigator.onLine) {
				document.body.innerHTML = 'Not Available';
			}
		}
		
		
	$(document).ready(function(){
							     

		
	
	});
	
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
        
        
        <?php
        if($usrRec->role=='admin')
        {
		?>
        <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-user"></i><span class="app-menu__label">Categories</span><i class="treeview-indicator fa fa-angle-right"></i></a>
          <ul class="treeview-menu">
            <li><a class="treeview-item" href="<?php echo base_url().'admin/category/index'; ?>"><i class="icon fa fa-circle-o"></i>List</a></li>
            <li><a class="treeview-item" href="<?php echo base_url().'admin/category/add'; ?>"><i class="icon fa fa-circle-o"></i>Add</a></li>
          </ul>
        </li>

        
        
        
        
        <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-user"></i><span class="app-menu__label">Coordinators</span><i class="treeview-indicator fa fa-angle-right"></i></a>
          <ul class="treeview-menu">
            <li><a class="treeview-item" href="<?php echo base_url().'admin/coordinator/index'; ?>"><i class="icon fa fa-circle-o"></i>List</a></li>
            <li><a class="treeview-item" href="<?php echo base_url().'admin/coordinator/add'; ?>"><i class="icon fa fa-circle-o"></i>Add</a></li>
          </ul>
        </li>
        
        
        <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-user"></i><span class="app-menu__label">CMS Users</span><i class="treeview-indicator fa fa-angle-right"></i></a>
          <ul class="treeview-menu">
            <li><a class="treeview-item" href="<?php echo base_url().'admin/admin_user/index'; ?>"><i class="icon fa fa-circle-o"></i>List</a></li>
            <li><a class="treeview-item" href="<?php echo base_url().'admin/admin_user/add'; ?>"><i class="icon fa fa-circle-o"></i>Add</a></li>
          </ul>
        </li>
        
        <?php
		}
		?>
<!--        
        
        
        
         <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-user"></i><span class="app-menu__label">API Users</span><i class="treeview-indicator fa fa-angle-right"></i></a>
          <ul class="treeview-menu">
            <li><a class="treeview-item" href="<?php // echo base_url().'admin/api_user/index'; ?>"><i class="icon fa fa-circle-o"></i>List</a></li>
          
          </ul>
        </li> -->
        
        
        
        
        <!--
        <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-laptop"></i><span class="app-menu__label">UI Elements</span><i class="treeview-indicator fa fa-angle-right"></i></a>
          <ul class="treeview-menu">
            <li><a class="treeview-item" href="bootstrap-components.html"><i class="icon fa fa-circle-o"></i> Bootstrap Elements</a></li>
            <li><a class="treeview-item" href="https://fontawesome.com/v4.7.0/icons/" target="_blank" rel="noopener"><i class="icon fa fa-circle-o"></i> Font Icons</a></li>
            <li><a class="treeview-item" href="ui-cards.html"><i class="icon fa fa-circle-o"></i> Cards</a></li>
            <li><a class="treeview-item" href="widgets.html"><i class="icon fa fa-circle-o"></i> Widgets</a></li>
          </ul>
        </li>
        <li><a class="app-menu__item" href="charts.html"><i class="app-menu__icon fa fa-pie-chart"></i><span class="app-menu__label">Charts</span></a></li>
        <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-edit"></i><span class="app-menu__label">Forms</span><i class="treeview-indicator fa fa-angle-right"></i></a>
          <ul class="treeview-menu">
            <li><a class="treeview-item" href="form-components.html"><i class="icon fa fa-circle-o"></i> Form Components</a></li>
            <li><a class="treeview-item" href="form-custom.html"><i class="icon fa fa-circle-o"></i> Custom Components</a></li>
            <li><a class="treeview-item" href="form-samples.html"><i class="icon fa fa-circle-o"></i> Form Samples</a></li>
            <li><a class="treeview-item" href="form-notifications.html"><i class="icon fa fa-circle-o"></i> Form Notifications</a></li>
          </ul>
        </li>
        <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-th-list"></i><span class="app-menu__label">Tables</span><i class="treeview-indicator fa fa-angle-right"></i></a>
          <ul class="treeview-menu">
            <li><a class="treeview-item" href="table-basic.html"><i class="icon fa fa-circle-o"></i> Basic Tables</a></li>
            <li><a class="treeview-item" href="table-data-table.html"><i class="icon fa fa-circle-o"></i> Data Tables</a></li>
          </ul>
        </li>
        <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-file-text"></i><span class="app-menu__label">Pages</span><i class="treeview-indicator fa fa-angle-right"></i></a>
          <ul class="treeview-menu">
            <li><a class="treeview-item" href="blank-page.html"><i class="icon fa fa-circle-o"></i> Blank Page</a></li>
            <li><a class="treeview-item" href="page-login.html"><i class="icon fa fa-circle-o"></i> Login Page</a></li>
            <li><a class="treeview-item" href="page-lockscreen.html"><i class="icon fa fa-circle-o"></i> Lockscreen Page</a></li>
            <li><a class="treeview-item" href="page-user.html"><i class="icon fa fa-circle-o"></i> User Page</a></li>
            <li><a class="treeview-item" href="page-invoice.html"><i class="icon fa fa-circle-o"></i> Invoice Page</a></li>
            <li><a class="treeview-item" href="page-calendar.html"><i class="icon fa fa-circle-o"></i> Calendar Page</a></li>
            <li><a class="treeview-item" href="page-mailbox.html"><i class="icon fa fa-circle-o"></i> Mailbox</a></li>
            <li><a class="treeview-item" href="page-error.html"><i class="icon fa fa-circle-o"></i> Error Page</a></li>
          </ul>
        </li>
        
        -->
        
        
      </ul>
    </aside>
    
    
    <?php
    echo $body
	?>

    
    <script src="<?php echo base_url(); ?>assets/admin/js/main.js"></script>

  </body>
</html>