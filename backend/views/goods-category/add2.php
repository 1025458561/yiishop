<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'parent_id');
echo $form->field($model,'intro');


echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-sm']);
\yii\bootstrap\ActiveForm::end();