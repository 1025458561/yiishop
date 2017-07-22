
<table class="table table-bordered table-condensed">
    <tr>
        <th>文章id</th>
        <th>文章内容</th>
    </tr>
    <a href="<?= \yii\helpers\Url::to(['article/index'])?>" class="btn btn-success">首页</a>
    <?php foreach ($article as $row):?>
        <tr>
            <td><?= $row->article_id?></td>
            <td><?= $row->content?></td>
        </tr>
    <?php endforeach;?>
</table>
<?php
echo \yii\widgets\LinkPager::widget(['pagination'=>$pager,'nextPageLabel'=>'>>','prevPageLabel'=>'<<','firstPageLabel'=>'首页']);
