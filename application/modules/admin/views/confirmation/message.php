<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$validH=$this->validation;

$csrfToken = array(
	'name' => $this->security->get_csrf_token_name(),
	'hash' => $this->security->get_csrf_hash()
);



?>



<section class="login-content">
      <div class="logo">
        <h1>Digital India Awards</h1>
      </div>
      <div class="login-box">
      
      
       
        
            <form name="confirmationForm" id="confirmationForm"  method="post" action="<?php echo base_url().'ims/confirmation?key='.$validH->xssSafe($invitation_key); ?>" autocomplete="off" class="login-form" >
   

<input type="hidden" name="<?php echo $csrfToken['name']; ?>" value="<?php echo md5($csrfToken['hash'].$this->session->csrf_salt); ?>" />
        
          <h3 class="login-head"><i class="fa fa-lg fa-fw fa-trophy"></i><?php echo $this->validation->xssSafe($title); ?></h3>
          
        
        
        <?php
		if(!empty($error_message))
		{
		?>
		<div class="alert alert-danger alert-dismissable">
		
		<strong>Error!</strong> <?php echo $this->validation->xssSafe($error_message); ?>
		</div>
		<?php
		}
		?>	
        
        
        <?php
		if(!empty($message))
		{
		?>
		<div class="alert alert-success alert-dismissable">
		
		<?php echo $this->validation->xssSafe($message); ?>
		</div>
		<?php
		}
		?>	
        
        
         <?php
		if(!empty($acceptance_message))
		{
		?>
		<div class="alert alert-success alert-dismissable">
		
		<?php echo $acceptance_message; ?>
		</div>
		<?php
		}
		?>	
    

<?php // echo form_close(); ?>

</form>
        
              </div>
    </section>


