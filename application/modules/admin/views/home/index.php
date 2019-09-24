<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$controller=$this->router->fetch_class();
$method=$this->router->fetch_method();
?>

<script>
$(document).ready(function(){
						   
			
						   
	$.validator.addMethod("alphaNumeric", isAlphaNumericString, "Please enter alphanumeric characters only. Spaces are not allowed.");				
						   					   
	$("#login-form").validate({
							  
		submitHandler: validateLoginForm,
		rules: {
			email:{
				email:true,
				maxlength:50,
				required:true,
			},
			password:{
				required:true,
				maxlength:64
			},
			user_captcha:{
				required:true,
				minlength:5,
				maxlength:7,
				alphaNumeric:true
			}
		},
		messages:{

		}
	});
	
	
		
	function validateLoginForm(form){
		
		
		var password=$("#password").val();
			
		if(password!==''){
			var salt='<?php echo $this->validation->xssSafe($this->session->rand_str); ?>';
			var nPass=makeHash(salt, password);				
			$("#password").val(nPass);
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
        <h1>Webcast Management System</h1>
      </div>
      <div class="login-box">
       
        
        <?php echo form_open(base_url()."admin/", array('name' => 'login-form', 'id' => 'login-form', 'autocomplete'=>'off', 'class'=>'login-form')); ?>
        
          <h3 class="login-head"><i class="fa fa-lg fa-fw fa-user"></i><?php echo $this->validation->xssSafe($title); ?></h3>
          
          
        <?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>
          
        <?php
		if(!empty($error_message))
		{
		?>
		<div class="alert alert-danger alert-dismissable">
		<button type="button" data-dismiss="alert" aria-hidden="true" class="close">&times;</button>
		<strong>Error!</strong> <?php echo $this->validation->xssSafe($error_message); ?>
		</div>
		<?php
		}
		?>	
        
        
        <?php
		if(!empty($success_message))
		{
		?>
		<div class="alert alert-success alert-dismissable">
		<button type="button" data-dismiss="alert" aria-hidden="true" class="close">&times;</button>
		<?php echo $this->validation->xssSafe($success_message); ?>
		</div>
		<?php
		}
		?>	

          <div class="form-group">
            <label class="control-label" for="email">EMAIL</label>
            <input class="form-control" name="email" id="email" type="text" placeholder="" maxlength="50">
          </div>
          
          
          <div class="form-group">
            <label class="control-label" for="password">PASSWORD</label>
            <input class="form-control" name="password" id="password" type="password" placeholder="" maxlength="64">
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
          
          
          
          <div class="form-group">
            <div class="utility">
              <p class="semibold-text mb-2"><a href="<?php echo base_url()."admin/home/forgot_password"; ?>" data-toggle="flip">Forgot Password ?</a></p>
            </div>
          </div>
                   
          
          
          <div class="form-group btn-container">
            <button class="btn btn-primary btn-block" name="submit_btn" value="1"><i class="fa fa-sign-in fa-lg fa-fw"></i>SIGN IN</button>
           
          </div>
        <?php echo form_close(); ?>
        
              </div>
    </section>