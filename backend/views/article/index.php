<div>
<?php
//搜索
$form = \yii\bootstrap\ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
    'id' => 'search-form',
    //'options' => ['class' => 'form-horizontal'],
]); ?>

<?= $form->field($search, 'k',[
    'options'=>['class'=>''],
    'inputOptions' => ['placeholder' => '文章搜索','class' => 'input-sm form-control'],
])->label(false); ?>

<?= \yii\bootstrap\Html::submitButton('Go!', ['class' => 'btn btn-sm btn-primary']) ?>

<?php \yii\bootstrap\ActiveForm::end();?>
</div>




    <table class="table table-bordered table-condensed">
<tr>
    <th>id</th>
    <th>名字</th>
    <th>简介</th>
    <th>文章分类id</th>
    <th>排序</th>
    <th>添加时间</th>
    <th>状态</th>
    <th>操作</th>
</tr>
    <a href="<?= \yii\helpers\Url::to(['article/add'])?>" class="btn btn-success">添加</a>
    <a href="<?= \yii\helpers\Url::to(['article-detail/index'])?>" class="btn btn-success">文章内容</a>
<?php foreach ($article as $row):?>
    <tr>
        <td><?= $row->id?></td>
        <td><?= $row->name?></td>
        <td><?= $row->intro?></td>
        <td><?= $row->articleCategory->name?></td>

      <!--<td><?/*= $row->articleDetail->content*/?></td>-->
        <td><?= $row->sort?></td>
        <td><?= date('Y-m-d-H-i-s',$row->create_time)?></td>
        <td><?= $row->status==0?'隐藏':'正常';?></td>
        <td><?= \yii\bootstrap\Html::a('删除',['article/delete','id'=>$row->id],['class'=>'btn btn-success'])?>
            <?= \yii\bootstrap\Html::a('修改',['article/edit','id'=>$row->id],['class'=>'btn btn-info'])?>
        </td>

    </tr>
<?php endforeach;?>
</table>
<?php
//分页工具条

echo \yii\widgets\LinkPager::widget(['pagination'=>$pager,'nextPageLabel'=>'下一页','prevPageLabel'=>'上一页','firstPageLabel'=>'首页']);