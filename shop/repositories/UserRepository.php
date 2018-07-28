<?php

namespace shop\repositories;

use shop\dispatcher\EventDispatcher;
use shop\entities\User\User;

class UserRepository
{
    private $dispatcher;

    public function __construct(EventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function findByUsernameOrEmail($value): ?User
    {
        $user = User::find()->where(['username' => $value])->one();
        return $user;
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
        //$this->dispatcher->dispatchAll($user->releaseEvent());
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

    /**
     * @param $productId
     * @return iterable|User[]
     */
    public function getAllByProductInWishList($productId): iterable
    {
        return User::find()
            ->alias('u')
            ->joinWith('wishlistItems w', false, 'INNER JOIN')
            ->andWhere(['w.product_id' => $productId])
            ->each();
    }
}