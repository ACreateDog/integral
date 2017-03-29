<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="refresh">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="http://cdn.static.runoob.com/libs/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="/Integral/Public/Admin/bracket/images/logo.png" type="image/png">
    <link rel="stylesheet" href="/Integral/Public/Admin/bracket/css/dropzone.css" />
    <script src="/Integral/Public/Admin/bracket/js/jquery-1.11.1.min.js"></script>
    <script src="/Integral/Public/Admin/bracket/js/dropzone.min.js"></script>
    <script src="/Integral/Public/Admin/bracket/js/jquery-migrate-1.2.1.min.js"></script>
    <script src="/Integral/Public/Admin/bracket/js/jquery-ui-1.10.3.min.js"></script>
    <script src="/Integral/Public/Admin/bracket/js/bootstrap.min.js"></script>
    <script src="/Integral/Public/Admin/bracket/js/modernizr.min.js"></script>
    <script src="/Integral/Public/Admin/bracket/js/jquery.sparkline.min.js"></script>
    <script src="/Integral/Public/Admin/bracket/js/toggles.min.js"></script>
    <script src="/Integral/Public/Admin/bracket/js/retina.min.js"></script>
    <script src="/Integral/Public/Admin/bracket/js/jquery.cookies.js"></script>

    <script src="/Integral/Public/Admin/bracket/js/morris.min.js"></script>
    <script src="/Integral/Public/Admin/bracket/js/raphael-2.1.0.min.js"></script>

    <script src="/Integral/Public/Admin/bracket/js/custom.js"></script>
    <script src="/Integral/Public/static/layer/layer.js"></script>

    <script src="/Integral/Public/Admin/bracket/js/html5shiv.js"></script>
    <script src="/Integral/Public/Admin/bracket/js/respond.min.js"></script>
    <title>积分查询</title>
    
    <style>
        #name{
            display: block;
            color: green;
            font-size: 20px;
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            margin-top: 25px;
        }
        #snum{
            display: block;
            color: green;
            font-size: 15px;
            margin-top: 10px;
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        }
    </style>

    <style>
        .contentDiv{
            top: 0px;
            bottom: 0px;
            /*left: 10px;*/
            /*right: 10px;*/
            width: auto;
            /*height: auto;*/
            height: 550px;

        }
        .footDiv{
            /*position: fixed;*/
            /*bottom: 0px;*/
            color: black;
            width: 100%;
        }
        .admin-footer{
            font-size: 10px;
            color: black;

        }
        .contentD{
            height: 550px;
            /*background-color: red;*/
        }
        #adn{
            display: inline-block;
        }
        #adnf{
            font-size: 15px;
            display: inline-block;
        }
        .marquee-class{

        }
    </style>
</head>
<body class="body-class">
<script>

</script>
<div class="marquee-class" style="position: relative;z-index: 9999;color: white;">
    <marquee direction="left">
        <p id="info"></p>
    </marquee>
</div>

<div class="contentDiv" align="center">
    <div class="contentD">
        
    <p id="name"><?php echo ($list['name']); ?></p>
    <p id="snum"><?php echo ($s_id); ?></p>
    <div class="table-responsive" style="margin-top:40px">
    <?php if($ishave == 1): ?><table class="table mb30">
            <thead align="center">
                <tr>
                    <td>总分</td>
                    <td><?php echo ($list['all_score']); ?></td>
                </tr>
                <tr>
                    <td>班级排名</td>
                    <td><?php echo ($list['class_rank']); ?></td>
                </tr>
                <tr>
                    <td>专业排名</td>
                    <td><?php echo ($list['major_rank']); ?></td>
                </tr>
            </tbody>
        </table>
             <br/>
         <form id="detail" style="border: none" action="personScoreDetail" method="post">
            <input type="hidden" name="s_id" value="<?php echo ($s_id); ?>">
            <input type="hidden" name="c_name" value="<?php echo ($list['class_name']); ?>">
            <input type="hidden" name="term" value="<?php echo ($list['term']); ?>">
            <div class="btn btn-primary" style="background-color: #128de3;border: none" id="person"  onclick="submitForm(this)">个人详情</div>
             <div class="btn btn-primary" style="background-color: #128de3;border: none" id="class" onclick="submitForm(this)">本班详情</div>
        </form>

        <?php elseif($iswarn == 1): ?>
             <h4 style="color: red"><?php echo ($warn); ?></h4>
        <?php else: ?>
         <h4 style="color: red">本学期没有你的积分数据，可以查看上学期的^_^！</h4><?php endif; ?>

    </div>

    </div>
    


</div>


    <script>
        function submitForm(ele){
            var  val = ele.getAttribute('id');
            var f = document.getElementById('detail');
            if(val == 'person'){
                f.action = 'personScoreDetail';
            }else if(val == 'class'){
                f.action = 'classScoreDetail'
            }
            f.submit();
        }
    </script>

</body>
</html>