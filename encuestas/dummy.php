<?php 
    if(!isset($id)){
        echo "No content here!";
        exit;
    } 
    require 'connectAsUser.php';
    $estado = getSimpleValue("SELECT ESTADO FROM ENCUESTA WHERE ID_ENCUESTA = $id", "ESTADO", $linker);
    if($estado==0){
        unlink("ENACTID".$id.".php");
        header("Location: ../encuestaInactiva.php?id=$id");
    }
?>
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
            <form id="formulario" class="form-horizontal" action="addRespuesta.php" method="POST">
                <div class="form-group">
                    <label class="control-label col-lg-offset-1 col-sm-3" for="carrera">Carrera</label>
                    <div class="col-sm-3">
                        <select class="form-control" name="carrera">
                            <option value="II">Ingeniería Industrial</option>
                            <option value="IN">Ingeniería en Informatica</option>
                            <option value="AI">Administración Industrial</option>
                            <option value="IT">Ingeniería en Transporte</option>
                            <option value="CI">Ciencias de la Informática</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-offset-1 col-sm-3" for="turno">Turno</label>
                    <label><input type="radio" name="turno" value="m" required checked>&nbsp;&nbsp;Matutino&nbsp;&nbsp;</label>
                    <label><input type="radio" name="turno" value="v">&nbsp;&nbsp;Vespertino&nbsp;&nbsp;</label>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-offset-1 col-sm-3" for="genero">Sexo</label>
                    <label><input type="radio" name="genero" value="0" required checked>&nbsp;&nbsp;Masculino&nbsp;&nbsp;</label>
                    <label><input type="radio" name="genero" value="1">&nbsp;&nbsp;Femenino&nbsp;&nbsp;</label>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-offset-1 col-sm-3" for="tipo">Tipo de escuela</label>
                    <label><input type="radio" name="tipo" value="0" required checked onchange="cambiarTipo()">&nbsp;&nbsp;P&uacute;blica&nbsp;&nbsp;</label>
                    <label><input type="radio" name="tipo" id="tPriv" value="1" onchange="cambiarTipo()">&nbsp;&nbsp;Privada&nbsp;&nbsp;</label>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-4" for="escuela">Escuela de procedencia</label>
                    <div class="col-sm-3">
                        <select class="form-control" name="escuela" id="selecEsc">
                            <option value="0">Preparatoria o CCH (UNAM)</option>
                            <option value="1">CECyT o CET (IPN)</option>
                            <option value="2">CONALEP</option>
                            <option value="3">DGTI</option>
                            <option value="4">Colegio de Bachilleres</option>
                            <option value="5">DGB</option>
                            <option value="6">Preparatoria abierta</option>
                            <option value="7">Otra</option>
                            <option  class="hide" value="8" id="opPrivada">Escuela privada</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-4" for="promedio">Promedio</label>
                    <div class="col-sm-6">
                        <input required type="number" class="form-control" name="promedio" min="5.00" max="10.00" step="0.01">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-4" for="semestre">Semestre</label>
                    <div class="col-sm-6">
                        <input required type="number" class="form-control" name="semestre" min="1" max="8">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-4" for="edad">Edad</label>
                    <div class="col-sm-6">
                        <input required type="number" class="form-control" name="edad" min="17" max="100">
                    </div>
                </div>
                <input name="id_c" value="<?php echo $id; ?>" class="hide">
                <input name="num_p" value="<?php echo $encuesta['NUM']; ?>" class="hide">
                <br>
                <h2>Preguntas</h2>
                <br>
                <?php
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
                    
                    $sql = "SELECT b.ID_PREGUNTA, REDACCION, DISTRACTOR_A, DISTRACTOR_B, DISTRACTOR_C, RESPUESTA FROM PREGUNTA a INNER JOIN 
                        ENCUESTA_PREGUNTA b ON b.ID_PREGUNTA=a.ID_PREGUNTA INNER JOIN ENCUESTA c on c.ID_ENCUESTA=b.ID_ENCUESTA WHERE b.ID_ENCUESTA = $id;";
                    $resultado = mysqli_query($linker, $sql);
                    if (!$resultado) {
                        echo "Error de BD, no se pudo consultar la base de datos\n";
                        echo "Error MySQL:" . mysqli_error($linker);
                        exit;
                    }
                    $preguntas = [];
                    $i = 0;
                    while ($fila = mysqli_fetch_assoc($resultado)) {
                        $preguntas[$i] = array($fila['REDACCION'], $fila['DISTRACTOR_A'], $fila['DISTRACTOR_B'], $fila['DISTRACTOR_C'], $fila['RESPUESTA'], $fila['ID_PREGUNTA']);
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
                            echo "\t\t\t\t\t\t\n<div class='radio'><label class='radio-inline'><input check='true' type='radio' name='oprad$i' value='{\"a\":[$p[5],$answrs[0]]}' checked>".$p[$answrs[0]]."</label></div>";
                            echo "\t\t\t\t\t\t\n<div class='radio'><label class='radio-inline'><input type='radio' name='oprad$i' value='{\"a\":[$p[5],$answrs[1]]}'>".$p[$answrs[1]]."</label></div>";
                            echo "\t\t\t\t\t\t\n<div class='radio'><label class='radio-inline'><input type='radio' name='oprad$i' value='{\"a\":[$p[5],$answrs[2]]}'>".$p[$answrs[2]]."</label></div>";
                            echo "\t\t\t\t\t\t\n<div class='radio'><label class='radio-inline'><input type='radio' name='oprad$i' value='{\"a\":[$p[5],$answrs[3]]}'>".$p[$answrs[3]]."</label></div>";
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
        <script type="text/javascript">
            function cambiarTipo(){
                var priv = document.getElementById("tPriv");
                if(priv.checked){
                    $("#selecEsc").attr("disabled", true);
                    $("#opPrivada").removeClass("hide");
                    $("#opPrivada").attr("selected", true);
                }
                else{
                    $("#selecEsc").attr("disabled", false);
                    $("#opPrivada").addClass("hide");
                    $("#opPrivada").removeAttr("selected");
                }
            }
        </script>
    </body>
</html> 