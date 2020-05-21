<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string|null $login
 * @property string|null $password
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['login', 'password', 'auth_key', 'date_register', 'date_last_login'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'login' => 'Login',
            'password' => 'Password',
            'auth_key' => 'Auth key',
            'date_register' => 'Registered',
            'date_last_login' => 'Last Seen',
        ];
    }

	public function fields()
	{
	    return [
		// field name is the same as the attribute name
		'id',
		'login',
		'date_register',
		'date_last_login',
		'password' => function ($model) {
			if (isset($_GET['auth_key']) && $_GET['auth_key'] == $model->auth_key) { return $model->password; }
			if (Yii::$app->user->identity && $model->id == Yii::$app->user->identity->id) { return $model->password; } return "";
		},
		'auth_key' => function ($model) {
			if (isset($_GET['auth_key']) && $_GET['auth_key'] == $model->auth_key) { return $model->auth_key; }
			if (Yii::$app->user->identity && $model->id == Yii::$app->user->identity->id) { return $model->auth_key; } return "";
		},
	    ];
	}

    public function getId()
    {
        return $this->id;
    }

    public function validatePassword($password)
    {
        return $password === $this->password;
    }

    public function before_login()
    {
        $this->date_last_login = date("Y-m-d H:i:s");
        $this->save();
    }

    public static function create($username, $password)
    {
        $user = new User();
	$user->login = $username;
	$user->password = $password;
        $user->date_register = date("Y-m-d H:i:s");
	$user->before_login();
	return $user;
    }

    public static function findIdentity($id)
    {
        return self::findOne(['id' => $id]);
    }

    public static function findByUsername($username)
    {
        return self::findOne(['login' => $username]);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->auth_key = \Yii::$app->security->generateRandomString();
            }
            return true;
        }
        return false;
    }

    /**
     * Finds an identity by the given token.
     *
     * @param string $token the token to be looked for
     * @return IdentityInterface|null the identity object that matches the given token.
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['auth_key' => $token]);
    }


    /**
     * @return string current user auth key
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @param string $authKey
     * @return bool if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }
}
