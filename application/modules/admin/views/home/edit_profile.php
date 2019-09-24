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
						    				   
	$.validator.addMethod("isIntegerString", isIntegerString, "Please enter numbers only.");					

	$("#userFormEdit").validate({
		rules:{
			fname:{
				required:true
			},
			lname:{
				required:true
			},
			mobile:{
				required:true,
				isIntegerString:true,
				minlength:10,
				maxlength:10
			},
			designation:{
				required:true,
			},
			organization:{
				required:true,
			},
			std_code:{
				required:false,
				isIntegerString:true,
				minlength:3,
				maxlength:3
			},
			phone:{
				required:false,
				isIntegerString:true,
				minlength:6,
				maxlength:8
			},
			intercom:{
				required:false,
				isIntegerString: true,
				minlength:1,
				maxlength:6
			},
			pin_code:{
				required:false,
				isIntegerString: true,
				minlength:6,
				maxlength:6
			}
		},
		messages:{
	
		},
		errorPlacement: function(error, element) {
				if ($(element).hasClass("one_required")) {
					var errCnt=$(element).closest('.radioError');
					//alert(errCnt.toSource());
					 error.appendTo(errCnt);	 	 
				}
				else {
					error.insertAfter(element);
					
				}
		}

	});
	
});


//alert("<?php //echo $this->validation->xssSafe($this->session->rand_str); ?>");	


</script>



<main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fa fa-user"></i> <?php echo $validH->xssSafe($title); ?></h1>
          <p>Update your personal and contact details</p>
         
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
    


<?php echo form_open_multipart("admin/home/edit_profile", array('name' => 'userFormEdit', 'id' => 'userFormEdit', 'method'=>'post', 'autocomplete'=>'off')); ?>

<div class="row">

<div class="col-md-6">
<div class="form-group">
<label class="control-label" for="fname">First Name *</label>
<input type="text" name="fname" id="fname" class="form-control" placeholder="" value="<?php if(!empty($usrRec)){ echo $validH->xssSafe($usrRec->fname); } else{ echo set_value('fname'); } ?>" >       
</div>
</div>

<div class="col-md-6">
<div class="form-group">
<label class="control-label" for="lname">Last Name *</label>
<input type="text" name="lname" id="lname" class="form-control" placeholder="" value="<?php if(!empty($usrRec)){ echo $validH->xssSafe($usrRec->lname); } else{ echo set_value('lname'); } ?>" >       
</div>
</div>

</div>





<div class="row">

<div class="col-md-6">
<div class="form-group">
<label class="control-label" for="email">Email *</label>
<div class="input-icon right">
<input type="text" class="form-control" placeholder="" name="email" id="email" value="<?php echo $validH->encodeEmail($validH->xssSafe($usrRec->email)); ?>" readonly="readonly"></div>
</div>
</div>


<div class="col-md-6">
<div class="form-group">
<label class="control-label" for="phone">Mobile *</label>
<input type="text" name="mobile" id="mobile" class="form-control" placeholder="" value="<?php if(!empty($usrRec)){ echo $validH->xssSafe($usrRec->mobile); } else{ echo set_value('mobile'); } ?>" > 
<small>10 digit mobile number</small>      
</div>
</div>

</div>




<div class="row">

<div class="col-md-6">
<div class="form-group">
<label class="control-label" for="designation">Designation *</label>
<input type="text" name="designation" id="designation" class="form-control" placeholder="" value="<?php if(!empty($usrRec)){ echo $validH->xssSafe($usrRec->designation); } else{ echo set_value('designation'); } ?>" >     
</div>
</div>

<div class="col-md-6">
<div class="form-group">
<label class="control-label" for="organization">Organization *</label>
<input type="text" name="organization" id="organization" class="form-control" placeholder="" value="<?php if(!empty($usrRec)){ echo $validH->xssSafe($usrRec->organization); } else{ echo set_value('organization'); } ?>" >     
</div>
</div>

</div>

                


<div class="row">

<div class="col-md-6">
<div class="form-group">
<label class="control-label" for="address">Address</label>
<textarea name="address" id="address" class="form-control" placeholder="" ><?php if(!empty($usrRec)){ echo $validH->xssSafe($usrRec->address); } else{ echo set_value('address'); } ?></textarea>    
<small>Max. 250 characters</small>   
</div>   
</div>

<div class="col-md-6">
<div class="form-group">
<label class="control-label" for="city">City</label>
<input type="text" name="city" id="city" class="form-control" placeholder="" value="<?php if(!empty($usrRec)){ echo $validH->xssSafe($usrRec->city); } else{ echo set_value('city'); } ?>" >     
</div>
</div>

</div>



<div class="row">

<div class="col-md-6">
<div class="form-group">
<label class="control-label" for="state" >State</label>
<select name="state" id="state" class="form-control" >
<option value="0">Select</option>
<?php
foreach($stateArr as $stateRec)
{
?>
<option value="<?php echo $validH->xssSafe($stateRec->state_id); ?>" <?php if($usrRec->state==$stateRec->state_id){ echo "selected";} ?>><?php echo $validH->xssSafe($stateRec->state_name); ?></option>
<?php
}
?>
</select>     
</div>
</div>

<div class="col-md-6">
<div class="form-group">
<label class="control-label" for="pin_code">Pin Code</label>
<input type="text" name="pin_code" id="pin_code" class="form-control" placeholder="" value="<?php if(!empty($usrRec)){ echo $validH->xssSafe($usrRec->pin_code); } else{ echo set_value('pin_code'); } ?>" >     
</div>
</div>

</div>


<div class="row">

<div class="col-md-6">
<div class="form-group">
<label class="control-label" for="phone" style="width:100%;display:block;" >Phone with STD code</label>
<input type="text" name="std_code" id="std_code" class="form-control" style="width:15%;display:inline-block;" placeholder="" value="<?php if(!empty($usrRec)){ echo $validH->xssSafe($usrRec->std_code); } else{ echo set_value('std_code'); } ?>" > 
<em> - </em>
<input type="text" name="phone" id="phone" class="form-control" style="width:50%;display:inline-block;" placeholder="" value="<?php if(!empty($usrRec)){ echo $validH->xssSafe($usrRec->phone); } else{ echo set_value('phone'); } ?>" >       
</div>
</div>

<div class="col-md-6">
<div class="form-group">
<label class="control-label" for="intercom" style="width:100%;display:block;" >Intercom</label>
<input type="text" name="intercom" id="intercom" class="form-control" style="width:15%;display:inline-block;" placeholder="" value="<?php if(!empty($usrRec)){ echo $validH->xssSafe($usrRec->intercom); } else{ echo set_value('intercom'); } ?>" >       
</div>
</div>

</div>








<div class="row">


<div class="col-md-6">
<div class="form-group">
<label class="control-label" style="width:100%;display:block;">Status</label>
<?php if($usrRec->active==1){ echo '<span class="label label-sm label-success">Active</span>'; } else if($usrRec->active==0){ echo '<span class="label label-sm label-danger">Inactive</span>'; } ?>
</div>
</div>

<div class="col-md-6">
<div class="form-group">
<label class="control-label" style="width:100%;display:block;">Role</label>
<?php echo $validH->xssSafe($usrRec->role);  ?> 
</div>
</div>

</div>



<div class="row">

<div class="col-md-6">
<div class="form-group"> 
<label class="control-label" >Created</label>
<div>
<?php 
echo date("d/m/Y", strtotime($usrRec->created));
?>
</div>
</div>
</div>

<div class="col-md-6">
<div class="form-group"> 
<label class="control-label" >Updated</label>
<div>
<?php 
echo date("d/m/Y", strtotime($usrRec->updated));
?>
</div>
</div>
</div>

</div>



<div class="text-right">
<input type="submit" name="submit" value="Submit" class="btn btn-primary">
</div>


<?php echo form_close(); ?>
            
            
            
            </div>
          </div>
        </div>
      </div>
      
           
      
      
    </main>