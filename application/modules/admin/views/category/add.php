<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$validH=$this->validation;

$csrfToken = array(
	'name' => $this->security->get_csrf_token_name(),
	'hash' => $this->security->get_csrf_hash()
);

?>

<script>
$(document).ready(function(){
						   
						   
	$.validator.addMethod("is_integer", isIntegerString, "Please enter an Integer value.");

	$("#categoryForm").validate({
	
		rules: {
			title:{
				required: true
			},
			title_hi:{
				required: true
			},
			desc:{
				required: true
			},
			desc_hi:{
				required: true
			},
			parent:{
				required: true
			},
			display_order:{
				required: false,
				/*is_integer: true,*/
				digits: true
			},
			thumb_image:{
				required:false,
				extension:"jpg|jpeg|png"
			},
			large_image:{
				required:false,
				extension:"jpg|jpeg|png"
			},
			active:{
				required: true
			}
		},
		messages:{
			thumb_image:{
				accept:"Please upload jpg, jpeg, png files only"
			},
			large_image:{
				accept:"Please upload jpg, jpeg, png files only"
			}
		},
		errorPlacement: function(error, element) {
				if ($(element).hasClass("required_one")) {
					 error.appendTo('.radioError');	 	 
				}
				else {
					  error.insertAfter(element);
					 return true;
				}
		}
	});					   
			
			
			
	$('.delImageBtn').on('click', function(e){

		e.preventDefault();

		var href= $(this).attr('href');


		$('#delCnfModal').modal({ backdrop: 'static', keyboard: false }).one('click', '#delBtn2', function() {		
			
			$.ajax({
					url: href,
					context:document.body,
					success: function(data){
						//alert(data);
						if(data=='deleted')
						{	
							window.location.href='<?php echo base_url()?>admin/category/add?id=<?php echo $validH->xssSafe($catRec->id); ?>&msg=image_deleted';	
						}
						else
						{
							window.location.href='<?php echo base_url()?>admin/category/add?id=<?php echo $validH->xssSafe($catRec->id); ?>&msg=delete_error';
						}
						
					}
			});	
			
			
		});
	

	});	
	

});

</script>





 <!-- Modal -->
<div id="delCnfModal" class="modal fade">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-body">
Are you sure to delete this record?
</div>

<div class="modal-footer">
<button type="button" data-dismiss="modal" class="btn btn-primary" id="delBtn2">Delete</button>&nbsp;&nbsp;&nbsp; <button type="button" data-dismiss="modal" class="btn btn-primary">Cancel</button>
</div>
</div>
</div>
</div>



 



<main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fa fa-dashboard"></i> Categories</h1>
          <p>Webcast Management System</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><a href="<?php echo base_url().'admin/dashboard';?>"><i class="fa fa-home fa-lg"></i></a></li>
           <li class="breadcrumb-item"><a href="<?php echo base_url().'admin/category/index';?>">Categories</a></li>
          <li class="breadcrumb-item"><?php echo $validH->xssSafe($title); ?></li>
        </ul>
      </div>
      
      
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
      
      
       <div class="row">
        <div class="col-md-8 offset-md-2">
                    
              <?php 
			  
			  if(!empty($id))
			  	$post_url=base_url().'admin/category/add?id='.$validH->xssSafe($id);
			  else
			  	$post_url=base_url().'admin/category/add';
			  
			  echo form_open_multipart($post_url, array('name' => 'categoryForm', 'id' => 'categoryForm', 'autocomplete'=>'off')); ?>
           
          <div class="tile">
            <h3 class="tile-title">Categories - <?php echo $validH->xssSafe($title); ?> </h3>
            <div class="tile-body">
   
              
               <div class="form-group">
                <label class="control-label" for="title">Title (en) *</label>
                <input type="text" name="title" id="title" class="form-control" placeholder="" value="<?php if($data_posted) { echo set_value('title'); } else { if(!empty($catRec)) { echo $validH->xssSafe($catRec->title); } }?>" >       
                </div>
                
                 <div class="form-group">
                <label class="control-label" for="title_hi">Title (hi) *</label>
                <input type="text" name="title_hi" id="title_hi" class="form-control" placeholder="" value="<?php if($data_posted) { echo set_value('title_hi'); } else { if(!empty($catRec)) { echo $validH->xssSafe($catRec->title_hi); } }?>" >       
                </div>
                
                
                 <div class="form-group">
                <label class="control-label" for="desc">Description (en)</label>
                <textarea name="desc" id="desc" class="form-control" placeholder="" ><?php if($data_posted) { echo set_value('desc'); } else { if(!empty($catRec)) { echo $validH->xssSafe($catRec->desc); } }?></textarea>   
                <small>Max. 1000 characters</small>   
                </div>
                
                
                <div class="form-group">
                <label class="control-label" for="desc_hi">Description (hi)</label>
                <textarea name="desc_hi" id="desc_hi" class="form-control" placeholder="" ><?php if($data_posted) { echo set_value('desc_hi'); } else { if(!empty($catRec)) { echo $validH->xssSafe($catRec->desc_hi); } }?></textarea>
                 <small>Max. 1000 characters</small>  
                </div>
                
                
                <div class="form-group">
                        
                 <label class="control-label" for="cat_parent">Parent *</label>
                   <select name="parent" id="parent" class="form-control">
                   <option value="0">None</option>
                    <?php
                    foreach ($topCatArr as $topCatRec)
                    {
                    ?>
                    <option value="<?php echo $validH->xssSafe($topCatRec->id); ?>" <?php 
                    
                    if($data_posted) { 
                        echo set_select('parent', $validH->xssSafe($catRec->id)); 
                    } else {
                        if(!empty($catRec)){ // edit form
                            
                            if($topCatRec->id==$catRec->parent)
                                echo "selected";
                        }
                    }
                    ?>><?php echo $validH->xssSafe($topCatRec->title); ?></option>
                    <?php
                    }
                    ?>
                    </select>
                 </div>
                            
                            
                            
                    <div class="form-group">
                    <label class="control-label" for="display_order">Display Order</label>
                    <input type="number" name="display_order" id="display_order" class="form-control" style="width:100px;" placeholder="" value="<?php 
                    
                    if(!empty($catRec)){ echo $validH->xssSafe($catRec->display_order); } else{ echo set_value('display_order'); }
                    
                    ?>" min="0">  
                    <small>e. g. '1' or '2' or '3'</small>  
                    </div>
                            
                            
                            
                    <div class="form-group">
                        
                    <label class="control-label" for="thumb_image">Thumbnail Image*</label>
                         
                         
                         
                        <?php
						if(is_file(FCPATH.'uploads/category/thumb/'.$catRec->thumb_image))
						{
						?> 
                        
                        <a href="<?php echo base_url(); ?>uploads/category/thumb/<?php echo $validH->xssSafe($catRec->thumb_image); ?>" rel="prettyPhoto" title=""><img src="<?php echo base_url(); ?>uploads/category/thumb/<?php echo $validH->xssSafe($catRec->thumb_image); ?>" style="width:100px;" alt="" ></a>
                            
                            
						&nbsp;&nbsp;&nbsp;
						<a href="<?php echo base_url(); ?>admin/category/deletecatimage?type=thumb_image&id=<?php echo $validH->xssSafe($catRec->id); ?>&<?php echo $csrfToken['name']; ?>=<?php echo md5($csrfToken['hash'].$this->session->csrf_salt); ?>" class="delImageBtn"><img src="<?php echo base_url(); ?>assets/admin/images/icons/remove_32.png" width="25" alt="Delete" align="absmiddle"></a>
                        <br/><br/>
						<?php 
						}
						?>  


                         <input type="file" name="thumb_image" id="thumb_image" class="form-control"  />  
                         <small></small>
                         
  
                         </div>
                         
                         
                         
                         <div class="form-group">
                        
                         <label class="control-label" for="large_image">Large Image (Banner)*</label>
                         
                        <?php
						if(is_file(FCPATH.'uploads/category/'.$catRec->large_image))
						{
						?>
                        
                        
    <a href="<?php echo base_url(); ?>uploads/category/<?php echo $validH->xssSafe($catRec->large_image); ?>" rel="prettyPhoto" title=""><img src="<?php echo base_url(); ?>uploads/category/<?php echo $validH->xssSafe($catRec->large_image); ?>" style="width:300px;" alt="" ></a>
    
                        
						&nbsp;&nbsp;&nbsp;
						<a href="<?php echo base_url(); ?>admin/category/deletecatimage?type=large_image&id=<?php echo $validH->xssSafe($catRec->id); ?>&<?php echo $csrfToken['name']; ?>=<?php echo md5($csrfToken['hash'].$this->session->csrf_salt); ?>" class="delImageBtn"><img src="<?php echo base_url(); ?>assets/admin/images/icons/remove_32.png" width="25" alt="Delete" align="absmiddle"></a>
                        <br/><br/>
						<?php 
						}
						?> 
                         
                         <input type="file" name="large_image" id="large_image" class="form-control"  />  
                         
                         <small></small>
                         
 				
                       
                         </div>
                         
                          
                   
                <?php
                if(!empty($cat_id)) // edit form
                {	
                ?>  
                 <div class="form-group"> 
                <label class="control-label" >Author</label>
				<?php 
             	echo $validH->xssSafe($catRec->fname).' '.$validH->xssSafe($catRec->lname);
				?>
                </div>
                <?php
				}
				?>
                
                
                
                 <?php
                if(!empty($cat_id)) // edit form
                {	
                ?>  
                 <div class="form-group"> 
                <label class="control-label" >Created At</label>
				<?php 
             	echo date("d/m/Y", strtotime($catRec->created_at));
				?>
                </div>
                <?php
				}
				?>
                
                
                
                <div class="form-group">
                  <label class="control-label">Status</label>
                  
                  <div class="form-check">
                    <label class="form-check-label">
                      <input class="form-check-input required_one" type="radio" name="active" value="1" <?php if($data_posted){ echo set_radio('active', '1'); } else { if(!empty($catRec)){ if($catRec->active==1){ echo "checked"; }}}?>>Active
                    </label>
                  </div>
                  
                  <div class="form-check">
                    <label class="form-check-label">
                      <input class="form-check-input required_one" type="radio" name="active" value="0" <?php if($data_posted){ echo set_radio('active', '0'); } else { if(!empty($catRec)){ if($catRec->active==0){ echo "checked"; }}else { echo "checked"; }}?>>Inactive
                    </label>
                  </div>
                  
                  <span class="radioError"></span>
                </div>
                
                
                
                 
           
            
            </div>
            
            <div class="tile-footer">
            
<button class="btn btn-primary" name="add_submit_btn"  value="1"><i class="fa fa-fw fa-lg fa-check-circle"></i><?php echo $validH->xssSafe($action_btn_title); ?></button>

&nbsp;&nbsp;&nbsp;
<a class="btn btn-secondary" href="<?php echo base_url(); ?>admin/category/index"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cancel</a>
            </div>

               
               
        </div>
        
         <?php echo form_close(); ?>
        
        
        
        
        </div>
        
        <div class="clearix"></div>
        
      </div>
        
      
      
      
      
      
      
      
    </main>