<table class="table table-bordered table-condensed">
<tr>
    <!--<th>Id</th>
    <th>name</th>
    <th>intro</th>
    <th>logo</th>
    <th>sort</th>
    <th>status</th>-->
    <th>id</th>
    <th>名字</th>
    <th>简介</th>
    <th>图片</th>
    <th>排序</th>
    <th>状态</th>
    <th>操作</th>

</tr>
    <a href="<?= \yii\helpers\Url::to(['brand/add'])?>" class="btn btn-success">添加</a>
<?php $statusOptions = \backend\models\Brand::getStatusOptions(true);
    foreach ($brands as $row):
    ?>
    <tr>
        <td><?= $row->id?></td>
        <td><?= $row->name?></td>
        <td><?= $row->intro?></td>
        <td><?= \yii\bootstrap\Html::img($row->logo?$row->logo:'/upload/default.jpg',['height'=>70])?></td>
        <td><?= $row->sort?></td>
        <td><?= $row->satustext?></td>
        <td><?= \yii\bootstrap\Html::a('删除',['brand/delete','id'=>$row->id])?>
            <?= \yii\bootstrap\Html::a('修改',['brand/edit','id'=>$row->id],['class'=>'btn btn-success'])?>
        </td>

    </tr>
    <?php endforeach;?>
</table>