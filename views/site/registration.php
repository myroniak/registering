<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
use yii\captcha\Captcha;

$this->title = 'Реєстрація';

?>
<script src="https://code.jquery.com/jquery-1.11.2.min.js"></script>

<?php if ($err) {
Modal::begin([
'id'=>'memberModal',
'header' => "<h2 align=center>".$err."</h2>",
'footer' => ' '
]);
echo Html::button('Закрити', ['class' => 'btn  btn-block ','data-dismiss' => 'modal', 'value' => 'no' ]);

modal::end();
} ?>

<?php if ($modal_w) {
Modal::begin([
'id'=>'memberModal',
'header' => "<h2 align=center> Зв'язати Профілі? </h2>",
'footer' => ' '
]);
$f = ActiveForm::begin(['id' => 'link-form',  ]);?>
<?=$f->field($model, 'login')->hiddenInput(['value'=>$login])->label(false); ?>
<?=$f->field($model, 'pass')->hiddenInput(['value'=>$pass])->label(false); ?>
<?=$f->field($model, 'name')->hiddenInput(['value'=>$name])->label(false); ?>
<?=$f->field($model, 'surname')->hiddenInput(['value'=>$surname])->label(false); ?>
<?=$f->field($model, 'email')->hiddenInput(['value'=>$email])->label(false); ?>
<?php
echo Html::submitButton('Так', ['class' => 'btn  btn-block btn-primary','value' => 'yes']); 
echo Html::a('Ні', ['/site/hello'], ['class' => 'btn  btn-block']);
ActiveForm::end();
modal::end();
 } ?>


<div class="col-lg-offset-3 col-lg-6">
	<div class="site-registration">
		<h1 align=center ><?= Html::encode($this->title) ?></h1>
		<div class="row">
<?php $f = ActiveForm::begin(['id' => 'registration-form','options' => ['enctype' => 'multipart/form-data']]);?>

	<?=$f->field($form, 'login') ?>
	<?=$f->field($form, 'pass')->textInput(['type'=>'password']) ?>
	<?=$f->field($form, 'conf_pass')->textInput(['type'=>'password']) ?>
	<?=$f->field($form, 'name') ?>
	<?=$f->field($form, 'surname') ?>
	<?=$f->field($form, 'email')->textInput(['type'=>'email']) ?>
	
	<?=$f->field($form, 'verifyCode')->widget(Captcha::className(), [
                        'template' => '<div class="row"><div class=" col-lg-3">{image}</div><div class="col-lg-3">{input}</div></div>',
                    ]) ?>
	<p align=center > <?= Html::submitButton('Перевірити', ['class' => 'btn  btn-block btn-primary']); ?></p>
<?php ActiveForm::end(); ?>

		</div>
	</div>
</div>
<script>
$(document).ready(function () {

    $('#memberModal').modal('show');

});
</script>