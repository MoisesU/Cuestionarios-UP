<?php
    extract($_POST);
    extract($_GET);
    if(!isset($pregunta)){
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
        if(isset($id)){
            $redir = "showPreguntas.php";
            $sql = "UPDATE PREGUNTA SET REDACCION='".trim($pregunta)."', DISTRACTOR_A='".trim($distractor1)."', DISTRACTOR_B='".trim($distractor2)."', DISTRACTOR_C='".trim($distractor3)."', RESPUESTA='".trim($respuesta)."', ID_TEMA=".$tema." WHERE ID_PREGUNTA = ".$id;
            $update = mysqli_query($linker,$sql);
            if($update){
                $title="Pregunta modificada correctamente";
                $msg="<p>La pregunta se ha modificado exitosamente<p><br>"
                            . "<p>". mysqli_error($linker)."<p>";
            }
            else{
                $typeP = "panel-warning";
                echo "<script>console.log(\"Consulta = $sql Error: ".mysqli_error($linker)."\");</script>\n";
                $title="Error al modificar la pregunta";
                $msg="<p>La pregunta no puede ser modificado correctamente:<p><br>"
                            . "<p>". mysqli_error($linker)."<p>";
            }
        }
        else{
            $redir = "pregunta.php";
            $sql = "INSERT INTO PREGUNTA (REDACCION,DISTRACTOR_A, DISTRACTOR_B, DISTRACTOR_C, RESPUESTA, ID_TEMA) VALUES ('".trim($pregunta)."', '".trim($distractor1)."', '".trim($distractor2)."', '".trim($distractor3)."', '".trim($respuesta)."', $tema)";
            $update = mysqli_query($linker,$sql);
            if($update)
            {
                $title="Pregunta agregado correctamente";
                $msg="<p>La pregunta se ha registrada exitosamente<p><br>"
                            . "<p>". mysqli_error($linker)."<p>";
            }
            else{
                $typeP = "panel-warning";
                echo "<script>console.log(\"Consulta = $sql  Error: ".mysqli_error($linker)."\");</script>\n";
                $title="Error al agregar la pregunta";
                $msg="<p>La pregunta no puede ser agregada correctamente:<p><br>"
                            . "<p>". mysqli_error($linker)."<p>";
            }    
        }    
        include "msgbox.php";
    ?>
    </body>
</html>