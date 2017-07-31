<!--<link rel="stylesheet" type="text/css" href="http://cdn.datatables.net/1.10.15/css/jquery.dataTables.css">-->
<!---->
<!--<!-- jQuery -->
<!--<script type="text/javascript" charset="utf8" src="http://code.jquery.com/jquery-1.10.2.min.js"></script>-->
<!---->
<!--<!-- DataTables -->
<!--<script type="text/javascript" charset="utf8" src="http://cdn.datatables.net/1.10.15/js/jquery.dataTables.js"></script>-->
<a href="<?= \yii\helpers\Url::to(['permission-add'])?>" class="btn btn-success">添加</a>
<table  id="table_id_example"  class="table table-bordered display">
    <thead>
    <tr>
        <th>权限名</th>
        <th>描述</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($model as $row):?>
        <tr>
            <td><?= $row->name?></td>
            <td><?= $row->description?></td>
            <td>
            <?=\yii\bootstrap\Html::a('修改',['permission-edit','name'=>$row->name])?>
            <?=\yii\bootstrap\Html::a('删除',['permission-delete','name'=>$row->name])?>

            </td>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>
<!--<script>
    $(document).ready( function () {
        $('#table_id_example').DataTable();
    } );

</script>
-->
<?php
/**
 * @var $this \yii\web\View
 */
#$this->registerCssFile('//cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css');
//$this->registerCssFile('//cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css');
$this->registerCssFile('//cdn.datatables.net/1.10.15/css/dataTables.bootstrap.css ');
$this->registerJsFile('//cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js',['depends '=>\yii\web\JqueryAsset::className()]);
$this->registerJsFile('//cdn.datatables.net/1.10.15/js/dataTables.bootstrap.js',['depends '=>\yii\web\JqueryAsset::className()]);
$this->registerJs('$(".table").DataTable({
language: {
    url: "//cdn.datatables.net/plug-ins/1.10.15/i18n/Chinese.json"
    }
});');









