<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'parent_id')->dropDownList(\yii\helpers\ArrayHelper::map($data,'id','name'),['prompt'=>'请选择上级菜单']);
echo $form->field($model,'url');
echo $form->field($model,'sort');
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();


?>