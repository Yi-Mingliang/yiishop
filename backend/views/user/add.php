<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($user,'username');
if(!$user->password_hash){
    echo $form->field($user,'password_hash')->passwordInput();
}
echo $form->field($user,'email');
echo $form->field($user,'status',['inline'=>true])->radioList(\backend\models\User::$statusOptions);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();

?>