<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Product;
use common\models\Category;
use Google_Client;
use Google_Service_YouTube;
use frontend\components\GeoBehavior;

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
            'geoBehavior' => GeoBehavior::className(),
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
        return $this->render('index', [
            'noveltyProducts' => Product::getNovelties(),
            'categories' => Category::getMainCategories(),
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
        $sql = "SELECT id, SNIPPET(title, :q) as _title, category_id, price, SNIPPET(article, :q) AS _article, 
                is_in_stock, is_novelty, size, new_price, count, SNIPPET('dtitle', :q) as _dtitle, SNIPPET('darticle', :q) AS _darticle
                FROM pack4uindex WHERE MATCH(:q) LIMIT " . Yii::$app->params['sphinxLimit'];
        $rows = Yii::$app->sphinx->createCommand($sql)
            ->bindValue('q', $q)
            ->queryAll();
        $snippets = [];
        foreach ($rows as $row) {
            $snippets[$row['id']] = [
                'title' => $row['_title'],
                'dtitle' => $row['_dtitle'],
                'article' => $row['_article'],
                'darticle' => $row['_darticle'],
                'category_id' => $row['category_id'],
                'price' => $row['price'],
                'is_in_stock' => $row['is_in_stock'],
                'is_novelty' => $row['is_novelty'],
                'size' => $row['size'],
                'new_price' => $row['new_price'],
                'count' => $row['count']];
        }
        Yii::$app->user->setReturnUrl(Yii::$app->request->url);

        return $this->render('search', [
                'noveltyProducts' => Product::getNovelties(),
                'menuItems' => Category::getMenuItems(null),
                'snippets' => $snippets,
            ]
        );
    }

    public function actionInstruction(){
        $data = $this->getYoutubeData();
        return $this->render('instruction', ['data' => $data]);
    }

    private function getYoutubeData($cache_life = 86400) {

        $cache = Yii::$app->cache;

        $data = $cache->getOrSet('youtube', function () {
            return $this->getYoutubePlaylist();
        }, $cache_life);
        return $data;
    }

    private function getYoutubePlaylist(){
        $client = new Google_Client();

        $client->setDeveloperKey(Yii::$app->params['youtubeApiKey']);
        $service = new Google_Service_YouTube($client);
        $response = $service->playlistItems->listPlaylistItems(
            'snippet,contentDetails',
            array('maxResults' => 25, 'playlistId' => Yii::$app->params['youtubePlayListId'])
        );
        return $response->getItems();
    }

    public function actionInstruction_item($id){
        $data = $this->getYoutubeVideoById($id);
        return $this->render('instruction-item', [
            'data' => $data,
            'menuItems' => Category::getMenuItems(null),
            'noveltyProducts' => Product::getNovelties(),
            ]);
    }

    private function getYoutubeVideoById($id){
        $client = new Google_Client();

        $client->setDeveloperKey(Yii::$app->params['youtubeApiKey']);
        $service = new Google_Service_YouTube($client);
        $response = $service->videos->listVideos(
            'snippet',
            array('id' => $id)
        );
        return $response->getItems();
    }

    public function actionChange_location($city)
    {
        $cache = Yii::$app->cache;
        $cache->set('location', $city);

    }

}
