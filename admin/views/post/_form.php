<?php

use vova07\imperavi\Widget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


/** @var yii\web\View $this */
/** @var common\models\Post $model */
/** @var yii\widgets\ActiveForm $form */

//Yii::import('ext.imperavi-redactor-widget.ImperaviRedactorWidget');
//use vendor\vova07\yii2-imperavi-widget\src\Widget;
?>


<div class="post-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'text')->widget(Widget::className(),  [
        'settings' => [
            'lang' => 'ru',
            'minHeight' => 200,
            'plugins' => [
                'clips',
                'fullscreen',
            ],
            'clips' => [
                ['Lorem ipsum...', 'Lorem...'],
                ['red', '<span class="label-red">red</span>'],
                ['green', '<span class="label-green">green</span>'],
                ['blue', '<span class="label-blue">blue</span>'],
            ],
        ],
    ]) ?>
<!--    --><?php //= $form->field($model, 'text')->textarea(['rows' => 6]) ?>

    <?php
    $id_category = new \common\models\PostCategory();?>

    <?= $form->field($model, 'post_category_id')->dropDownList($id_category->getNameArray()); ?>

<!--    --><?php //= $form->field($model, 'post_category_id')->textInput() ?>

    <?php
        $const = new \common\models\Status();?>
    <?=
        $form->field($model, 'status')->dropDownList($const->getConstants());
    ?>


    <?= $form->field($model, 'imageFile')->fileInput() ?>


    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
