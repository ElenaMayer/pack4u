<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{
    public $name;
    public $email;
    public $phone;
    public $body;
    public $verifyCode;
    public $validator;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'email', 'body'], 'required'],
            // email has to be a valid email address
            ['email', 'email'],
            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha'],
            ['validator', function ($attribute, $params) {
                if ($this->$attribute) {
                    $this->addError($attribute, "Don't spam!");
                }
            }],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Имя',
            'email' => 'Email',
            'phone' => 'Телефон',
            'body' => 'Сообщение',
            'verifyCode' => 'Код подтверждения',
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     *
     * @param  string  $email the target email address
     * @return boolean whether the email was sent
     */
    public function sendEmail()
    {
        $body = 'Имя: ' . $this->name;
        $body .= $this->phone?' Телефон: ' . $this->phone:'';
        $body .= $this->email?' Email: ' . $this->email:'';
        $body .= ' Сообщение : ' . $this->body;
        return Yii::$app->mailer->compose()
            ->setTo(Yii::$app->params['adminEmail'])
            ->setFrom(Yii::$app->params['adminEmail'])
            ->setSubject(Yii::$app->params['domain'] . ' - Сообщение из контактов')
            ->setTextBody($body)
            ->send();
    }
}
