<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$validH = $this->validation;
$csrfToken = array('name' => $this->security->get_csrf_token_name(),'hash' => $this->security->get_csrf_hash());

?>
<style>
    .multiselect-custom{ height: 150px; overflow-y: scroll; overflow-x: hidden;border: 2px solid #ced4da;padding: 5px;border-radius: 4px; }
    .multiselect-custom .container.child{margin-left:20px;}
    .container { display: block; position: relative; padding-left: 23px; margin-bottom: 12px; cursor: pointer; }
    .container input { position: absolute; opacity: 0; cursor: pointer; height: 0; width: 0; }
    .checkmark { position: absolute; top: 3px; left: 0; height: 18px; width: 18px; background-color: #c5c5c5; }
    .container:hover input ~ .checkmark { background-color: #ccc; }
    .container input:checked ~ .checkmark { background-color: #009688; }
    .checkmark:after { content: ""; position: absolute; display: none; }
    .container input:checked ~ .checkmark:after { display: block; }
    .container .checkmark:after { left: 7px; top: 3px; width: 5px; height: 10px; border: solid white; border-width: 0 3px 3px 0; -webkit-transform: rotate(45deg); -ms-transform: rotate(45deg); transform: rotate(45deg);}

    .container.radio{display: inline-block; }
    .container.radio .checkmark { border-radius: 50%;}
    p.err-msg {  margin: 2px 0; }
    .hide {  display: none; }
    .image-edit-preview {
        border: 2px solid #ced4da;
        padding: 7px 7px;
        width: 30%;
        position: relative;
    }
    .image-edit-preview img {
        max-width: 100%;
    }
    span.remove-edit-image-remove {
        position: absolute;
        font-size: 24px;
        right: 12px;
        top: 5px;
        cursor: pointer;
    }
    span.remove-edit-image-remove i {  color: #fff;}
    span.remove-edit-image-remove i:hover {  color: #c5c5c5;}
</style>
<div id="audienceCnfModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body"> Please check audience before submission.</div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-danger" id="audienceConfirmedOk">Ok</button>
                <button type="button" data-dismiss="modal" class="btn btn-primary">Cancel</button>
            </div>
        </div>
    </div>
</div>
<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i> Events</h1>
            <p>Webcast Management System</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo base_url().'admin/dashboard';?>"><i class="fa fa-home fa-lg"></i></a></li>
            <li class="breadcrumb-item"><a href="<?php echo base_url().'admin/events/index';?>">Events</a></li>
            <li class="breadcrumb-item"><?php echo $validH->xssSafe($title); ?></li>
        </ul>
    </div>
    <?php if(!empty($success_message)){   ?>
        <div class="alert alert-success alert-dismissable">
            <button type="button" data-dismiss="alert" aria-hidden="true" class="close">&times;</button>
            <?php echo $success_message; ?>
        </div>
    <?php }if(!empty($errors)) { ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error){ ?>
                <p class="err-msg"><?php echo $error; ?></p>
             <?php } ?>
        </div>
    <?php } ?>
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <?php

            $post_url = base_url().'admin/events/add';
            $post_url .= ($editing) ? '?event_id='.$validH->xssSafe($event_id) : '';

            echo form_open_multipart($post_url, array('name' => 'event-form', 'id' => 'eventForm', 'autocomplete'=>'off')); ?>
            <div class="tile">
                <h3 class="tile-title">Events - <?php echo $validH->xssSafe($title); ?> </h3>
                <div class="tile-body">
                    <div class="form-group">
                        <label class="control-label" for="titleEn">Title (en) *</label>
                        <input type="text" name="title_en" id="titleEn" class="form-control" placeholder="Title (En)" value="<?php echo ($editing && !$form_submitting) ? $form_data['title_en'] : set_value('title_en'); ?>">
                        <span class="showError"></span>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="titleHi">Title (hi)</label>
                        <input type="text" name="title_hi" id="titleHi" class="form-control" placeholder="Title (Hi)" value="<?php echo ($editing && !$form_submitting) ? $form_data['title_hi'] : set_value('title_hi'); ?>">
                        <span class="showError"></span>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="titleReg">Title (Regional)</label>
                        <input type="text" name="title_reg" id="titleReg" class="form-control" placeholder="Title (Regional)" value="<?php echo ($editing && !$form_submitting) ? $form_data['title_reg'] : set_value('title_reg'); ?>">
                        <span class="showError"></span>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="desc">Description (en)*</label>
                        <textarea name="desc_en" id="desc_en" class="form-control" placeholder="Description English"  rows="6"><?php echo ($editing && !$form_submitting) ? $form_data['desc_en'] : set_value('desc_en'); ?></textarea>
                        <small>Max. 1000 characters</small>
                        <span class="showError"></span>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="descHi">Description (hi)*</label>
                        <textarea name="desc_hi" id="descHi" class="form-control" placeholder="Description Hindi" rows="6"><?php echo ($editing && !$form_submitting) ? $form_data['desc_hi'] : set_value('desc_hi'); ?></textarea>
                        <small>Max. 1000 characters</small>
                        <span class="showError"></span>
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="keywordsEn">Keywords (en) *</label>
                        <input type="text" name="keywords_en" id="keywordsEn" class="form-control" placeholder="Keywords En" value="<?php echo ($editing && !$form_submitting) ? $form_data['keywords_en'] : set_value('keywords_en'); ?>">
                        <span class="showError"></span>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="keywordsHi">Keywords (hi) *</label>
                        <input type="text" name="keywords_hi" id="keywordsHi" class="form-control" placeholder="Keywords Hi" value="<?php echo ($editing && !$form_submitting) ? $form_data['keywords_hi'] : set_value('keywords_hi'); ?>">
                        <span class="showError"></span>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="nodal_officer">Nodal Officer</label>
                        <input type="text" name="nodal_officer" id="nodalOfficer" class="form-control" placeholder="Nodal Officer" value="<?php echo ($editing && !$form_submitting) ? $form_data['nodal_officer'] : set_value('nodal_officer'); ?>">
                        <span class="showError"></span>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="categories">Categories *</label>
                        <select name="categories[]" id="categories" class="form-control" multiple>
                            <option value="">None</option>
                            <?php foreach ($categories['parents'] as $category_parent) { ?>
                                <option <?php echo (isset($form_data['categories']) && in_array($category_parent->id, $form_data['categories'])) ? "selected" : "" ; ?> value="<?php echo $validH->xssSafe($category_parent->id); ?>"><?php echo $validH->xssSafe($category_parent->title); ?></option>
                                <?php
                                $childs = $categories['childs'][$category_parent->id];
                                if(isset($childs) && !empty($childs)){
                                    ?>
                                        <?php foreach ($childs as $category_child) {  ?>
                                            <option <?php echo (isset($form_data['categories']) && in_array($category_child->id,$form_data['categories'])) ? "selected" : "" ; ?> value="<?php echo $validH->xssSafe($category_child->id); ?>"><?php echo $validH->xssSafe($category_child->title); ?></option>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    <span class="showError"></span>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="owner">Owner *</label>
                        <select name="owner" id="owner" class="form-control">
                            <option value="">None</option>
                            <option <?php echo ("central" == $form_data['owner']) ? "selected" : "" ; ?> value="central">Central</option>
                            <option <?php echo ("state" == $form_data['owner']) ? "selected" : "" ; ?> value="state">State</option>
                        </select>
                        <span class="showError"></span>
                    </div>

                    <div class="state-group">
                        <div class="form-group">
                            <label class="control-label" for="state">State *</label>
                            <select name="state" id="state" class="form-control">
                                <option value="">None</option>
                                <?php foreach ($states as $state){ ?>
                                    <option <?php echo ($state->state_id == $form_data['state']) ? "selected" : "" ; ?> value="<?php echo $validH->xssSafe($state->state_id); ?>"><?php echo $validH->xssSafe($state->state_name); ?></option>
                                <?php } ?>
                            </select>
                            <span class="showError"></span>
                        </div>
                    </div>
                     <div class="state-dept-group">
                        <div class="form-group">
                            <label class="control-label" for="state_department">Department *</label>
                            <select style="display: block;width: 100%;" name="state_department" id="state_department" class="form-control" data-selected="<?php echo $validH->xssSafe($form_data['state_department']); ?>">
                            </select>
                            <span class="showError"></span>
                        </div>
                    </div>
                    <div class="ministry-group">
                        <div class="form-group">
                            <label class="control-label" for="ministry">Ministry *</label>
                            <select style="display: block;width: 100%;" name="ministry" id="ministry" class="form-control">
                                <option value="">None</option>
                                <?php foreach ($ministries as $ministry){ ?>
                                    <option <?php echo ($ministry->orgn_id == $form_data['ministry']) ? "selected" : "" ; ?> value="<?php echo $validH->xssSafe($ministry->orgn_id); ?>"><?php echo $validH->xssSafe($ministry->orgn_name); ?></option>
                                <?php } ?>
                            </select>
                            <span class="showError"></span>
                        </div>
                    </div>
                    <div class="department-group">
                        <div class="form-group">
                            <label class="control-label" for="department">Department *</label>
                            <select style="display: block;width: 100%;" name="department" id="department" class="form-control" data-selected="<?php echo $validH->xssSafe($form_data['department']); ?>">
                                <option value="">None</option>
                                <?php foreach ($departments as $department){ ?>
                                    <option <?php echo ($department->orgn_id == $form_data['department']) ? "selected" : "" ; ?> data-ministry-id="<?php echo $validH->xssSafe($department->ministry_id); ?>" value="<?php echo $validH->xssSafe($department->orgn_id); ?>"><?php echo $validH->xssSafe($department->orgn_name); ?></option>
                                <?php } ?>
                            </select>
                            <span class="showError"></span>
                        </div>
                    </div>
                    <div class="organization-group">
                        <div class="form-group">
                            <label class="control-label" for="organization">Organization *</label>
                            <select style="display: block;width: 100%;" name="organization" id="organization" class="form-control" data-selected="<?php echo $validH->xssSafe($form_data['organization']); ?>">
                            </select>
                            <span class="showError"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="coordinators">Coordinators *</label>
                        <select id='coordinators' name="coordinators[]" style="display: block;width: 100%;" multiple>
                            <?php foreach ($coordinators as $coordinator){ ?>
                           <option <?php echo (isset($form_data['coordinators']) && in_array($coordinator->id,$form_data['coordinators'])) ? "selected" : "";?> value="<?php echo $validH->xssSafe($coordinator->id); ?>" ><?php echo $validH->xssSafe($coordinator->name); ?></option>
                            <?php } ?>
                        </select>
                        <span class="showError"></span>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="startDate">Start Date *</label>
                        <input type="text" name="start_date" id="startDate" class="form-control datetimepicker" placeholder="Start Date" readonly value="<?php echo ($editing && !$form_submitting) ? $form_data['start_date'] : set_value('start_date'); ?>">
                        <span class="showError"></span>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="startDate">End Date *</label>
                        <input type="text" name="end_date" id="endDate" class="form-control datetimepicker" placeholder="End Date" readonly value="<?php echo ($editing && !$form_submitting) ? $form_data['end_date'] : set_value('end_date'); ?>">
                        <span class="showError"></span>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="thumb_image">Thumbnail Image*</label>
                        <?php if($editing && is_file(FCPATH.$form_data['thumb_image'])){ ?>
                            <div class="image-edit-preview">
                                <a href="<?php echo base_url().$validH->xssSafe($form_data['thumb_image']); ?>" rel="prettyPhoto" title=""><img src="<?php echo base_url().$validH->xssSafe($form_data['thumb_image']); ?>" alt="" ></a>
                                <span class="remove-edit-image-remove">
                                    <i class="fa fa-times-circle" aria-hidden="true"></i>
                                </span>
                            </div>
                        <?php } ?>
                        <div class="image-upload <?php echo ($editing && is_file(FCPATH.$form_data['thumb_image'])) ? "hide" : ""; ?>" >
                            <input type="file" name="thumb_image" id="thumb_image" class="form-control" accept="image/jpeg,image/jpg,image/png" />
                            <input type="hidden" class="imageUploadStatus" name="image_upload_status[thumb_image]" value="0">
                        </div>
                        <span class="showError"></span>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="large_image">Large Image (Banner)*</label>
                        <?php if($editing && is_file(FCPATH.$form_data['large_image'])){ ?>
                            <div class="image-edit-preview">
                                <a href="<?php echo base_url().$validH->xssSafe($form_data['large_image']); ?>" rel="prettyPhoto" title=""><img src="<?php echo base_url().$validH->xssSafe($form_data['large_image']); ?>" alt="" ></a>
                                <span class="remove-edit-image-remove">
                                    <i class="fa fa-times-circle" aria-hidden="true"></i>
                                </span>
                            </div>
                        <?php } ?>
                        <div class="image-upload <?php echo ($editing && is_file(FCPATH.$form_data['large_image'])) ? "hide" : ""; ?>" >
                            <input type="file" name="large_image" id="large_image" class="form-control" accept="image/jpeg,image/jpg,image/png" />
                            <input type="hidden" class="imageUploadStatus" name="image_upload_status[large_image]" value="0">
                        </div>
                        <span class="showError"></span>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Source *</label>
                        <label class="container radio">DD
                            <input type="radio" <?php echo ("dd" == $form_data['source']) ? "checked" : "" ; ?> name="source" value="dd">
                            <span class="checkmark"></span>
                        </label>
                        <label class="container radio">VC
                            <input type="radio" <?php echo ("vc" == $form_data['source']) ? "checked" : "" ; ?> name="source" value="vc">
                            <span class="checkmark"></span>
                        </label>
                        <label class="container radio">Agency
                            <input type="radio" <?php echo ("agency" == $form_data['source']) ? "checked" : "" ; ?> name="source" value="agency">
                            <span class="checkmark"></span>
                        </label>
                        <span class="showError"></span>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Audience *</label>
                        <label class="container radio">NIC
                            <input type="radio" <?php echo ("nic" == $form_data['audience']) ? "checked" : "" ; ?> name="audience" value="nic" checked="checked">
                            <span class="checkmark"></span>
                        </label>
                        <label class="container radio">Public
                            <input type="radio" <?php echo ("public" == $form_data['audience']) ? "checked" : "" ; ?> name="audience" value="public">
                            <span class="checkmark"></span>
                        </label>
                        <span class="showError"></span>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Status *</label>
                        <label class="container radio">Draft
                            <input type="radio" <?php echo ("draft" == $form_data['status'] || $user->role !== 'admin') ? "checked" : "" ; ?> name="status" value="draft" >
                            <span class="checkmark"></span>
                        </label>
                        <?php if(in_array($user->role, array('admin','publisher'))){ ?>
                            <label class="container radio">Published
                                <input type="radio" <?php echo ("published" == $form_data['status']) ? "checked" : "" ; ?> name="status" value="published">
                                <span class="checkmark"></span>
                            </label>
                        <?php } ?>
                        <span class="showError"></span>
                    </div>
                </div>
                <div class="tile-footer">
                    <button class="btn btn-primary" name="event_submit"  value="1"><i class="fa fa-fw fa-lg fa-check-circle"></i><?php echo ($editing) ? 'Update' : 'Add' ?></button>
                    <a class="btn btn-secondary" href="<?php echo base_url(); ?>admin/events/index"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cancel</a>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
        <div class="clearix"></div>
    </div>
</main>
<script>
    jQuery(document).ready(function() {

        $('.state-group,.ministry-group,.department-group,.organization-group,.state-dept-group').hide();

       $("#eventForm").validate({
            rules: {
                title_en: {required: true},
                desc: {required: true},
                desc_en: {required: true},
                desc_hi: {required: true},
                keywords_en: {required: true},
                keywords_hi: {required: true},
                start_date: {required: true},
                'categories[]': {required: true},
                end_date: {required: true},
                source: {required: true},
                owner: {required: true},
                state: {required: true},
                state_department: {required: true},
                ministry: {required: true},
                'coordinators[]': {required: true},

                thumb_image: {required: true, extension: "jpg|jpeg|png"},
                large_image: {required: true, extension: "jpg|jpeg|png"},
                status: {required: true}
            },
            messages: {
                thumb_image: {accept: "Please upload jpg, jpeg, png files only"},
                large_image: {accept: "Please upload jpg, jpeg, png files only"}
            },
            errorPlacement: function (error, element) {
                error.appendTo($(element).parents('div.form-group').find('.showError'));
            }
        });

         var eventDatePickerOptions = { timepicker:false,  format:'d-m-Y', formatDate: 'd-m-Y',scrollInput: false}

        jQuery('#startDate')
            .datetimepicker({
                            ...eventDatePickerOptions,
                            onSelectDate:function(dp,$input){
                                $('#endDate').val('');
                                if($input.val()){
                                    //eventDatePickerOptions.minDate = $input.val();
                                }
                                $('#endDate').datetimepicker({...eventDatePickerOptions });
                            }
        });

        <?php if($editing){ ?>
            eventDatePickerOptions.minDate = '<?php echo $form_data['start_date'];?>';
        <?php } ?>

        jQuery('#endDate').datetimepicker({...eventDatePickerOptions});
        $('.remove-edit-image-remove').on('click',function () {
            var imageHold = $(this).parents('div.image-edit-preview');
            var imageUpload = imageHold.next('div.image-upload');
            imageUpload.find('input.imageUploadStatus').val(1);
            imageHold.remove(); imageUpload.removeClass('hide');

        })
        $('.multiselect-custom input').change(function(){
            if($(this).parent().hasClass('parent')){
                $(this).parents('.cat-group').find('.childs input').prop('checked', this.checked);
            }else {

                if($(this).parents('.childs').find('input:checked').length == $(this).parents('.childs').find('input').length){
                    $(this).parents('.cat-group').find('.parent input').prop('checked', true);
                }else {
                    $(this).parents('.cat-group').find('.parent input').prop('checked', false);
                }
            }
        });
        
        $('select#owner').on('change',function () {
            $('.state-group,.ministry-group,.department-group,.organization-group,.state-dept-group').hide();
            
            var owner = $(this).val();  
            if(owner == '') return;

            if(owner == 'state')  $('.state-group').slideDown();
            else if(owner == 'central') $('.ministry-group').slideDown();


        })
        $('select#ministry').on('change',function () {

            $('.department-group,.organization-group').hide();

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
                        $('.department-group').hide();
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

            //$('.organization-group').hide();
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
        $('select#state').on('change',function () {

            $('.state-dept-group').hide();
            var state_id = $(this).val();

            if(state_id == ''){
                $('.state-dept-group').hide();
                return;
            }
            
            var selected_dept_id = $('#state_department').attr('data-selected');

            $.ajax({
				type: 'GET',
				url:'<?php echo base_url(); ?>/admin/events/get_state_departments?state_id=' + state_id + '&selected_dept_id='+selected_dept_id,
				dataType: 'json',
				success: function(data){
                   
                     if(data.result != ''){
                            $('#state_department').html(data.result);	
                            $('.state-dept-group').show();
                        }else{
                            $('.state-dept-group').hide();
                            $('#state_department').val('');
                        }
                        
				}
		    });

        })

        $('select#owner,select#ministry,select#state').trigger('change');
        
        setTimeout(function(){
         $('select#department').trigger('change');
         }, 900);
    
        $("select#owner").select2({
            placeholder : 'Select Owner..'
        });
        $("select#ministry").select2({
            placeholder : 'Select Ministry..'
        });
        $("select#department").select2({
            placeholder : 'Select Department..'
        });
        $("select#organization").select2({
            placeholder : 'Select Organization..'
        });
        $("select#categories").select2({
            placeholder : 'Select Category...'
        });
        var pageSize = 20;
        $("#coordinators").select2({
            placeholder: "Select Coordinators",
            //minimumInputLength: 3,
            allowClear: true,
            multiselect:true,
            debug:true,
            minimumResultsForSearch: 11,
            closeOnSelect: false,
            ajax: {
                url: "<?php echo base_url().'admin/events/get_ajax_coordinators'; ?>",
                dataType: 'json',
                delay: 250,
                cache: true,
                data: function (params) {
                    return {
                        searchTerm: params.term, // search term
                        pageSize: pageSize,
                        page: params.page || 1
                    };
                },
                processResults: function (data, params) {

                    params.page = params.page || 1;

                    return {
                        results: data.items,
                        pagination: {
                            more: (params.page * pageSize) < data.total_count
                        }
                    };
                }
            },
            createSearchChoice: function(term) {
                if(term.match(/^[a-zA-Z0-9]+$/g))
                    return { id: term, text: term };
            }
        });

        /*$('#eventForm').on('submit', function(e){
            e.preventDefault();

            $('#audienceCnfModal').modal({ backdrop: 'static', keyboard: false })
                .one('click', '#audienceConfirmedOk', function() {
                    $('#eventForm').unbind('submit').submit();
            });
        });*/

    })
</script>