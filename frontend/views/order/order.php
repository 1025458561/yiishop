<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>填写核对订单信息</title>
    <link rel="stylesheet" href="<?= Yii::getAlias('@web') ?>/style/base.css" type="text/css">
    <link rel="stylesheet" href="<?= Yii::getAlias('@web') ?>/style/global.css" type="text/css">
    <link rel="stylesheet" href="<?= Yii::getAlias('@web') ?>/style/header.css" type="text/css">
    <link rel="stylesheet" href="<?= Yii::getAlias('@web') ?>/style/fillin.css" type="text/css">
    <link rel="stylesheet" href="<?= Yii::getAlias('@web') ?>/style/footer.css" type="text/css">

    <script type="text/javascript" src="<?= Yii::getAlias('@web') ?>/js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="<?= Yii::getAlias('@web') ?>/js/cart2.js"></script>

</head>
<body>
<!-- 顶部导航 start -->
<div class="topnav">
    <div class="topnav_bd w990 bc">
        <div class="topnav_left">

        </div>
        <div class="topnav_right fr">
            <ul>
                <li>您好，欢迎来到京西！[<a href="login.html">登录</a>] [<a href="register.html">免费注册</a>] </li>
                <li class="line">|</li>
                <li>我的订单</li>
                <li class="line">|</li>
                <li>客户服务</li>

            </ul>
        </div>
    </div>
</div>
<!-- 顶部导航 end -->

<div style="clear:both;"></div>

<!-- 页面头部 start -->
<div class="header w990 bc mt15">
    <div class="logo w990">
        <h2 class="fl"><a href="index.html"><img src="<?= Yii::getAlias('@web') ?>/images/logo.png" alt="京西商城"></a></h2>
        <div class="flow fr flow2">
            <ul>
                <li>1.我的购物车</li>
                <li class="cur">2.填写核对订单信息</li>
                <li>3.成功提交订单</li>
            </ul>
        </div>
    </div>
</div>
<!-- 页面头部 end -->

<div style="clear:both;"></div>

<!-- 主体部分 start -->
<div class="fillin w990 bc mt15">
    <div class="fillin_hd">
        <h2>填写并核对订单信息</h2>
    </div>

    <div class="fillin_bd">
        <!-- 收货人信息  start-->

        <div class="address">
            <h3>收货人信息</h3>
            <div class="address_info">
                <?php foreach($address as $row): ?>
                <p>
                    <input type="radio" value="<?= $row->id ?>" name="address_id" />
                    <?= $row->name?> <?= $row->province?> <?= $row->center?> <?= $row->area?>
                    <!--张三  17002810530  北京市 昌平区 一号楼大街 -->
               </p>
                <?php endforeach;?>
            </div>


        </div>
        <!-- 收货人信息  end-->

        <!-- 配送方式 start -->
        <div class="delivery">
            <h3>送货方式 </h3>


            <div class="delivery_select">
                <table>
                    <thead>
                    <tr>
                        <th class="col1">送货方式</th>
                        <th class="col2">运费</th>
                        <th class="col3">运费标准</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr >
                        <?php foreach (\frontend\models\Order::$deliveries as $row=>$a):?>
                        <td>
                            <input type="radio" name="delivery_id" value="<?= $a['id'] ?>" checked="checked" /><?= $a['name'] ?>
                        </td>
                        <td><?= $a['price'] ?></td>
                        <td><?= $a['detail']?></td>
                    </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>

            </div>
        </div>
        <!-- 配送方式 end -->

        <!-- 支付方式  start-->
        <div class="pay">
            <h3>支付方式 </h3>


            <div class="pay_select">
                <table>
                    <?php foreach (\frontend\models\Order::$pay as $row=>$b):
                        ?>
                    <tr>
                        <td class="col1"><input type="radio" name="pay_id" value="<?= $b['id'] ?>" /><?= $b['name'] ?></td>
                        <td class="col2"><?= $b['detail']?></td>
                    </tr>
                    <?php endforeach;?>
                </table>

            </div>
        </div>
        <!-- 支付方式  end-->

        <!-- 发票信息 start-->
        <div class="receipt none">
            <h3>发票信息 </h3>


            <div class="receipt_select ">
                <form action="">
                    <ul>
                        <li>
                            <label for="">发票抬头：</label>
                            <input type="radio" name="type" checked="checked" class="personal" />个人
                            <input type="radio" name="type" class="company"/>单位
                            <input type="text" class="txt company_input" disabled="disabled" />
                        </li>
                        <li>
                            <label for="">发票内容：</label>
                            <input type="radio" name="content" checked="checked" />明细
                            <input type="radio" name="content" />办公用品
                            <input type="radio" name="content" />体育休闲
                            <input type="radio" name="content" />耗材
                        </li>
                    </ul>
                </form>

            </div>
        </div>
        <!-- 发票信息 end-->

        <!-- 商品清单 start -->
        <div class="goods">
            <h3>商品清单</h3>
            <table>
                <thead>
                <tr>
                    <th class="col1">商品</th>
                    <th class="col3">价格</th>
                    <th class="col4">数量</th>
                    <th class="col5">小计</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $sum = 0;//总金额
                $num = 0; //商品总数量
                foreach ($goods as $row):
                    $num++;
                    $sum +=($row->shop_price)*($amount[$row->id]);
                    ?>
                <tr class="goods-info" goods_id="<?=$row['id']?>">
                    <td class="col1"><a href=""><img src="<?=$row->logo?>" alt="" /></a>  <strong><a href=""><?=$row->name ?></a></strong></td>
                    <td class="col3"><?=$row->shop_price ?></td>
                    <td class="col4" id="td<?=$row['id']?>"><?=$amount[$row->id]?></td>
                    <td class="col5"><span><?= ($row->shop_price)*($amount[$row->id]) ?></span></td>
                </tr>
            <?php endforeach;?>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="5">
                        <ul>
                            <li>
                                <span><?php echo $num; ?>件商品，总商品金额：</span>
                                <em><?= $sum?></em>
                            </li>
                            <li>
                                <span>运费：</span>
                                <em>￥10.00</em>
                            </li>
                        </ul>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
        <!-- 商品清单 end -->

    </div>

    <div class="fillin_ft">
        <a id="sub-mit" href="javascript:;"><span>提交订单</span></a>
        <p>应付总额：<strong><?= $sum?></strong></p>

    </div>
</div>
<!-- 主体部分 end -->

<div style="clear:both;"></div>
<!-- 底部版权 start -->
<div class="footer w1210 bc mt15">
    <p class="links">
        <a href="">关于我们</a> |
        <a href="">联系我们</a> |
        <a href="">人才招聘</a> |
        <a href="">商家入驻</a> |
        <a href="">千寻网</a> |
        <a href="">奢侈品网</a> |
        <a href="">广告服务</a> |
        <a href="">移动终端</a> |
        <a href="">友情链接</a> |
        <a href="">销售联盟</a> |
        <a href="">京西论坛</a>
    </p>
    <p class="copyright">
        © 2005-2013 京东网上商城 版权所有，并保留所有权利。  ICP备案证书号:京ICP证070359号
    </p>
    <p class="auth">
        <a href=""><img src="<?= Yii::getAlias('@web') ?>/images/xin.png" alt="" /></a>
        <a href=""><img src="<?= Yii::getAlias('@web') ?>/images/kexin.jpg" alt="" /></a>
        <a href=""><img src="<?= Yii::getAlias('@web') ?>/images/police.jpg" alt="" /></a>
        <a href=""><img src="<?= Yii::getAlias('@web') ?>/images/beian.gif" alt="" /></a>
    </p>
</div>
<!-- 底部版权 end -->
</body>
</html>
<script type="text/javascript">
$('#sub-mit').click(function () {
    var address_id = $("input[name='address_id']:checked").val();
    if(!address_id){
        alert('请选择收货地址');
        return false;
    }
    var delivery_id = $("input[name='delivery_id']:checked").val();
    if(!delivery_id){
        alert('请选择收货地址');
        return false;
    }
    var pay_id=$("input[name='pay_id']:checked").val();
    if(!pay_id){
        alert('请选择收货地址');
        return false;
    }
    var data = {};
    var goods = {};
    $('.goods-info').each(function (index,tr_node) {
        var goods_id = $(tr_node).attr('goods_id');
        var amount =  $('#td'+goods_id).text();
        goods[goods_id] = amount;
    });

     data["address_id"] = address_id;
     data["delivery_id"] = delivery_id;
     data["pay_id"] = pay_id;
     data["goods"] = goods;

    $.post('/order/order-add',data,function (data) {
        console.log(data);
    },'json');
})
</script>

















