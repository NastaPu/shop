<?php

namespace shop\entities\User;

use shop\entities\User\User;
use Yii;

/**
 * This is the model class for table "user_network".
 *
 * @property int $id
 * @property int $user_id
 * @property string $identity
 * @property string $network
 *
 * @property User $user
 */
class Network extends \yii\db\ActiveRecord
{
    public static function create($network, $identity):self
    {
        if(empty($network)) {
            throw new \DomainException('Network is empty');
        }
        if(empty($identity)) {
            throw new \DomainException('Identity is empty');
        }

        $item = new static();
        $item->network = $network;
        $item->identity = $identity;
        return $item;
    }

    public function isFor($network, $identity)
    {
        return $this->network === $network && $this->identity === $identity;
    }

    public static function tableName()
    {
        return 'user_network';
    }
}
