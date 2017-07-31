<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'menu_name');

echo $form->field($model,'parent_id')->dropDownList(
    //$categories[] = ['id'=>0,'parent_id'=>0,'name'=>'顶级菜单','open'=>1],
    //\backend\models\Menu::getmenu(),['prompt' => '顶级菜单']
    \yii\helpers\ArrayHelper::map(\backend\models\Menu::find()->where('parent_id=0')->all(),'id','menu_name'),['prompt' => '顶级菜单']
);
echo $form->field($model,'menu_url')->dropDownList(\yii\helpers\ArrayHelper::map
    (\Yii::$app->authManager->getPermissions(), 'name', 'description'),['prompt' => '请选择路由']);


echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-default']);
\yii\bootstrap\ActiveForm::end();