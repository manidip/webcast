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
<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i> Logs</h1>
            <p>Webcast Management System</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo base_url().'admin/dashboard';?>"><i class="fa fa-home fa-lg"></i></a></li>
            <li class="breadcrumb-item"><a href="<?php echo base_url().'admin/logs/index';?>">Logs</a></li>
            <li class="breadcrumb-item"><?php echo $validH->xssSafe($title); ?></li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <h3 class="tile-title">Logs - <?php echo $validH->xssSafe($title); ?> </h3>
                <div class="tile-body">

                    <?php
                    if(!empty($logs))
                    {
                        ?>
                        <div class="row">
                            <div class="col-md-12">
                                <?php echo $total_items; ?></strong> records</span>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="30%">Name</th>
                                    <th width="25%">Activity</th>
                                    <th width="20%">Time</th>
                                    <th width="20%">Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach($logs as $log){ ?>
                                   <tr>
                                    <td align="left" valign="top"> <?php echo $log->log_id; ?> </td>
                                    <td align="left" valign="top"><?php echo $log->user_email; ?></td>
                                    <td align="left" valign="top">
                                        <?php
                                        $ref_url = false;
                                        if(strpos($log->activity, 'Add Event Session') !== false || strpos($log->activity, 'Edit Event Session') !== false) {
                                            $ref_url = base_url() . 'admin/event_sessions/add?event_session_id='.$log->item_id;
                                        }else if(strpos($log->activity, 'Add Event') !== false || strpos($log->activity, 'Edit Event') !== false){
                                            $ref_url = base_url() . 'admin/events/add?event_id='.$log->item_id;
                                        }

                                        if($ref_url){
                                        ?>
                                            <a class="btn btn-sm btn-info btn-small" target="_blank" href="<?php echo $ref_url; ?>"><?php echo $log->activity.' - '.$log->item_id; ?></a>
                                        <?php }else{
                                         echo $log->activity;
                                         } ?>
                                    </td>
                                    <td align="left" valign="top"><?php echo date('d/m/Y h:i A', strtotime($log->activity_time)); ?></td>
                                    <td align="left" valign="top"><?php echo $log->activity_result; ?> </td>
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