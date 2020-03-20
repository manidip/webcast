<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
.no-active-session{
    position: relative;
}
.no-active-session span.fa.fa-play.play-icon {
    position: absolute;
    left: 50%;
    top: 50%;
    height: 70px;
    width: 70px;
    background: hsla(0, 0%, 0%, 0.65);
    line-height: 70px;
    text-align: center;
    border-radius: 50%;
    font-size: 24px;
    margin-left: -35px;
    transform: translateY(-50%);
    color: #ffffff;
}
.no-active-session img {
    width: 100%;
}
</style>
<section>
    <div class="single-video-outer">
        <div class="container">
            <div class="single-video-container">
                <div class="single-video">
                    <?php 
                    if(!empty($now_active_session)){ 
                        echo $now_active_session->session_embed; 
                     }else{
                    ?>
                    <div class="no-active-session">
                    <img src="<?php echo base_url(); ?>assets/images/nic_logo_full.gif" />
                    <span class="fa fa-play play-icon" aria-hidden="true"><strong class="hide">Play Video</strong></span>
                    </div>
                     <?php } ?>
                </div>
                <div class="video-content">
                    <p class="sec-head"><strong>Event</strong></p>
                    <p><?php echo $event->title_en; ?></p>
                    <?php if(!empty($event->title_hi)){ ?>
                        <p><?php echo $event->title_hi; ?></p>
                    <?php } ?>
                    <?php if(!empty($event->title_reg)){ ?>
                        <p><?php echo $event->title_reg; ?></p>
                    <?php } ?>

                    <?php if(!empty($now_active_session)) { ?>
                        <p class="sec-head"><strong>Session</strong></p>
                        <p><?php echo $now_active_session->title_en; ?></p>
                        <?php if(!empty($now_active_session->title_hi)){ ?>
                            <p><?php echo $now_active_session->title_hi; ?></p>
                        <?php } ?>
                        <p class="sec-head"><strong>VIP</strong></p>
                        <p><?php echo $now_active_session->vip; ?></p>
                        <?php
                        if(!empty($now_active_session->speakers)){
                            ?>
                            <p class="sec-head"><strong>Speakers</strong></p>
                            <p>
                                <?php
                                $speakers = explode(',', $now_active_session->speakers);
                                $speakers = array_filter($speakers);
                                array_walk($speakers,'trim');
                                $speakers = implode(' ,',$speakers);
                                echo $speakers;
                                ?>
                            </p>
                        <?php } ?>
                        <p>
                        <p class="sec-head">
                            <strong>On</strong>
                            <?php echo date('d',strtotime($now_active_session->start_time)); ?><sup><?php echo date('S',strtotime($now_active_session->start_time)); ?></sup> <?php echo date('M, Y',strtotime($now_active_session->start_time)); ?> at <?php echo date('h:i A',strtotime($now_active_session->start_time)); ?></p>
                        </p>

                    <?php } ?>
                    <p class="sec-head"><strong> Webcast by : </strong> <a href="https://www.nic.in" target="_new" > National Informatics Centre</a></p>
                </div>
            </div>
        </div>
    </div>
    <?php if(!empty($active_sessions)){ ?>
        <div class="grid-box-outer">
            <div class="container">
                <div class="more-vid-header">
                    <h2>Active Sessions</h2>
                </div>
                <div class="grid-box-container">
                    <?php foreach ($active_sessions as $session){;?>
                        <div class="grid-col">
                            <a class="grid-box" href="<?php echo base_url();?>events/<?php echo $event->id; ?>/session/<?php echo $session->id; ?>" data-id="<?php echo $session->id; ?>">
                                <div class="box-img">
                                    <img src="<?php echo base_url().$session->thumb_image; ?>" class="" alt="No Image." />
                                    <span class="fa fa-play play-icon" aria-hidden="true"><strong class="hide">Play Video</strong></span>
                                </div>
                                <div class="box-content">
                                    <h4><?php echo $session->title_en; ?></h4>
                                </div>
                                <div class="post-date">
                                    <?php echo date('d-M h:i A', strtotime($session->start_time)); ?> - <?php echo date('d-M h:i A', strtotime($session->end_time)); ?>
                                </div>
                            </a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php } ?>

    <?php if(count($upcoming_sessions) > 0){ ?>
        <div class="grid-box-outer">
            <div class="container">
                <div class="more-vid-header">
                    <h2>Upcoming Sessions</h2>
                </div>
                <div class="grid-box-container">
                    <?php foreach ($upcoming_sessions as $session){;?>
                        <div class="grid-col">
                            <a class="grid-box invalid" href="javascript:void(0)" data-id="<?php echo $session->id; ?>">
                                <div class="box-img">
                                    <img src="<?php echo base_url().$session->thumb_image; ?>" class="" alt="No Image." />
                                    <!--                                    <span class="fa fa-play play-icon" aria-hidden="true"><strong class="hide">Play Video</strong></span>-->
                                </div>
                                <div class="box-content">
                                    <h4><?php echo $session->title_en; ?></h4>
                                </div>
                                <div class="post-date">
                                    <?php echo date('d-M h:i A', strtotime($session->start_time)); ?> -  <?php echo date('d-M h:i A', strtotime($session->end_time)); ?>
                                </div>
                            </a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php } ?>

    <?php
        $scount = count( $vod_sessions);
        if( $scount > 0){
     ?>
        <div class="grid-box-outer">
            <div class="container">
                <div class="more-vid-header">
                    <h2>Archived Sessions</h2>
                </div>
                <div class="grid-box-container">
                    <?php foreach ($vod_sessions as $session){ ?>
                        <div class="grid-col">
                            <a class="grid-box" href="<?php echo base_url();?>events/<?php echo $event->id; ?>/session/<?php echo $session->id; ?>" data-id="<?php echo $session->id; ?>">
                                <div class="box-img">
                                    <img src="<?php echo base_url().$session->thumb_image; ?>" class="" alt="No Image." />
                                    <span class="fa fa-play play-icon" aria-hidden="true"><strong class="hide">Play Video</strong></span>
                                </div>
                                <div class="box-content">
                                    <h4><?php echo $session->title_en; ?> - Session <?php echo $scount -- ; ?></h4>
                                </div>
                                <div class="post-date">
                                    <?php echo date('d-M h:i A', strtotime($session->start_time)); ?> -  <?php echo date('d-M h:i A', strtotime($session->end_time)); ?>
                                </div>
                            </a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php } ?>

</section>

