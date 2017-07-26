<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username');
echo $form->field($model,'password')->passwordInput();
echo $form->field($model,'rember')->checkbox();
//验证码
echo $form->field($model,'code')->widget(\yii\captcha\Captcha::className(),['captchaAction'=>'user/captcha']);
echo \yii\bootstrap\Html::submitButton('登录',['class'=>'btn btn-info']);

\yii\bootstrap\ActiveForm::end();