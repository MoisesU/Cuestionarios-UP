<?php
    session_start();
    if (!isset($_SESSION['user'])) 
    {
        header("Location:index.php");
    }
?>
<html>
    <head>
        <?php include ('include/header.php');
              require 'connect.php';
        ?>
        <title>Agregar pregunta</title>
    </head>
    <body>
        <?php include ('include/navbar.php');?>
        
        <div class="container">
            <h1>Estadísticas por periodo</h1>
            <br>
            
            <form class="form-horizontal">
                <div class="form-group">
                    <label class="control-label col-sm-2" for="respuesta" style="text-align: left">Periodo de</label>
                    <div class="col-sm-3">
                        <input type="date" class="form-control" name="respuesta">
                    </div>
                    <label class="control-label col-sm-2" for="respuesta" style="text-align: left">&nbsp;&nbsp;&nbsp;&nbsp;al </label>
                    <div class="col-sm-3">
                        <input type="date" class="form-control" name="respuesta">
                    </div>
                    <div class="col-sm-2">
                        <button class="btn btn-lg btn-primary btn-block btn-signin">Buscar</button>
                    </div>
                </div>
            </form>
            <br>
            <br>
<!--            <h3><center>No hay encuestas registradas en este periodo</center></h3>-->
            <div class="col-sm-offset-1 col-sm-10">
                <h4>Tres amigos se cooperan para comprar un billete de lotería aportando cada uno $75.00, $65.00, $60.00, respectivamente. Si el billete sale premiado con 50,000.00 pesos, ¿cuánto le corresponderá, en pesos, al que aportó $65.00 si se reparten el premio proporcionalmente?</h4>
            </div>
            
            <br>
            <br>
            
            <div>
                <center><img src="img/pastel.png" class="img-fluid" alt="Gráfico"></center>
            </div>
        </div>
    </body>
</html> 
