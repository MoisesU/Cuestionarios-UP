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
              $query = "SELECT encuestado.ESCUELA, sum(respuesta.CORRECTA) AS CAL FROM respuesta
                        INNER JOIN encuestado ON respuesta.ID_ENCUESTADO = encuestado.ID_ENCUESTADO
                        INNER JOIN encuesta ON encuesta.ID_ENCUESTA = encuestado.ID_ENCUESTA
                        WHERE encuesta.ESTADO='TRUE'
                        group by encuestado.ESCUELA";
              $consulta = mysqli_query($linker,$query);
        ?>
        <title>Resultados por Encuesta</title>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script src="js/loader.js" type="text/javascript"></script>
        <script type="text/javascript">
          google.charts.load('current', {'packages':['bar']});
          google.charts.setOnLoadCallback(drawStuff);

          function drawStuff() {
            var data = new google.visualization.arrayToDataTable([
              ['Escuela', 'Porcentaje'],
//              ["King's pawn (e4)", 44],
//              ["Queen's pawn (d4)", 31],
//              ["Knight to King 3 (Nf3)", 12],
//              ["Queen's bishop pawn (c4)", 10],
//              ['Other', 3]
            <?php
            while($col = mysqli_fetch_array($consulta))
             {
              echo "['".$col["escuela"]."',".$col["cal"]."],";
             }
            ?>
            ]);

            var options = {
              title: 'Estadísticas por Escuela',
              width: 900,
              legend: { position: 'none' },
              chart: { title: 'Estadísticas por Escuela',
                       subtitle: 'Respuestas correctas' },
              bars: 'horizontal', // Required for Material Bar Charts.
              axes: {
                x: {
                  0: { side: 'top', label: 'Porcentaje'} // Top x-axis.
                },
                y:{
                  0: { side: 'left', label: 'Escuela'} // Left y-axis.   
                }
              },
              bar: { groupWidth: "90%" }
            };

            var chart = new google.charts.Bar(document.getElementById('top_x_div'));
            chart.draw(data, options);
          };
        </script>

    </head>
    <body>
        <?php include ('include/navbar.php');?>
            <div class="container">
            <h1>Estadísticas por Escuela</h1>
            <br>
            
            <br>
<!--            <h3><center>No hay encuestas registradas en esta carrera en este periodo</center></h3>-->
           
        </div>
            <div>
                <center>
                <div id="top_x_div" style="width: 900px; height: 500px;"></div>
                </center>
            </div>
        </div>
    </body>
</html> 
