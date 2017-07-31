<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username');
if($model->isNewRecord){
    echo $form->field($model,'password')->passwordInput();
}
echo $form->field($model,'email');
if(!$model->isNewRecord){
    echo $form->field($model,'status')->radioList(\backend\models\User::$status);
}
echo $form->field($model,'Roles')->checkboxList(
    \yii\helpers\ArrayHelper::map(Yii::$app->authManager->getRoles(),'name','name'));

echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-default']);
\yii\bootstrap\ActiveForm::end();