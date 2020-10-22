<?php
    extract($_POST);
    extract($_GET);
    $redir="showCuestionarios.php";
    if(!isset($descrip)){
        echo "Error 505: Access denied.";
        exit;
    }
    require 'connect.php';
?>
<!DOCTYPE html>
<html>
<head>
    <?php include "include/header.php"; ?>
    <title>Cuestionario</title>
</head>
<body>
    <?php
    include "include/navbar.php";
    //<script>
    /*alert("NO SE PUDO AGREGAR REGISTRO! \n Error en la base de datos.");*/
    //alert("PREGUNTA MODIFICADA CORRECTAMENTE");
    //var reply=confirm("¿Seguro que desea salir del sistema?");
    
    if(!isset($id)){
        
        $sql = "INSERT INTO ENCUESTA (DESCRIPCION, FECHA_INI, FECHA_FIN, MUESTRA, NUM_PREGUNTAS, CUBRIR_MUESTRA) VALUES ('".trim($descrip)."', '$fechaIni', '$fechaFin', $muestra, $numPreguntas, ".(isset($finalizar)?1:0).")";
        $update = mysqli_query($linker,$sql);
        $obj = json_decode("{\"a\":$selecP}");
        $preguntas = $obj -> {'a'};
        echo "<script>console.log(\"".$sql."\");\n</script>";
        if($update)
        {
            $id = getSimpleValue("SELECT MAX(ID_ENCUESTA) AS 'ID_C' FROM ENCUESTA",'ID_C', $linker);
            addPreguntas($preguntas, $linker, $id);
            if($update){
                //echo "alert('CUESTIONARIO AGREGADO EXITOSAMENTE');\n";
                //echo "location.href='showCuestionarios.php';</script>\n";
                $dir = "encuestas/ENACTID".$id.".php";
                createDocument($dir, $id);
                $title="Encuesta $id agregada correctamente";
                $msg="<p>La encuesta se ha creado correctamente y puede acceder a ella a través del siguiente enlace:<p><br>"
                        . "<a class='text-center' href='$dir'>$dir<a>";
                
                include "msgbox.php";
            }else{
                $typeP = "panel-warning";
                echo "<script>console.log(\"".mysqli_error($linker)."\");</script>\n";
                $title="Error al insertar la encuesta";
                $msg="<p>La encuesta no puede ser agregada correctamente:<p><br>"
                            . "<p>". mysqli_error($linker)."<p>";
                include "msgbox.php";
            }
        }
        else{
            $typeP = "panel-warning";
            echo "<script>console.log(\"".mysqli_error($linker)."\");</script>\n";
            $title="Error al insertar la encuesta";
            $msg="<p>La encuesta no puede ser agregada correctamente:<p><br>"
                        ."<p>". mysqli_error($linker)."<p>";
            include "msgbox.php";
            //echo "alert('ERROR AL AGREGAR EL CUESTIONARIO');\n";
            //echo "location.href='showCuestionarios.php';</script>\n";
        }
    }
    //------------------------------------MODIFICAR ENCUESTA--------------------------------------------------------------------------------------------------
    else{
       
        $sql = "UPDATE ENCUESTA SET DESCRIPCION = '".trim($descrip)."', FECHA_INI = '$fechaIni', FECHA_FIN = '$fechaFin', MUESTRA = $muestra, NUM_PREGUNTAS = $numPreguntas, CUBRIR_MUESTRA = ".(isset($finalizar)?1:0)." WHERE ID_ENCUESTA = $id";
        $update = mysqli_query($linker,$sql);
        $error = "0";
        if($update){
            $sql = "DELETE FROM ENCUESTA_PREGUNTA WHERE ID_ENCUESTA = $id";
            $update = mysqli_query($linker, $sql);
            $obj = json_decode("{\"a\":$selecP}");
            $preguntas = $obj -> {'a'};
            if($update){
                if(addPreguntas($preguntas, $linker, $id)){
                    $dir = "encuestas/ENACTID".$id.".php";
                    $title="Encuesta $id agregada correctamente";
                    $msg="<p>La encuesta se ha modificado correctamente y puede acceder a ella a través del siguiente enlace:<p><br>"
                            . "<a class='text-center' href='$dir'>$dir<a>";
                    include "msgbox.php";
                }
                else{
                    $error = "insertar nuevas preguntas";
                }
            }
            else{
                $error = "borrar preguntas anteriores";
            }
        }
        else{
            $error = "modificar encuesta";
        }
        
        if($error!=="0"){
            echo "<script>console.log(\"".mysqli_error($linker)."\");</script>\n";
            $title="Error al modificar la encuesta";
            $msg="<p>La encuesta no puede ser modificada correctamente:<br>Error al $error<p><br>"
                        . "<p>". mysqli_error($linker)."<p>";
            include "msgbox.php";
        }
    }
    
    function addPreguntas($arr, $link, $id_cuest){
        foreach($arr as $id_p){
                $sql = "INSERT INTO ENCUESTA_PREGUNTA (ID_ENCUESTA, ID_PREGUNTA) VALUES ($id_cuest,$id_p)";
                $update = mysqli_query($link,$sql);
                if(!$update){
                    echo "<script>console.log(\"".mysqli_error($link)."\");\n";
                    echo "console.log('ERROR AL AGREGAR PREGUNTA $id_p AL CUESTIONARIO: $sql');</script>\n";
                    //var_dump($arr);
                    return false;
                }
        }
        return true;
    }
    
    
    function createDocument($dir, $id){
        $conten = "<?php \n\t\$id = $id; \n\tinclude ('dummy.php')?>";
        $archivo = fopen($dir, "w+b");
        if($archivo){
            if(fwrite($archivo, $conten))
            {
                echo "<script>console.log('Se ha creado correctamente el archivo');</script>\n";
            }
            else
            {
                echo "<script>console.log('No se pudo crear el archivo');</script>\n";
            }
            fclose($archivo);
        }
    }
        
    
?>
</body>
</html>

