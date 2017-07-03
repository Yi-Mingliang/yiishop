<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'description')->textarea();
echo $form->field($model,'permissions')->checkboxList(\backend\models\RoleForm::getPermissionsOptions());
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-xs btn-info']);
\yii\bootstrap\ActiveForm::end();


?>