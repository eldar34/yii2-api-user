<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\web\IdentityInterface;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $auth_key
 * @property string|null $verification_token
 * @property string $password_hash
 * @property string $password
 * @property string|null $password_reset_token
 * @property string $email
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 */
class User extends ActiveRecord implements IdentityInterface
{
    public $password;

    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    const SCENARIO_USER_SIGNUP = 'user_signup';
    const SCENARIO_USER_UPDATE = 'user_update';

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            if($insert){
                
                $this->generateAccessToken();
                $this->generateAuthKey();
            }

            $this->username = mb_strtolower($this->username);

            if(!is_null($this->password)){
                $this->setPassword($this->password);
            }

            
            

            return parent::beforeSave($insert);
        } else {
            return false;
        }
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();

        $scenarios[self::SCENARIO_USER_UPDATE] = ['username', 'email', 'password'];
        $scenarios[self::SCENARIO_USER_SIGNUP] = ['username', 'email', 'password'];

        return $scenarios;
        
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'password', 'email'], 'required', 'on' => 'user_signup'],
            ['username', 'match', 'pattern' => '/^[A-z0-9_-]*$/i'],
            ['username', 'string', 'min' => 2, 'max' => 64],
            ['password', 'string', 'min' => 6, 'max' => 120],
            ['email', 'email'],
            [['auth_key', 'password_hash'], 'required'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            [['verification_token', 'password_hash', 'password_reset_token'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['username'], 'unique'],
            [['verification_token'], 'unique'],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],
        ];
    }

    public function fields()
    {

        $fields = parent::fields();

        // Hide unsafe fields
        unset(
            $fields['auth_key'], 
            $fields['status'],
            $fields['password_hash'],
            $fields['verification_token'],
            $fields['password_reset_token'],
            $fields['created_at'],
            $fields['updated_at']
        );

        return $fields;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }
 
    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['verification_token' => $token]);
    }

     /**
     * Generates "api" access token
     */
    public function generateAccessToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString($length = 16);
    }

     /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

     /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }
 
    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }
 
    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }
 
    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }
 
    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        // default length=32
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Username',
            'auth_key' => 'Auth Key',
            'verification_token' => 'Verification Token',
            'password_hash' => 'Password',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}