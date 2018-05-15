<?php
namespace frontend\controllers;

use Yii;
use frontend\models\ContactForm;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Product;
use common\models\Category;

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
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Спасибо,что написали нам. Мы свяжемся с Вами в ближайщее время.');
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка при отправке сообщения.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
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
}
