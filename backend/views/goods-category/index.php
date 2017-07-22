<table class="table table-bordered table-condensed">
    <tr>
        <!--<th>Id</th>
        <th>name</th>
        <th>intro</th>
        <th>logo</th>
        <th>sort</th>
        <th>status</th>-->
        <th>id</th>
        <th>树id</th>
        <th>左值</th>
        <th>右值</th>
        <th>深度</th>
        <th>名字</th>
        <th>父id</th>
        <th>简介</th>
        <th>操作</th>

    </tr>
    <a href="<?= \yii\helpers\Url::to(['goods-category/add'])?>" class="btn btn-success">添加</a>
    <?php $statusOptions = \backend\models\Brand::getStatusOptions(true);
    foreach ($goods as $row):
        ?>
        <tr>
            <td><?= $row->id?></td>
            <td><?= $row->tree?></td>
            <td><?= $row->lft?></td>
            <td><?= $row->rgt?></td>
            <td><?= $row->depth?></td>
            <td><?= $row->name?></td>
            <td><?= $row->parent_id?></td>
            <td><?= $row->intro?></td>

            <td><?= \yii\bootstrap\Html::a('删除',['goods-category/delete','id'=>$row->id])?>
                <?= \yii\bootstrap\Html::a('修改',['goods-category/edit','id'=>$row->id],['class'=>'btn btn-success'])?>
            </td>

        </tr>
    <?php endforeach;?>
</table>
