<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$controller=$this->router->fetch_class();
$method=$this->router->fetch_method();

//echo $method; 

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
     <meta name="robots" content="noindex, nofollow">
   
     <title>Government Webcast Management System</title>
    

     <!-- Main CSS-->
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/admin/css/main.css">
    <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/ims.css">
    <!-- Font-icon css-->
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
     
      
    <!-- Essential javascripts for application to work-->
    <script src="<?php echo base_url(); ?>assets/admin/js/jquery-3.2.1.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/admin/js/popper.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/admin/js/bootstrap.min.js"></script>
    
    <!-- The javascript plugin to display page loading on top-->
    <script src="<?php echo base_url(); ?>assets/admin/js/plugins/pace.min.js"></script>
    
    <script src="<?php echo base_url(); ?>assets/admin/js/jquery.validate.min.js"></script>
    
    <script src="<?php echo base_url(); ?>assets/admin/js/additional-methods.js"></script>
    <script src="<?php echo base_url(); ?>assets/admin/js/jquery.form.js"></script>
    <script src="<?php echo base_url(); ?>assets/admin/js/sha256.js"></script>
    <script src="<?php echo base_url(); ?>assets/admin/js/common.js"></script>
    
    
    <script type="text/javascript">
	
	
	
	function burstCache() {
			
			if (!navigator.onLine) {
				document.body.innerHTML = 'Not Available';
			}
		}
		
		
	$(document).ready(function(){
							     

		
	
	});
	
	</script>
    
  </head>
  <body onLoad="burstCache();">
    <section class="material-half-bg">
      <div class="cover"></div>
    </section>
    
    
    
    
<?php
echo $body;
?>

<script src="<?php echo base_url(); ?>assets/admin/js/main.js"></script>
    
  </body>
</html>
