<?php
    extract($_POST);
    extract($_GET);
    $redir = "showUasytemas.php";
    if(!isset($nombre)){
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
        if(isset($mody)){
            $sql = "UPDATE UNIDAD_DE_APRENDIZAJE SET  NOM_UNIDAD = '".trim($nombre)."' WHERE ID_UNIDAD = '".$id."'";
            $update = mysqli_query($linker,$sql);
            if($update)
            {
                $title="Unidad modificada correctamente";
                $msg="<p>La unidad de aprendizaje se ha modificado exitosamente<p><br>";
            }
            else{
                $typeP = "panel-warning";
                echo "<script>console.log(\"Consulta = $sql  Error: ".mysqli_error($linker)."\");</script>\n";
                $title= mysqli_errno($linker) . ":Error al modificar la unidad";
                if(mysqli_errno($linker) == 1062){
                    $msg="<p><b>La unidad de aprendizaje no puede ser modificada correctamente:</b><p><br><p>La clave de la unidad '$id' ha sido registrada previamente.<p>";
                }
                else{
                    $msg="<p><b>La unidad de aprendizaje no puede ser modificada correctamente:</b><p><br>"
                            . "<p>" . mysqli_error($linker) ."<p>";
                }
            }
        }
        else{
            $sql = "INSERT INTO UNIDAD_DE_APRENDIZAJE (ID_UNIDAD, NOM_UNIDAD) VALUES ('".trim($id)."', '".trim($nombre)."')";
            $update = mysqli_query($linker,$sql);
            if($update)
            {
                $title="Unidad agregado correctamente";
                $msg="<p>La unidad de aprendizaje se ha registrado exitosamente<p><br>";
            }
            else{
                $typeP = "panel-warning";
                echo "<script>console.log(\"Consulta = $sql  Error: ".mysqli_error($linker)."\");</script>\n";
                $title= mysqli_errno($linker) . ":Error al agregar la unidad";
                if(mysqli_errno($linker) == 1062){
                    $msg="<p><b>La unidad de aprendizaje no puede ser agregada correctamente:</b><p><br><p>La clave de la unidad '$id' ha sido agregada previamente.<p>";
                }
                else{
                    $msg="<p><b>La unidad de aprendizaje no puede ser agregada correctamente:</b><p><br>"
                            . "<p>" . mysqli_error($linker) ."<p>";
                }
            }  
        }
        include "msgbox.php";
    ?>
    </body>
</html>
    