<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$controller=$this->router->fetch_class();
$method=$this->router->fetch_method();
//echo $fp_key;

?>

<script>
$(document).ready(function(){
	$.validator.addMethod("alphaNumeric", isAlphaNumericString, "Please enter alphanumeric characters only. Spaces are not allowed.");					   					   
					   			 					   			   
	$.validator.addMethod("passwordFollowsPolicy", passwordFollowsPolicy, "Your password doesn't follow Password policy.");
						   					   
	$("#rp-form").validate({
							  
		submitHandler: validatePasswordForm,
		
		rules: {
			password:{
				passwordFollowsPolicy:true,
				required:true,
				minlength:8,
				maxlength:64
			},
			cpassword:{
				equalTo:'#password',
				required:true,
				minlength:8,
				maxlength:64
			},
			user_captcha: {
				required:true,
				minlength:5,
				maxlength:7,
				alphaNumeric:true
			}
		},
		messages:{
		}

	});
	
	
	
	function validatePasswordForm(form)
	{
			
			var salt='<?php echo $this->validation->xssSafe($this->session->rand_str); ?>';
			
			var password=$("#password").val();
			
			var cpassword=$("#cpassword").val();
			
			
			if(password!==''  && cpassword!=='')
			{
				var enc_password=sha256(password);
				
				var enc_cpassword=makeHash(salt, cpassword);
				
				$("#password").val(enc_password);
				$("#cpassword").val(enc_cpassword);
			}

			form.submit();
			

	}
	
	
	
	$("#refresh_captcha").on("click", function(e){

		e.preventDefault();
		$("#captcha_image_cont").html('<img src="<?php echo base_url(); ?>assets/admin/images/ajax-loader.gif" />');
		
		$.ajax({
                url: "<?php echo base_url(); ?>admin/home/refresh_captcha?"+Math.random(),
                type: 'get',
                async: true,
                success: function(data){

                   $("#captcha_image_cont").html(data);
                }
            });
	});

	
	


});
</script>


    <section class="login-content">
      <div class="logo">
        <h1>Webcasts Management System</h1>
      </div>
      <div class="login-box">
      
      
      
       <?php echo form_open(base_url()."admin/home/reset_password?fp_key=".$this->validation->xssSafe($fp_key), array('name' => 'rp-form', 'id' => 'rp-form', 'autocomplete'=>'off', 'class'=>'login-form')); ?>
         
         <h3 class="login-head"><i class="fa fa-lg fa-fw fa-user"></i><?php echo $this->validation->xssSafe($title); ?></h3>
      
            
<?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>

<?php

if(!empty($error_message))
{
	echo '<div class="alert alert-danger">'.$this->validation->xssSafe($error_message).'</div>';
}
?>

<?php

	if(!empty($link_error))
	{
		echo '<div class="alert alert-danger">'.$this->validation->xssSafe($link_error).'</div>';
	}
	else
	{
	?> 
          <div class="form-group">
            <label class="control-label" for="password">PASSWORD</label>
            <input class="form-control" name="password" id="password" type="password" placeholder="" maxlength="64">
          </div>
          
          
          
          <div class="form-group">
            <label class="control-label" for="password">CONFIRM PASSWORD</label>
            <input class="form-control" name="cpassword" id="cpassword" type="password" placeholder="" maxlength="64">
        <a  title="Password should be at least 8 characters long and start with a letter and include at least one number, one uppercase letter, one or more lowercase letters and one special characters:  ? &nbsp; @ &nbsp; $ &nbsp; % &nbsp; & &nbsp; * &nbsp; , &nbsp; ; &nbsp; : "
        data-placement="bottom"  
        data-toggle="tooltip" 
        data-container="body"
        style="float:right;" >
        Password Policy
        </a>  
          </div>
          
          
          
          
          
          
          
          <div class="form-group">
              <div id="captcha_image_cont"><?php echo $captcha['image']; ?></div>
              <div><small><a href="#" id="refresh_captcha">Refersh</a></small></div>
            </div>
        
        
        <div class="form-group">
        
        <label class="control-label" for="user_captcha">VERIFICATION CODE</label>
        <input class="form-control" name="user_captcha" id="user_captcha" type="text" placeholder="" maxlength="8">
		<small>Please enter the characters (case sensitive) as shown in the above image</small>
                   
        </div>
         
          
          
        
          
          
          
          <div class="form-group btn-container">
            <button class="btn btn-primary btn-block" name="submit_btn" value="1"><i class="fa fa-sign-in fa-lg fa-fw"></i>SUBMIT</button>
           
          </div>
      
        
        
        
        <?php
	}

	?>
    
     <div class="form-group mt-3">
            <p class="semibold-text mb-0"><a href="<?php echo base_url().'admin'?>"><i class="fa fa-angle-left fa-fw"></i> Back to Login</a></p>
          </div>
    
    
    
        
    <?php echo form_close(); ?>      
    
    
    
    
        
              </div>
    </section>
