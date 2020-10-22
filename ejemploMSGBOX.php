<?php
    /*if(!isset($msg) && !isset($title)){
        echo "No content here!";
        exit;
    }*/
    $title = "Esto es un ejemplo de título";
    $msg = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent nec diam eget nisi rhoncus fermentum a a leo. In porttitor libero ipsum";
    $redir = "ejemploMSGBOX.php";
?>
<!DOCTYPE html>
<html>
<head>
    <?php include "include/header.php";?>
    <title>Ejemplo MSGBOX</title>
</head>
<body>
    <?php include "include/navbar.php";?>
    <div class="container" style="padding: 84px;">
            <div class="panel <?php echo isset($typeP)?$typeP:"panel-primary"; ?>">
                <div class="panel-heading"><?php echo $title ?></div>
                <div class="panel-body">
                        <?php echo $msg ?>
                        <br>
                        <br>
                        <?php if(isset($redir)){echo"<div class='col-sm-offset-5 col-sm-2'><button type='button' class='btn btn-lg btn-block btn-big' onclick=\"location.href "
                            . "= '$redir'\">Aceptar</button></div><br>";} ?>
                </div>
            </div>
        </div>
</body>
</html>