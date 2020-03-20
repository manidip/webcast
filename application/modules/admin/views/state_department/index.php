<?php
   /**
    * Created by PhpStorm.
    * User: manidip
    * Date: 9/24/2019
    * Time: 9:05 AM
    */
   
   defined('BASEPATH') OR exit('No direct script access allowed');
   
   $validH=$this->validation;
   
 
   
   ?>
<style>
   input[type="text"]{
      padding: 5px;
   }
   input.error {
    border: 1px solid red;
   }
   span.success{
      color: #155724;
      font-weight: 600;
   }
   span.error{
      color: #dc3545;
      font-weight: 600;
   }
</style>
<main class="app-content">
   <div class="app-title">
      <div>
         <h1><i class="fa fa-dashboard"></i> State Departments</h1>
         <p>Webcast Management System</p>
      </div>
      <ul class="app-breadcrumb breadcrumb">
         <li class="breadcrumb-item"><a href="<?php echo base_url().'admin/dashboard';?>"><i class="fa fa-home fa-lg"></i></a></li>
         <li class="breadcrumb-item"><a href="<?php echo base_url().'admin/state_department/index';?>">State Departments</a></li>
         <li class="breadcrumb-item"><?php echo $validH->xssSafe($title); ?></li>
      </ul>
   </div>
   <?php if(!empty($success_message)){   ?>
   <div class="alert alert-success alert-dismissable">
      <button type="button" data-dismiss="alert" aria-hidden="true" class="close">&times;</button>
      <?php echo $success_message; ?>
   </div>
   <?php }if(!empty($error_message)) { ?>
   <div class="alert alert-danger alert-dismissable">
      <button type="button" data-dismiss="alert" aria-hidden="true" class="close">&times;</button>
      <?php echo $error_message; ?>
   </div>
   <?php } ?>
   <div class="row">
      <div class="col-md-12">
         <div class="tile">
            <div class="tile-body">
               <form name="sortForm" id="sortForm" method="get" class="row" action="" autocomplete="off">
                  <div class="form-group col-md-8">
                     <div class="row">
                        <div class="form-group col-md-8">
                           <input class="form-control" name="search_kw" id="search_kw" type="text" placeholder="Search Departments..." value="<?php echo $search_kw; ?>">
                        </div>
                        <div class="form-group col-md-4 px-0 mx-0 align-left">
                           <button class="btn btn-primary" type="submit" name="search" value="1"><i class="fa fa-fw fa-lg fa-search"></i>Search</button>
                        </div>
                     </div>
                  </div>
                  <div class="form-group col-md-4">
                     <div class="row">
                     <div class="col-md-6 pr-0 mx-0">
                     </div>
                     <div class="col-md-6 pr-0 mx-0">
                           <select class="form-control" name="ipp" id="ipp">
                              <option value="">--Items per page--</option>
                              <option value="10" <?php if($ipp==10){ echo "selected"; }?>>10 per page</option>
                              <option value="20" <?php if($ipp==20){ echo "selected"; }?>>20 per page</option>
                              <option value="50" <?php if($ipp==50){ echo "selected"; }?>>50 per page</option>
                           </select>
                        </div>
                        <!-- <div class="col-md-6 ml-0">
                           <select class="form-control" name="type" id="type">
                              <option value="">--Type--</option>
                              <option value="all" <?php //echo ('all' == $status) ? "selected" : ""; ?>>All</option>
                              <option value="ministry" <?php //echo ('ministry' == $status) ? "selected" : ""; ?>>Ministry</option>
                              <option value="department" <?php //echo ('department' == $status) ? "selected" : ""; ?>>Department</option>
                              <option value="organization" <?php //echo ('organization' == $status) ? "selected" : ""; ?>>Organization</option>
                           </select>
                        </div> -->
                     </div>
                  </div>

               </form>
               <?php
                  if(!empty($state_department_data['departments']))
                  {
                    $current_url = explode('?msg', $_SERVER['REQUEST_URI']);

                    $current_url = $current_url[0];
                    $url = base_url().'admin/state_department/index';
                  ?>
               <?php echo form_open_multipart($current_url, array('name' => 'department_form', 'id' => 'departmentsForm', 'autocomplete'=>'off')); ?>
                  <div class="row">
                     <div class="col-md-12">
                        <?php echo $state_department_data['total_items']; ?></strong> records</span>
                     </div>
                  </div>
                  <div class="table-responsive">
                     <table class="table table-bordered table-hover">
                        <thead>
                           <tr>
                              <th width="20%">#</th>
                              <th width="50%">Name</th>
                              <th width="20%">Alias</th>
                              <th width="10%">Action</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php foreach($state_department_data['departments'] as $department){?>
                           <tr>
                              <td align="left" valign="top"> <?php echo $department->department_id; ?> </td>
                              <td align="left" valign="top"> <?php echo $department->department_name; ?></td>
                              <td align="left" valign="top">
                                 <input class="<?php echo array_key_exists($department->department_id, $existing_alias) ? "error" : ""; ?>" value="<?php echo (!$data_posted || array_key_exists($department->department_id, $existing_alias)) ? $department->alias : set_value('alias['.$department->department_id.']'); ?>" name="alias[<?php echo $department->department_id; ?>]" type="text" style="width:100%" value="<?php echo $department->alias; ?>">
                                 <?php if(array_key_exists($department->department_id, $existing_alias)){ ?>
                                    <span class="error"><?php echo $existing_alias[$department->department_id]; ?></span>
                                 <?php } ?>
                              </td>
                              <td align="left" valign="top">
                                 <button class="btn btn-sm btn-info btn-block updateAlias" type="button"  data-id="<?php echo $department->department_id; ?>">Update</button>
                              </td>
                           </tr>
                           <?php } ?>
                        </tbody>
                     </table>
                  </div>
                  <div class="clearfix">
                     <button type="submit" class="btn btn-sm btn-info pull-right">Update</button>
                  </div>
                  <?php echo form_close(); ?>
               <div class="row">
                  <div class="col-md-12">
                     <?php echo $pagination_links; ?>
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
<script>
   jQuery(document).ready(function($){

       $("#sort_option,#ipp").on("change", function(){
           $("#sortForm").trigger('submit');
       });
       $("select#ipp").select2({
           placeholder: "Items Per Page...",
           minimumResultsForSearch: -1
       });
       $("#departmentsForm").on('submit',function (e) {

          var inputs = $(this).find('input[type="text"]');
          var error = false;
          inputs.each(function name() {
           if($(this).trigger('checkAlias').data('error'))
            error = true;
          })

          if(error) return false
          else return true;
       })
       $('#departmentsForm input[type="text"]').on('checkAlias keyup',function() {
          
         $(this).val($(this).val().toLowerCase());

          if($(this).val() !== '' && $(this).val().trim().indexOf(' ') > 0){
      
            if($(this).next('span').length){
                  $(this).next('span').text('No Space allowed');
               }else{
                  
                  $(this).addClass('error').after('<span class="error">No Space allowed</span>');
               }
               $(this).data('error',true);
          }else{
            $(this).removeClass('error').next('span').remove();
            $(this).data('error',false);
          }

       })
       $('.updateAlias').click(function(event){

         event.preventDefault()
         var updateBtn = $(this);
         var updateBtnAjaxRunning = false;
         var stateDepartmentId = updateBtn.attr('data-id');
         var alias = updateBtn.parents('tr').find('input').val();

         if(updateBtnAjaxRunning){
            alert('One Request still in Progress..');
            return;
         }

         if(updateBtn.parents('tr').find('input').trigger('checkAlias').data('error')){
            return false;
         }

         $.ajax({
                url: "<?php echo base_url().'/admin/state_department/update_alias'; ?>",
                type: 'GET',
                dataType: 'json',
                data: { alias: alias, department_id : stateDepartmentId},
                beforeSend: function name() {
                  updateBtn.attr('disabled', 'disabled');
                  updateBtnAjaxRunning = true;
                },
                success: (response) =>  {
                  
                   if(response.error){
                     updateBtn.parents('tr').find('input').addClass('error').after('<span class="error">' + response.error + '</span>');
                     updateBtn.removeAttr('disabled');
                   }else{
                      if(updateBtn.parents('tr').find('input').next('span')){
                        updateBtn.parents('tr').find('input').next('span').remove().after('<span class="success">' + response.success + '</span>');
                      }
                     updateBtn.parents('tr').find('input').removeClass('error').after('<span class="success">' + response.success + '</span>');
                     updateBtn.removeAttr('disabled');
                   }
                   aliasUpdateTimeout = setTimeout(() => {
                     updateBtn.parents('tr').find('input').next('span').remove();
                   }, 3000);
                   updateBtnAjaxRunning = false;
                },
                error: function name(params) {
                  updateBtn.parents('tr').find('input').after('<span class="error">Unable to update</span>');
                  setTimeout(() => {
                     updateBtn.parents('tr').find('input').next('span').remove();
                   }, 3000);
                }

       });
       
      });
})
</script>