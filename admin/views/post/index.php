<?php

use common\models\Post;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\PostSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Posts') ;
$this->params['breadcrumbs'][] = Yii::t('app', $this->title);
?>
<div class="post-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать пост', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <h2>Поиск постов</h2>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

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
            //'status',
            //'image',
            //'created_at',
            //'updated_at',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Post $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
