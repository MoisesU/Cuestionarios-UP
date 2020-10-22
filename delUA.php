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
        $sql = "DELETE FROM UNIDAD_DE_APRENDIZAJE WHERE ID_UNIDAD = '".$id."'";
        $update = mysqli_query($linker,$sql);
        if($update)
        {
            $title="Unidad elimidada correctamente";
            $msg="<p>La unidad de aprendizaje se ha eliminado exitosamente<p><br>"
                        . "<p>". mysqli_error($linker)."<p>";
        }
        else{
            $typeP = "panel-warning";
            echo "<script>console.log(\"Consulta = $sql \n Error: ".mysqli_error($linker)."\");</script>\n";
            $title=mysqli_errno($linker).": Error al eliminar la unidad";
            if(mysqli_errno($linker)==1451){
                $msg="<p>La unidad de aprendizaje no pudo ser eliminada de la base de datos:<p><br>"
                        . "<p>La unidad de aprendizaje '$id' tiene registrado uno o más temas por lo que no puede ser eliminada. <br> Si desea eliminarla, elimine primero los temas relacionados a esta unidad<p>";
            }
            else{
                $msg="<p>La unidad de aprendizaje no pudo ser eliminada de la base de datos:<p><br>"
                        . "<p>". mysqli_error($linker)."<p>";
            }
        }
        include "msgbox.php";
    ?>
    </body>
</html>
