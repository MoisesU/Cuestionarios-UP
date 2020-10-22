<?php
    //--------------Unidades-------------------------------------
    $sql       = 'SELECT * FROM UNIDAD_DE_APRENDIZAJE ORDER BY ID_UNIDAD';
    $resultado = mysqli_query($linker, $sql);

    if (!$resultado) {
        echo "Error de BD al obtener 'UNIDADES', no se pudo consultar la base de datos\n";
        echo "Error MySQL:" .  mysqli_error($linker);
        exit;
    }
    $i = 0;
    $temas = [];
    $uas = [];
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $uas[$i] = array($fila['ID_UNIDAD'], $fila['NOM_UNIDAD']);
        $i++;
    }
    
    mysqli_free_result($resultado);
    //-------------------Temas------------------------------------
    $sql        = "SELECT * FROM TEMA ORDER BY ID_UNIDAD";
    $resultado2 = mysqli_query($linker, $sql);
    if (!$resultado2) {
        echo "Error de BD al obtener 'TEMAS', no se pudo consultar la base de datos\n";
        echo "Error MySQL:" .  mysqli_error($linker);
        exit;
    }
    $x = 0;
    $i = 0; 
    while ($fila = mysqli_fetch_assoc($resultado2)) {
        while($fila['ID_UNIDAD'] != $uas[$i][0]){
            $i++;
            $x = 0;
        }
        $temas[$i][$x] = array($fila['ID_TEMA'], $fila['NOM_TEMA']);
        $x++;
    }
    
    mysqli_free_result($resultado2);
?>