<h1><?=$model->scenario==\backend\models\PermissionForm::SCENARIO_ADD?'添加':'修改'?>权限</h1><?php
$form  = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput(['readonly'=>$model->scenario!=\backend\models\PermissionForm::SCENARIO_ADD]);
echo $form->field($model,'description');
echo \yii\bootstrap\Html::submitButton('添加',['class'=>'btn btn-default']);
\yii\bootstrap\ActiveForm::end();