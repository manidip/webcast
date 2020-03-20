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
            <li class="breadcrumb-item"><a href="<?php echo base_url().'admin/event_sessions/index';?>">Events</a></li>
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
                <h3 class="tile-title">Event Sessions - <?php echo $validH->xssSafe($title); ?> </h3>
                <div class="tile-body">
                    <form name="sortForm" id="sortForm" method="get" class="row" action="" autocomplete="off">
                        <div class="form-group col-md-6">
                            <div class="row">
                                <div class="form-group col-md-8">
                                    <input class="form-control" name="search_kw" id="search_kw" type="text" placeholder="Search Event Sessions..." value="<?php echo $search_kw; ?>">
                                </div>
                                <div class="form-group col-md-4 align-left">
                                    <button class="btn btn-primary" type="submit" name="search" value="1"><i class="fa fa-fw fa-lg fa-search"></i>Search</button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <select class="form-control" name="event" id="event">
                                        <option value="">--Select Event--</option>
                                        <?php foreach ($events as $event){ ?>
                                            <option value="<?php echo $event->id; ?>" <?php echo ($event->id == $event_selected) ? "selected" : ""; ?>><?php echo $event->title_en; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-control" name="ipp" id="ipp">
                                        <option value="1" <?php if($ipp==1){ echo "selected"; }?>>1 per page</option>
                                        <option value="3" <?php if($ipp==3){ echo "selected"; }?>>3 per page</option>
                                        <option value="5" <?php if($ipp==5){ echo "selected"; }?>>5 per page</option>
                                        <option value="10" <?php if($ipp==10){ echo "selected"; }?>>10 per page</option>
                                        <option value="20" <?php if($ipp==20){ echo "selected"; }?>>20 per page</option>
                                        <option value="50" <?php if($ipp==50){ echo "selected"; }?>>50 per page</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-control" name="sort_option" id="sort_option">
                                        <option value="">--Sort by--</option>
                                            <option value="event_sessions.title_en|asc" <?php echo ($sort_option == 'event_sessions.title_en|asc')? "selected" : "";?>>Title A-Z</option>
                                        <option value="event_sessions.title_en|desc" <?php echo ($sort_option == 'event_sessions.title_en|desc')? "selected" : "";?>>Title Z-A</option>
                                        <option value="event_sessions.created_at|asc" <?php echo ($sort_option == 'event_sessions.created_at|asc')? "selected" : "";?>>Oldest First</option>
                                        <option value="event_sessions.created_at|desc" <?php echo ($sort_option == 'event_sessions.created_at|desc')? "selected" : "";?>>Recent First</option>
                                        <option value="event_sessions.start_time|asc" <?php echo ($sort_option == 'event_sessions.start_time|asc')? "selected" : "";?>>Start Time ASC</option>
                                        <option value="event_sessions.start_time|desc" <?php echo ($sort_option == 'event_sessions.start_time|desc')? "selected" : "";?>>Start Time DESC</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                    <?php
                    if(!empty($event_data['event_sessions']))
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
                                    <th width="10%">Start Date</th>
                                    <th width="10%">End Date</th>
                                    <th width="10%">VIP</th>
                                    <th width="10%">Speakers</th>
                                    <th width="5%">Session Status</th>
                                    <th width="5%">Status</th>
                                    <th width="12%">Details</th>
                                    <th width="15%">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach($event_data['event_sessions'] as $event_session){ ?>
                                   <tr>
                                    <td align="left" valign="top"> <?php echo $event_session->id; ?> </td>
                                    <td align="left" valign="top">
                                        <strong>English: </strong> <?php echo $event_session->title_en; ?><br/>
                                        <strong>Hindi: </strong><?php echo $event_session->title_hi; ?>
                                    </td>
                                    <td align="left" valign="top"><?php echo date('d-M-Y H:i', strtotime($event_session->start_time)); ?></td>
                                    <td align="left" valign="top"><?php echo date('d-M-Y H:i', strtotime($event_session->end_time)); ?></td>
                                       <td align="left" valign="top"><?php echo strtoupper($event_session->vip); ?> </td>
                                       <td align="left" valign="top">
                                           <?php
                                           $speakers = explode(',',$event_session->speakers);
                                           if(!empty($speakers)){
                                               foreach ($speakers as $speaker){
                                                   ?>
                                                   <?php echo $speaker; ?><br/>
                                                   <?php
                                               }
                                           }
                                           ?>
                                       </td>
                                    <td align="left" valign="top"><?php echo strtoupper($event_session->session_status); ?></td>
                                    <td align="left" valign="top"><?php echo strtoupper($event_session->status); ?></td>
                                    <td align="left" valign="top">
                                        <strong>Event :</strong> <a target="_blank" href="<?php echo base_url()."admin/events/add?event_id=".$validH->xssSafe($event_session->event->id); ?>"><?php echo $event_session->event->title_en; ?></a><br/>
                                        <strong>Created At :</strong> <?php echo date('d/m/Y h:i A', strtotime($event_session->created_at)); ?><br/>
                                        <?php if($event_session->updated_at){ ?>
                                            <strong>Updated At :</strong> <?php echo date('d/m/Y h:i A', strtotime($event_session->updated_at)); ?><br/>
                                        <?php } ?>

                                    </td>
                                    <td align="left" valign="top">
                                        <a class="btn btn-sm btn-info btn-block" target="_blank" href="<?php echo base_url()."admin/event_sessions/add?event_session_id=".$validH->xssSafe($event_session->id); ?>">Edit</a>
                                        <a class="btn btn-sm btn-danger deleteSession btn-block"  href="<?php echo base_url().'admin/event_sessions/delete?event_session_id='.$validH->xssSafe($event_session->id) ?>&<?php echo $csrfToken['name']; ?>=<?php echo md5($csrfToken['hash'].$this->session->csrf_salt); ?>" title="Delete">Delete</a>
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

        $("#sort_option,#ipp,#event").on("change", function(){
            $("#sortForm").trigger('submit');
        });


        $('.deleteSession').on('click', function(e){
            e.preventDefault();

            var href= $(this).attr('href');

            $('#delCnfModal').modal({ backdrop: 'static', keyboard: false }).one('click', '#deleteSessionOk', function() {
                window.location.href = href;
            });
        });
        $("select#event").select2({
            placeholder: "Select Event..",
            minimumResultsForSearch: 10
        });
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