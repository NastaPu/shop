<?php

namespace shop\services\manage;


class TransactionManager
{
    public function wrap(callable $function): void
    {
        \Yii::$app->db->transaction($function);
    }
}