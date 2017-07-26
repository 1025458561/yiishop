<?php

$form = \yii\bootstrap\ActiveForm::begin([
        //get提交过去
    'method' => 'get',
    //get方式提交,需要显式指定action
    'action'=>\yii\helpers\Url::to(['goods/index']),
    'layout'=>'inline'
]);

echo $form->field($model,'name')->textInput(['placeholder'=>'商品名']);
echo $form->field($model,'sn')->textInput(['placeholder'=>'货号']);
echo \yii\bootstrap\Html::submitButton('搜索',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();
?>
<table class="table table-bordered table-condensed">
    <tr>
        <th>id</th>
        <th>名字</th>
        <th>货号</th>
        <th>图片</th>
        <th>商品分类</th>
        <th>品牌分类</th>
        <th>市场价格</th>
        <th>商品价格</th>
        <th>库存</th>
        <th>是否上架</th>
        <th>状态</th>
        <th>排序</th>
        <th>添加时间</th>
        <th>浏览次数</th>
        <th>操作</th>
    </tr>
   <a href="<?= \yii\helpers\Url::to(['goods/add'])?>" class="btn btn-success">添加</a>
    <?php $statusOptions = \backend\models\Brand::getStatusOptions(true);
    foreach ($goods as $row):?>
        <tr>
            <td><?= $row->id?></td>
            <td><?= $row->name?></td>
            <td><?= $row->sn?></td>
            <td><?= \yii\bootstrap\Html::img($row->logo?$row->logo:'/upload/default.jpg',['height'=>70])?></td>
            <td><?= $row->goodsCategory->name?></td>
            <td><?= $row->brand->name?></td>
            <td><?= $row->market_price?></td>
            <td><?= $row->shop_price?></td>
            <td><?= $row->stock?></td>
            <td><?= $row->is_on_sale==1?'是':'否'?></td>
            <td><?= $row->status==0?'回收站':'正常'?></td>
            <td><?= $row->sort?></td>
            <td><?= date('Y-m-d',$row->create_time)?></td>
            <td><?= $row->view_times?></td>
            <td><?= \yii\bootstrap\Html::a('删除',['goods/delete','id'=>$row->id])?>
                <?= \yii\bootstrap\Html::a('修改',['goods/edit','id'=>$row->id],['class'=>'btn btn-sm'])?><br/>
                <?= \yii\bootstrap\Html::a('相册',['goods/gallery','id'=>$row->id],['class'=>'btn btn-sm']) ?>
                <?= \yii\bootstrap\Html::a('内容',['goods/content','id'=>$row->id],['class'=>'btn btn-sm']) ?>

            </td>

        </tr>
    <?php endforeach;?>
</table>
<?php
//分页工具条
echo \yii\widgets\LinkPager::widget(['pagination'=>$pager,'nextPageLabel'=>'下一页','prevPageLabel'=>'上一页','firstPageLabel'=>'首页']);





