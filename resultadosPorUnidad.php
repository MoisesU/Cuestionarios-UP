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
              $query = "SELECT unidad_de_aprendizaje.NOM_UNIDAD, SUM(respuesta.CORRECTA) AS CAL FROM respuesta
                        INNER JOIN PREGUNTA ON pregunta.ID_PREGUNTA = respuesta.ID_PREGUNTA
                        INNER JOIN TEMA ON tema.ID_TEMA = pregunta.ID_TEMA
                        INNER JOIN unidad_de_aprendizaje ON unidad_de_aprendizaje.ID_UNIDAD = tema.ID_UNIDAD
                        GROUP BY unidad_de_aprendizaje.NOM_UNIDAD";
              $consulta = mysqli_query($linker,$query);
              ?>
        <title>Resultados por UA</title>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script src="js/loader.js" type="text/javascript"></script>
        <script type="text/javascript">
          google.charts.load("current", {packages:["corechart"]});
          google.charts.setOnLoadCallback(drawChart);
          function drawChart() {
            var data = google.visualization.arrayToDataTable([
              ['Unidad de Aprendizaje', 'Respuestas correctas'],
//              ['Algebra',     11],
//              ['Aritmetica',      2],
//              ['Calculo Diferencia e Integral',  2]

              <?php
              while($col = mysqli_fetch_array($consulta))
              {
                  echo "['".$col["nom_unidad"]."',".$col["cal"]."],";
              }
              ?>
             ]);

            var options = {
              title: 'Resultados por Unidad de Aprendizaje',
              is3D: true
            };

            var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
            chart.draw(data, options);
          }
        </script>
   
    </head>
    <body>
        <?php include ('include/navbar.php');?>
        
        <div class="container">
            <h1>Estadísticas por unidad de aprendizaje</h1>
            <br>
            <!--
            <form class="form-horizontal">
                <div class="form-group">
                    <label class="control-label col-sm-3" for="fechaIni" style="text-align: left">Unidad de aprendizaje</label>
                        <div class="col-sm-3">
                            <select class="form-control" name="tema"><option>&Aacute;lgebra</option></select>
                        </div>
                </div>
            </form>
            -->
            <br>
            <br>
            
            <h3><center>No hay respuestas registradas</center></h3>
           
            
<!--            <div>
                <center><img src="img/barras2.png" class="img-fluid" alt="Gráfico"></center>
            </div>-->
        </div>
        
        <div><center>
        <div id="piechart_3d" class="estilo2" style="width: 900px; height: 500px;"></div>
            </center>
        </div>
    </body>
</html> 
