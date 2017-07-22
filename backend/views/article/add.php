<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'intro');
echo $form->field($model,'article_category_id')->dropDownList(\yii\helpers\ArrayHelper::map(\backend\models\ArticleCategory::find()->all(),'id','name'));
echo $form->field($model,'sort')->input('number');
echo $form->field($model,'status',['inline'=>true])->radioList(\backend\models\Article::getStatusOptions());
//echo $form->field($admodel,'content')->textarea();
echo $form->field($admodel,'content')->widget('kucha\ueditor\UEditor',[
    'clientOptions' => [
        //编辑区域大小
       // 'initialFrameWidth' => '50',
        'initialFrameHeight' => '200',
        //设置语言
        'lang' =>'en', //中文为 zh-cn
        'serverUrl'=>\yii\helpers\Url::to(['u-upload']),
        //定制菜单

        //添加菜单里面的
        'toolbars' => [
            [
                'fullscreen', 'source', 'undo', 'redo', '|',
                'fontsize',
                'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'removeformat',
                'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|',
                'forecolor', 'backcolor', '|',
                'lineheight', '|',
                'indent', '|','simpleupload','insertimage'
            ],
        ]
    ]
]);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-sm']);
\yii\bootstrap\ActiveForm::end();