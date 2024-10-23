<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Post $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="post-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>

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
