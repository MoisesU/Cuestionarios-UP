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
        $sql = "DELETE FROM ENCUESTA_PREGUNTA WHERE ID_ENCUESTA = ".$id;
        $update1 = mysqli_query($linker,$sql);
        $sql = "DELETE FROM ENCUESTA WHERE ID_ENCUESTA = ".$id;
        $update2 = mysqli_query($linker,$sql);
        if($update1 && $update2)
        {
            $title="Encuesta elimidada correctamente";
            $msg="<p>La encuesta se ha eliminado exitosamente<p><br>"
                        . "<p>". mysqli_error($linker)."<p>";
            unlink("encuestas/ENACTID".$id.".php");
        }
        else{
            $typeP = "panel-warning";
            echo "<script>console.log(\"Consulta = $sql \n Error: ".mysqli_error($linker)."\");</script>\n";
            $title="Error al eliminar la encuesta";
            $msg="<p>La encuesta no pudo ser eliminada de la base de datos:<p><br>"
                        . "<p>". mysqli_error($linker)."<p>";
            }
        include "msgbox.php";
    ?>
    </body>
</html>
    