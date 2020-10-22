<?php
    extract($_POST);
    if(!isset($carrera)){
        echo "Error 505: Access denied.";
        exit;
    }
?> 
 <!DOCTYPE html>
 <html>
 <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../img/pencil.png" />
    <link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="../css/encuestas.css" rel="stylesheet" type="text/css"/>
    <script src="../js/jquery-3.2.1.min.js" type="text/javascript"></script>
    <script src="../js/bootstrap.min.js" type="text/javascript"></script>        
    <script src="../js/jquery.scrollUp.min.js" type="text/javascript"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.5/MathJax.js?config=TeX-MML-AM_CHTML' async></script>
        <?php 
            require 'connectAsUser.php';
        ?>
     <title>Registro de respuesta</title>
 </head>
 <body>
     <?php   
        $respuestas = [];
        for($i = 1; $i<$num_p+1; $i++){
            if(isset($_POST['oprad'.$i])){
                $obj = json_decode($_POST['oprad'.$i]);
                $respuestas[$i] = $obj -> {'a'};
            }
            else{
                break;
            }
        }

        $calif = 0;
        if($tipo==1){
            $escuela = 8;
        }
        $sql = "INSERT INTO ENCUESTADO (CARRERA, SEMESTRE, EDAD, GENERO, ESCUELA, TIPO, ID_ENCUESTA, PROMEDIO) VALUES ('$carrera', $semestre, $edad, $genero, $escuela, $tipo, $id_c, $promedio)";
        $update = mysqli_query($linker,$sql);
        $options = ['X', 'A', 'B', 'C', 'R'];
        if($update)
        {

            $id_cuest = getSimpleValue("SELECT MAX(ID_ENCUESTADO) AS 'ID_U' FROM ENCUESTADO",'ID_U', $linker);
            foreach($respuestas as $r){
                $calif = $calif + ($r[1]==4?1:0);
                $sql = "INSERT INTO RESPUESTA (ID_ENCUESTADO, ID_PREGUNTA, OPCION, CORRECTA) VALUES ($id_cuest, ".$r[0].", '".$options[$r[1]]."', ".($r[1]==4?1:0).")";
                echo "<script>console.log(\"".$sql."\");</script>\n";
                $update = mysqli_query($linker,$sql);
                if(!$update){
                    //echo "<script>console.log(\"".$sql."\");</script>\n";
                    echo "<script>console.log(\"".mysqli_error($linker)."\");</script>\n";
                    break;
                }
            }
            if($update){
                echo "<script>console.log('Registro exitoso');</script>\n";
                $title="Respuesta registrada correctamente";
                $sql = "CALL CALIFICAR($id_cuest, @var_asc, @var_calif);";
                $resultado1 = mysqli_query($linker,$sql);
                if (!$resultado1) {
                    echo "<script>console.log(\"".mysqli_error($linker)."\");</script>\n";
                }
                $sql = "SELECT @var_asc AS ACI, @var_calif AS CAL FROM DUAL;";
                $resultado1 = mysqli_query($linker,$sql);
                if (!$resultado1) {
                    echo "<script>console.log(\"".mysqli_error($linker)."\");</script>\n";
                    $msg="<p>Gracias por haber contestado la encuesta. <br> Tu respuesta se ha registrado satisfactoriamente!<p><br><p>Obtuviste una puntuaci&oacute;n de  ".round($calif, 1)." aciertos</p>";
                    include "../msgbox.php";
                }
                else{
                    $fila = mysqli_fetch_assoc($resultado1);
                    $calif=$fila["CAL"];
                    $points=$fila["ACI"];
                    include "../include/jumbotron.php";
                }
            }
        }
        else{
            //echo "<script>console.log(\"".$sql."\");</script>\n";
            echo "<script>console.log(\"".mysqli_error($linker)."\");</script>\n";
            $title="Error al registrar la respuesta";
            $msg="<p>Ha ocurrido un error al momento de agregar tu respuesta. Intenta de nuevo más tarde.<p><br>";
            if(isset($calif)){$msg = $msg."<p>Tu calificación fue de $calif aciertos</p>";}
            include "../msgbox.php";
        }
    ?>
 </body>
 </html>
