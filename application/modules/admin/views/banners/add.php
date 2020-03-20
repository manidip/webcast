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
            <h1><i class="fa fa-dashboard"></i> Banners</h1>
            <p>Webcast Management System</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo base_url().'admin/dashboard';?>"><i class="fa fa-home fa-lg"></i></a></li>
            <li class="breadcrumb-item"><a href="<?php echo base_url().'admin/banners/index';?>">Banners</a></li>
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

            $post_url = base_url().'admin/banners/add';
            $post_url .= ($editing) ? '?banner_id='.$validH->xssSafe($banner_id) : '';

            echo form_open_multipart($post_url, array('name' => 'banner-form', 'id' => 'bannerForm', 'autocomplete'=>'off')); ?>
            <div class="tile">
                <h3 class="tile-title">Banners - <?php echo $validH->xssSafe($title); ?> </h3>
                <div class="tile-body">
                    <div class="form-group">
                        <label class="control-label" for="titleEn">Title *</label>
                        <input type="text" name="title_en" id="titleEn" class="form-control" placeholder="Title" value="<?php echo ($editing && !$form_submitting) ? $form_data['title_en'] : set_value('title_en'); ?>">
                        <span class="showError"></span>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="url">URL *</label>
                        <input type="text" name="url" id="url" class="form-control" placeholder="URL" value="<?php echo ($editing && !$form_submitting) ? $form_data['url'] : set_value('url'); ?>">
                        <span class="showError"></span>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="display_order">Display Order</label>
                        <input type="number" name="display_order" id="display_order" class="form-control" style="width:100px;" placeholder="" value="<?php echo ($editing && !$form_submitting) ? $form_data['display_order'] : set_value('display_order'); ?>" min="0">
                        <small>e. g. '1' or '2' or '3'</small>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="large_image">Image (Banner)*</label>
                        <?php if($editing && is_file(FCPATH.$form_data['large_image'])){ ?>
                            <div class="image-edit-preview">
                                <a href="<?php echo base_url().$validH->xssSafe($form_data['large_image']); ?>" rel="prettyPhoto" title=""><img src="<?php echo base_url().$validH->xssSafe($form_data['large_image']); ?>" alt="" ></a>
                                <span class="remove-edit-image-remove">
                                    <i class="fa fa-times-circle" aria-hidden="true"></i>
                                </span>
                            </div>
                        <?php } ?>
                        <div class="image-upload <?php echo ($editing && is_file(FCPATH.$form_data['large_image'])) ? "hide" : ""; ?>" >
                            <input type="file" name="large_image" id="large_image" class="form-control" accept="image/jpeg,image/jpg,image/png"  />
                        </div>
                        <span class="showError"></span>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Status *</label>
                        <label class="container radio">Inactive
                            <input type="radio" <?php echo ("draft" == $form_data['status']) ? "checked" : "" ; ?> name="status" value="draft" checked="checked">
                            <span class="checkmark"></span>
                        </label>
                        <label class="container radio">Active
                            <input type="radio" <?php echo ("published" == $form_data['status']) ? "checked" : "" ; ?> name="status" value="published">
                            <span class="checkmark"></span>
                        </label>
                        <span class="showError"></span>
                    </div>
                </div>
                <div class="tile-footer">
                    <button class="btn btn-primary" name="banner_submit"  value="1"><i class="fa fa-fw fa-lg fa-check-circle"></i><?php echo ($editing) ? 'Update' : 'Add' ?></button>
                    &nbsp;&nbsp;&nbsp;
                    <a class="btn btn-secondary" href="<?php echo base_url(); ?>admin/banners/index"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cancel</a>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
        <div class="clearix"></div>
    </div>
</main>
<script>
    jQuery(document).ready(function() {

        $("#bannerForm").validate({
            rules: {
                title_en: {required: true},
                url: {required: true},
                large_image: {required: true, extension: "jpg|jpeg|png"},
                status: {required: true}
            },
            messages: {
                large_image: {accept: "Please upload jpg, jpeg, png files only"}
            },
            errorPlacement: function (error, element) {
                error.appendTo($(element).parents('div.form-group').find('.showError'));

            }
        });

        $('.remove-edit-image-remove').on('click',function () {
            var imageHold = $(this).parents('div.image-edit-preview');
            var imageUpload = imageHold.next('div.image-upload');
            imageHold.remove(); imageUpload.removeClass('hide');

        })

        
    })
</script>