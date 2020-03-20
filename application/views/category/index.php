<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
    ul.pagination.pagination-sm {
        text-align: center;
        display: block;
        width: 30%;
        margin: 15px auto;
    }
    li.page-item{
        display: inline-block;
        border: 1px solid #c5c5c5;
        margin-right: 3px;
    }

    ul.pagination li.page-item a {
        padding: 10px 15px;
        display: block;
        text-decoration: none;
        color: #000;
    }
    ul.pagination .page-item.active a {
        background: #c5c5c5;
        color: #000;
    }
</style>
<section>
    <?php if(count($events) > 0){ ?>
        <div class="grid-box-outer">
            <div class="container">
                <div class="more-vid-header">
                    <h2><?php echo $category->title; ?></h2>
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
                                    <h4><?php echo $event->title_en; ?></h4>
                                </div>
                                <div class="post-date">
                                    <?php echo date('d-M-Y', strtotime($event->start_date)); ?> - <?php echo date('d-M-Y', strtotime($event->end_date)); ?>
                                </div>
                            </a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?php echo $pagination_links; ?>
            </div>
        </div>
    <?php } ?>


</section>

