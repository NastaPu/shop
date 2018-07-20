<?php

/* @var $this \yii\web\View */
/* @var $content string */

use frontend\widgets\Blog\CategoryWidget;

?>
<?php $this->beginContent('@frontend/views/layouts/main.php') ?>

    <div class="row">
        <div id="content" class="col-sm-9">
            <?= $content ?>
        </div>
        <aside id="column-left" class="col-sm-3 hidden-xs">
            <?= CategoryWidget::widget([
                'active' => $this->params['active_category'] ?? null
            ]) ?>
        </aside>
    </div>

<?php $this->endContent() ?>