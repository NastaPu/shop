<?php

namespace shop\forms\manage;

use shop\entities\User\User;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class UserCreateForm extends Model
{

    public $username;
    public $email;
    public $password;
    public $role;
    public $phone;

    public function rules(): array
    {
        return [
            [['username', 'email', 'password', 'role', 'phone'], 'required'],
            ['email', 'email'],
            [['username', 'email'], 'string', 'max' => 255],
            [['username', 'email', 'phone'], 'unique', 'targetClass' => User::class],
            ['password', 'string', 'min' => 6],
        ];
    }

    public function rolesList():array
    {
        return ArrayHelper::map(\Yii::$app->authManager->getRoles(), 'name', 'description');
    }
}