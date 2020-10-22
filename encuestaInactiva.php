<?php
    extract($_GET);
    if(!isset($id)){
        echo "Error 505: Access denied.";
        exit;
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include "include/header.php"; ?>
        <title>Encuesta inactiva</title>
    </head>
    <body>
        <?php
            $title = "Encuesta $id inactiva";
            $msg = "<p>La encuesta a la que intentas acceder está no está activa. <br> Verifica que el enlace al que accediste "
                    . "sea correcto o consulta las fechas en las que puedes acceder con tu profesor.</p>";
            include "msgbox.php";
        ?>
    </body>
</html>