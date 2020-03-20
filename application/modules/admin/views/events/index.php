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
                <button type="button" data-dismiss="modal" class="btn btn-danger" id="deleteSessionOk">Delete</button>
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
                <h3 class="tile-title">Events - <?php echo $validH->xssSafe($title); ?> </h3>
                <div class="tile-body">
                    <form name="sortForm" id="sortForm" method="get" class="row" action="" autocomplete="off">
                        <div class="form-group col-md-6">
                            <div class="row">
                                <div class="form-group col-md-8">
                                    <input class="form-control" name="search_kw" id="search_kw" type="text" placeholder="Search Events..." value="<?php echo $search_kw; ?>">
                                </div>
                                <div class="form-group col-md-4 px-0 mx-0 align-left">
                                    <button class="btn btn-primary" type="submit" name="search" value="1"><i class="fa fa-fw fa-lg fa-search"></i>Search</button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <div class="row">
                                <div class="col-md-4 pr-0 mx-0">
                                    <select class="form-control" name="ipp" id="ipp">
                                        <option value="">--Items per page--</option>
                                        <option value="1" <?php if($ipp==1){ echo "selected"; }?>>1 per page</option>
                                        <option value="3" <?php if($ipp==3){ echo "selected"; }?>>3 per page</option>
                                        <option value="5" <?php if($ipp==5){ echo "selected"; }?>>5 per page</option>
                                        <option value="10" <?php if($ipp==10){ echo "selected"; }?>>10 per page</option>
                                        <option value="20" <?php if($ipp==20){ echo "selected"; }?>>20 per page</option>
                                        <option value="50" <?php if($ipp==50){ echo "selected"; }?>>50 per page</option>
                                    </select>
                                </div>
                                <div class="col-md-4 pr-0 mx-0">
                                    <select class="form-control" name="sort_option" id="sort_option">
                                        <option value="">Sort by</option>
                                            <option value="event.title_en|asc" <?php echo ($sort_option == 'event.title_en|asc')? "selected" : "";?>>Title A-Z</option>
                                        <option value="event.title_en|desc" <?php echo ($sort_option == 'event.title_en|desc')? "selected" : "";?>>Title Z-A</option>
                                        <option value="event.created_at|asc" <?php echo ($sort_option == 'event.created_at|asc')? "selected" : "";?>>Oldest First</option>
                                        <option value="event.created_at|desc" <?php echo ($sort_option == 'event.created_at|desc')? "selected" : "";?>>Recent First</option>
                                        <option value="event.start_date|asc" <?php echo ($sort_option == 'event.start_date|asc')? "selected" : "";?>>Start Date ASC</option>
                                        <option value="event.start_date|desc" <?php echo ($sort_option == 'event.start_date|desc')? "selected" : "";?>>Start Date DESC</option>
                                    </select>
                                </div>
                                <div class="col-md-4 ml-0">
                                    <select class="form-control" name="status" id="status">
                                        <option value="">--Status--</option>
                                        <option value="all" <?php echo ('all' == $status) ? "selected" : ""; ?>>All</option>
                                        <option value="draft" <?php echo ('draft' == $status) ? "selected" : ""; ?>>Draft</option>
                                        <option value="published" <?php echo ('published' == $status) ? "selected" : ""; ?>>Published</option>
                                        <option value="active" <?php echo ('active' == $status) ? "selected" : ""; ?>>Active (Ongoing|Upcoming)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 10px;">
                                <div class="col-md-12">
                                    <select class="form-control" name="coordinator" id="coordinator">
                                    <?php foreach ($coordinators as $coordinator){ ?>
                                            <option <?php echo ($current_coordinator == $coordinator->id) ? "selected" : "";?> value="<?php echo $validH->xssSafe($coordinator->id); ?>" ><?php echo $validH->xssSafe($coordinator->name); ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <div class="row">

                            </div>
                        </div>
                    </form>
                    <?php
                    if(!empty($event_data['events']))
                    {
                        ?>
                        <div class="row">
                            <div class="col-md-12">
                                <?php echo $event_data['total_items']; ?></strong> records</span>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="22%">Title</th>
                                    <th width="10%">Coordinators</th>
                                    <th width="10%">Duration</th>
                                    <th width="5%">Source</th>
                                    <th width="5%">Audience</th>
                                    <th width="5%">Status</th>
                                    <th width="15%">Details</th>
                                    <th width="12%">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach($event_data['events'] as $event){ ?>
                                   <tr>
                                    <td align="left" valign="top"> <?php echo $event->id; ?> </td>
                                    <td align="left" valign="top">
                                        <?php if(!empty($event->title_en)){ ?>
                                            <strong>English: </strong> <?php echo $event->title_en; ?><br/>
                                        <?php } ?>
                                        <?php if(!empty($event->title_hi)){ ?>
                                            <strong>Hindi: </strong><?php echo $event->title_hi; ?><br/>
                                        <?php } ?>
                                        <?php if(!empty($event->title_reg)){ ?>
                                            <strong>Regional: </strong><?php echo $event->title_reg; ?>
                                        <?php } ?>
                                    </td>
                                    <td align="left" valign="top">
                                        <?php
                                            if(!empty($event->coordinators)){
                                                foreach ($event->coordinators as $coordinator){
                                         ?>
                                                    <a href="<?php echo base_url().'admin/events/index?coordinator='.$coordinator['id']; ?>"><?php echo $coordinator['name']; ?></a>
                                        <?php
                                                }
                                            }
                                         ?>
                                    </td>
                                    <td align="left" valign="top">
                                        <?php echo date('d-M-Y', strtotime($event->start_date)); ?> -
                                        <?php echo date('d-M-Y', strtotime($event->end_date)); ?>
                                    </td>
                                    <td align="left" valign="top"><?php echo strtoupper($event->source); ?> </td>
                                    <td align="left" valign="top"><?php echo strtoupper($event->audience); ?></td>
                                    <td align="left" valign="top">
                                        <span class="badge <?php echo ($event->status == 'published') ? "badge-success" : "badge-danger"; ?>"><?php echo strtoupper($event->status); ?></span>
                                    </td>
                                    <td align="left" valign="top">
                                        <strong>Owner :</strong> <?php echo ucwords($event->owner); ?><hr/>
                                        <?php if($event->nodal_officer){ ?>
                                            <strong>Nodal Officer :</strong> <?php echo ucwords($event->nodal_officer); ?><hr/>
                                        <?php } ?>
                                        <?php if($event->state){ ?>
                                            <strong>State :</strong> <?php echo ucwords($event->state->state_name); ?><hr/>
                                        <?php } ?>
                                        <?php if($event->ministry){ ?>
                                            <strong>Ministry :</strong> <?php echo ucwords($event->ministry->orgn_name); ?><hr/>
                                        <?php } ?>
                                        <?php if($event->department){ ?>
                                            <strong>Department :</strong> <?php echo ucwords($event->department->orgn_name); ?><hr/>
                                        <?php } ?>
                                        <?php if($event->organization){ ?>
                                            <strong>Organization :</strong> <?php echo ucwords($event->organization->orgn_name); ?><hr/>
                                        <?php } ?>
                                        <strong>Created At :</strong> <?php echo date('d/m/Y h:i A', strtotime($event->created_at)); ?><br/>
                                        <?php if($event->updated_at){ ?>
                                            <strong>Updated At :</strong> <?php echo date('d/m/Y h:i A', strtotime($event->updated_at)); ?><br/>
                                        <?php } ?>
                                    </td>
                                    <td align="left" valign="top">
                                        <a class="btn btn-sm btn-info btn-block" target="_blank" href="<?php echo base_url()."admin/events/add?event_id=".$validH->xssSafe($event->id); ?>">Edit</a>
                                        <a class="btn btn-sm btn-primary btn-block" target="_blank" href="<?php echo base_url().'admin/event_sessions/index?event='.$validH->xssSafe($event->id); ?>" title="View Sessions">Sessions</a>
                                       <?php if($user->role == 'admin'){ ?>
                                        <a class="btn btn-sm btn-danger btn-block deleteSession" href="<?php echo base_url().'admin/events/delete?event_id='.$validH->xssSafe($event->id) ?>&<?php echo $csrfToken['name']; ?>=<?php echo md5($csrfToken['hash'].$this->session->csrf_salt); ?>" title="Delete">Delete</a>
                                       <?php } ?>
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

        $("#sort_option,#ipp,#coordinator, #status").on("change", function(){
            $("#sortForm").trigger('submit');
        });
        $('.deleteSession').on('click', function(e){
            e.preventDefault();

            var href= $(this).attr('href');

            $('#delCnfModal').modal({ backdrop: 'static', keyboard: false }).one('click', '#deleteSessionOk', function() {
                window.location.href = href;
            });
        });

        var pageSize = 2;
        $("select#coordinator").select2({
            placeholder: "Select Coordinator..",
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
        $("select#ipp").select2({
            placeholder: "Select Items Per Page...",
            minimumResultsForSearch: -1
        });
        $("select#sort_option").select2({
            placeholder: "Select Sort Option...",
            minimumResultsForSearch: -1
        });
        $("select#status").select2({
            placeholder: "Select Status...",
            minimumResultsForSearch: -1
        });
    });
</script>