<?php

use common\models\AsDateValidator;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Post $model */

$this->title = Yii::t('app', $model->title);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Posts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', $this->title);
\yii\web\YiiAsset::register($this);
?>
<div class="post-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' =>  $model,

        'attributes' => [
            'id',
            [
                'attribute'=>'user_id',
                'value'=>function($model){
                    $item = \common\models\User::find()->where(['id' => $model->user_id])->one();
                    return $item->username;
                }
            ],
            'title',
            'text:html',
             [
                'attribute'=>'post_category_id',
                'value'=>function($model){
                    $item = \common\models\PostCategory::find()->where(['id' => $model->post_category_id])->one();
                    return $item->name;
                }
            ],
            [
                'attribute'=>'status',
                'value'=>function($model){
                    $item = new \common\models\Status();
                    return $item->getStatusName($model->status);
                }
            ],
            'image',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
