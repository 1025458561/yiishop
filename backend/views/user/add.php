<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username');
echo $form->field($model,'password')->passwordInput();
echo $form->field($model,'email');
if(!$model->isNewRecord){
    echo $form->field($model,'status')->radioList(\backend\models\User::$status);
}


echo \yii\helpers\Html::submitButton('提交',['class'=>'btn btn-default']);
\yii\bootstrap\ActiveForm::end();