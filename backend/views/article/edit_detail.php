<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($detail,'content')->textarea();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info0']);
\yii\bootstrap\ActiveForm::end();


?>