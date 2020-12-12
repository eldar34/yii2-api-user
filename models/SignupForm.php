<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;




class SignupForm extends Model
{
    public $username;
    public $password;
    public $email;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['username', 'password', 'email'], 'required'],
            ['username', 'match', 'pattern' => '/^[A-z0-9_-]*$/i'],
            ['password', 'string', 'min' => 6, 'max' => 120],
            ['username', 'string', 'min' => 2, 'max' => 64],
            ['email', 'email'],
            
        ];
    }

    public function signup()
    {
        if ($this->validate()) {
            $class = \Yii::$app->getUser()->identityClass;
            $user = new $class();
            $user->username = mb_strtolower($this->username);
            $user->email = $this->email;            
            $user->setPassword($this->password);
            $user->generateAccessToken();
            $user->generateAuthKey();
            
            if ($user->save()) {
                return $user;
            }
        }

        return null;
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Username',
            'password' => 'Password',
            'email' => 'Email'
        ];
    }

    
    
}