<?php
    extract($_GET);
    $redir = "showCuestionarios.php";
    if(!isset($id)){
        echo "Error 505: Access denied.";
        exit;
    }
    require 'connect.php';
    ?>
<html>
    <head>
        <?php include "include/header.php" ?>
    </head>
    <body>
    <?php 
        include "include/navbar.php";
        $sql = "UPDATE ENCUESTA SET ESTADO = 0 WHERE ID_ENCUESTA = ".$id;
        $update = mysqli_query($linker,$sql);
        if($update)
        {
            $title="Encuesta finalizada correctamente";
            $msg="<p>La encuesta se ha finalizado exitosamente<p><br>"
                        . "<p>". mysqli_error($linker)."<p>";
            unlink("encuestas/ENACTID".$id.".php");
        }
        else{
            $typeP = "panel-warning";
            echo "<script>console.log(\"Consulta = $sql \n Error: ".mysqli_error($linker)."\");</script>\n";
            $title="Error al finalizar la encuesta";
            $msg="<p>La encuesta no pudo ser modificada en la base de datos:<p><br>"
                        . "<p>". mysqli_error($linker)."<p>";
            }
        include "msgbox.php";
    ?>
    </body>
</html>