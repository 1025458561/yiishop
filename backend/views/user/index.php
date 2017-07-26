<a href="<?= \yii\helpers\Url::to(['user/add'])?>">添加用户</a>
<a href="<?= \yii\helpers\Url::to(['user/login'])?>">登录用户</a>
<a href="<?= \yii\helpers\Url::to(['user/personal'])?>">修改密码</a>

<table class="table table-bordered table-condensed">
    <tr>
        <th>id</th>
        <th>名字</th>
        <th>邮箱</th>
        <th>最后登录IP</th>
        <th>最后登录时间</th>
        <th>操作</th>
    </tr>
    <?php foreach ($model as $row): ?>
    <tr>
        <td><?= $row->id?></td>
        <td><?= $row->username?></td>
        <td><?= $row->email?></td>
        <td><?= $row->last_login_ip?></td>
        <td><?= $row->last_login_time?></td>
        <td>
            <?=\yii\helpers\Html::a('删除',['user/delete','id'=>$row->id]) ?>
            <?=\yii\helpers\Html::a('修改',['user/edit','id'=>$row->id]) ?>

        </td>
    </tr>

    <?php endforeach;?>
</table>
<?php
//分页工具条
echo \yii\widgets\LinkPager::widget(['pagination'=>$pager,'nextPageLabel'=>'下一页','prevPageLabel'=>'上一页','firstPageLabel'=>'首页']);