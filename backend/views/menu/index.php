<a href="<?= \yii\helpers\Url::to(['menu/add'])?>" class="btn btn-default">添加</a>
<table class="table table-bordered table-responsive">
    <tr>
        <th>名字</th>
        <th>路由</th>
        <th>添加时间</th>
        <th>操作</th>
    </tr>
        <?php foreach ($model as $row): ?>
            <tr>
                <td><?= $row->parent_id>0?'——'.$row->menu_name:$row->menu_name?></td>
                <td><?= $row->menu_url?></td>
                <td><?= date('Y-m-d-H-i-s',$row->create_time)?></td>
                <td><?= \yii\bootstrap\Html::a('修改',['menu/edit','id'=>$row->id],['class'=>'btn btn-info'])?>
                <?= \yii\bootstrap\Html::a('删除',['menu/delete','id'=>$row->id],['class'=>'btn btn-default'])?></td>

            </tr>

        <?php endforeach;?>
</table>
<?php
//分页工具条
echo \yii\widgets\LinkPager::widget(['pagination'=>$pager,'nextPageLabel'=>'下一页','prevPageLabel'=>'上一页','firstPageLabel'=>'首页']);