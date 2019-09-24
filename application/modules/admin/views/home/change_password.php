<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$controller=$this->router->fetch_class();
$method=$this->router->fetch_method();
$validH=$this->validation;

$csrfToken = array(
	'name' => $this->security->get_csrf_token_name(),
	'hash' => $this->security->get_csrf_hash()
);

?>



<script>
$(document).ready(function(){
						   			 					   			   
	$.validator.addMethod("passwordFollowsPolicy", passwordFollowsPolicy, "Your password doesn't follow Password policy.");
						   
	$("#password_form").validate({
														 
	  	submitHandler: validatePasswordForm,
		
		rules: {
			opassword:{
				required:true,
				minlength:8,
				maxlength:64
			},
			npassword:{
				passwordFollowsPolicy:true,
				required:true,
				minlength:8,
				maxlength:64
			},
			cpassword:{
				equalTo:'#npassword',
				required:true,
				minlength:8,
				maxlength:64
			}
		},
		messages:{

			opassword:{
				required:"Please enter Current Password."
			},
			npassword:{
				required:"Please enter New Password."
			},
			cpassword:{
				required:"Please re-type New Password.",
				equalTo:"The two passwords don't match."
			
			}
		}

	});



	function validatePasswordForm(form){
			
			var opassword=$("#opassword").val();
			var npassword=$("#npassword").val();
			var cpassword=$("#cpassword").val();
			
			if(opassword!=='' && npassword!=='' && cpassword!==''){
				
				var salt='<?php echo $validH->xssSafe($this->session->rand_str); ?>';
			
				var encopassword=makeHash(salt,opassword);
				
				var encnpassword=sha256($("#npassword").val());
				
				var enccpassword=makeHash(salt,cpassword);
				
				$("#opassword").val(encopassword);
				$("#npassword").val(encnpassword);
				$("#cpassword").val(enccpassword);
			}
			
			form.submit();
			

	}

	
	
	
});


</script>









<main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fa fa-user"></i> <?php echo $validH->xssSafe($title); ?></h1>
          <p>View your personal and contact details</p>
         
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="<?php echo base_url().'admin/dashboard/index'; ?>">Dashboard</a></li>
          <li class="breadcrumb-item"><?php echo $validH->xssSafe($title); ?></li>
          
        </ul>
      </div>
      
      
      <div class="row">
        <div class="col-md-12">
          <div class="tile">
            <div class="tile-body">
            
            
        <?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>
            
            
      
    <?php					
	if(!empty($success_message))
	{
	?> 
	<div class="alert alert-success alert-dismissable">
	<button type="button" data-dismiss="alert" aria-hidden="true" class="close">&times;</button>
	<?php echo $success_message; ?>
	</div>
	<?php
	}
	
	if(!empty($error_message))
	{
	?> 
	<div class="alert alert-danger">
	<strong>Error!</strong> <?php echo $error_message; ?>
	</div>
	<?php

	}
	?>
    
    
<?php echo form_open("admin/home/change_password", array('name' => 'password_form', 'id' => 'password_form', 'autocomplete'=>'off')); ?>
   
       <!-- <input type="hidden" name="<?php //echo $csrfToken['name']; ?>" value="<?php //echo $csrfToken['hash']; ?>" /> -->
              
 
                 
            
<div class="form-body pal">


<div class="row">
<div class="col-md-6">
<div class="form-group">
<label class="control-label" for="opassword">Current Password *</label>
<input type="password" name="opassword" id="opassword" class="form-control" placeholder="" value="" >       
</div>
</div>
<div class="col-md-6">
</div>
</div>


<div class="row">
<div class="col-md-6">
<div class="form-group">
<label class="control-label" for="npassword">New Password *</label>
<input type="password" name="npassword" id="npassword" class="form-control" placeholder="" value="" >   


<a  title="Password should be at least 8 characters long and start with a letter and include at least one number, one uppercase letter, one or more lowercase letters and one special characters:  ? &nbsp; @ &nbsp; $ &nbsp; % &nbsp; & &nbsp; * &nbsp; , &nbsp; ; &nbsp; : "
data-placement="bottom"  
data-toggle="tooltip" 
data-container="body"
style="float:right;" >
Password Policy
</a>  

</div>
</div>
<div class="col-md-6">
</div>
</div>


<div class="row">
<div class="col-md-6">
<div class="form-group">
<label class="control-label" for="cpassword">Retype Password *</label>
<input type="password" name="cpassword" id="cpassword" class="form-control" placeholder="" value="" > 
</div>
</div>
<div class="col-md-6">
</div>
</div>



</div>


<div class="">
<input type="submit" name="submit" value="Submit" class="btn btn-primary">
</div>

            
            

            
            
     <?php echo form_close(); ?>
            
            
            
            </div>
          </div>
        </div>
      </div>
      
           
      
      
    </main>
