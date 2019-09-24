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
						   
						   
		///////////////validations for form//////////////////
	$("#add-category-form").validate({
	
		rules: {
			title:{
				required: true
			},
			description:{
				required: true
			}
		},
		messages:{
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
						   
						   
						   
						   
	$("#sort_option,#ipp").on("change", function(){
	
		$("#sortForm").trigger('submit');
	
	});
						   
	$('.delBtn').on('click', function(e){
									
	
			e.preventDefault();
	
			var href= $(this).attr('href');
	
			$('#delCnfModal').modal({ backdrop: 'static', keyboard: false }).one('click', '#delBtn2', function() {		
	
				$.ajax({
						url: href,
						context:document.body,
						success: function(data){	
							if(data=='deleted')
							{
								window.location.href='<?php echo base_url()?>ims/invitee_field/categories?msg=deleted';	
							}
							else
							{
								alert(data);
								window.location.href='<?php echo base_url()?>ims/invitee_field/categories?cat_id=<?php echo $validH->xssSafe($catRec->cat_id); ?>&msg=delete_error';
							}
						}
				});	
				
				
			});
		
	
		});
});	
</script>



<main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fa fa-dashboard"></i> <?php echo $validH->xssSafe($title); ?></h1>
          <p>Webcast Management System</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><a href="<?php echo base_url().'ims/dashboard';?>"><i class="fa fa-home fa-lg"></i></a></li>
           <li class="breadcrumb-item">Invitee Fields</li>
          <li class="breadcrumb-item"><?php echo $validH->xssSafe($title); ?></li>
        </ul>
      </div>
      
      
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
        <div class="col-md-12">
          <div class="tile">
            <h3 class="tile-title"> <?php echo $validH->xssSafe($action_title); ?></h3>
            <div class="tile-body">
            
            <?php echo form_open_multipart(base_url()."ims/invitee_field/categories?cat_id=".$validH->xssSafe($cat_id), array('name' => 'add-category-form', 'id' => 'add-category-form', 'autocomplete'=>'off', 'class'=>'row')); ?>

        	 
             
             
                <div class="form-group col-md-6">
              
                  <input class="form-control" name="title" id="title" type="text" placeholder="Enter Title" value="<?php 
				  
				  
				  	if($data_posted) // if the form is posted
					{
						echo set_value('title'); 
					}
					else
					{
						if(!empty($catRec))
						{ 
							echo $validH->xssSafe($catRec->title); 
						}
					}
				  ?>">
                </div>
               
                <div class="form-group col-md-6">
                 <input class="form-control" name="description" id="description" type="text" placeholder="Enter Description" value="<?php 
				  
				  
				  	if($data_posted) // if the form is posted
					{
						echo set_value('description'); 
					}
					else
					{
						if(!empty($catRec))
						{ 
							echo $validH->xssSafe($catRec->description); 
						}
					}
				  ?>">
                </div>
    
                
                
                <?php
                
				
				if($userRec->id==1) // only admin (id=1) can add/edit card prefix
				{
				
				?>
                
                <div class="form-group col-md-12 align-self-end">
                
                 <input class="form-control" name="card_prefix" id="card_prefix" type="text" placeholder="Enter Card Prefix" value="<?php 
				  
				  
				  	if($data_posted) // if the form is posted
					{
						echo set_value('card_prefix'); 
					}
					else
					{
						if(!empty($catRec))
						{ 
							echo $validH->xssSafe($catRec->card_prefix); 
						}
					}
				  ?>">
                </div>
                
                <?php
                }
				?>
                
               
              
                <div class="col-md-12 align-self-end" style="text-align:right;">
                
                
                
                
                
                
                  <button class="btn btn-primary" name="add_submit_btn" value="1"><i class="fa fa-fw fa-lg fa-plus-circle"></i><?php echo $validH->xssSafe($action_btn_title); ?></button>
                </div>
              
   
                
              <?php echo form_close(); ?>
            </div>
          </div>
        </div>
        </div>
        
      
      
      <div class="row">
        <div class="col-md-12">
          <div class="tile">
           <h3 class="tile-title"><?php echo $validH->xssSafe($title); ?> </h3>
            <div class="tile-body">
            
            
            
            
       
    
  
  
    <form name="sortForm" id="sortForm" method="get" class="row" action="" autocomplete="off"> 
    
    
    
    <div class="form-group col-md-3">
    <input class="form-control" name="search_kw" id="search_kw" type="text" placeholder="Search category..." value="<?php echo $search_kw; // already encoded ?>">
    </div>
    
    <div class="form-group col-md-3 align-self-end">
    <button class="btn btn-primary" type="submit" name="search" value="1"><i class="fa fa-fw fa-lg fa-search"></i>Search</button>
    </div>
    
    
     <div class="form-group col-md-6">
     <div class="row">
     
     
      <label class="control-label col-md-2">Sort by</label>
      <div class="col-md-4">        
        
        <select class="form-control" name="sort_option" id="sort_option">
   
        <option value="ims_category.title|asc" <?php if($sort_option=='ims_category.title|asc'){ echo "selected"; }?>>Title A-Z</option>
        <option value="ims_category.title|desc" <?php if($sort_option=='ims_category.title|desc'){ echo "selected"; }?>>Title Z-A</option>
        <option value="ims_category.date_created|asc" <?php if($sort_option=='ims_category.date_created|asc'){ echo "selected"; }?>>Oldest First</option>
        <option value="ims_category.date_created|desc" <?php if($sort_option=='ims_category.date_created|desc'){ echo "selected"; }?>>Recent First</option>
        </select>                  
      </div>
      
      
      
      <label class="control-label col-md-2">Items/Page</label>
        <div class="col-md-4">        
        
        <select class="form-control" name="ipp" id="ipp">
        <option value="1" <?php if($ipp==1){ echo "selected"; }?>>1 per page</option>
        <option value="3" <?php if($ipp==3){ echo "selected"; }?>>3 per page</option>
        <option value="5" <?php if($ipp==5){ echo "selected"; }?>>5 per page</option>
        <option value="10" <?php if($ipp==10){ echo "selected"; }?>>10 per page</option>
        <option value="20" <?php if($ipp==20){ echo "selected"; }?>>20 per page</option>
        <option value="50" <?php if($ipp==50){ echo "selected"; }?>>50 per page</option>
        </select>              
      </div>
      
      
      </div>
    </div>

    </form>
    

    
    
    <?php

	if(!empty($categories))
	{
	?>
    

    <div class="row">                            
    <div class="col-md-12">
     <?php echo $total_rows; ?></strong> records</span>
    </div>
    </div>
    
    
    
    <div class="table-responsive">
     <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Title</th>
                  <th>Card Prefix</th>
                  <th>Created</th>
                  <th>Updated</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
            <?php  
				$c=1;
              	foreach($categories as $category)
				{
				?>
                <tr>
                  <td><?php echo $c; ?></td>
                  <td>
				  
				  <?php echo $validH->xssSafe($category->title); ?>
                  <small>
                  <p><?php echo $validH->xssSafe($category->description); ?></p>
                  </small>
                  </td>
                  
                  
                  <td>
				  
				  <?php echo $validH->xssSafe($category->card_prefix); ?>
                 
                  </td>
                  
                   <td>
                  <?php 
					if(!empty($category->date_created) && $category->date_created!='0000-00-00 00:00:00')
					{
						echo date('d/m/Y', strtotime($category->date_created));
					}
					?>
					</td>
                  
                  <td>
                  <?php 
					if(!empty($category->date_updated) && $category->date_updated!='0000-00-00 00:00:00')
					{
						echo date('d/m/Y', strtotime($category->date_updated));
					}
					else if(!empty($category->date_created) && $category->date_created!='0000-00-00 00:00:00')
					{
						echo date('d/m/Y', strtotime($category->date_created));
					}
					?>
					</td>
                  
                  
                  
                 
                  <td><a href="<?php echo base_url()."ims/invitee_field/categories?cat_id=".$validH->xssSafe($category->id); ?>">Edit</a> | 
                  
                
                  <a href="<?php echo base_url().'ims/invitee_field/delete_category?cat_id='.$validH->xssSafe($category->id) ?>&<?php echo $csrfToken['name']; ?>=<?php echo md5($csrfToken['hash'].$this->session->csrf_salt); ?>" class="delBtn" title="Delete">Delete</a>
                  
                  
                  </td>
                </tr>
                <?php
                $c++;
				}
				?>
              
                 
              </tbody>
            </table>
            </div>
            
            <div class="row">
            <div class="col-md-12">
            <?php echo $paging_links; ?>
            </div>
            </div> 
            

            
            <?php
	}
	else
	{
	?>
    <div class="alert alert-danger">
	No record found!
	</div>
    <?php
	}
	?>
    
    
            
            
            
            </div>
          </div>
        </div>
      </div>
      
      
      
      
    </main>