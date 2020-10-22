<?php
    session_start();
    if (!isset($_SESSION['user'])) 
    {
        header("Location:index.php");
    }
    extract($_GET);
    $mody = isset($id);
?>
<html>
    <head>
        <?php 
            include ('include/header.php');
            require 'connect.php';
            if($mody){
                $sql       = "SELECT * FROM PREGUNTA WHERE ID_PREGUNTA = ".$id;
                $resultado = mysqli_query($linker, $sql);
                if (!$resultado) {
                    echo "Error de BD al obtener 'TEMAS', no se pudo consultar la base de datos\n";
                    echo "Error MySQL:" .  mysqli_error($linker);
                    exit;
                }
                $registro = mysqli_fetch_assoc($resultado);
                if($registro == null){
                    echo "No se pudo encontrar el registro ".$id;
                    exit;
                }
                else{
                    $redaccion = $registro['REDACCION'];
                    $respuesta = $registro['RESPUESTA'];
                    $d1 = $registro['DISTRACTOR_A'];
                    $d2 = $registro['DISTRACTOR_B'];
                    $d3 = $registro['DISTRACTOR_C'];
                    $tema = $registro['ID_TEMA'];
                }
                mysqli_free_result($resultado); 
            }
        ?>
        <title><?php echo $mody?"Modificar":"Agregar";?> pregunta</title>
        <script type="text/javascript" src="generic_wiris/core/display.js"></script>
        <script type="text/javascript" src="generic_wiris/wirisplugin-generic.js"></script>
        <script type="text/javascript" src="js/mathtextarea.js"></script>
    </head>
    <body>
        <?php include ('include/navbar.php');?>
        <div class="container">
            <h1><?php echo $mody?"Modificar":"Agregar";?> pregunta</h1>
            <br>
            <form id="formulario" class="form-horizontal" action="addPregunta.php<?php echo $mody?"?id=".$id:"";?>" method="POST">
                <div class="text-danger" id="msgDiv"></div><br>
                <div class="form-group">
                    <label class="control-label col-sm-12" for="pregunta" style="text-align: left">Pregunta</label>
                    <textarea id="textPregunta" name="pregunta" class="form-control col-sm-12"><?php echo $mody?$redaccion:"";?></textarea>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="respuesta" style="text-align: left">Respuesta</label>
                    <div class="col-sm-10 col-lg-8">
                        <input required type="text" class="form-control" id="respuesta" name="respuesta"<?php echo $mody?" value='".$respuesta."'":"";?>>
                    </div>
<!--                    <div class="col-sm-2">
                        <button class="btn btn-lg btn-primary btn-block btn-signin">imagen</button>
                    </div>-->
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="distractor1" style="text-align: left">Distractor 1</label>
                    <div class="col-sm-10 col-lg-8">
                        <input required type="text" class="form-control" id="distractor1" name="distractor1"<?php echo $mody?" value='".$d1."'":"";?>>
                    </div>
<!--                    <div class="col-sm-2">
                        <button class="btn btn-lg btn-primary btn-block btn-signin">imagen</button>
                    </div>-->
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="distractor2" style="text-align: left">Distractor 2</label>
                    <div class="col-sm-10 col-lg-8">
                        <input required type="text" class="form-control" id="distractor2" name="distractor2"<?php echo $mody?" value='".$d2."'":"";?>>
                    </div>
<!--                    <div class="col-sm-2">
                        <button class="btn btn-lg btn-primary btn-block btn-signin">imagen</button>
                    </div>-->
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="distractor3" style="text-align: left">Distractor 3</label>
                    <div class="col-sm-10 col-lg-8">
                        <input required type="text" class="form-control" id="distractor3" name="distractor3"<?php echo $mody?" value='".$d3."'":"";?>>
                    </div>
<!--                    <div class="col-sm-2">
                        <button class="btn btn-primary btn-block btn-signin">imagen</button>
                    </div>-->
                </div>
                <div class="form-group">
                    <label for="tema" class="col-sm-2" style="text-align: left">Tema</label>
                    <div class="col-sm-10 col-lg-8">
                        <select class="form-control" name="tema"><?php 
                            $sql       = 'SELECT * FROM TEMA';
                            $resultado = mysqli_query($linker, $sql);

                            if (!$resultado) {
                                echo "Error de BD, no se pudo consultar la base de datos\n";
                                echo "Error MySQL:" . mysql_error();
                                exit;
                            }
                            if($mody){
                                while ($fila = mysqli_fetch_assoc($resultado)) {
                                    echo "\n\t\t\t\t\t\t\t<option value='".$fila['ID_TEMA']."' ".($fila['ID_TEMA']==$tema?"selected":"").">" . $fila['NOM_TEMA'] . "</option>";
                                }
                            }
                            else{
                                while ($fila = mysqli_fetch_assoc($resultado)) {
                                    echo "\n\t\t\t\t\t\t\t<option value='".$fila['ID_TEMA']."'>" . $fila['NOM_TEMA'] . "</option>";
                                }
                            }
                            echo "\n";
                            mysqli_free_result($resultado);
                        ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">        
                    <div class="col-sm-offset-2 col-sm-8">
                        <button type="button" onclick="addP()" class="btn btn-lg btn-primary btn-block btn-signin"><?php echo $mody?"Modificar":"Agregar";?></button>
                    </div>
                </div>
            </form>
        </div>
        <script type="text/javascript">
            function addP(){

                var input = document.getElementById("textPregunta_iframe");
                var x = document.getElementById("textPregunta_iframe");
                var p = x.contentWindow.document.getElementsByTagName("body")[0].innerHTML.trim();
                var formulario = document.getElementById("formulario");
                if(formulario.reportValidity()){
                    if(p === "" || p === "<br>" || p === "&nbsp;" || fieldsAreNotFilled()){
                        $("#msgDiv").html("<b>Advertencia: </b>No puedes dejar campos vacios.");
                    }
                    else{
                        $("#msgDiv").html("");
                        $("#formulario").submit();
                    }
                }
            }
            function fieldsAreNotFilled(){
                var respuesta = $("#respuesta").val().trim() === "";
                var distractor1 = $("#distractor1").val().trim() === "";
                var distractor2 = $("#distractor2").val().trim() === "";
                var distractor3 = $("#distractor3").val().trim() === "";
                return (respuesta || distractor1 || distractor2 || distractor3);
            }
        </script>
    </body>
</html> 