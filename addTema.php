<?php
    extract($_POST);
    extract($_GET);
    $redir = "showUasytemas.php";
    if(!isset($nomTema)){
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
            $sql = "UPDATE TEMA SET NOM_TEMA = '".trim($nomTema)."', ID_UNIDAD = '$claveUA' WHERE ID_TEMA = ".$id;
            $update = mysqli_query($linker,$sql);
            if($update)
            {
                $title="Tema modificado correctamente";
                $msg="<p>El tema se ha modificado exitosamente<p><br>"
                            . "<p>". mysqli_error($linker)."<p>";
            }
            else{
                $typeP = "panel-warning";
                echo "<script>console.log(\"Consulta = $sql  Error: ".mysqli_error($linker)."\");</script>\n";
                $title= mysqli_errno($linker) . ": Error al modificar tema";
                if(mysqli_errno($linker) == 1062){
                    $msg="<p><b>El tema no puede ser modificado correctamente:</b><p><br><p>El tema '$nomTema' ya ha sido registrado previamente.<p>";
                }
                else{
                    $msg="<p><b>El tema no puede ser modificado correctamente:</b><p><br>"
                            . "<p>" . mysqli_error($linker) ."<p>";
                }
            }
        }
        else{
            $sql = "INSERT INTO TEMA (NOM_TEMA, ID_UNIDAD) VALUES ('".trim($nomTema)."', '$claveUA')";
            $update = mysqli_query($linker,$sql);
            if($update)
            {
                $title="Tema agregado correctamente";
                $msg="<p>El tema se ha registrado exitosamente<p><br>"
                            . "<p>". mysqli_error($linker)."<p>";
            }
            else{
                $typeP = "panel-warning";
                echo "<script>console.log(\"Consulta = $sql  Error: ".mysqli_error($linker)."\");</script>\n";
                $title= mysqli_errno($linker) . ": Error al agregar tema";
                if(mysqli_errno($linker) == 1062){
                    $msg="<p><b>El tema no puede ser agregado correctamente:</b><p><br><p>El tema '$nomTema' ya ha sido registrado previamente.<p>";
                }
                else{
                    $msg="<p><b>El tema no puede ser agregado correctamente:</b><p><br>"
                            . "<p>" . mysqli_error($linker) ."<p>";
                }
            } 
        }
        include "msgbox.php";
    ?>
    </body>
</html>
    
