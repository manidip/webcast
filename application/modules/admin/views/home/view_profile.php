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
    
    
<div class="table-responsive">
    <table class="table table-hover table-bordered">
<tbody>

<tr>
<td width="30%" align="left">First Name</td>
<td width="70%" align="left"><?php echo $validH->xssSafe($usrRec->fname); ?></td>

</tr>


<tr>
<td>Last Name</td>
<td><?php echo $validH->xssSafe($usrRec->lname); ?></td>

</tr>


<tr>
<td>Email</td>
<td><?php echo $validH->encodeEmail($validH->xssSafe($usrRec->email)); ?></td>

</tr>


<tr>
<td>Mobile</td>
<td><?php echo $validH->xssSafe($usrRec->mobile); ?></td>

</tr>


<tr>
<td>Designation</td>
<td> <?php echo $validH->xssSafe($usrRec->designation); ?></td>

</tr>


<tr>
<td>Organization</td>
<td> <?php echo $validH->xssSafe($usrRec->organization); ?></td>

</tr>


<tr>
<td>Address</td>
<td> <?php echo $validH->xssSafe($usrRec->address); ?></td>

</tr>


<tr>
<td>City</td>
<td> <?php echo $validH->xssSafe($usrRec->city); ?></td>

</tr>


<tr>
<td>State</td>
<td> <?php echo $validH->xssSafe($usrRec->state_name); ?></td>

</tr>



<tr>
<td>Pin Code</td>
<td> <?php echo $validH->xssSafe($usrRec->pin_code); ?></td>

</tr>




<tr>
<td>Phone with STD code</td>
<td><?php echo $validH->xssSafe($usrRec->std_code);  ?> 

<em> - </em>
<?php echo $validH->xssSafe($usrRec->phone); ?></td>

</tr>


<tr>
<td>Intercom</td>
<td><?php echo $validH->xssSafe($usrRec->intercom);  ?> 
</td>

</tr>
    


<tr>
<td>Created</td>
<td> <?php echo date("d/m/Y", strtotime($usrRec->created)); ?></td>

</tr>     


<tr>
<td>Updated</td>
<td> <?php echo date("d/m/Y", strtotime($usrRec->updated)); ?></td>

</tr>   


<tr>
<td>Status</td>
<td><?php if($usrRec->active==1){ echo '<span class="label label-sm label-success">Active</span>'; } else if($usrRec->active==0){ echo '<span class="label label-sm label-danger">Inactive</span>'; } ?>       </td>

</tr>     


<tr>
<td>Role</td>
<td><?php echo $validH->xssSafe($usrRec->role);  ?> 

</tr>     


<tr>
<td></td>
<td><a href="<?php echo base_url().'admin/home/edit_profile'; ?>"  class="btn btn-sm btn-primary" title="Edit">Edit</a></td>

</tr>   



</tbody>
</table>

</div>
            
            
            
            </div>
          </div>
        </div>
      </div>
      
           
      
      
    </main>


