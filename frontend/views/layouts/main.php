<?php
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use frontend\assets\IeAsset;
use frontend\widgets\Alert;
use common\models\Category;
use yii\bootstrap\Modal;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
IeAsset::register($this);

$cookies = Yii::$app->request->cookies;
$location = $cookies->getValue('location');
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="theme-color" content="#96cb62"/>
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title . ' - ' . Yii::$app->name ) ?></title>
    <link rel="manifest" href="/manifest.json">
    <?php $this->registerLinkTag(['rel' => 'icon', 'type' => 'image/png', 'href' => '/images/favicon-16x16.png?1', 'sizes' => '16x16']); ?>
    <?php $this->registerLinkTag(['rel' => 'icon', 'type' => 'image/png', 'href' => '/images/favicon-32x32.png', 'sizes' => '32x32']); ?>
    <?php $this->registerLinkTag(['rel' => 'icon', 'type' => 'image/png', 'href' => '/images/favicon-96x96.png', 'sizes' => '96x96']); ?>
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>
    <div class="noo-spinner">
        <div class="spinner">
            <div class="cube1"></div>
            <div class="cube2"></div>
        </div>
    </div>
    <div class="site">
        <header class="noo-header header-2 header-static">
            <div class="noo-topbar">
                <div class="container">
                    <ul>
                        <li class = 'geo_modal'>
                            <a class="link" onclick="$('#w1').modal()"><i class="fa fa-map-marker"></i> <span><?=$location?></span></a>
                        </li>
                        <li>
                            <a href="tel:<?= Yii::$app->params['phone1'] ?>"><i class="fa fa-phone"></i><?= Yii::$app->params['phone1'] ?></a>
                        </li>
                        <li>
                            <div class="noo_social">
                                <div class="social-all">
                                    <a href="<?= Yii::$app->params['linkInstagram'] ?>" class="fa fa-instagram"></a>
                                    <a href="<?= Yii::$app->params['linkVk'] ?>" class="fa fa-vk"></a>
                                    <a href="<?= Yii::$app->params['linkFacebook'] ?>" class="fa fa-facebook"></a>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <ul>
                        <?php if (Yii::$app->user->isGuest):?>
                            <li>
                                <a href="/user/login" title="Вход"><i class="fa fa-user"></i>Вход</a>
                            </li>
                        <?php else:?>
                            <li>
                                <span><i class="fa fa-heart-o"></i></span>
                                <a href="/wishlist">Избранное</a>
                            </li>
                            <li>
                                <span><i class="fa fa-user"></i></span>
                                <a href="/user/settings/profile" data-method='post'>Мои данные</a>
                            </li>
                            <li>
                                <span><i class="fa fa-history"></i></span>
                                <a href="/history" data-method='post'>История заказов</a>
                            </li>
                            <li>
                                <span><i class="fa fa-sign-out"></i></span>
                                <a href="/user/security/logout" data-method='post'>Выйти</a>
                            </li>
                        <?php endif;?>
                        <li>
                            <a class="main_cart" href="/cart">
                                <span class="has-cart">
                                    <i class="fa fa-shopping-cart"></i>
                                    <?php $itemsInCart = Yii::$app->cart->getCount(); ?>
                                    <em <?php if($itemsInCart == 0):?>style="display: none" <?php endif; ?>><?= $itemsInCart ?></em>
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="fa fa-search noo-search" id="noo-search"></a>
                        </li>
                    </ul>
                </div>
            </div>
            <div id="sticky-header-with-topbar" class="navbar-wrapper sticky__header">
                <div class="navbar navbar-default">
                    <div class="container">
                        <div class="menu-position">
                            <div class="navbar-header pull-left">
                                <h1 class="sr-only"><?= Yii::$app->name ?></h1>
                                <div class="noo_menu_canvas">
                                    <div class="btn-search noo-search topbar-has-search">
                                        <i class="fa fa-search"></i>
                                    </div>
                                    <div class="topbar-has-cart btn-cart">
                                        <a class="scroll-main" href="/cart">
                                            <span class="has-cart">
                                                <i class="fa fa-shopping-cart"></i>
                                                <?php $itemsInCart = Yii::$app->cart->getCount(); ?>
                                                <em <?php if($itemsInCart == 0):?>style="display: none" <?php endif; ?>><?= $itemsInCart ?></em>
                                            </span>
                                        </a>
                                    </div>
                                    <div data-target=".nav-collapse" class="btn-navbar">
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                    </div>
                                </div>
                                <a href="/" class="navbar-brand">
                                    <img class="noo-logo-img noo-logo-normal" src="/images/logo.png?2" alt="<?= Yii::$app->name ?>">
                                </a>
                            </div>
                            <nav class="pull-right noo-main-menu">
                                <ul class="nav-collapse navbar-nav">
                                    <li class="menu-item-has-children current-menu-item">
                                        <a href="/catalog">Каталог</a>
                                        <ul class="sub-menu">
                                            <?php $categories = Category::find()->where(['is_active' => 1, 'parent_id' => null])->all(); ?>
                                            <?php foreach ($categories as $category):?>
                                                <li><a href="/catalog/<?= $category->slug ?>" <?php if($category->slug == 'sale'):?>class="red"<?php elseif($category->slug == 'newyear'):?>class="newyear"<?php endif;?>><?= $category->title ?><?php if($category->slug == 'newyear'):?> <i class="fa fa-tree"></i><?php endif;?></a></li>
                                            <?php endforeach;?>
                                        </ul>
                                    </li>
                                    <li><a class="red" href="/instruction">Инструкции</a></li>
                                    <li><a href="/contact">Контакты</a></li>
                                    <li><a href="/shipping">Доставка</a></li>
                                    <li><a href="/payment">Оплата</a></li>

                                    <?php if (Yii::$app->user->isGuest):?>
                                        <li class="small-menu"><a href="/user/login" title="Вход">Вход</a></li>
                                    <?php else:?>
                                        <li class="small-menu"><a href="/wishlist">Избранное</a></li>
                                        <li class="small-menu"><a href="/user/settings/profile" data-method='post'>Мои данные</a></li>
                                        <li class="small-menu"><a href="/history" data-method='post'>История заказов</a></li>
                                        <li class="small-menu"><a href="/user/security/logout" data-method='post'>Выйти</a></li>
                                    <?php endif;?>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="search-header5">
                <div class="remove-form"></div>
                <div class="container">
                    <form class="form-horizontal" action="/search">
                        <label class="note-search">Введите текст и нажмите Enter</label>
                        <input type="search" name="s" class="form-control" value="" placeholder="Найти...">
                        <input type="submit" value="Search">
                    </form>
                </div>
            </div>
        </header>
        <?php if($this->context->action->id != 'index'):?>
        <section class="noo-page-heading eff heading-2 <?= $this->context->action->id ?>">
            <div class="container">
                <div class="noo-heading-content">
                    <span class="page-title eff"><?= $this->title ?></span>
                    <?= Breadcrumbs::widget([
                        'itemTemplate' => "<span>{link}</span>\n",
                        'activeItemTemplate' => "<span class=\"active\">{link}</span>\n",
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                        'options'       =>  [
                            'class'        =>  'noo-page-breadcrumb eff',
                        ]]) ?>
                </div>
            </div>
        </section>
        <?php endif;?>
        <div class="main">
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
        <footer class="wrap-footer footer-2 colophon wigetized">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 col-sm-6 item-footer-four">
                        <div class="widget widget_about">
                            <div class="noo_about_widget">
                                <a href="/">
                                    <img src="/images/logo.png?2" alt="<?= Yii::$app->name ?>" />
                                </a>
                                <p><?= Yii::$app->params['companySlogan'] ?></p>
                            </div>
                        </div>
                        <div class="widget widget_noo_social">
                            <div class="noo_social">
                                <div class="social-all">
                                    <a href="<?= Yii::$app->params['linkInstagram'] ?>" class="fa fa-instagram"></a>
                                    <a href="<?= Yii::$app->params['linkVk'] ?>" class="fa fa-vk"></a>
                                    <a href="<?= Yii::$app->params['linkFacebook'] ?>" class="fa fa-facebook"></a>
                                </div>
                            </div>
                        </div>
                        <div class="widget widget_text">
                            <div class="textwidget">
                                <div class="copyright">
                                    Copyright &copy; <?= date('Y') ?> <?= Yii::$app->params['domain'] ?><br/>
                                    Developed with <i class="fa fa-heart-o"></i> by <a href="<?= Yii::$app->params['developerSite'] ?>" rel="external"><?= Yii::$app->params['developer'] ?></a>.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-sm-6 item-footer-four">
                        <div class="widget widget_text">
                            <h4 class="widget-title">Помощь</h4>
                            <div class="textwidget">
                                <p><a href="/contact">Контакты</a></p>
                                <p><a href="/instruction">Инструкции по сборке</a></p>
                                <p><a href="/shipping">Информация о доставке</a></p>
                                <p><a href="/payment">Способы оплаты</a></p>
                                <p><a href="/refund">Возврат товара</a></p>
                                <p><a href="/offer">Политика конфиденциальности</a></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 item-footer-four">
                        <div class="widget widget_flickr">
                            <h4 class="widget-title">Мы в Instagram</h4>
                            <div id='instafeed'></div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 item-footer-four">
                        <div class="widget widget_noo_openhours">
                            <h4 class="widget-title">Контакты</h4>
                            <ul class="noo-openhours">
                                <li>
                                    <i class="fa fa-phone"></i><a href="tel:<?= Yii::$app->params['phone1'] ?>"><?= Yii::$app->params['phone1'] ?></a>
                                </li>
                                <li>
                                    <i class="fa fa-envelope"></i><a href="mailto:<?= Yii::$app->params['email'] ?>">
                                        <?= Yii::$app->params['email'] ?>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="widget widget_noo_happyhours">
                            <!--Самовывозы-->
                            <p class="widget-title">Время обработки <br>заказов</p>
                            <ul class="noo-happyhours">
                                <?php foreach (Yii::$app->params['pickup_time'] as $time):?>
                                    <li>
                                        <div><?= $time?></div>
                                    </li>
                                <?php endforeach;?>
                            </ul>
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    <a href="#" class="go-to-top hidden-print"><i class="fa fa-angle-up"></i></a>

    <?php Modal::begin([
        'header' => '<h2>Выберете город</h2>',
    ]);
    echo '<section class="container">
          <input id="geo_city" name="city" type="text" placeholder="Ваш город ..." class="form-control dark"/>
          <div class="geo_cities_list">
              <div><a class="geo_city_const link">Новосибирск</a></div>
              <div><a class="geo_city_const link">Москва</a></div>
              <div><a class="geo_city_const link">Санкт-Петербург</a></div>
          </div>
          <br/><br/>
    </section>';
    Modal::end();?>

    <?= $this->render('_metrika'); ?>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
