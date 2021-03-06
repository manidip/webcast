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

        $("#search_submit_btn").on('click', function(e){

            $("#search").val('1');
            $("#sortForm").submit();

        });


        $("#sort_option,#ipp").on("change", function(){
            $("#search").val('<?php echo $validH->xssSafe($search); ?>');
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
                            window.location.href='<?php echo base_url()?>admin/admin_user/index?msg=deleted';
                        }
                        else
                        {
                            alert(data);
                            window.location.href='<?php echo base_url()?>admin/admin_user/index?msg=delete_error';
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
            <h1><i class="fa fa-dashboard"></i> CMS Users</h1>
            <p>Webcast Management System</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo base_url().'admin/dashboard';?>"><i class="fa fa-home fa-lg"></i></a></li>
            <li class="breadcrumb-item"><a href="<?php echo base_url().'admin/admin_user/index'; ?>">CMS Users</a></li>
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
                <h3 class="tile-title">CMS Users - <?php echo $validH->xssSafe($title); ?> </h3>
                <div class="tile-body">
                    <form name="sortForm" id="sortForm" method="get"  action="" autocomplete="off">
                        <div class="row">
                            <div class="col-md-7">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group ">
                                            <input class="form-control" name="search_kw" id="search_kw" type="text" placeholder="Search by name, email, mobile" value="<?php echo set_value('search_kw'); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group align-self-end">
                                            <button class="btn btn-primary" type="button" name="search_submit_btn" id="search_submit_btn" value="1"><i class="fa fa-fw fa-lg fa-search"></i>Search</button>
                                            <input type="hidden" name="search" id="search" value="<?php echo $validH->xssSafe($search); ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="row ">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <select class="form-control" name="sort_option" id="sort_option">
                                                <option value="admin_user.fname|asc" <?php if($sort_option=='admin_user.fname|asc'){ echo "selected"; }?>>Name A-Z</option>
                                                <option value="admin_user.fname|desc" <?php if($sort_option=='admin_user.fname|desc'){ echo "selected"; }?>>Name Z-A</option>
                                                <option value="admin_user.created|asc" <?php if($sort_option=='admin_user.created|asc'){ echo "selected"; }?>>Oldest First</option>
                                                <option value="admin_user.created|desc" <?php if($sort_option=='admin_user.created|desc'){ echo "selected"; }?>>Recent First</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <select class="form-control" name="ipp" id="ipp">
                                                <option value="1" <?php if($ipp==1){ echo "selected"; }?>>1 per page</option>
                                                <option value="3" <?php if($ipp==3){ echo "selected"; }?>>3 per page</option>
                                                <option value="5" <?php if($ipp==5){ echo "selected"; }?>>5 per page</option>
                                                <option value="10" <?php if($ipp==10){ echo "selected"; }?>>10 per page</option>
                                                <option value="20" <?php if($ipp==20){ echo "selected"; }?>>20 per page</option>
                                                <option value="50" <?php if($ipp==50){ echo "selected"; }?>>50 per page</option>
                                                <option value="200" <?php if($ipp==200){ echo "selected"; }?>>200 per page</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <?php
                    if(!empty($admin_users))
                    {
                        ?>
                        <div class="row">
                            <div class="col-md-12">
                                <?php echo $total_rows; ?> records
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th width="20%">Name</th>
                                    <th width="10%">Role</th>
                                    <th width="20%">Contact</th>
                                    <th width="20%">Organization</th>
                                    <th width="10%">Status</th>
                                    <th width="10%">Created by</th>
                                    <th width="10%">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $c=(($page-1)*$per_page)+1;
                                foreach($admin_users as $admin_user)
                                {
                                    ?>
                                    <tr>
                                        <td align="left" valign="top">
                                            <?php
                                            echo '<p>'.$validH->xssSafe($admin_user->fname).' '.$validH->xssSafe($admin_user->lname).'</p>';

                                            ?>
                                        </td>
                                        <td align="left" valign="top">
                                            <?php
                                            echo '<p>'.$validH->xssSafe($admin_user->role).'</p>';

                                            ?>
                                        </td>
                                        <td align="left" valign="top">
                                            <?php
                                            echo '<p>'.$validH->encodeEmail($admin_user->email).'</p>';
                                            echo '<p>'.$validH->xssSafe($admin_user->mobile).'</p>';
                                            ?>
                                        </td>
                                        <td align="left" valign="top">
                                            <?php
                                            echo $validH->xssSafe($admin_user->organization);
                                            ?>
                                        </td>
                                        <td align="left" valign="top">
                                            <?php
                                            if($admin_user->active==1)
                                            {

                                                echo '<span class="badge badge-success">Active</span>';

                                            }
                                            else if($admin_user->active==0)
                                            {
                                                echo '<span class="badge badge-danger">Inactive</span>';

                                            }

                                            ?>
                                        </td>
                                        <td align="left" valign="top">
                                            <?php
                                            echo $validH->xssSafe($admin_user->author_fname).' '.$validH->xssSafe($admin_user->author_lname);

                                            echo "<br/>";

                                            if(!empty($admin_user->created) && $admin_user->created!='0000-00-00 00:00:00')
                                            {
                                                echo 'Created: '.date('d/m/Y', strtotime($admin_user->created));
                                            }

                                            if(!empty($admin_user->updated) && $admin_user->updated!='0000-00-00 00:00:00')
                                            {
                                                echo '<br/>Updated: '.date('d/m/Y', strtotime($admin_user->updated));
                                            }
                                            else if(!empty($admin_user->created) && $admin_user->created!='0000-00-00 00:00:00')
                                            {
                                                echo '<br/>Updated: '.date('d/m/Y', strtotime($admin_user->created));
                                            }


                                            ?>
                                        </td>
                                        <td valign="top">
                                            <a class="btn btn-sm btn-info btn-block"  href="<?php echo base_url()."admin/admin_user/add?id=".$validH->xssSafe($admin_user->id); ?>">Edit</a>
                                            <a class="btn btn-sm btn-block btn-danger"  href="<?php echo base_url().'admin/admin_user/delete?id='.$validH->xssSafe($admin_user->id) ?>&<?php echo $csrfToken['name']; ?>=<?php echo md5($csrfToken['hash'].$this->session->csrf_salt); ?>" class="delBtn" title="Delete">Delete</a>
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
<script>
    jQuery(document).ready(function($){

        $("select#ipp").select2({
            placeholder: "Select Items Per Page...",
            minimumResultsForSearch: -1
        });
        $("select#sort_option").select2({
            placeholder: "Select Sort Option...",
            minimumResultsForSearch: -1
        });
    });
</script>