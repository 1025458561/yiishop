<h1><?= $model->scenario==\backend\models\RoleForm::SCENARIO_ADD?'添加':'修改'?>角色</h1>

<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'description');
echo $form->field($model,'permissions',['inline'=>true])->checkboxList(
    \yii\helpers\ArrayHelper::map(Yii::$app->authManager->getPermissions(),'name','description'));

//echo $form->field($model,'permissions')->checkboxList(\yii\helpers\ArrayHelper::map(\Yii::$app->authManager->getPermissions(),'name','description'));
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-default']);
\yii\bootstrap\ActiveForm::end();