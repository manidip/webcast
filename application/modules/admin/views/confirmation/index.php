<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$validH=$this->validation;

$csrfToken = array(
	'name' => $this->security->get_csrf_token_name(),
	'hash' => $this->security->get_csrf_hash()
);


if(empty($link_error))
{
?>

<script>

function toggle_government_subfields(government){

	if(government=='State'){
		
		
		$("#sg_fields_cont").show();
		$("#ug_fields_cont").hide();
		$("#nogov_fields_cont").hide();

		$('#ug_min').val('');	
		$('#ug_dept').val('');	
		$("#ug_org").val('');
		
		$("#ug_org_other").val('');
	
	}else if(government=='Union'){
		
		$("#ug_fields_cont").show();
		$("#sg_fields_cont").hide();
		$("#nogov_fields_cont").hide();
		
		$('#sg_state').val('');	
		$('#sg_dept').val('');	
		$('#sg_org').val('');	
		
	}else if(government=='None'){
		
		$("#nogov_fields_cont").show();
		$("#ug_fields_cont").hide();
		$("#sg_fields_cont").hide();
		

		$('#ug_min').val('');	
		$('#ug_dept').val('');	
		$("#ug_org").val('');
		
		$("#ug_org_other").val('');
		
		$('#sg_state').val('');	
		$('#sg_dept').val('');	
		$('#sg_org').val('');	
	}
}

function populate_dept_and_org(ministry_id, selected_dept_id='', selected_org_id=''){
	
	if(ministry_id){
					   
		$.ajax({
			   	async:false,
				type: 'GET',
				url:'<?php echo base_url(); ?>ims/confirmation/get_ug_data?type=min_dept&ministry_id='+ministry_id+'&selected_dept_id='+selected_dept_id,
				dataType: 'json',
				success: function(data){
					$('#ug_dept').html(data.result);	
				}
		});
		

		$.ajax({
			    async:false,
				type: 'GET',
				url:'<?php echo base_url(); ?>ims/confirmation/get_ug_data?type=min_org&ministry_id='+ministry_id+'&selected_org_id='+selected_org_id,
				dataType: 'json',
				success: function(data){
					$('#ug_org').html(data.result);	
					
				}
		});
	}
				
}


function populate_org(ministry_id, dept_id, selected_org_id=''){
	
	if(ministry_id && dept_id){

		$.ajax({
				async:false,
				type: 'GET',
				url:'<?php echo base_url(); ?>ims/confirmation/get_ug_data?type=dept_org&dept_id='+dept_id+'&ministry_id='+ministry_id+'&selected_org_id='+selected_org_id,
				dataType: 'json',
				success: function(data){
					$('#ug_org').html(data.result);	
				}
		});
		
		
	}
}


function populate_sg_dept(state_id, selected_dept_id=''){
	
	if(state_id){

		$.ajax({
				async:false,
				type: 'GET',
				url:'<?php echo base_url(); ?>ims/confirmation/get_sg_departments?state_id='+state_id+'&selected_dept_id='+selected_dept_id,
				dataType: 'json',
				success: function(data){
					$('#sg_dept').html(data.result);
				}
		});
		
		
	}
}

</script>

<?php
if($data_posted)
{
	?>
	<script>
	$(document).ready(function(){

		toggle_government_subfields('<?php echo $validH->xssSafe($this->input->post('government')); ?>');
		
		populate_dept_and_org('<?php echo $validH->xssSafe($this->input->post('ug_min')); ?>','<?php echo $validH->xssSafe($this->input->post('ug_dept')); ?>', '<?php echo $validH->xssSafe($this->input->post('ug_org')); ?>');
		
		populate_org('<?php echo $validH->xssSafe($this->input->post('ug_min')); ?>', '<?php echo $validH->xssSafe($this->input->post('ug_dept')); ?>',  '<?php echo $validH->xssSafe($this->input->post('ug_org')); ?>');
		
		populate_sg_dept('<?php echo $validH->xssSafe($this->input->post('sg_state')); ?>', '<?php echo $validH->xssSafe($this->input->post('sg_dept')); ?>');
		
		
			
	});
	</script>
	<?php
}
else
{
	
	?>
	<script>
	$(document).ready(function(){
							   
  					   
		
		toggle_government_subfields('<?php echo $validH->xssSafe($invRec->government); ?>');
		
		populate_dept_and_org('<?php echo $validH->xssSafe($invRec->ug_min); ?>','<?php echo $validH->xssSafe($invRec->ug_dept); ?>', '<?php echo $validH->xssSafe($invRec->ug_org); ?>');
		
		populate_org('<?php echo $validH->xssSafe($invRec->ug_min); ?>', '<?php echo $validH->xssSafe($invRec->ug_dept); ?>',  '<?php echo $validH->xssSafe($invRec->ug_org); ?>');
		
		
		populate_sg_dept('<?php echo $validH->xssSafe($invRec->sg_state); ?>', '<?php echo $validH->xssSafe($invRec->sg_dept); ?>');
		
	});
	</script>
	<?php
}
?>


<script>

$(document).ready(function(){
						   
				   
	$('#designation').typeahead({
														
		source: function(query, process) {
			
			return $.ajax({
				url: "<?php echo base_url()?>ims/confirmation/get_designation_list",
				type: 'get',
				data: {query: query},
				dataType: 'json',
				success: function(data) {
					process(data);
				}
			});
    	},
		// how many items to show
		items: 10,
		// min length to trigger the suggestion list
	    minLength: 1,
		// auto selects the first item
	  	autoSelect: false,
		
		// callbacks
	    afterSelect: $.noop,
	    afterEmptySelect: $.noop
	});	
	
	
	
	$(".government").on("click", function(){
								   
		var government=$("input[type=radio]:checked").val();

		toggle_government_subfields(government);
												 										 
	});
	
	
	$("#ug_min").on("change", function(){
								   	
			$('#ug_dept').html('');	
			$("#ug_org").html('');
			$('#ug_org_other').val('');	   
			ministry_id=$(this).val();
			populate_dept_and_org(ministry_id);			   
	});
	
	
	$("#ug_dept").on("change", function(){
								   
			$("#ug_org").html('');
			$('#ug_org_other').val('');
			
			ministry_id=$("#ug_min").val();
				   
			dept_id=$(this).val();
			
			populate_org(ministry_id, dept_id);
								   
	});
	
	
	$("#sg_state").on("change", function(){
								   	
			$('#sg_dept').html('');	
			$("#sg_org").val('');
				   
			state_id=$(this).val();
			
			populate_sg_dept(state_id);
	
					   
	});
	
	
	
	/*
	$("#refresh_captcha").on("click", function(e){

		e.preventDefault();
		$("#captcha_image_cont").html('<img src="<?php // echo base_url(); ?>assets/ims/images/ajax-loader.gif" />');
		
		$.ajax({
                url: "<?php // echo base_url(); ?>ims/confirmation/refresh_captcha?"+Math.random(),
                type: 'get',
                async: true,
                success: function(data){

                   $("#captcha_image_cont").html(data);
                }
            });
	});
	*/
	
});	
</script>


<script>

$(document).ready(function(){


	$.validator.addMethod("isIntegerString", isIntegerString, "Please enter numbers only.");
	
	$("#confirmationForm").validate({
		rules:{
			designation:{
				required:true,
			},
			government:{
				required:true,
			}
			/*,
			user_captcha:{
				required:true,
				minlength:5,
				maxlength:7,
				alphaNumeric:true
			}*/
		},
		messages:{
	
		},
		errorPlacement: function(error, element) {
				if ($(element).hasClass("one_required")) {
					
					var errCnt=$(element).closest('.radioError');
					 error.appendTo(errCnt);	 	 
				}
				else {
					error.insertAfter(element);
					
				}
		}
	
	});

});

</script>


<?php
}
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
		
		if(!empty($link_error))
		{
			
			if($link_used==0) // show error only if the link was not used
			{
			?>
            <div class="alert alert-danger alert-dismissable">
            
            <strong>Error!</strong> <?php echo $this->validation->xssSafe($link_error); ?>
            </div>
            <?php
			}
			else if($link_used==1)
			{
			?>
            You have successfully accepted the invitation. <a href="<?php echo base_url(); ?>ims/confirmation/print_invitation?key=<?php echo $this->validation->xssSafe($invitation_key); ?>" target="_blank">Click here to print invitation card</a>
            <?php
			}
			
			if($link_expired==1)
			{
			?>
            <a href="<?php echo base_url(); ?>ims/confirmation/regenerate_invitation?key=<?php echo $this->validation->xssSafe($invitation_key); ?>">Click here to regenerate invitation link</a>
            <?php
			}
			
			
		}
			
		?>
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
		if(!empty($success_message))
		{
		?>
		<div class="alert alert-success alert-dismissable">
		
		<?php echo $this->validation->xssSafe($success_message); ?>
		</div>
		<?php
		}
		?>	
        
    <?php
		
	if(empty($link_error))
	{
	?>   
     
  <div class="form-group">
  <p>To accept the invitation, please verify your details as given below. If the given details are incomplete or incorrect, kindly update them. </p>
  </div>   
  
     
     
<div class="form-group">
<label class="control-label" for="name">Name *</label>
<input class="form-control" name="name" id="name" type="text" placeholder="" value="<?php echo $validH->xssSafe($invRec->name); ?>" readonly="readonly" disabled="disabled"> 
</div>   


<div class="form-group">
<label class="control-label" for="email">Email ID *</label>
<input class="form-control" name="email" id="email" type="text" placeholder="" value="<?php echo $validH->xssSafe($invRec->email); ?>" readonly="readonly" disabled="disabled">     
</div>


<div class="form-group">
<label class="control-label" for="designation">Designation *</label>
<input type="text" name="designation" id="designation" class="form-control" placeholder="" value="<?php if($data_posted){ echo set_value('designation'); } else{ if(!empty($invRec)){ echo $validH->xssSafe($invRec->designation); }} ?>" > 
</div>
        


<div class="form-group">
    <fieldset class="form-group radioError">
    <label>Your organization belongs to ? *</label>
    <div class="form-check control-label">
    <label class="form-check-label">
    <input class="form-check-input government one_required" id="govt_state" name="government"  value="State" type="radio" <?php if($data_posted){ echo set_radio('government', 'State'); } else{ if(!empty($invRec) && $invRec->government=='State'){ echo 'checked'; }} ?>>State Government
    </label>
    </div>
    <div class="form-check">
    <label class="form-check-label">
    <input class="form-check-input government one_required" id="govt_central" name="government" value="Union" type="radio" <?php if($data_posted){ echo set_radio('government', 'Union'); } else{ if(!empty($invRec) && $invRec->government=='Union'){ echo 'checked'; }} ?>>Central Government
    </label>
    </div>
    
    
     <div class="form-check">
    <label class="form-check-label">
    <input class="form-check-input government" id="govt_none" name="government" value="None" type="radio" <?php if($data_posted){ echo set_radio('government', 'None'); } else{ if(!empty($invRec) && $invRec->government=='None'){ echo 'checked'; }} ?>>Other
    </label>
    </div>
    
    </fieldset>
                    
                    
</div>




<div id="ug_fields_cont" style="display:none;">

<div class="form-group">

<label class="control-label" for="ug_min">Ministry</label>
<select name="ug_min" id="ug_min" class="form-control" >
<option value="">Select</option>
<?php
foreach($ugMinArr as $ugMinRec)
{
?>
<option value="<?php echo $validH->xssSafe($ugMinRec->orgn_id); ?>" 
 
<?php if($data_posted){ echo set_select('ug_min', $ugMinRec->orgn_id); } else{ if(!empty($invRec) && $invRec->ug_min==$ugMinRec->orgn_id){ echo 'selected'; }} ?>

><?php echo $validH->xssSafe($ugMinRec->orgn_name); ?></option>
<?php
}
?>
</select>         
   
</div>




<div class="form-group">
<label class="control-label" for="ug_dept">Department</label>
<select name="ug_dept" id="ug_dept" class="form-control" >
</select>   
</div>




<div class="form-group">
<label class="control-label" for="ug_org">Organization</label>
<select name="ug_org" id="ug_org" class="form-control" >
</select> 
</div>


<div class="form-group">
<label class="control-label" for="ug_org_other">Organization (if not in list)</label>
<input type="text" name="ug_org_other" id="ug_org_other" class="form-control" placeholder="" value="<?php if($data_posted){ echo set_value('ug_org_other'); } else{ if(!empty($invRec)){ echo $validH->xssSafe($invRec->ug_org_other); } } ?>" >     
</div>

</div>





<div id="sg_fields_cont" style="display:none;">

<div class="form-group">
<label class="control-label" for="sg_state">State</label>
<select name="sg_state" id="sg_state" class="form-control" >
<option value="">Select</option>
<?php
foreach($sgStaArr as $sgStaRec)
{
?>
<option value="<?php echo $validH->xssSafe($sgStaRec->state_code); ?>" 
 
<?php if($data_posted){ echo set_select('sg_state', $sgStaRec->state_code); } else{ if(!empty($invRec) && $invRec->sg_state==$sgStaRec->state_code){ echo 'selected'; }} ?>

><?php echo $validH->xssSafe($sgStaRec->state_name); ?></option>
<?php
}
?>
</select>
</div>



<div class="form-group">
<label class="control-label" for="sg_dept">Department</label>
<select name="sg_dept" id="sg_dept" class="form-control" >
</select>   
</div>


<div class="form-group">
<label class="control-label" for="sg_org">Organization</label>
<input type="text" name="sg_org" id="sg_org" class="form-control" placeholder="" value="<?php if($data_posted){ echo set_value('sg_org'); } else{ if(!empty($invRec)){ echo $validH->xssSafe($invRec->sg_org); } } ?>" > 
</div>


</div>



<div id="nogov_fields_cont" style="display:none;">

<div class="form-group">
<label class="control-label" for="org_other">Organization</label>
<input type="text" name="org_other" id="org_other" class="form-control" placeholder="" value="<?php if($data_posted){ echo set_value('org_other'); } else{ if(!empty($invRec)){ echo $validH->xssSafe($invRec->org_other); } } ?>" > 
</div>

</div>

        
    
    
    <?php
	/*


<div class="form-group">

<label class="control-label" for="user_captcha">Verification Code *</label>
<input class="form-control" name="user_captcha" id="user_captcha" type="text" placeholder="" maxlength="8">
<small>Please enter the characters (case sensitive) as shown in the image below</small>

<div><span id="captcha_image_cont"><?php echo $captcha['image']; ?></span> <small><a href="#" id="refresh_captcha">Refersh</a></small></div>


</div> 

          
      */
	  ?>
         


<div class="form-group btn-container">
<button class="btn btn-primary btn-block" name="submit_btn" value="1"><i class="fa fa-sign-in fa-lg fa-fw"></i>Accept Invitation</button>
</div>


<?php
}
?>


<?php // echo form_close(); ?>

</form>
        
              </div>
    </section>


