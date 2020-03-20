<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$validH=$this->validation;

$csrfToken = array(
    'name' => $this->security->get_csrf_token_name(),
    'hash' => $this->security->get_csrf_hash()
);

?>
<?php
if(empty($usrRec))
{

    ?>
    <script>
        $(document).ready(function(){


            $.validator.addMethod("is_email", is_email, "Please enter valid Email.");
            $.validator.addMethod("isIntegerString", isIntegerString, "Please enter numbers only.");
            $.validator.addMethod("isValidPassword", isValidPassword, "Please enter a valid Password.");
            $.validator.addMethod("passwordFollowsPolicy", passwordFollowsPolicy, "Your password doesn't follow Password Policy.");


            $("#userFormAdd").validate({
                submitHandler: validateUserForm,
                rules:{
                    fname:{
                        required:true
                    },
                    lname:{
                        required:true
                    },
                    email:{
                        required:true,
                        email:true,
                        remote: {
                            url: "<?php echo base_url(); ?>admin/admin_user/check_user_email",
                            type: 'get',
                            async: true
                        }
                    },
                    password:{
                        required:true,
                        isValidPassword:true,
                        minlength:8,
                        maxlength:64,
                        passwordFollowsPolicy:true
                    },
                    cpassword:{
                        equalTo:'#password',
                        required:true,
                        minlength:8,
                        maxlength:64
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
                    role:{
                        required:true
                    },
                    active:{
                        required:true
                    }
                },
                messages:{

                    email:{
                        remote: "Email ID is already in use."
                    },
                    cpassword:{
                        equalTo:"The two passwords don't match."

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


            function validateUserForm(form)
            {

                if(!is_empty($("#password").val()))
                {
                    var sha256pass=sha256($("#password").val());

                    var sha256confirmpassword=makeHash('<?php echo $this->validation->xssSafe($this->session->rand_str); ?>',$("#cpassword").val());

                    $("#password").val(sha256pass);
                    $("#cpassword").val(sha256confirmpassword);
                }

                form.submit();

            }


        });


    </script>
    <?php
}
else
{

    ?>
    <script>
        $(document).ready(function(){


            $.validator.addMethod("is_email", is_email, "Please enter valid Enail.");
            $.validator.addMethod("isIntegerString", isIntegerString, "Please enter numbers only.");
            $.validator.addMethod("isValidPassword", isValidPassword, "Please enter a valid Password.");
            $.validator.addMethod("passwordFollowsPolicy", passwordFollowsPolicy, "Your password doesn't follow Password Policy.");

            $("#userFormEdit").validate({
                submitHandler: validateUserForm,
                rules:{
                    fname:{
                        required:true
                    },
                    lname:{
                        required:true
                    },
                    email:{
                        required:true,
                        email:true,
                        remote: {
                            url: "<?php echo base_url(); ?>admin/admin_user/check_user_email?id=<?php echo $validH->xssSafe($usrRec->id); ?>",
                            type: 'get',
                            async: true
                        }
                    },
                    password:{
                        required:false,
                        isValidPassword:true,
                        minlength:8,
                        maxlength:64,
                        passwordFollowsPolicy:{
                            depends:function(){
                                if($('#password').val()==''){
                                    return false;
                                }
                                else{
                                    return true;
                                }
                            }
                        }
                    },
                    cpassword:{
                        equalTo:'#password',
                        required:false,
                        minlength:8,
                        maxlength:64
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
                    role:{
                        required:true
                    },
                    active:{
                        required:true
                    }
                },
                messages:{

                    email:{
                        remote: "Email ID is already in use."
                    },
                    cpassword:{
                        equalTo:"The two passwords don't match."
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


            function validateUserForm(form)
            {


                if(!is_empty($("#password").val()))
                {

                    var sha256pass=sha256($("#password").val());

                    var sha256confirmpassword=makeHash('<?php echo $this->validation->xssSafe($this->session->rand_str); ?>',$("#cpassword").val());

                    $("#password").val(sha256pass);
                    $("#cpassword").val(sha256confirmpassword);
                }

                form.submit();


            }

        });

    </script>
    <?php
}
?>

<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i> CMS Users</h1>
            <p>Webcast Management System</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo base_url().'admin/dashboard';?>"><i class="fa fa-home fa-lg"></i></a></li>
            <li class="breadcrumb-item"><a href="<?php echo base_url().'admin/admin_user/index';?>">CMS Users</a></li>
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
                    if(empty($usrRec))
                    {
                    ?>
                    <form name="userFormAdd" id="userFormAdd"  method="post" action="" autocomplete="off" enctype="multipart/form-data">
                        <?php
                        }
                        else
                        {
                        ?>
                        <form name="userFormEdit" id="userFormEdit"  method="post" action="" autocomplete="off" enctype="multipart/form-data">
                            <?php
                            }
                            ?>
                            <input type="hidden" name="<?php echo $csrfToken['name']; ?>" value="<?php echo md5($csrfToken['hash'].$this->session->csrf_salt); ?>" />
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="fname">First Name *</label>
                                        <input type="text" name="fname" id="fname" class="form-control" placeholder="" value="<?php if($data_posted){ echo set_value('fname'); } else{ if(!empty($usrRec)){ echo $validH->xssSafe($usrRec->fname); }} ?>" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="lname">Last Name *</label>
                                        <input type="text" name="lname" id="lname" class="form-control" placeholder="" value="<?php if($data_posted){ echo set_value('lname'); } else{ if(!empty($usrRec)){ echo $validH->xssSafe($usrRec->lname); }} ?>" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="email">Email *</label>
                                        <div class="input-icon right">
                                            <input type="text" class="form-control" placeholder="" name="email" id="email" value="<?php if($data_posted){ echo set_value('email'); } else{ if(!empty($usrRec)){ echo $validH->xssSafe($usrRec->email); }} ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="phone">Mobile *</label>
                                        <input type="text" name="mobile" id="mobile" class="form-control" placeholder="" value="<?php if($data_posted){ echo set_value('mobile'); } else{ if(!empty($usrRec)){ echo $validH->xssSafe($usrRec->mobile); }} ?>" >
                                        <small>10 digit mobile number</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row form-cntr">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="password">Password <?php if(empty($usrRec)){?>  *<?php }?></label>
                                        <input type="password" name="password" id="password" class="form-control passwod-polc" placeholder="" value="" >
                                        <a  title="Password should be at least 8 characters long and start with a letter and include at least one number, one uppercase letter, one or more lowercase letters and one special characters:  ? &nbsp; @ &nbsp; $ &nbsp; % &nbsp; & &nbsp; * &nbsp; , &nbsp; ; &nbsp; : "
                                            data-placement="bottom"
                                            data-toggle="tooltip"
                                            data-container="body"
                                            style="float:right;" >
                                            Password Policy
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="cpassword">Retype Password <?php if(empty($usrRec)){?>  *<?php }?></label>
                                        <input type="password" name="cpassword" id="cpassword" class="form-control" placeholder="" value="" >
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="designation">Designation *</label>
                                        <input type="text" name="designation" id="designation" class="form-control" placeholder="" value="<?php if($data_posted){ echo set_value('designation'); } else{ if(!empty($usrRec)){ echo $validH->xssSafe($usrRec->designation); }} ?>" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="organization">Organization *</label>
                                        <input type="text" name="organization" id="organization" class="form-control" placeholder="" value="<?php if($data_posted){ echo set_value('organization'); } else{ if(!empty($usrRec)){ echo $validH->xssSafe($usrRec->organization); }} ?>" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="address">Address</label>
                                        <input type="text" name="address" id="address" class="form-control" placeholder="" value="<?php if($data_posted){ echo set_value('address'); } else{ if(!empty($usrRec)){ echo $validH->xssSafe($usrRec->address); } } ?>" >
                                    </div>
                                </div>
                            </div>
                            <div class="row" >
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="city">City</label>
                                        <input type="text" name="city" id="city" class="form-control" placeholder="" value="<?php if($data_posted){ echo set_value('city'); } else{ if(!empty($usrRec)){ echo $validH->xssSafe($usrRec->city); } } ?>" >
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
                                                    <?php if($data_posted){ echo set_select('state', $stateRec->state_id); } else{ if(!empty($usrRec) && $usrRec->state==$stateRec->state_id){ echo 'selected'; }} ?>
                                                ><?php echo $validH->xssSafe($stateRec->state_name); ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="pin_code">Pincode</label>
                                        <input type="text" name="pin_code" id="pin_code" class="form-control" placeholder="" value="<?php if($data_posted){ echo set_value('pin_code'); } else{ if(!empty($usrRec)){ echo $validH->xssSafe($usrRec->pin_code); } } ?>" >
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="phone" style="width:100%;display:block;" >Phone with STD code</label>
                                        <input type="text" name="std_code" id="std_code" class="form-control" style="width:25%;display:inline-block;" placeholder="" value="<?php if($data_posted){ echo set_value('std_code'); } else{ if(!empty($usrRec)){ echo $validH->xssSafe($usrRec->std_code); } } ?>" >
                                        <em> - </em>
                                        <input type="text" name="phone" id="phone" class="form-control" style="width:60%;display:inline-block;" placeholder="" value="<?php if($data_posted){ echo set_value('phone'); } else{ if(!empty($usrRec)){ echo $validH->xssSafe($usrRec->phone); } } ?>" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="intercom">Intercom</label>
                                        <input type="text" name="intercom" id="intercom" class="form-control" placeholder="" value="<?php if($data_posted){ echo set_value('intercom'); } else{ if(!empty($usrRec)){ echo $validH->xssSafe($usrRec->intercom); } } ?>" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <?php
                                    if(!empty($usrRec))
                                    {
                                        ?>
                                        <div class="form-group">
                                            <label class="control-label" >Created</label>
                                            <?php
                                            if(!empty($usrRec->created) && $usrRec->created!='0000-00-00 00:00:00')
                                            {
                                                echo date('d/m/Y', strtotime($usrRec->created));
                                            }
                                            ?>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label" >Updated</label>
                                            <?php
                                            if(!empty($usrRec->updated) && $usrRec->updated!='0000-00-00 00:00:00')
                                            {
                                                echo date('d/m/Y', strtotime($usrRec->updated));
                                            }
                                            else if(!empty($usrRec->created) && $usrRec->created!='0000-00-00 00:00:00')
                                            {
                                                echo date('d/m/Y', strtotime($usrRec->created));
                                            }
                                            ?>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <fieldset class="form-group radioError">
                                            <label>Status</label>
                                            <div class="form-check">
                                                <label class="form-check-label" for="status_active">
                                                    <input class="form-check-input one_required" id="status_active" name="active"  value="1" type="radio" <?php if($data_posted){ echo set_radio('active', '1'); } else{ if(!empty($usrRec) && $usrRec->active=='1'){ echo 'checked'; }} ?>>Active
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <label class="form-check-label" for="status_inactive">
                                                    <input class="form-check-input one_required" id="status_inactive" name="active" value="0" type="radio" <?php if($data_posted){ echo set_radio('active', '0'); } else{ if(!empty($usrRec) && $usrRec->active=='0'){ echo 'checked'; }} ?>>Inactive
                                                </label>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <?php
                                    if(!empty($usrRec) && $usrRec->role=='admin') // if role of the user is Admin, it can't be changed
                                    {
                                        ?>
                                        <div class="form-group">
                                            <label class="control-label" for="role">Role</label>
                                            Admin
                                        </div>
                                        <?php
                                    }
                                    else // in case of add and edit (if role is not admin)
                                    {
                                        ?>
                                        <div class="form-group">
                                            <fieldset class="form-group radioError">
                                                <label>Role</label>
                                                <div class="form-check">
                                                    <label class="form-check-label" for="role_creator">
                                                        <input type="radio" name="role" id="role_creator" class="form-check-input one_required" value="creator" <?php if($data_posted){ echo set_radio('role', 'creator'); } else{ if(!empty($usrRec) && $usrRec->role=='creator'){ echo 'checked'; }} ?> />Creator
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <label class="form-check-label" for="role_publisher">
                                                        <input type="radio" name="role" id="role_publisher" class="form-check-input one_required" value="publisher" <?php if($data_posted){ echo set_radio('role', 'publisher'); } else{ if(!empty($usrRec) && $usrRec->role=='publisher'){ echo 'checked'; }} ?> />Puiblisher
                                                    </label>
                                                </div>
                                            </fieldset>
                                        </div>
                                        <?php
                                    }
                                    ?>
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

        $("select#state").select2({
            placeholder: "Select State...",
        });

    });
</script>