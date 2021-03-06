<?php

use frontend\assets\IndexAsset;

IndexAsset::register($this);

/* @var $this yii\web\View */
$this->title = Yii::$app->params['indexTitle'];
$this->params['breadcrumbs'][] = $this->title;
Yii::$app->view->registerMetaTag(['name' => 'description','content' => Yii::$app->params['companyDesc']]);

?>
<div class="rev_slider_wrapper fullscreen-container">
    <div id="rev_slider_3" class="rev_slider fullscreenbanner">
        <ul>
            <li data-transition="fade" data-slotamount="default" data-easein="Power3.easeOut" data-easeout="Power3.easeOut" data-masterspeed="700" data-rotate="0" data-saveperformance="off" data-title="Slide">
                <img src="images/slider/index_1.jpg?1" alt="" width="1920" height="1080" data-bgposition="center bottom" data-bgfit="cover" data-bgrepeat="no-repeat" class="rev-slidebg" />
                <div class="tp-caption caption-15 tp-resizeme"
                     data-x="center"
                     data-hoffset=""
                     data-y="center"
                     data-voffset="35"
                     data-width="['auto']"
                     data-height="['auto']"
                     data-transform_idle="o:1;"
                     data-transform_in="z:0;rX:0;rY:0;rZ:0;sX:0.9;sY:0.9;skX:0;skY:0;opacity:0;s:1500;e:Power3.easeInOut;"
                     data-transform_out="opacity:0;s:300;s:300;"
                     data-start="500"
                     data-splitin="none"
                     data-splitout="none"
                     data-responsive_offset="on"><?= Yii::$app->params['companyName']; ?>
                </div>
                <div class="tp-caption tp-resizeme"
                     data-x="center"
                     data-hoffset="-200"
                     data-y="center"
                     data-voffset="-80"
                     data-width="['none','none','none','none']"
                     data-height="['none','none','none','none']"
                     data-transform_idle="o:1;"
                     data-transform_in="x:-50px;opacity:0;s:1500;e:Power3.easeOut;"
                     data-transform_out="opacity:0;s:300;s:300;"
                     data-start="1500"
                     data-responsive_offset="on">
                    <img src="images/banner/shap_icon.png" alt="" width="120" height="112" />
                </div>
                <div class="tp-caption tp-resizeme"
                     data-x="center"
                     data-hoffset="200"
                     data-y="center"
                     data-voffset="-80"
                     data-width="['none','none','none','none']"
                     data-height="['none','none','none','none']"
                     data-transform_idle="o:1;"
                     data-transform_in="x:50px;opacity:0;s:1500;e:Power3.easeOut;"
                     data-transform_out="opacity:0;s:300;s:300;"
                     data-start="1500"
                     data-responsive_offset="on">
                    <img src="images/banner/shap_icon2.png" alt="" width="120" height="112" />
                </div>
                <div class="tp-caption caption-16 tp-resizeme"
                     data-x="center"
                     data-hoffset=""
                     data-y="center"
                     data-voffset="-60"
                     data-width="['auto']"
                     data-height="['auto']"
                     data-transform_idle="o:1;"
                     data-transform_in="sX:2;sY:2;opacity:0;s:1500;e:Power3.easeOut;"
                     data-transform_out="opacity:0;s:300;s:300;"
                     data-start="2200"
                     data-splitin="chars"
                     data-splitout="none"
                     data-responsive_offset="on"
                     data-elementdelay="0.1">Для Ваших изделий
                </div>
                <div class="tp-caption tp-resizeme"
                     data-x="center"
                     data-hoffset=""
                     data-y="center"
                     data-voffset="-150"
                     data-width="['none','none','none','none']"
                     data-height="['none','none','none','none']"
                     data-transform_idle="o:1;"
                     data-transform_in="z:0;rX:0;rY:0;rZ:0;sX:0.9;sY:0.9;skX:0;skY:0;opacity:0;s:1500;e:Power3.easeInOut;"
                     data-transform_out="opacity:0;s:300;s:300;"
                     data-start="4000"
                     data-responsive_offset="on">
                    <img src="images/banner/star_b.png" alt="" width="25" height="20" />
                </div>
                <div class="tp-caption tp-resizeme"
                     data-x="center"
                     data-hoffset="50"
                     data-y="center"
                     data-voffset="-145"
                     data-width="['none','none','none','none']"
                     data-height="['none','none','none','none']"
                     data-transform_idle="o:1;"
                     data-transform_in="z:0;rX:0;rY:0;rZ:0;sX:0.9;sY:0.9;skX:0;skY:0;opacity:0;s:1500;e:Power3.easeInOut;"
                     data-transform_out="opacity:0;s:300;s:300;"
                     data-start="4200"
                     data-responsive_offset="on">
                    <img src="images/banner/star2_b.png" alt="" width="15" height="18" />
                </div>
                <div class="tp-caption tp-resizeme"
                     data-x="center"
                     data-hoffset="-55"
                     data-y="center"
                     data-voffset="-145"
                     data-width="['none','none','none','none']"
                     data-height="['none','none','none','none']"
                     data-transform_idle="o:1;"
                     data-transform_in="z:0;rX:0;rY:0;rZ:0;sX:0.9;sY:0.9;skX:0;skY:0;opacity:0;s:1500;e:Power3.easeInOut;"
                     data-transform_out="opacity:0;s:300;s:300;"
                     data-start="4200"
                     data-responsive_offset="on">
                    <img src="images/banner/star2_b.png" alt="" width="15" height="18" />
                </div>
                <div class="tp-caption rev-btn btn-2"
                     data-x="center"
                     data-hoffset=""
                     data-y="center"
                     data-voffset="160"
                     data-width="['auto']"
                     data-height="['auto']"
                     data-transform_idle="o:1;"
                     data-transform_hover="o:1;rX:0;rY:0;rZ:0;z:0;s:0;e:Linear.easeNone;"
                     data-style_hover="c:rgba(0, 0, 0, 1.00);bg:rgba(255, 255, 255, 1.00);"
                     data-transform_in="y:50px;opacity:0;s:1500;e:Power3.easeOut;"
                     data-transform_out="opacity:0;s:300;s:300;"
                     data-start="5000"
                     data-splitin="none"
                     data-splitout="none"
                     data-responsive_offset="on"
                     data-responsive="off"
                     data-actions='[{"event":"click","action":"simplelink","target":"_self","url":"/catalog"}]'>Каталог
                </div>
            </li>
            <li data-transition="fade" data-slotamount="default" data-easein="Power3.easeOut" data-easeout="Power3.easeOut" data-masterspeed="700" data-rotate="0" data-saveperformance="off" data-title="Slide">
                <img src="images/slider/index_2.jpg?1" alt="" width="1920" height="1080" data-bgposition="center bottom" data-bgfit="cover" data-bgrepeat="no-repeat" class="rev-slidebg" />
            </li>
            <li data-transition="fade" data-slotamount="default" data-easein="Power3.easeOut" data-easeout="Power3.easeOut" data-masterspeed="700" data-rotate="0" data-saveperformance="off" data-title="Slide">
                <img src="images/slider/index_3.jpg?1" alt="" width="1920" height="1080" data-bgposition="center bottom" data-bgfit="cover" data-bgrepeat="no-repeat" class="rev-slidebg" />
            </li>
        </ul>
        <div class="tp-bannertimer"></div>
    </div>
</div>
<div class="noo-simple-product-wrap product-sliders">
    <ul class="noo-simple-product-slider">
        <?php foreach ($categories as $category) :?>
            <li>
                <a href="/catalog/<?= $category->slug ?>">
                    <div class="noo-simple-slider-item" data-bg="images/slider/<?= $category->slug ?>.jpg?4">
                        <h3><?= $category->title ?></h3>
                    </div>
                </a>
            </li>
        <?php endforeach;?>
    </ul>
</div>
<?php if($noveltyProducts):?>
    <div class="pt-5 pb-5 clear product-sliders">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="noo-product-slider-wrap commerce">
                        <div class="noo-sh-title">
                            <h2>Новинки</h2>
                        </div>
                        <div class="row noo-product-slider products product-grid">
                            <div class="noo-product-sliders">
                                <?php foreach (array_values($noveltyProducts) as $index => $model) :?>
                                    <?= $this->render('/catalog/_product', ['model'=>$model]); ?>
                                <?php endforeach;?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif;?>
<div class="noo-footer-shop-now">
    <div class="container">
        <div class="col-md-7">
            <h4>- Красивая упаковка -</h4>
            <h3>Каждый день</h3>
        </div>
    </div>
</div>