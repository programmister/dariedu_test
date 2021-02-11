<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\grid\GridView;
use \yii\widgets\Pjax;
use yii\bootstrap\Modal;

$this->title = 'Leads';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

<?
Pjax::begin();
echo GridView::widget([
	'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
	'columns' => [
		'name', 'email', 'phone',
		[
			'class' => 'yii\grid\ActionColumn',
			/*'visibleButtons' => [
				'update' => true,
				'delete' => true,
				'view' => false,
			]*/
			'options'=>['class'=>'action-column'],
			'template'=>'{update} {delete}',
			'buttons'=>[
				'update' => function ($url, $model) {
					return Html::a(
						'<span class="glyphicon glyphicon-pencil"></span>',
						$url, [
							'data-toggle' => 'modal',
							'data-target' => '#mymodal-win',
							'onclick' => "$('#mymodal-win .modal-dialog .modal-content .modal-body').load($(this).attr('href'))",
						]
					);
				},
			]
		],
	]
]);
Pjax::end();

echo Modal::widget([
	'id' => 'mymodal-win',
	'toggleButton' => false,
	'header' => '<h2>Lead edit</h2>'
]);
?>

</div>
