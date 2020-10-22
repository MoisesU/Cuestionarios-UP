<html>
    <?php
        $id = 4;
    ?>
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
        <title>Encuesta <?php if(isset($id)){echo $id;}?></title>
    </head>
    <body>
        <div class="container">
            <h1>Encuesta <?php if(isset($id)){echo $id;}?></h1>
            <br>
            <h2>Descripci&oacute;n</h2>
            <p class="description">
                <?php 
                    $encuesta = getValues($id, $linker);
                    echo $encuesta['DESC'];
                ?>
            </p>
            <br>
            <h2>Intrucciones</h2>
            <p class="instructions">
                - La información solicitada es únicamente para fines académicos por lo cual se te pide que contestes con la mayor objetividad y sinceridad.
                <br>- El cuestionario consta de dos secciones: la primera es para contar con tus datos generales, así como para conocer tu actitud hacia las matemáticas; La segunda es sobre tus conocimientos básicos.
                <br>- Ya que este cuestionario es para fines académicos, por favor, NO COPIES, NI USES CALCULADORA.
                <br>- Favor de contestar TODO el cuestionario (no dejar preguntas en blanco).
            </p>
            <br>
            <form id="formulario" class="form-horizontal" action="addCuestionario.php" method="POST">
                <div class="form-group">
                    <label class="control-label col-lg-offset-1 col-sm-3" for="carrera">Carrera</label>
                    <div class="col-sm-3">
                        <select class="form-control" name="carrera">
                            <option value="0">Ingeniería Industrial</option>
                            <option value="1">Ingeniería en Informatica</option>
                            <option value="2">Administración Industrial</option>
                            <option value="3">Ingeniería en Transporte</option>
                            <option value="4">Ciencias de la Informática</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-offset-1 col-sm-3" for="turno">Turno</label>
                    <label><input type="radio" name="turno" value="m">&nbsp;&nbsp;Matutino&nbsp;&nbsp;</label>
                    <label><input type="radio" name="turno" value="v">&nbsp;&nbsp;Vespertino&nbsp;&nbsp;</label>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-offset-1 col-sm-3" for="genero">Sexo</label>
                    <label><input type="radio" name="genero" value="0">&nbsp;&nbsp;Masculino&nbsp;&nbsp;</label>
                    <label><input type="radio" name="genero" value="1">&nbsp;&nbsp;Femenino&nbsp;&nbsp;</label>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-4" for="escuela">Escuela de procedencia</label>
                    <div class="col-sm-6">
                        <input required type="text" class="form-control" name="escuela">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-4" for="promedio">Promedio</label>
                    <div class="col-sm-6">
                        <input required type="text" class="form-control" name="promedio">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-4" for="semestre">Semestre</label>
                    <div class="col-sm-6">
                        <input required type="text" class="form-control" name="semestre">
                    </div>
                </div>
                <br>
                <h2>Preguntas</h2>
                <br>
                <form><?php
                    function reorder($a){
                        $arr = $a;
                        $l = count($arr);
                        for($x=0; $x<$l; $x++){
                            $new_i = rand(0, $l-1);
                            $aux = $arr[$x];
                            $arr[$x] = $arr[$new_i];
                            $arr[$new_i] = $aux;
                        }
                        return $arr;
                    }
                    
                    $sql = "select REDACCION, DISTRACTOR_A, DISTRACTOR_B, DISTRACTOR_C, RESPUESTA from pregunta a inner join 
                        encuesta_pregunta b on b.id_pregunta=a.id_pregunta inner join encuesta c on c.id_encuesta=b.id_encuesta where b.id_encuesta = $id;";
                    $resultado = mysqli_query($linker, $sql);
                    if (!$resultado) {
                        echo "Error de BD, no se pudo consultar la base de datos\n";
                        echo "Error MySQL:" . mysqli_error($linker);
                        exit;
                    }
                    $preguntas = [];
                    $i = 0;
                    while ($fila = mysqli_fetch_assoc($resultado)) {
                        $preguntas[$i] = array($fila['REDACCION'], $fila['DISTRACTOR_A'], $fila['DISTRACTOR_B'], $fila['DISTRACTOR_C'], $fila['RESPUESTA']);
                        $i++;
                    }
                    //DESORDENAR LAS PREGUNTAS 
                    $reorpreg = reorder($preguntas);
                    $answrs = [1,2,3,4];
                    
                    echo "\t\t\t\t\n<div class='form-group'>";
                    $i = 1;
                    foreach($reorpreg as $p){
                        if($i<=$encuesta['NUM']){
                            echo "\t\t\t\t\t\n<div class='pregunta'>";
                            echo "\t\t\t\t\t\n<p>";
                            echo $i.".- ".$p[0];
                            echo "\t\t\t\t\t\n</p>";
                            $answrs = reorder($answrs);
                            echo "\t\t\t\t\t\t\n<div class='radio'><label class='radio-inline'><input type='radio' name='oprad$i' value='$answrs[0]'>".$p[$answrs[0]]."</label></div>";
                            echo "\t\t\t\t\t\t\n<div class='radio'><label class='radio-inline'><input type='radio' name='oprad$i' value='$answrs[1]'>".$p[$answrs[1]]."</label></div>";
                            echo "\t\t\t\t\t\t\n<div class='radio'><label class='radio-inline'><input type='radio' name='oprad$i' value='$answrs[2]'>".$p[$answrs[2]]."</label></div>";
                            echo "\t\t\t\t\t\t\n<div class='radio'><label class='radio-inline'><input type='radio' name='oprad$i' value='$answrs[3]'>".$p[$answrs[3]]."</label></div>";
                            echo "\t\t\t\t\t\n</div>";
                            echo "\t\t\t\t\t\n<br>";
                        }
                        $i++;
                    }
                    echo "\t\t\t\t\n</div>"; 
                ?>
                <div class="form-group">        
                    <div class="col-sm-offset-2 col-sm-8">
                        <button type="submit" class="btn btn-lg btn-primary btn-block btn-signin">Agregar</button>
                    </div>
                </div>
            </form>
        </div>
        <?php 
            function createDocument($dir, $id){
                $conten = "<?php \n\t\$id = $id; \n\tinclude ('dummy.php')?>";
                $archivo = fopen($dir, "w+b");
                if($archivo){
                    if(fwrite($archivo, $conten))
                    {
                        echo "Se ha ejecutado correctamente";
                    }
                    else
                    {
                        echo "Ha habido un problema al crear el archivo";
                    }
                    fclose($archivo);
                }
            }
            createDocument("x/ENSDOS", "8");
        ?>
    </body>
</html> 

