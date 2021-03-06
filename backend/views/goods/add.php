<?php
//添加商品
use yii\web\JsExpression;
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');

echo $form->field($model,'logo')->hiddenInput();
echo \yii\bootstrap\Html::fileInput('test', NULL, ['id' => 'test']);
echo \flyok666\uploadifive\Uploadifive::widget([
    'url' => yii\helpers\Url::to(['s-upload']),
    'id' => 'test',
    'csrf' => true,
    'renderTag' => false,
    'jsOptions' => [
        'formData'=>['someKey' => 'someValue'],
        'width' => 120,
        'height' => 40,
        'onError' => new JsExpression(<<<EOF
function(file, errorCode, errorMsg, errorString) {
    console.log('The file ' + file.name + ' could not be uploaded: ' + errorString + errorCode + errorMsg);
}
EOF
        ),
        'onUploadComplete' => new JsExpression(<<<EOF
function(file, data, response) {
    data = JSON.parse(data);
    if (data.error) {
        console.log(data.msg);
    } else {
        console.log(data.fileUrl);
        //将图片地址赋值给logo
        $("#goods-logo").val(data.fileUrl);
        //将上传成功的图片回显
        $("#img").attr('src',data.fileUrl);
    }
}
EOF
        ),
    ]
]);
echo \yii\bootstrap\Html::img($model->logo?$model->logo:false,['id'=>'img','height'=>60]);
//分类
echo $form->field($model,'goods_category_id')->hiddenInput(['id'=>'goods_category_id']);
echo '<div>
    <ul id="treeDemo" class="ztree"></ul>
</div>';

echo $form->field($model,'brand_id')->dropDownList(\yii\helpers\ArrayHelper::map(\backend\models\Brand::find()->all(),'id','name'));

echo $form->field($model,'market_price');
echo $form->field($model,'shop_price');
echo $form->field($model,'stock');
echo $form->field($model,'is_on_sale',['inline'=>true])->radioList(\backend\models\Goods::$sale);
echo $form->field($model,'status',['inline'=>true])->radioList(\backend\models\Goods::$statuss);
echo $form->field($model,'sort');
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
//调用视图加载静态资源
$this->registerCssFile('@web/zTree/css/zTreeStyle/zTreeStyle.css');
//加载js文件
$this->registerJsFile('@web/zTree/js/jquery.ztree.core.js',['depends'=>\yii\web\JqueryAsset::className()]);
//加载js代码
/*$nodes = '[
                {id:1, pId:0,  name: "父节点1"},
                {id:11, pId:1, name: "子节点1"},
                {id:12, pId:1, name: "子节点2"}

        ];';*/
$categories[] = ['id'=>0,'parent_id'=>0,'name'=>'顶级分类','open'=>1];
$nodes = \yii\helpers\Json::encode($categories) ;
$nodesId = $model->goods_category_id?$model->goods_category_id:2;
$this->registerJs(new \yii\web\JsExpression(
    <<<JS

var zTreeObj;
        // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
        var setting = {
            data: {
                simpleData: {
                    enable: true,
                    idKey: "id",
                    pIdKey: "parent_id",
                    rootPId: 0
                }
            },
            callback: {
		      onClick: function(event, treeId, treeNode) {
		        $("#goods_category_id").val(treeNode.id);
		    }
	     }
        };
        // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
        var zNodes = {$nodes};
            $("#goodscategory-parent_id").val({$nodesId});
            zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
            //展开全部节点
           // zTreeObj.expandAll(true);
           var node = zTreeObj.getNodeByParam("id",{$nodesId},null);
           //选中节点
           zTreeObj.selectNode(node);
            
JS

));