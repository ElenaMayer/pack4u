<?php

$this->title = 'Инструкции';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="masonry noo-blog noo-shop-main">
    <div class="container">
        <div class="row">
            <div class="noo-main">
                <div class="masonry-container">
                    <?php foreach ($data as $item):?>
                        <?php
                        $snippet = $item->getSnippet();
                        $videoId = $snippet->getResourceId()->getVideoId();
                        $img = $snippet->getThumbnails();
                        if($img):
                            $img = $img->getStandard()->getUrl();
                            ?>
                            <div class="masonry-item col-md-4 col-sm-6 format-image">
                                <div class="blog-item">
                                    <a class="blog-thumbnail" href="/instruction/<?= $videoId?>">
                                        <img width="640" height="480" src="<?= $img?>" alt="<?= $snippet->title ?>"/>
                                    </a>
                                    <div class="blog-description">
                                        <h3>
                                            <a href="/instruction/<?= $videoId?>"><?= $snippet->title ?></a>
                                        </h3>
                                        <a class="view-more" href="/instruction/<?= $videoId?>">Смотреть</a>
                                    </div>
                                </div>
                            </div>
                        <?php endif;?>
                    <?php endforeach;?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="noo-footer-shop-now">
    <div class="container">
        <div class="col-md-7">
            <h4>- Красивая упаковка -</h4>
            <h3>Каждый день</h3>
        </div>
    </div>
</div>