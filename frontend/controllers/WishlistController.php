<?php

namespace frontend\controllers;

use frontend\models\Wishlist;
use common\models\Product;
use Yii;

class WishlistController extends \yii\web\Controller
{

    public function actionList(){

        if (!Yii::$app->user->isGuest) {
            Yii::$app->user->setReturnUrl(Yii::$app->request->url);
            $wishlist = Wishlist::find()->where(['user_id' => Yii::$app->user->id])->all();

            return $this->render('list', [
                'wishlist' => $wishlist,
            ]);
        } else {
            $this->redirect('/catalog');
        }
    }

    public function actionAdd($id)
    {
        if (!Yii::$app->user->isGuest) {
            $product = Product::findOne($id);
            if ($product) {
                $wishlistItem = new Wishlist();
                $wishlistItem->product_id = $id;
                $wishlistItem->user_id = Yii::$app->user->id;
                $wishlistItem->save();
                return $this->goBack();
            }
        }
    }

    public function actionUpdate($id)
    {
        if (!Yii::$app->user->isGuest) {
            $wishlist = Wishlist::find()->where(['user_id' => Yii::$app->user->id, 'product_id' => $id])->one();
            if (!$wishlist) {
                $wishlistItem = new Wishlist();
                $wishlistItem->product_id = $id;
                $wishlistItem->user_id = Yii::$app->user->id;
                $wishlistItem->save();
            } else {
                $wishlist->delete();
            }
            return true;
        } else
            return false;
    }

    public function actionRemove($id)
    {
        if (!Yii::$app->user->isGuest) {
            $wishlistItem = Wishlist::findOne($id);
            if ($wishlistItem) {
                $wishlistItem->delete();
            }
            $this->redirect(['/wishlist']);
        }
    }
}

