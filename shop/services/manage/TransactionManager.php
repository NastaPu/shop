<?php

namespace shop\services\manage;

use shop\dispatcher\DeferredEventDispatcher;

class TransactionManager
{
    public function wrap(callable $function): void
    {
        \Yii::$app->db->transaction($function);
    }
}