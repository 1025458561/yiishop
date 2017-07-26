<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'apassword');
echo $form->field($model,'bpassword');
echo $form->field($model,'cpassword');
echo \yii\helpers\Html::submitButton('修改',['class'=>'btn btn-default']);
\yii\bootstrap\ActiveForm::end();