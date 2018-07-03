<?php

namespace shop\validators;

use yii\validators\RegularExpressionValidator;

class SlugValidate extends RegularExpressionValidator
{
    public $pattern = '#^[a-z0-9_-]*$#s';
    public $message = 'Only [a-z0-9_-] characters are available';
}