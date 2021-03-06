<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$validH=$this->validation;

$csrfToken = array(
    'name' => $this->security->get_csrf_token_name(),
    'hash' => $this->security->get_csrf_hash()
);

?>
<?php
if(empty($corRec))
{

    ?>
    <script>
        $(document).ready(function(){

            $.validator.addMethod("isIntegerString", isIntegerString, "Please enter numbers only.");

            $("#coordinatorFormAdd").validate({
                rules:{
                    name:{
                        required:true
                    },
                    email:{
                        required:true,
                        email:true,
                        remote: {
                            url: "<?php echo base_url(); ?>admin/coordinator/check_user_email",
                            type: 'get',
                            async: true,
                        }
                    },
                    mobile:{
                        isIntegerString:true,
                        minlength:10,
                        maxlength:10
                    },
                    std_code:{
                        required:false,
                        isIntegerString:true,
                        minlength:3,
                        maxlength:5
                    },
                    phone:{
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
                    },
                    active:{
                        required:true
                    }
                },
                messages:{

                    email:{
                        remote: "Email ID is already in use."
                    }

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


    </script>
    <?php
}
else
{

    ?>
    <script>
        $(document).ready(function(){

            $("#coordinatorFormEdit").validate({
                rules:{
                    name:{
                        required:true
                    },
                    email:{
                        required:true,
                        email:true,
                        remote: {
                            url: "<?php echo base_url(); ?>admin/coordinator/check_user_email?id=<?php echo $validH->xssSafe($corRec->id); ?>",
                            type: 'get',
                            async: true
                        }
                    },
                    mobile:{
                        required: true,
                        isIntegerString:true,
                        minlength:10,
                        maxlength:10
                    },
                    std_code:{
                        required:false,
                        isIntegerString:true,
                        minlength:3,
                        maxlength:5
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
                    },
                    active:{
                        required:true
                    }
                },
                messages:{

                    email:{
                        remote: "Email ID is already in use."
                    }
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

    </script>
    <?php
}
?>

<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i> Coordinators</h1>
            <p>Webcast Management System</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo base_url().'admin/dashboard';?>"><i class="fa fa-home fa-lg"></i></a></li>
            <li class="breadcrumb-item"><a href="<?php echo base_url().'admin/coordinator/index';?>">Coordinators</a></li>
            <li class="breadcrumb-item"><?php echo $validH->xssSafe($title); ?></li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <h3 class="tile-title"><?php echo $validH->xssSafe($title); ?> </h3>
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
                    <?php
                    if(empty($id))
                    {
                    ?>
                    <form name="coordinatorFormAdd" id="coordinatorFormAdd"  method="post" action="<?php echo base_url().'admin/coordinator/add'; ?>" autocomplete="off" enctype="multipart/form-data">
                        <?php
                        }
                        else
                        {
                        ?>
                        <form name="coordinatorFormEdit" id="coordinatorFormEdit"  method="post" action="<?php echo base_url().'admin/coordinator/add?id='.$validH->xssSafe($id); ?>" autocomplete="off" enctype="multipart/form-data">
                            <?php
                            }
                            ?>
                            <input type="hidden" name="<?php echo $csrfToken['name']; ?>" value="<?php echo md5($csrfToken['hash'].$this->session->csrf_salt); ?>" />
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="name">Name *</label>
                                        <input type="text" name="name" id="name" class="form-control" placeholder="" value="<?php if($data_posted){ echo set_value('name'); } else{ if(!empty($corRec)){ echo $validH->xssSafe($corRec->name); }} ?>" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="email">Email *</label>
                                        <div class="input-icon right">
                                            <input type="text" class="form-control" placeholder="" name="email" id="email" value="<?php if($data_posted){ echo set_value('email'); } else{ if(!empty($corRec)){ echo $validH->xssSafe($corRec->email); }} ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="designation">Designation</label>
                                        <input type="text" name="designation" id="designation" class="form-control" placeholder="" value="<?php if($data_posted){ echo set_value('designation'); } else{ if(!empty($corRec)){ echo $validH->xssSafe($corRec->designation); }} ?>" >
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="phone">Mobile</label>
                                        <input type="text" name="mobile" id="mobile" class="form-control" placeholder="" value="<?php if($data_posted){ echo set_value('mobile'); } else{ if(!empty($corRec)){ echo $validH->xssSafe($corRec->mobile); }} ?>" >
                                        <small>10 digit mobile number</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="address">Address</label>
                                        <input type="text" name="address" id="address" class="form-control" placeholder="" value="<?php if($data_posted){ echo set_value('address'); } else{ if(!empty($corRec)){ echo $validH->xssSafe($corRec->address); } } ?>" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="pin_code">Pincode</label>
                                        <input type="text" name="pin_code" id="pin_code" class="form-control" placeholder="" value="<?php if($data_posted){ echo set_value('pin_code'); } else{ if(!empty($corRec)){ echo $validH->xssSafe($corRec->pin_code); } } ?>" >
                                    </div>
                                </div>
                            </div>
                            <div class="row" >
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="city">City</label>
                                        <input type="text" name="city" id="city" class="form-control" placeholder="" value="<?php if($data_posted){ echo set_value('city'); } else{ if(!empty($corRec)){ echo $validH->xssSafe($corRec->city); } } ?>" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="state" >State</label>
                                        <select name="state" id="state" class="form-control" >
                                            <option value="">Select</option>
                                            <?php
                                            foreach($stateArr as $stateRec)
                                            {
                                                ?>
                                                <option value="<?php echo $validH->xssSafe($stateRec->state_id); ?>"
                                                    <?php if($data_posted){ echo set_select('state', $stateRec->state_id); } else{ if(!empty($corRec) && $corRec->state==$stateRec->state_id){ echo 'selected'; }} ?>
                                                ><?php echo $validH->xssSafe($stateRec->state_name); ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="ministry-group">
                                <div class="form-group">
                                    <label class="control-label" for="ministry">Ministry</label>
                                    <select name="ministry" id="ministry" class="form-control" style="display: block;width: 100%;">
                                        <option value="">None</option>
                                        <?php foreach ($ministries as $ministry){ ?>
                                            <option <?php echo (($data_posted && set_value('department') == $ministry->orgn_id) || $corRec->ministry == $ministry->orgn_id) ? "selected" : "" ; ?> value="<?php echo $validH->xssSafe($ministry->orgn_id); ?>"><?php echo $validH->xssSafe($ministry->orgn_name); ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="department-group">
                                <div class="form-group">
                                    <label class="control-label" for="department">Department</label>
                                    <select style="display: block;width: 100%;" name="department" id="department" class="form-control" data-selected="<?php if($data_posted){ echo set_value('department'); } else{ if(!empty($corRec)){ echo $validH->xssSafe($corRec->department); } } ?>">
                                        <option value="">None</option>
                                        <?php foreach ($departments as $department){ ?>
                                            <option <?php echo ($department->orgn_id == $form_data['department']) ? "selected" : "" ; ?> data-ministry-id="<?php echo $validH->xssSafe($department->ministry_id); ?>" value="<?php echo $validH->xssSafe($department->orgn_id); ?>"><?php echo $validH->xssSafe($department->orgn_name); ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="organization-group">
                                <div class="form-group">
                                    <label class="control-label" for="organization">Organization</label>
                                    <select style="display: block;width: 100%;" name="organization" id="organization" class="form-control" data-selected="<?php if($data_posted){ echo set_value('organization'); } else{ if(!empty($corRec)){ echo $validH->xssSafe($corRec->organization); } } ?>">
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="phone" style="width:100%;display:block;" >Phone with STD code</label>
                                        <input type="text" name="std_code" id="std_code" class="form-control" style="width:25%;display:inline-block;" placeholder="" value="<?php if($data_posted){ echo set_value('std_code'); } else{ if(!empty($corRec)){ echo $validH->xssSafe($corRec->std_code); } } ?>" >
                                        <em> - </em>
                                        <input type="text" name="phone" id="phone" class="form-control" style="width:60%;display:inline-block;" placeholder="" value="<?php if($data_posted){ echo set_value('phone'); } else{ if(!empty($corRec)){ echo $validH->xssSafe($corRec->phone); } } ?>" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="intercom">Intercom</label>
                                        <input type="text" name="intercom" id="intercom" class="form-control" placeholder="" value="<?php if($data_posted){ echo set_value('intercom'); } else{ if(!empty($corRec)){ echo $validH->xssSafe($corRec->intercom); } } ?>" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <?php
                                    if(!empty($corRec))
                                    {
                                        ?>
                                        <div class="form-group">
                                            <label class="control-label" >Created</label>
                                            <?php
                                            if(!empty($corRec->created_at) && $corRec->created_at!='0000-00-00 00:00:00')
                                            {
                                                echo date('d/m/Y', strtotime($corRec->created_at));
                                            }
                                            ?>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label" >Updated</label>
                                            <?php
                                            if(!empty($corRec->updated_at) && $corRec->updated_at!='0000-00-00 00:00:00')
                                            {
                                                echo date('d/m/Y', strtotime($corRec->updated_at));
                                            }
                                            else if(!empty($corRec->created_at) && $corRec->created_at!='0000-00-00 00:00:00')
                                            {
                                                echo date('d/m/Y', strtotime($corRec->created_at));
                                            }
                                            ?>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <fieldset class="form-group radioError">
                                            <label>Status</label>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input class="form-check-input one_required" id="status_active" name="active"  value="1" type="radio" <?php if($data_posted){ echo set_radio('active', '1'); } else{ if(!empty($corRec) && $corRec->active=='1'){ echo 'checked'; }} ?>>Active
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input class="form-check-input one_required" id="status_inactive" name="active" value="0" type="radio" <?php if($data_posted){ echo set_radio('active', '0'); } else{ if(!empty($corRec) && $corRec->active=='0'){ echo 'checked'; }} ?>>Inactive
                                                </label>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <input type="submit" name="add_submit_btn" value="<?php echo $validH->xssSafe($action_btn_title); ?>" class="btn btn-primary">
                            </div>
                        </form>
                </div>
            </div>
        </div>
    </div>
</main>
<script>
    jQuery(document).ready(function($){
        $('.department-group,.organization-group').hide();

        $('select#ministry').on('change',function () {

            $('.department-group,.organization-group').hide();
            $('.department-group,.organization-group').val('');

            var ministry_id = $(this).val();
            if(ministry_id == ''){
                $('.department-group').hide();
                return;
            }

            var selected_dept_id = $('#department').attr('data-selected');
            var selected_org_id = $('#organization').attr('data-selected');

            $.ajax({
                type: 'GET',
                url:'<?php echo base_url(); ?>/admin/events/get_ug_data?type=min_dept&ministry_id='+ministry_id+'&selected_dept_id='+selected_dept_id,
                dataType: 'json',
                success: function(data){
                    if(data.result != ''){
                        $('#department').html(data.result);
                        $('.department-group').show();

                    }else{
                        $('.department-group').hide().val('');
                        $('#department').val('');
                        $.ajax({
                            type: 'GET',
                            url:'<?php echo base_url(); ?>/admin/events/get_ug_data?type=min_org&ministry_id='+ministry_id+'&selected_org_id='+selected_org_id,
                            dataType: 'json',
                            success: function(data){
                                if(data.result != ''){
                                    $('#organization').html(data.result);
                                    $('.organization-group').show();
                                }else{
                                    $('.organization-group').hide();
                                    $('#organization').val('');
                                }

                            }
                        });
                    }

                }
            });
        })
        $('select#department').on('change',function () {

            $('.organization-group').hide();
            $('#organization').val('');

            var dept_id = $(this).val();
            if(dept_id == ''){
                $('.department-group').hide();
                return;
            }

            var ministry_id = $('#ministry').val();
            var selected_org_id = $('#organization').attr('data-selected');

            $.ajax({
                type: 'GET',
                url:'<?php echo base_url(); ?>/admin/events/get_ug_data?type=dept_org&dept_id='+dept_id+'&ministry_id='+ministry_id+'&selected_org_id='+selected_org_id,
                dataType: 'json',
                success: function(data){
                    if(data.result != ''){
                        $('#organization').html(data.result);
                        $('.organization-group').show();

                    }else{
                        $('.organization-group').hide();
                        $('#organization').val('');
                    }

                }
            });

        })
        $('select#ministry').trigger('change');

        setTimeout(function(){
            $('select#department').trigger('change');
        }, 900);

        $("select#ministry").select2({
            placeholder : 'Select Ministry..'
        });
        $("select#department").select2({
            placeholder : 'Select Department..'
        });
        $("select#organization").select2({
            placeholder : 'Select Organization..'
        });
        $("select#state").select2({
            placeholder: "Select State...",
            allowClear: true
        })



    })
</script>