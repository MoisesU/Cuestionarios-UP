<?php

    $linker = new MySQLi("localhost", "alumno", "3ncu", "cuestionariosbd");
    if ($linker -> connect_errno)
    {
        die( "Fallo la conexión a MySQL: (" . $linker -> mysqli_connect_errno() . ") " . $linker -> mysqli_connect_error());
    }
    
    function getValues($id, $linker){
        $sql = "SELECT DESCRIPCION as 'DESC', NUM_PREGUNTAS as NUM FROM ENCUESTA WHERE ID_ENCUESTA = $id";
        $resultado = mysqli_query($linker, $sql);
        if (!$resultado) {
            echo "Error de BD al obtener '$value', no se pudo consultar la base de datos\n";
            echo "Error MySQL:" .  mysqli_error($linker);
            exit;
        }
        $fila = mysqli_fetch_assoc($resultado);
        mysqli_free_result($resultado);
        return $fila;
    }
    function getSimpleValue($sql, $value, $linker){
        $resultado = mysqli_query($linker, $sql);
        if (!$resultado) {
            echo "Error de BD al obtener '$value', no se pudo consultar la base de datos\n";
            echo "Error MySQL:" .  mysqli_error($linker);
            exit;
        }
        $fila = mysqli_fetch_assoc($resultado);
        mysqli_free_result($resultado);
        return $fila[$value];
    }
?>