<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Form';
$this->params['breadcrumbs'][] = $this->title;
$edit = $edit ?? false;
?>
<div class="site-contact">
    <?if (!$edit):?><h1><?= Html::encode($this->title) ?></h1><?endif;?>

    <?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>

        <div class="alert alert-success">
            Спасибо!
        </div>

    <?php else: ?>

        <div class="row">
            <div class="col-lg-5">

                <?php $form = ActiveForm::begin([
                	'id' => 'contact-form',
	                'enableAjaxValidation' => true,
	                'enableClientValidation' => false,
	                'validateOnBlur' => false,
	                'validateOnChange' => false,
	                'validateOnType' => false,
                ]); ?>

	            <?if ($edit):?>
		            <?= $form->field($model, 'lead_id')->hiddenInput()->label(false) ?>
	            <?endif;?>
                    <?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>

                    <?= $form->field($model, 'email')->input('email') ?>

                    <?= $form->field($model, 'phone')->widget(\yii\widgets\MaskedInput::className(), ['mask' => '+7 (999) 999-99-99']) ?>

		            <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                        'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
                    ]) ?>

                    <div class="form-group">
                        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                    </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>

    <?php endif; ?>
</div>
