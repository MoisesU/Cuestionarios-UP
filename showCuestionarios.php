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
        <title>Encuestas</title>
    </head>
    <body>
        <?php include ('include/navbar.php');?>
        
        <div class="container">
            <h1>Encuestas</h1>
            <br>
            
            <form class="form-horizontal" action="cuestionario.php">
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-6">
                        <button type="submit" class="btn btn-lg btn-primary btn-block btn-big">Crear nueva encuesta</button>
                    </div>
                </div>
            </form><br><br>
            <?php 
                $sql       = "SELECT * FROM ENCUESTA ORDER BY ESTADO DESC";
                $resultado = mysqli_query($linker, $sql);
                if (!$resultado) {
                    echo "Error de BD al obtener 'ENCUESTAS', no se pudo consultar la base de datos\n";
                    echo "Error SQL:" .  mysqli_error($linker);
                    exit;
                }
                if (mysqli_num_rows($resultado)!=0){
                    echo "\n\t\t\t<div class='table-responsive'><table class='table table-hover'>\n\t\t\t\t<thead>\n\t\t\t\t\t<tr>\n\t\t\t\t\t\t<th>ID</th>\n\t\t\t\t\t\t<th>Descripción</th>
                    \n\t\t\t\t\t\t<th>Estado</th>\n\t\t\t\t\t\t<th>Contestados</th>\n\t\t\t\t\t\t<th>Fecha de Cierre</th>\n\t\t\t\t\t\t<th>Opciones</th>\n\t\t\t\t\t</tr>
                    \n\t\t\t\t</thead>\n\t\t\t\t<tbody>";
                    while($fila = mysqli_fetch_assoc($resultado)){
                        echo "\n\t\t\t\t\t<tr".($fila["ESTADO"]==0?"":" class='success'").">";
                        echo "\n\t\t\t\t\t\t<td>".$fila['ID_ENCUESTA']."</td>";
                        echo "\n\t\t\t\t\t\t<td class='overflow-str'>".$fila['DESCRIPCION']."</td>";
                        echo "\n\t\t\t\t\t\t<td>".($fila["ESTADO"]==1?"ACTIVA":"FINALIZADA")."</td>";
                        echo "\n\t\t\t\t\t\t<td>".$fila["CONTESTADAS"]."/".$fila["MUESTRA"]."</td>";
                        echo "\n\t\t\t\t\t\t<td>".$fila["FECHA_FIN"]."</td>";
                        echo "\n\t\t\t\t\t\t<td>";
                        if($fila["CONTESTADAS"]>0){
                            echo "<button class='btn btn-group-sm btn-primary btn-block' onclick=\"location.href='resultadosPorEncuesta.php?id=".$fila['ID_ENCUESTA']."'\">Resultados</button>";
                            if($fila["ESTADO"]==1){
                                echo "<button class='btn btn-group-sm btn-primary btn-block' onclick=\"location.href='finalizar.php?id=".$fila['ID_ENCUESTA']."'\">Finalizar</button>";
                            }
                        }
                        else{
                            echo "<button class='btn btn-group-sm btn-primary btn-block' onclick=\"location.href='cuestionario.php?id=".$fila['ID_ENCUESTA']."'\">Modificar</button>";
                            echo "<button class='btn btn-group-sm btn-primary btn-block' onclick=\"sure('delCuestionario.php?id=".$fila['ID_ENCUESTA']."')\">Eliminar</button>";
                        }
                        echo "</td>";
                        echo "</tr>";
                    }
                    echo "\n\t\t\t\t<tbody>\n\t\t\t<table></div>\n";
                }
                else{
                    echo "<h3><center>No hay encuestas registradas</center></h3>";
                }
            ?>
        </div>
        <script type="text/javascript">
            function sure(value){
                    //alert("fui presionado\n"+value);
                    if(confirm("¿Está seguro de que quiere eliminar este registro?")){
                        location.href = value;
                    }   
                }
        </script>
    </body>
</html> 