<a href="<?= \yii\helpers\Url::to(['role-add'])?>" class="btn btn-success">添加</a>
<table class="table table-bordered table-responsive">
    <tr>
    <th>角色名</th>
    <th>描述</th>
    <th>操作</th>
    </tr>

 <?php foreach ($model as $row ) : ?>
    <tr>
        <td><?= $row->name?></td>
        <td><?=  $row->description?></td>
        <td>
            <?php echo \yii\bootstrap\Html::a('修改',['role-edit','name'=>$row->name]) ?>
            <?php echo \yii\bootstrap\Html::a('删除',['role-delete','name'=>$row->name]) ?>

        </td>
    </tr>

    <?php endforeach; ?>
</table>
