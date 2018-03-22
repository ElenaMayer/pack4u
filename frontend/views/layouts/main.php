<?php
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use frontend\widgets\Alert;
use common\models\Category;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <?= Html::csrfMetaTags() ?>
    <?php Yii::$app->view->registerMetaTag(['name' => 'description','content' => Yii::$app->params['companySlogan']]); ?>
    <title><?= Html::encode($this->title) ?></title>
<!--    --><?php //$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/png', 'href' => '/img/favicon-16x16.png', 'sizes' => '16x16']); ?>
<!--    --><?php //$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/png', 'href' => '/img/favicon-32x32.png', 'sizes' => '32x32']); ?>
<!--    --><?php //$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/png', 'href' => '/img/favicon-96x96.png', 'sizes' => '96x96']); ?>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>
<!--    <div class="noo-spinner">-->
<!--        <div class="spinner">-->
<!--            <div class="cube1"></div>-->
<!--            <div class="cube2"></div>-->
<!--        </div>-->
<!--    </div>-->
    <div class="site">

        <header class="noo-header header-2 header-static">
            <div class="noo-topbar">
                <div class="container">
                    <ul>
                        <li>
                            <span><i class="fa fa-phone"></i></span>
                            <a href="#"><?= Yii::$app->params['phone1'] ?></a>
                        </li>
                        <li>
                            <div class="noo_social">
                                <div class="social-all">
                                    <a href="<?= Yii::$app->params['linkInstagram'] ?>" class="fa fa-instagram"></a>
                                    <a href="<?= Yii::$app->params['linkVk'] ?>" class="fa fa-twitter"></a>
                                    <a href="<?= Yii::$app->params['linkFacebook'] ?>" class="fa fa-facebook"></a>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <ul>
                        <?php if (Yii::$app->user->isGuest):?>
                            <li>
                                <span><i class="fa fa-user"></i></span>
                                <a href="/user/login" title="Вход">Вход</a>
                            </li>
                        <?php else:?>
                            <li>
                                <span><i class="fa fa-user"></i></span>
                                <a href="/user/security/logout" data-method='post'>Выйти</a>
                            </li>
                        <?php endif;?>

                        <li>
                            <span><i class="fa fa-heart-o"></i></span>
                            <a href="wishlist.html">Избранное</a>
                        </li>
                        <li>
                            <a href="/cart">
                                <span class="has-cart">
                                    <i class="fa fa-shopping-cart"></i>
                                    <?php $itemsInCart = Yii::$app->cart->getCount(); ?>
                                    <?php if($itemsInCart):?>
                                    <em><?= $itemsInCart ?></em>
                                    <?php endif; ?>
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="navbar-wrapper">
                <div class="navbar navbar-default">
                    <div class="container">
                        <div class="menu-position">
                            <div class="navbar-header pull-left">
                                <h1 class="sr-only"><?= Yii::$app->params['title'] ?></h1>
                                <div class="noo_menu_canvas">
                                    <div class="btn-search noo-search topbar-has-search">
                                        <i class="fa fa-search"></i>
                                    </div>
                                    <div data-target=".nav-collapse" class="btn-navbar">
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                    </div>
                                </div>
                                <a href="/" class="navbar-brand">
                                    <img class="noo-logo-img noo-logo-normal" src="/images/logo.png" alt="<?= Yii::$app->params['title'] ?>">
                                </a>
                            </div>

                            <nav class="pull-right noo-main-menu">
                                <ul class="nav-collapse navbar-nav">
                                    <li class="menu-item-has-children current-menu-item">
                                        <a href="/catalog">Каталог</a>
                                        <ul class="sub-menu">
                                            <?php $categories = Category::find()->all(); ?>
                                            <?php foreach ($categories as $category):?>
                                                <li><a href="/catalog/<?= $category->slug ?>"><?= $category->title ?></a></li>
                                            <?php endforeach;?>
                                        </ul>
                                    </li>
                                    <li><a href="/contact">Контакты</a></li>
                                    <li><a href="#">Доставка</a></li>
                                    <li><a href="#">Оплата</a></li>
                                    <li><a href="/site/about">О нас</a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <?php if($this->context->action->id != 'index'):?>
        <section class="noo-page-heading eff heading-2">
            <div class="container">
                <div class="noo-heading-content">
                    <h1 class="page-title eff"><?= $this->title ?></h1>
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
                    <div class="col-md-3 col-sm-6 item-footer-four">
                        <div class="widget widget_about">
                            <div class="noo_about_widget">
                                <a href="#">
                                    <img src="/images/logo.png" alt="<?= Yii::$app->params['title'] ?>" />
                                </a>
                                <p><?= Yii::$app->params['companySlogan'] ?></p>
                            </div>
                        </div>
                        <div class="widget widget_noo_social">
                            <div class="noo_social">
                                <div class="social-all">
                                    <a href="<?= Yii::$app->params['linkInstagram'] ?>" class="fa fa-instagram"></a>
                                    <a href="<?= Yii::$app->params['linkVk'] ?>" class="fa fa-twitter"></a>
                                    <a href="<?= Yii::$app->params['linkFacebook'] ?>" class="fa fa-facebook"></a>
                                </div>
                            </div>
                        </div>
                        <div class="widget widget_text">
                            <div class="textwidget">
                                <div class="copyright">
                                    Copyright &copy; 2018 <?= Yii::$app->params['domain'] ?><br/>
                                    Developed with <i class="fa fa-heart-o"></i> by <a href="<?= Yii::$app->params['developerSite'] ?>" rel="external"><?= Yii::$app->params['developer'] ?></a>.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 item-footer-four">
                        <div class="widget widget_text">
                            <h4 class="widget-title">Контакты</h4>
                            <div class="textwidget">
                                <h5>Адрес</h5>
                                <p><?= Yii::$app->params['address'] ?></p>
                                <h5>Телефон</h5>
                                <p>
                                    <a href="#"><?= Yii::$app->params['phone1'] ?></a><br/>
                                    <a href="#"><?= Yii::$app->params['phone2'] ?></a>
                                </p>
                                <h5>Email</h5>
                                <p>
                                    <a href="mailto:<?= Yii::$app->params['email'] ?>">
                                        <?= Yii::$app->params['email'] ?>
                                    </a>
                                </p>
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
                            <h4 class="widget-title">Время работы</h4>
                            <ul class="noo-openhours">
                                <li>
                                    <span>С 9:00 до 18:00 </span>
                                    <span>Без выходных </span>
                                </li>
                            </ul>
                        </div>
                        <div class="widget widget_noo_happyhours">
                            <h4 class="widget-title">Время работы</h4>
                            <ul class="noo-happyhours">
                                <li>
                                    <div>С 9:00 до 18:00 </div>
                                    <div>Без выходных </div>
                                </li>
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
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
