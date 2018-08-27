<?php
namespace frontend\controllers;

use Yii;
use frontend\models\ContactForm;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Product;
use common\models\Category;
use yii\sphinx\Query;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        $categories = Category::find()->where(['is_active' => 1])->indexBy('id')->orderBy('id')->all();
        return $this->render('index', [
            'noveltyProducts' => Product::getNovelties(),
            'categories' => $categories
        ]);
    }

    public function actionContact()
    {
        return $this->render('contact');
    }

    public function actionShipping()
    {
        return $this->render('shipping');
    }

    public function actionPayment()
    {
        return $this->render('payment');
    }

    public function actionRefund()
    {
        return $this->render('refund');
    }

    public function actionOffer()
    {
        return $this->render('offer');
    }

    public function actionSearch(){

        $q = Yii::$app->sphinx->escapeMatchValue($_GET['s']);
        $sql = "SELECT id, SNIPPET(title, :q) as _title, SNIPPET(description, :q) AS _description,
                        category_id, price, SNIPPET(article, :q) AS _article, is_in_stock, is_novelty, size, new_price, count
                FROM pack4uindex WHERE MATCH(:q)";
        $rows = Yii::$app->sphinx->createCommand($sql)
            ->bindValue('q', $q)
            ->queryAll();
        $snippets = [];
        foreach ($rows as $row) {
            $snippets[$row['id']] = [
                'title' => $row['_title'],
                'description' => $row['_description'],
                'article' => $row['_article'],
                'category_id' => $row['category_id'],
                'price' => $row['price'],
                'is_in_stock' => $row['is_in_stock'],
                'is_novelty' => $row['is_novelty'],
                'size' => $row['size'],
                'new_price' => $row['new_price'],
                'count' => $row['count']];
        }

//        $query = new Query();
//        $rows = $query->from('pack4uindex')
//            ->match($_GET['s'])
//            ->all();
//        print_r($rows);die();

        return $this->render('search', [
                'noveltyProducts' => Product::getNovelties(),
                'menuItems' => Category::getMenuItems(null),
                'snippets' => $snippets,
            ]
        );
    }
}
