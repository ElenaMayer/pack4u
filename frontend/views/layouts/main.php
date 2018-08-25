<?php
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use frontend\assets\IeAsset;
use frontend\widgets\Alert;
use common\models\Category;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
IeAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <?= Html::csrfMetaTags() ?>
    <?php Yii::$app->view->registerMetaTag(['name' => 'description','content' => Yii::$app->params['companySlogan']]); ?>
    <title><?= Html::encode(Yii::$app->name . ' - ' .  Yii::$app->params['title']) ?></title>
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
                        <li>
                            <span><i class="fa fa-phone"></i></span>
                            <a href="<?= Yii::$app->params['phone1'] ?>"><?= Yii::$app->params['phone1'] ?></a>
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
                                <span><i class="fa fa-user"></i></span>
                                <a href="/user/login" title="Вход">Вход</a>
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
            <div id="sticky-header-with-topbar" class="navbar-wrapper sticky__header">
                <div class="navbar navbar-default">
                    <div class="container">
                        <div class="menu-position">
                            <div class="navbar-header pull-left">
                                <h1 class="sr-only"><?= Yii::$app->name ?></h1>
                                <div class="noo_menu_canvas">
                                    <div class="topbar-has-cart btn-cart">
                                        <a href="/cart">
                                            <span class="has-cart">
                                                <i class="fa fa-shopping-cart"></i>
                                                <?php $itemsInCart = Yii::$app->cart->getCount(); ?>
                                                <?php if($itemsInCart):?>
                                                    <em><?= $itemsInCart ?></em>
                                                <?php endif; ?>
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
                                            <?php $categories = Category::find()->where(['is_active' => 1])->all(); ?>
                                            <?php foreach ($categories as $category):?>
                                                <li><a href="/catalog/<?= $category->slug ?>"><?= $category->title ?></a></li>
                                            <?php endforeach;?>
                                        </ul>
                                    </li>
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
                                    Copyright &copy; 2018 <?= Yii::$app->params['domain'] ?><br/>
                                    Developed with <i class="fa fa-heart-o"></i> by <a href="<?= Yii::$app->params['developerSite'] ?>" rel="external"><?= Yii::$app->params['developer'] ?></a>.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 item-footer-four">
                        <div class="widget widget_text">
                            <h4 class="widget-title">Помощь</h4>
                            <div class="textwidget">
                                <p><a href="/contact">Контакты</a></p>
                                <p><a href="/shipping">Информация о доставке</a></p>
                                <p><a href="/payment">Способы оплаты</a></p>
                                <p><a href="/refund">Возврат товара</a></p>
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
                                    <i class="fa fa-phone"></i><a href="<?= Yii::$app->params['phone1'] ?>"><?= Yii::$app->params['phone1'] ?></a>
                                </li>
                                <li>
                                    <i class="fa fa-envelope"></i><a href="mailto:<?= Yii::$app->params['email'] ?>">
                                        <?= Yii::$app->params['email'] ?>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="widget widget_noo_happyhours">
                            <h4 class="widget-title">Время работы</h4>
                            <ul class="noo-happyhours">
                                <li>
                                    <div>9:00 - 21:00</div>
                                    <div>Без выходных</div>
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
    <?= $this->render('_metrika'); ?>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
