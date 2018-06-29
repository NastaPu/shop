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
    public static function tableName()
    {
        return 'user_network';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'identity', 'network'], 'required'],
            [['user_id'], 'integer'],
            [['identity'], 'string', 'max' => 255],
            [['network'], 'string', 'max' => 16],
            [['identity', 'network'], 'unique', 'targetAttribute' => ['identity', 'network']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'identity' => 'Identity',
            'network' => 'Network',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
