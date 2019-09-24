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
								window.location.href='<?php echo base_url()?>admin/category/index?msg=deleted';
									
							}
							else
							{
								alert(data);
								window.location.href='<?php echo base_url()?>admin/category/index?msg=error';
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
          <h1><i class="fa fa-dashboard"></i> Categories</h1>
          <p>Webcast Management System</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><a href="<?php echo base_url().'admin/dashboard';?>"><i class="fa fa-home fa-lg"></i></a></li>
           <li class="breadcrumb-item"><a href="<?php echo base_url().'admin/category/index';?>">Categories</a></li>
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
           <h3 class="tile-title">Categories - <?php echo $validH->xssSafe($title); ?> </h3>
            <div class="tile-body">
            
  
    <form name="sortForm" id="sortForm" method="get" class="row" action="" autocomplete="off"> 
    
    
    
    <div class="form-group col-md-6">
     
     
     <div class="row">
    
    
    <div class="form-group col-md-4">
    <input class="form-control" name="search_kw" id="search_kw" type="text" placeholder="Search category..." value="<?php echo $search_kw; // already encoded ?>">
    </div>
    
    
    
    <div class="form-group col-md-4"> 
    <select name="parent" id="parent" class="form-control">
    
   <option value="" <?php if($parent=='') {echo "selected";} ?>>All Categories</option>

   <option value="0" <?php if($parent=='0') {echo "selected";} ?>>With No Parent</option>
   
    <?php
    foreach ($topCatArr as $topCatRec)
    {
    ?>
    <option value="<?php echo $validH->xssSafe($topCatRec->id); ?>" <?php if($topCatRec->id==$parent) {echo "selected";} ?>><?php echo $validH->xssSafe($topCatRec->title); ?></option>
    <?php
    }
    ?>
    </select>
    </div>

    
    
    <div class="form-group col-md-4 align-self-end">
    <button class="btn btn-primary" type="submit" name="search" value="1"><i class="fa fa-fw fa-lg fa-search"></i>Search</button>
    </div>
    
    
    
    </div>
    </div>
    
    
     <div class="form-group col-md-6">
     <div class="row">
     
     
      <label class="control-label col-md-2">Sort by</label>
      <div class="col-md-4">        
        
        <select class="form-control" name="sort_option" id="sort_option">
   
        <option value="category.title|asc" <?php if($sort_option=='category.title|asc'){ echo "selected"; }?>>Title A-Z</option>
        <option value="category.title|desc" <?php if($sort_option=='category.title|desc'){ echo "selected"; }?>>Title Z-A</option>
        <option value="category.created_at|asc" <?php if($sort_option=='category.created_at|asc'){ echo "selected"; }?>>Oldest First</option>
        <option value="category.created_at|desc" <?php if($sort_option=='category.created_at|desc'){ echo "selected"; }?>>Recent First</option>
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
                  <th width="10%">#</th>
                  <th width="25%">Title</th>
                  <th width="25%">Parent</th>
                  <th width="10%">Status</th>
                  <th width="20%">Deatils</th>
                  <th width="10%">Action</th>
                </tr>
              </thead>
              <tbody>
            <?php  
				$c=(($page-1)*$per_page)+1;
              	foreach($categories as $category)
				{
				?>
                <td align="left" valign="top">
				  <?php echo $c; ?>
                  </td>
                  
                  
                  <td align="left" valign="top">
				   <p><?php echo $validH->xssSafe($category->title); ?></p>
                   <p><?php echo $validH->xssSafe($category->title_hi); ?></p>
                  </td>
                  
                  
                  <td align="left" valign="top">
				  
				  <p><?php echo $validH->xssSafe($category->parent_title); ?></p>
                   <p><?php echo $validH->xssSafe($category->parent_title_hi); ?></p>
                 
                  </td>
                  
                  
                   
                  <td align="left" valign="top">
                  <?php 
					if($category->active==1)
					{
						
						echo '<span class="badge badge-success">Published</span>';
					}
					else if($category->active==0)
					{
						echo '<span class="badge badge-danger">Draft</span>';	
					}
					?>
                  </td>
                  
                   <td>
                   
                   
                    <p>Display Order : <span><?php echo $validH->xssSafe($category->display_order); ?></span></p>
                               
                  <p>Created by : <span><?php echo $validH->xssSafe($category->fname).' '.$validH->xssSafe($category->lname); ?></span></p>

                    <p>Created on : <span><?php echo date('d/m/Y', strtotime($category->created_at));?></span></p>
                    
                     <p>Updated on : <span><?php 
                    if(!empty($category->updated_at) && $category->updated_at!='0000-00-00 00:00:00')
                    {
                        echo date('d/m/Y', strtotime($category->updated_at));
                    }
                    else // show create date
                    {
                        echo date('d/m/Y', strtotime($category->created_at));
                    }
                    ?></span></p>
                   
                   

					</td>
                  
                  
                  
                  
                 
                  <td><a href="<?php echo base_url()."admin/category/add?id=".$validH->xssSafe($category->id); ?>">Edit</a> | 
                  
                
                  <a href="<?php echo base_url().'admin/category/delete?id='.$validH->xssSafe($category->id) ?>&<?php echo $csrfToken['name']; ?>=<?php echo md5($csrfToken['hash'].$this->session->csrf_salt); ?>" class="delBtn" title="Delete">Delete</a>
                  
                  
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