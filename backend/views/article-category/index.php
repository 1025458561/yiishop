<table class="table table-bordered table-condensed">
<tr>
    <th>id</th>
    <th>名字</th>
    <th>简介</th>
    <th>排序</th>
    <th>状态</th>
    <th>操作</th>
</tr>
    <a href="<?= \yii\helpers\Url::to(['article-category/add'])?>" class="btn btn-success">添加</a>
<?php foreach ($article as $row):?>
        <tr>
            <td><?= $row->id?></td>
            <td><?= $row->name?></td>
            <td><?= $row->intro?></td>
            <td><?= $row->sort?></td>
            <td><?= $row->status==0?'隐藏':'正常';?></td>
            <td><?= \yii\bootstrap\Html::a('删除',['article-category/delete','id'=>$row->id])?>
                <?= \yii\bootstrap\Html::a('修改',['article-category/edit','id'=>$row->id],['class'=>'btn btn-success'])?>
            </td>

        </tr>
    <?php endforeach;?>
</table>