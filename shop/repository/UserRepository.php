<?php

namespace shop\repository;

use shop\entities\User\User;

class UserRepository
{
    public function findByUsernameOrEmail($value): ?User
    {
        return User::find()->where(['username' => $value])->one();
       // return User::find()->andWhere(['username' => $value])->one();
    }

    public function findByNetworkIdentity($network, $identity): User
    {
        return User::find()->joinWith('networks n')->andWhere(['n.network' => $network, 'n.identity' => $identity])->one();
    }
}