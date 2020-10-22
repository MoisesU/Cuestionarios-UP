<?php
    if(!isset($msg) && !isset($title)){
        echo "No content here!";
        exit;
    }
?>
        
        <div class="container" style="padding: 84px;">
            <div class="panel <?php echo isset($typeP)?$typeP:"panel-primary"; ?>">
                <div class="panel-heading"><?php echo $title ?></div>
                <div class="panel-body">
                        <?php echo $msg ?>
                        <br>
                        <br>
                        <?php if(isset($redir)){echo"<div class='col-sm-offset-5 col-sm-2'><button type='button' class='btn btn-lg btn-primary btn-block btn-big' onclick=\"location.href "
                            . "= '$redir'\">Aceptar</button></div><br>";} ?>
                </div>
            </div>
        </div>