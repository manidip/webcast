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
<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i> Events</h1>
            <p>Webcast Management System</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo base_url().'admin/dashboard';?>"><i class="fa fa-home fa-lg"></i></a></li>
            <li class="breadcrumb-item"><a href="<?php echo base_url().'admin/event_sessions/index';?>">Events</a></li>
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

            $post_url = base_url().'admin/event_sessions/add';
            $post_url .= ($editing) ? '?event_session_id='.$validH->xssSafe($event_session_id) : '';

            echo form_open_multipart($post_url, array('name' => 'event-session-form', 'id' => 'eventSessionForm', 'autocomplete'=>'off')); ?>
            <div class="tile">
                <h3 class="tile-title">Event Sessions - <?php echo $validH->xssSafe($title); ?> </h3>

                <?php if(empty($events)){
                    echo "No Active Events"; return;
                 } ?>
                <div class="tile-body">
                    <div class="events-group">
                        <div class="form-group">
                            <label class="control-label" for="eventId">Event *</label>
                            <select name="event_id" id="eventId" class="form-control">
                                <option value="">None</option>
                                <?php foreach ($events as $event){ ?>
                                    <option <?php echo ($event->id == $form_data['event_id']) ? "selected" : "" ; ?> value="<?php echo $validH->xssSafe($event->id); ?>"><?php echo $validH->xssSafe($event->title_en); ?></option>
                                <?php } ?>
                            </select>
                            <span class="showError"></span>
                        </div>
                    </div>
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
                        <label class="control-label" for="speakers">Speakers ( Separate by ( , ) for multiple )</label>
                        <input type="text" name="speakers" id="speakers" class="form-control" placeholder="Speakers" value="<?php echo ($editing && !$form_submitting) ? $form_data['speakers'] : set_value('speakers'); ?>">
                        <span class="showError"></span>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="vip">VIP</label>
                        <input type="text" name="vip" id="vip" class="form-control" placeholder="VIP" value="<?php echo ($editing && !$form_submitting) ? $form_data['vip'] : set_value('vip'); ?>">
                        <span class="showError"></span>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="startTime">Start Date/Time *</label>
                        <input type="text" name="start_time" id="startTime" class="form-control datetimepicker" placeholder="Start Time" readonly value="<?php echo ($editing && !$form_submitting) ? $form_data['start_time'] : set_value('start_time'); ?>">
                        <span class="showError"></span>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="endTime">End Date/Time *</label>
                        <input type="text" name="end_time" id="endTime" class="form-control datetimepicker" placeholder="End Time" readonly value="<?php echo ($editing && !$form_submitting) ? $form_data['end_time'] : set_value('end_time'); ?>">
                        <span class="showError"></span>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="sessionEmbed">Embed Code*</label>
                        <textarea name="session_embed" id="sessionEmbed" class="form-control" placeholder="Embed Code" rows="6"><?php echo ($editing && !$form_submitting) ? $form_data['session_embed'] : set_value('session_embed'); ?></textarea>
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
                            <input type="file" name="thumb_image" id="thumb_image" class="form-control"  accept="image/jpeg,image/jpg,image/png"/>
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
                            <input type="file" name="large_image" id="large_image" class="form-control"  accept="image/jpeg,image/jpg,image/png"/>
                            <input type="hidden" class="imageUploadStatus" name="image_upload_status[large_image]" value="0">
                        </div>
                        <span class="showError"></span>
                    </div>

                    <div class="form-group">
                        <label class="control-label">Session Status *</label>
                        <label class="container radio">Default
                            <input type="radio" <?php echo ("default" == $form_data['session_status']) ? "checked" : "" ; ?> name="session_status" value="default" checked="checked">
                            <span class="checkmark"></span>
                        </label>
                        <label class="container radio">Closed
                            <input type="radio" <?php echo ("closed" == $form_data['session_status']) ? "checked" : "" ; ?> name="session_status" value="closed">
                            <span class="checkmark"></span>
                        </label>
                        <label class="container radio">VOD
                            <input type="radio" <?php echo ("vod" == $form_data['session_status']) ? "checked" : "" ; ?> name="session_status" value="vod">
                            <span class="checkmark"></span>
                        </label>
                        <span class="showError"></span>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Status *</label>
                        <label class="container radio">Draft
                            <input type="radio" <?php echo ("draft" == $form_data['status']) ? "checked" : "" ; ?> name="status" value="draft" checked="checked">
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
                    <button class="btn btn-primary" name="event_session_submit"  value="1"><i class="fa fa-fw fa-lg fa-check-circle"></i><?php echo ($editing) ? 'Update' : 'Add' ?></button>
                    &nbsp;&nbsp;&nbsp;
                    <a class="btn btn-secondary" href="<?php echo base_url(); ?>admin/event_sessions/index"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cancel</a>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
        <div class="clearix"></div>
    </div>
</main>
<script>
    jQuery(document).ready(function() {

      /*  $("#eventSessionForm").validate({
            rules: {
                event_id : {required: true},
                title_en: {required: true},
                desc: {required: true},
                desc_en: {required: true},
                desc_hi: {required: true},
                keywords_en: {required: true},
                keywords_hi: {required: true},
                start_time: {required: true},
                session_embed: {required: true},
                session_status : {required: true},
                end_time: {required: true},
                source: {required: true},
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
        });*/

        var eventSessionDatePickerOptions = { timepicker:true,  format:'d-m-Y H:i', formatTime: 'H:i a', formatDate: 'd-m-Y', step: 5,minDate: 0, minTime:false,scrollInput: false}

        jQuery('#startTime')
            .datetimepicker({
                            ...eventSessionDatePickerOptions,
                            onSelectDate:function(dp,$input){

                                jQuery('#endTime').val('');
                                eventSessionDatePickerOptions.minDate = $input.val();
                                $('#endTime').datetimepicker({...eventSessionDatePickerOptions})
                            },
                            onSelectTime: function(dp,$input){
                                jQuery('#endTime').val('');
                            }
        });

        jQuery('#endTime').datetimepicker({
            ...eventSessionDatePickerOptions,
            onSelectDate:function(dp,$input){

                var startTime = jQuery('#startTime').val();
                    startTime = startTime.split(' ');

                var inputTime = $input.val();
                    inputTime = inputTime.split(' ');

                if(startTime[0] == inputTime[0]){
                    this.setOptions({
                        minTime: startTime[1]
                    });
                }else {
                    this.setOptions({
                        minTime: false
                    });
                }


        }
        });

        $('.remove-edit-image-remove').on('click',function () {
            var imageHold = $(this).parents('div.image-edit-preview');
            var imageUpload = imageHold.next('div.image-upload');
            imageUpload.find('input.imageUploadStatus').val(1);
            imageHold.remove(); imageUpload.removeClass('hide');

        })

        $('select#eventId').on('change',function (e, data) {

            var eventId = $(this).val();
            if(eventId == '') return;

            if(typeof data == typeof undefined){
                jQuery('#startTime, #endTime').val('');
            }
             $.ajax({
				type: 'GET',
				url:'<?php echo base_url(); ?>/admin/events/get_event?event_id='+eventId,
				dataType: 'json',
                 beforeSend: function(){},
				success: function(data){
                    if(data.result != ''){
                        if(data.result.start_date){
                            eventSessionDatePickerOptions.minDate = data.result.start_date;
                        }
                        if(data.result.end_date){
                            eventSessionDatePickerOptions.maxDate = data.result.end_date;
                        }
                        jQuery('#startTime,#endTime').datetimepicker({ ...eventSessionDatePickerOptions});
                    }
				}
		    });

        })

        $('select#eventId').trigger('change',[{'onload':true}]);

        $("select#eventId").select2({
            placeholder: "Select Event...",
            minimumResultsForSearch: 11
        });
    })
</script>