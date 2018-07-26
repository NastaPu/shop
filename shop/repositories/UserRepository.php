<?php

namespace shop\repositories;

use shop\entities\User\User;

class UserRepository
{
    public function findByUsernameOrEmail($value): ?User
    {
        $user = User::find()->where(['username' => $value])->one();
        //if($user == null ) {
          //  throw new \DomainException('Invalid login or password');
      // }
        return $user;
       // return User::find()->andWhere(['username' => $value])->one();
    }

    public function getByEmailConfirmToken($token) :?User
    {
        $user = User::findOne(['email_confirm_token' => $token]);
        if(!$user) {
            throw new \DomainException('User is not found');
        }
        return $user;
    }

    public function findByNetworkIdentity($network, $identity): ?User
    {
        $user =  User::find()->joinWith('networks n')->andWhere(['n.network' => $network, 'n.identity' => $identity])->one();
        return $user;
    }

    public function save(User $user)
    {
        if (!$user->save()) {
            throw new \DomainException('Saving error');
        }
    }

    public function getBy(array $value):User
    {
        if (!$user = User::find()->andWhere($value)->limit(1)->one()) {
            throw new \DomainException('User not found');
        }
        return $user;
    }

    public function get($id):User
    {
        return $this->getBy(['id' => $id]);
    }
}