<?php
/**
 * Created by PhpStorm.
 * User: manidip
 * Date: 9/24/2019
 * Time: 9:05 AM
 */

defined('BASEPATH') OR exit('No direct script access allowed');

$validH=$this->validation;

$csrfToken = array(
    'name' => $this->security->get_csrf_token_name(),
    'hash' => $this->security->get_csrf_hash()
);

?>

<div id="delCnfModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                Are you sure to delete this record?
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-danger" id="deleteBannerOk">Delete</button>
                <button type="button" data-dismiss="modal" class="btn btn-primary">Cancel</button>
            </div>
        </div>
    </div>
</div>

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
    <?php }if(!empty($error_message)) { ?>
        <div class="alert alert-danger alert-dismissable">
            <button type="button" data-dismiss="alert" aria-hidden="true" class="close">&times;</button>
            <?php echo $error_message; ?>
        </div>
    <?php } ?>

    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <h3 class="tile-title">Banners - <?php echo $validH->xssSafe($title); ?> </h3>
                <div class="tile-body">
                    <form name="sortForm" id="sortForm" method="get" class="row" action="" autocomplete="off">
                        <div class="form-group col-md-6">
                            <div class="row">
                                <div class="form-group col-md-8">
                                    <input class="form-control" name="search_kw" id="search_kw" type="text" placeholder="Search Banners..." value="<?php echo $search_kw; ?>">
                                </div>
                                <div class="form-group col-md-4 align-left">
                                    <button class="btn btn-primary" type="submit" name="search" value="1"><i class="fa fa-fw fa-lg fa-search"></i>Search</button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <div class="row">
                                <div class="col-md-4">
                                </div>
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
                                <div class="col-md-4">
                                    <select class="form-control" name="sort_option" id="sort_option">
                                        <option value="">Sort by</option>
                                            <option value="banners.title_en|asc" <?php echo ($sort_option == 'banners.title_en|asc')? "selected" : "";?>>Title A-Z</option>
                                        <option value="banners.title_en|desc" <?php echo ($sort_option == 'banners.title_en|desc')? "selected" : "";?>>Title Z-A</option>
                                        <option value="banners.created_at|asc" <?php echo ($sort_option == 'banners.created_at|asc')? "selected" : "";?>>Oldest First</option>
                                        <option value="banners.created_at|desc" <?php echo ($sort_option == 'banners.created_at|desc')? "selected" : "";?>>Recent First</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                    <?php
                    if(!empty($banner_data['banners']))
                    {
                        ?>
                        <div class="row">
                            <div class="col-md-12">
                                <?php echo $banner_data['total_items']; ?></strong> records</span>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="25%">Title</th>
                                    <th width="20%">Image</th>
                                    <th width="25%">URL</th>
                                    <th width="5%">Status</th>
                                    <th width="15%">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach($banner_data['banners'] as $banner){ ?>
                                   <tr>
                                    <td align="left" valign="top"> <?php echo $banner->id; ?> </td>
                                    <td align="left" valign="top"><?php echo $banner->title_en; ?></td>
                                    <td align="left" valign="top">
                                        <?php if(is_file(FCPATH.$banner->large_image)){ ?>
                                            <div class="image-edit-preview">
                                                <a href="<?php echo base_url().$validH->xssSafe($banner->large_image); ?>" rel="prettyPhoto" title=""><img src="<?php echo base_url().$validH->xssSafe($banner->large_image); ?>" alt="" width="100px" ></a>
                                            </div>
                                        <?php } ?>

                                    </td>
                                    <td align="left" valign="top"><?php echo $banner->url; ?> </td>
                                    <td align="left" valign="top"><?php echo strtoupper($banner->status); ?></td>
                                    <td align="left" valign="top">
                                        <a class="btn btn-sm btn-info btn-block" target="_blank" href="<?php echo base_url()."admin/banners/add?banner_id=".$validH->xssSafe($banner->id); ?>">Edit</a>
                                        <a class="btn btn-sm btn-danger btn-block deleteBanner" href="<?php echo base_url().'admin/banners/delete?banner_id='.$validH->xssSafe($banner->id) ?>&<?php echo $csrfToken['name']; ?>=<?php echo md5($csrfToken['hash'].$this->session->csrf_salt); ?>" title="Delete">Delete</a>

                                    </td>
                                   </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
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
        $('.deleteBanner').on('click', function(e){
            e.preventDefault();

            var href= $(this).attr('href');

            $('#delCnfModal').modal({ backdrop: 'static', keyboard: false }).one('click', '#deleteBannerOk', function() {
                window.location.href = href;
            });
        });

    });
</script>