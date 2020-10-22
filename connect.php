<?php
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    }
    $user = $_SESSION['user'];
    $pass = $_SESSION['pass'];
    
    $linker = new MySQLi("localhost", $user, $pass, "cuestionariosbd");
    if ($linker -> connect_errno)
    {
        die( "Fallo la conexión a MySQL: (" . $linker -> mysqli_connect_errno() . ") " . $linker -> mysqli_connect_error());
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
    
    function getColumn($sql, $colname, $linker){
        $resultado = mysqli_query($linker, $sql);
        if (!$resultado) {
            echo "Error de BD al obtener '$value', no se pudo consultar la base de datos\n";
            echo "Error MySQL:" .  mysqli_error($linker);
            exit;
        }
        $arr = [];
        $i = 0;
        while($fila = mysqli_fetch_assoc($resultado)){
            $arr[$i] = $fila[$colname];
            $i++;   
        }
        mysqli_free_result($resultado);
        return $arr;
    }
?>
    