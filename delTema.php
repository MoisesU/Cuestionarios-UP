<?php
    extract($_GET);
    $redir = "showUasytemas.php";
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
        $sql = "DELETE FROM TEMA WHERE ID_TEMA = ".$id;
        $update = mysqli_query($linker,$sql);
        if($update)
        {
            $title="Tema elimidado correctamente";
            $msg="<p>El tema se ha eliminado exitosamente<p><br>"
                        . "<p>". mysqli_error($linker)."<p>";
        }
        else{
            $typeP = "panel-warning";
            echo "<script>console.log(\"Consulta = $sql \n Error: ".mysqli_error($linker)."\");</script>\n";
            $title=mysqli_errno($linker).": Error al eliminar el tema";
            if(mysqli_errno($linker)==1451){
                $msg="<p>El tema no pudo ser eliminado de la base de datos:<p><br>"
                        . "<p>El tema '$id' está asociado a una o más preguntas. <br>Se recomienda que simplemente modifique el nombre del tema, si por el contrario, desea eliminarlo, modifique las preguntas asociadas a este tema<p>";
            }
            else{
                $msg="<p>El tema no pudo ser eliminado de la base de datos:<p><br>"
                        . "<p>". mysqli_error($linker)."<p>";
            }
        }
        include "msgbox.php";
    ?>
    </body>
</html>