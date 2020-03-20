<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<section>
    <?php if(count($events) > 0){ ?>
        <div class="grid-box-outer">
            <div class="container">
                <div class="more-vid-header">
                    <h2><?php echo $category->title_hi; ?></h2>
                </div>
                <div class="grid-box-container">
                    <?php foreach ($events as $event){ ?>
                        <div class="grid-col">
                            <a class="grid-box" href="<?php echo base_url();?>events/<?php echo $event->id; ?>?lang=<?php echo $lang; ?>">
                                <div class="box-img">
                                    <img src="<?php echo base_url().$event->thumb_image; ?>" class="" alt="No Image." />
                                    <span class="fa fa-play play-icon" aria-hidden="true"><strong class="hide">Play Video</strong></span>
                                </div>
                                <div class="box-content">
                                    <h4><?php echo (!empty($event->title_hi)) ? $event->title_hi : $event->title_en ?></h4>
                                </div>
                                <div class="post-date">
                                    <?php echo date('d, M Y', strtotime($event->start_date)); ?> - <?php echo date('d,M Y', strtotime($event->end_date)); ?>
                                </div>
                            </a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php } ?>


</section>

