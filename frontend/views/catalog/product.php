<?php
use yii\helpers\Html;
use yii\helpers\Markdown;

/* @var $this yii\web\View */
$title = $category === null ? 'Welcome!' : $category->title;
$this->title = Html::encode($title);
?>

<h1><?= Html::encode($title) ?></h1>

<div class="container-fluid">
  <div class="row">
      <div class="col-xs-8">
          <div class="col-xs-12 well">
              <div class="col-xs-2">
                  <?php
                  $images = $product->images;
                  if (isset($images[0])) {
                      echo Html::img($images[0]->getUrl(), ['width' => '100%']);
                  }
                  ?>
              </div>
              <div class="col-xs-6">
                  <h2><?= Html::encode($product->title) ?></h2>
                  <?= Markdown::process($product->description) ?>
              </div>

              <div class="col-xs-4 price">
                  <div class="row">
                      <div class="col-xs-12">$<?= $product->price ?></div>
                      <div class="col-xs-12"><?= Html::a('Add to cart', ['cart/add', 'id' => $product->id], ['class' => 'btn btn-success'])?></div>
                  </div>
              </div>
          </div>
      </div>
  </div>
</div>