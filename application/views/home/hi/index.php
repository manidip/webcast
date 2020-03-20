<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<section>
    <div class="video-wrapper">
        <div class="container">
            <div class="video-outer">
                <iframe width="560" height="350" src="https://www.youtube.com/embed/vj1d1YzuTRg" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                <div class="live-container">
                    <!--<div class="live-webcast">
                        <select name="" id="">
                            <option value="" selected>Live Webcast</option>
                            <option value="">Webcast 1</option>
                            <option value="">Webcast 2</option>
                            <option value="">Webcast 3</option>
                        </select>
                    </div>-->
                    <div class="live-channel">
                        <h3>Live Channels:</h3>
                        <ul>
                            <li><a href="#" title=""><img src="<?php echo base_url(); ?>assets/images/LokSabha.jpg" alt="DD Kisan"></a></li>
                            <li><a href="#" title=""><img src="<?php echo base_url(); ?>assets/images/RajyaSabha.jpg" alt=""></a></li>
                            <li><a href="#" title=""><img src="<?php echo base_url(); ?>assets/images/DDNews.jpg" alt=""></a></li>
                            <li><a href="#" title=""><img src="<?php echo base_url(); ?>assets/images/Pujabi.jpg" alt=""></a></li>
                            <li><a href="#" title=""><img src="<?php echo base_url(); ?>assets/images/UGC-GEC.jpg" alt=""></a></li>
                            <li><a href="#" title=""><img src="<?php echo base_url(); ?>assets/images/DDKisanLogo.jpg" alt=""></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if(count($upcoming_events) > 0){ ?>

        <div class="announcement-slider">
            <div class="container">
                <div class="slide-head">
                    <strong>घोषणाएँ</strong>
                </div>
                <div class="slide-content">
                    <div class="owl-carousel owl-theme announcement-carousel">
                        <?php foreach ($upcoming_events as $event){ ?>
                            <div class="item">
                                <a href="<?php echo base_url();?>events/<?php echo $event->id; ?>?lang=<?php echo $lang; ?>"><?php echo $event->title_hi; ?> <span class="event-date"> <?php echo date('d-M-Y', strtotime($event->start_date)); ?> - <?php echo date('d-M-Y', strtotime($event->end_date)); ?></span></a>
                            </div>
                        <?php } ?>


                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
    <?php if(count($ongoing_events) > 0){ ?>
        <div class="more-vid-section <?php echo (count($ongoing_events) >= 5) ? 'odd': ''; ?>">
            <div class="container">
                <div class="more-vid-header">
                    <h2>चल रहे वेबकास्ट</h2>
                </div>
                <div class="owl-carousel owl-theme">

                    <?php foreach ($ongoing_events as $event){ ?>
                        <div class="item">
                            <a href="<?php echo base_url();?>events/<?php echo $event->id; ?>">
                                <div class="vdoimg-container">
                                    <span class="fa fa-play play-icon" aria-hidden="true"><strong class="hide">Play Video</strong></span>
                                    <img src="<?php echo base_url().'/'.$event->thumb_image; ?>" class="owl-img" alt="No Image." />
                                </div>
                                <div class="vdo-details">
                                    <div class="vdo-title"><?php echo $event->title_en; ?></div>
                                </div>
                                <div class="vdo-details">
                                    <?php echo date('d-M-Y', strtotime($event->start_date)); ?> -  <?php echo date('d-M-Y', strtotime($event->end_date)); ?>
                                </div>
                            </a>
                        </div>
                    <?php } ?>

                </div>
            </div>
        </div>
    <?php } ?>
    <?php if(count($recent_events) > 0){ ?>
        <div class="more-vid-section <?php echo (count($recent_events) <= 5) ? 'odd' : ''; ?>">
            <div class="container">
                <div class="more-vid-header">
                    <h2>हाल ही में वेबकास्ट</h2>
                </div>
                <div class="owl-carousel owl-theme">

                    <?php foreach ($recent_events as $event){ ?>
                        <div class="item">
                            <a href="<?php echo base_url();?>events/<?php echo $event->id; ?>?lang=<?php echo $lang; ?>">
                                <div class="vdoimg-container">
                                    <span class="fa fa-play play-icon" aria-hidden="true"><strong class="hide">Play Video</strong></span>
                                    <img src="<?php echo base_url().'/'.$event->thumb_image; ?>" class="owl-img" alt="No Image." />
                                </div>
                                <div class="vdo-details">
                                    <div class="vdo-title"><?php echo (!empty($event->title_hi)) ? $event->title_hi : $event->title_en?></div>
                                </div>
                                <div class="vdo-details">
                                    <?php echo date('d-M-Y', strtotime($event->start_date)); ?> -  <?php echo date('d-M-Y', strtotime($event->end_date)); ?>
                                </div>
                            </a>
                        </div>
                    <?php } ?>

                </div>
            </div>
        </div>

    <?php } ?>
    <?php
    foreach ($categories_events as $categories_event) {
        $events = $categories_event['events'];
        if(count($events) == 0) continue;
        ?>
        <div class="more-vid-section <?php echo (count($events) <= 5) ? 'odd' : ''; ?>">
            <div class="container">
                <div class="more-vid-header">
                    <h2><?php echo $categories_event['category']->title_hi; ?></h2>
                    <?php if(count($events) > 5){ ?>
                        <a href="<?php echo base_url();?>category/<?php echo $categories_event['category']->id; ?>?lang=<?php echo $lang; ?>" class="btn gen-btn">सभी देखें</a>
                    <?php } ?>
                </div>
                <div class="owl-carousel owl-theme">
                    <?php foreach ($events as $event){ ?>
                        <div class="item">
                            <a href="<?php echo base_url();?>events/<?php echo $event->id; ?>?lang=<?php echo $lang; ?>">
                                <div class="vdoimg-container">
                                    <span class="fa fa-play play-icon" aria-hidden="true"><strong class="hide">Play Video</strong></span>
                                    <img src="<?php echo base_url().'/'.$event->thumb_image; ?>" class="owl-img" alt="No Image." />
                                </div>
                                <div class="vdo-details">
                                    <div class="vdo-title"><?php echo (!empty($event->title_hi)) ? $event->title_hi : $event->title_en?></div>
                                </div>
                                <div class="vdo-details">
                                    <?php echo date('d-M-Y', strtotime($event->start_date)); ?> - <?php echo date('d-M-Y', strtotime($event->end_date)); ?>
                                </div>
                            </a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php } ?>
</section>

