<?php
    session_start();
    if (!isset($_SESSION['user'])) 
    {
        header("Location:index.php");
    }
    extract($_GET);
    $idNotFound = false;
?>
<html>
    <head>
        
        <?php include ('include/header.php');
              require 'connect.php';
              if(isset($id)){
                $query = "SELECT PREGUNTA.REDACCION AS Pregunta, PREGUNTA.ID_PREGUNTA AS IDP,
                        COUNT(IF(RESPUESTA.OPCION='R',1, NULL)) AS CO, PREGUNTA.RESPUESTA AS COR,
                        COUNT(IF(RESPUESTA.OPCION='A',1, NULL)) AS DA, PREGUNTA.DISTRACTOR_A AS DAR,
                        COUNT(IF(RESPUESTA.OPCION='B',1, NULL)) AS DB, PREGUNTA.DISTRACTOR_B AS DBR,
                        COUNT(IF(RESPUESTA.OPCION='C',1, NULL)) AS DC, PREGUNTA.DISTRACTOR_C AS DCR
                        FROM RESPUESTA
                        INNER JOIN ENCUESTADO ON  RESPUESTA.ID_ENCUESTADO = ENCUESTADO.ID_ENCUESTADO
                            INNER JOIN PREGUNTA ON RESPUESTA.ID_PREGUNTA = PREGUNTA.ID_PREGUNTA
                            INNER JOIN ENCUESTA ON ENCUESTADO.ID_ENCUESTA = ENCUESTA.ID_ENCUESTA
                        WHERE ENCUESTA.ID_ENCUESTA = $id
                        GROUP BY ENCUESTA_PREGUNTA.ID_PREGUNTA;";

                $consulta = mysqli_query($linker,$query);
                if (!$consulta) {
                    echo "\n<script>console.log('Error de BD al obtener RESULTADO POR ENCUESTA, no se pudo consultar la base de datos');\n";
                    echo "\nconsole.log(\"Error MySQL:" .  mysqli_error($linker). "\"); </script>";
                }
                if($consulta!=null){
                    $idNotFound = mysqli_num_rows($consulta)==0;
                }
                else{
                    $idNotFound = true;
                }
              }
        ?>
        <title>Estadísticas por Encuesta</title>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">
            google.charts.load('current', {'packages':['corechart']});
            <?php 
                if(isset($id)){
                    $i = 0;
                    $table = [];
                    $titles = [];
                    $options = [];
                    while($fila= mysqli_fetch_array($consulta)){
                        $titles[$i] = $fila['Pregunta'];
                        $options[$i] = array($fila['COR'], $fila['DAR'], $fila['DBR'], $fila['DCR']);
                        $table[$i] = array([$fila["IDP"], "Selección"],["Correcta", (int)$fila['CO']], ["Opción A", (int)$fila['DA']], ["Opción B", (int)$fila['DB']], ["Opción C", (int)$fila['DC']]);
                        $i++;        
                    }
                    mysqli_free_result($consulta);
                    //var_dump($table[0]);
                    echo "\n\t\t\tvar titles = ". json_encode($titles).";";
                    //echo "\n\t\t\tvar table = ". json_encode($table).";";
                    $max = count($titles);
                    for($i=0; $i<$max; $i++){
                        echo "\n\t\t\tgoogle.charts.setOnLoadCallback(drawChart$i);";
                    }
                    for($i=0; $i<$max; $i++){
                        echo "\n\t\t\tfunction drawChart$i(){"
                                . "\n\t\t\t\tvar data = google.visualization.arrayToDataTable(". json_encode($table[$i]).");"
                                . "\n\t\t\t\tvar chart = new google.visualization.PieChart(document.getElementById('piechart$i'));"
                                //. "\n\t\t\t\tvar options = {'legend':'none'};"
                                . "\n\t\t\t\tchart.draw(data);"
                                . "\n\t\t\t}";
                    }
                }
            ?>
        </script>
    </head>
    <body>
        <?php include ('include/navbar.php');?>
        <div class="container">
            <h1>Estadísticas por encuesta</h1>
            <br>
            <form class="form-horizontal" action="resultadosPorEncuestaPie.php" method="GET">
                <label class="control-label col-sm-2" for="id" style="text-align: left">Encuesta</label>
                <div class="col-sm-6">
                    <input required="" type="text" class="form-control" name="id">
                </div>
                <div class="col-sm-2">
                    <button type="submit" class="btn btn-lg btn-primary btn-block btn-signin">Buscar</button>
                </div>
                
            </form>
        </div> 
        <br>
        <br>
        <div id="chart-container" class="container">
            <?php 
                if(isset($id)){ 
                    if($idNotFound){
                        echo "<h3>No se encontraron respuestas para la encuesta $id</h3>";
                    }
                    else{
                        for($i=0; $i<$max; $i++){
                            echo "\n<div>\n<h4>".$table[$i][0][0].". $titles[$i]<h4>\n<center><div id='piechart$i' style='graphic'></div></center></div>";
                        }
                    }
                } 
            ?>
        </div>
        
    </body>
</html> 
