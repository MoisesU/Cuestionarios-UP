<?php
    session_start();
    if (isset($_SESSION['user'])) 
    {
        header("Location:showCuestionarios.php");
    }
?>
<!DOCTYPE html>
<html style="background-color: #418bd2">
    <head>
        <?php include ("include/header.php"); ?>
        <title>Login</title>
    </head>
    
<body style="background-color: #418bd2">
	<div class="container">
        <div class="card card-container">
            <img id="profile-img" class="profile-img-card" src="img/log.png" />
            <p id="profile-name" class="profile-name-card"></p>
            <form class="form-signin" action="validate.php" method="POST">
                <span id="reauth-email" class="reauth-email"></span>
                <input type="text" name="inputEmail" class="form-control" placeholder="Usuario" required autofocus>
                <input type="password" name="inputPassword" class="form-control" placeholder="Contraseña" required>
                <!--<div id="remember" class="checkbox">
                    <label>
                        <input type="checkbox" value="remember-me"> Remember me
                    </label>
                </div>-->
                <button class="btn btn-lg btn-primary btn-block btn-signin" type="submit">Iniciar sesi&oacute;n</button>
            </form><!-- /form -->
            <!--<a href="#" class="forgot-password">
                Forgot the password?
            </a>-->
            </div><!-- /card-container -->
        </div><!-- /container -->
</body>
</html>