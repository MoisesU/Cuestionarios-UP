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
              $query = "SELECT ENCUESTADO.CARRERA, sum(respuesta.CORRECTA) AS CAL FROM respuesta
                        INNER JOIN encuestado ON respuesta.ID_ENCUESTADO = encuestado.ID_ENCUESTADO
                        INNER JOIN encuesta ON encuesta.ID_ENCUESTA = encuestado.ID_ENCUESTA
                        WHERE encuesta.ESTADO='TRUE'
                        group by encuestado.CARRERA";
               $consulta = mysqli_query($linker,$query);
        ?>
        <title>Resultados por Carrera</title>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script src="js/loader.js" type="text/javascript"></script>
        <script type="text/javascript">
          google.charts.load('current', {'packages':['corechart']});
          google.charts.setOnLoadCallback(drawChart);

          function drawChart() {

            var data = google.visualization.arrayToDataTable([
              ['Carrera', 'Respuestas Correctas'],
//              ['IINF',     11],
//              ['AI',      2],
//              ['II',  2],
//              ['CI', 2],
//              ['IT',    7]
              <?php
              while($col = mysqli_fetch_array($consulta))
             {
              echo "['".$col["carrera"]."',".$col["cal"]."],";
             }
              ?>
            ]);

            var options = {
              title: 'Resultado por Carrera'
            };

            var chart = new google.visualization.PieChart(document.getElementById('piechart'));

            chart.draw(data, options);
          }
        </script>

    </head>
    <body>
        <?php include ('include/navbar.php');?>
        
        <div class="container">
            <h1>Estadísticas por carrera</h1>
            <br>
            
            <br>
<!--            <h3><center>No hay encuestas registradas en esta carrera en este periodo</center></h3>-->
           
        </div>
        <div>
            <center>    
        <div id="piechart" style="width: 900px; height: 500px;"></div>
        </center></div>
    </body>
</html> 
