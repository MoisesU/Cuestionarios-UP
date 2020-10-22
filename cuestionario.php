<?php
    session_start();
    if (!$_SESSION['user']) 
    {
        header("Location:index.php");
    }
    extract($_GET);
    $mody = isset($id);
?>
<html>
    <head>
        <?php include ('include/header.php');
              require 'connect.php';
              
            $maxPreguntas = getSimpleValue("SELECT COUNT(*) AS 'NUM_P' FROM PREGUNTA",'NUM_P', $linker);
            if($mody){
                $sql       = "SELECT * FROM ENCUESTA WHERE ID_ENCUESTA = '".$id."'";
                $resultado = mysqli_query($linker, $sql);
                if (!$resultado) {
                    echo "Error de BD al obtener 'ENCUESTAS', no se pudo consultar la base de datos\n";
                    echo "Error MySQL:" .  mysqli_error($linker);
                    exit;
                }
                $registro = mysqli_fetch_assoc($resultado);
                if($registro == null){
                    echo "No se pudo encontrar el registro ".$id;
                    exit;
                }
                else{
                    $descrip = $registro['DESCRIPCION'];
                    $fechaIni = $registro['FECHA_INI'];
                    $fechaFin = $registro['FECHA_FIN'];
                    $muestra = $registro['MUESTRA'];
                    $numPreguntas = $registro['NUM_PREGUNTAS'];
                    $finalizar = $registro['CUBRIR_MUESTRA'];
                }
                mysqli_free_result($resultado); 
            }
        ?>
        <script src='js/searcher.js' type='text/javascript'></script>
        <title><?php echo $mody?"Modificar":"Agregar";?> encuesta</title>
    </head>
    <body>
        <?php include ('include/navbar.php');
        ?>
        <div class="container">
            <h1><?php echo $mody?"Modificar":"Agregar";?> encuesta</h1>
            <h2>Datos generales</h2>
            <br>
            <form class="form-horizontal" action="addCuestionario.php<?php echo $mody?"?id=".$id:"";?>" method="POST" id="cuestform" onload="initInputs()">
                <div class="form-group">
                    <label class="control-label col-sm-12" for="descrip" style="text-align: left">Descripci&oacute;n</label>
                    <textarea required name="descrip" class="form-control col-sm-12"><?php echo $mody?$descrip:"";?></textarea>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="fechaIni" style="text-align: left">Fecha inicio</label>
                    <div class="col-sm-2">
                        <input required type="date" class="form-control" name="fechaIni" id="fechaIni" value="<?php echo $mody?$fechaIni:"";?>">
                    </div>
                    <label class="control-label col-sm-2" for="fechaFin" style="text-align: left">Fecha de término</label>
                    <div class="col-sm-2">
                        <input required type="date" class="form-control" name="fechaFin" id="fechaFin" value="<?php echo $mody?$fechaFin:"";?>">
                    </div>
                    <label class="control-label col-sm-2" for="muestra" style="text-align: left">Muestra</label>
                    <div class="col-sm-2">
                        <input required type="number" class="form-control" name="muestra" min="5" max="1000" value="<?php echo $mody?$muestra:"";?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3" for="numPreguntas" style="text-align: left">Preguntas por encuesta</label>
                    <div class="col-sm-3">
                        <input required type="number" value="<?php echo $mody?$numPreguntas:"";?>" class="form-control" name="numPreguntas" id="numPreguntas" min="3" max="<?php echo $maxPreguntas?>">
                    </div>
                    <label class="col-sm-6"><input type="checkbox" value="1" name="finalizar" <?php echo $mody && $finalizar==1?"checked":"";?>>&nbsp;&nbsp;Finalizar hasta cubrir con la muestra</label>
                </div>
                <input id="selecP" name="selecP" class="hide">
            </form>
            <br><br>
            <h2>Selección de preguntas</h2>
            <div class="text-danger" id="msgDiv"></div><br>
                <?php
                    $sql = "SELECT ID_PREGUNTA, REDACCION, UNIDAD_DE_APRENDIZAJE.ID_UNIDAD FROM PREGUNTA 
                            INNER JOIN TEMA ON TEMA.ID_TEMA=PREGUNTA.ID_TEMA 
                            INNER JOIN UNIDAD_DE_APRENDIZAJE ON UNIDAD_DE_APRENDIZAJE.ID_UNIDAD=TEMA.ID_UNIDAD";
                    if($mody){
                        $preguntas = getColumn("SELECT ID_PREGUNTA FROM ENCUESTA_PREGUNTA WHERE ID_ENCUESTA = $id", "ID_PREGUNTA", $linker);
                    }
                    $resultado = mysqli_query($linker, $sql);
                    if (!$resultado) {
                        echo "Error de BD, no se pudo consultar la base de datos\n";
                        echo "Error MySQL:" . mysqli_error($linker);
                        exit;
                    }
                    
                    if (mysqli_num_rows($resultado)!=0){
                        include 'include/filters.php';
                        $unidades = [];
                        $i = 0;
                        echo "\n\t\t\t<table class='table table-hover'>\n\t\t\t\t<thead>\n\t\t\t\t\t"
                            . "<tr>\n\t\t\t\t\t\t<th id='selec'>Selecci&oacute;n</th>\n\t\t\t\t\t\t<th>"
                            . "Pregunta</th>\n\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t</thead>\n\t\t\t\t\t\t<tbody id='preguntas'>";
                        if($mody){
                            while ($fila = mysqli_fetch_assoc($resultado)) {
                                $unidad[$i] = $fila['ID_UNIDAD'];
                                echo "\n\t\t\t\t\t<tr>"
                                ."\n\t\t\t\t\t\t<td><input onchange='count($i)' name='pregunta' type='checkbox' value='".$fila['ID_PREGUNTA']."'".(in_array($fila['ID_PREGUNTA'], $preguntas)?" checked":"")."></input></th>"
                                ."\n\t\t\t\t\t\t<td>".$fila['REDACCION']."</th>"
                                ."\n\t\t\t\t\t</tr>";
                                $i++;
                            }
                        }
                        else{
                            while ($fila = mysqli_fetch_assoc($resultado)) {
                                $unidad[$i] = $fila['ID_UNIDAD'];
                                echo "\n\t\t\t\t\t<tr>"
                                ."\n\t\t\t\t\t\t<td class='selCol'><input onchange='count($i)' name='pregunta' type='checkbox' value='".$fila['ID_PREGUNTA']."'></input></td>"
                                ."\n\t\t\t\t\t\t<td class='overflow-str'>".$fila['REDACCION']."</td>"
                                ."\n\t\t\t\t\t</tr>";
                                $i++;
                            }
                        }
                        echo "\n\t\t\t\t</tbody>\n\t\t\t</table>";
                        echo "<script>var idunidades = ". json_encode($unidad).";</script>";
                    }
                    else{
                        echo "\n<h3><center>No hay preguntas registradas</center></h3>";
                    }
                ?>
            
                <script type="text/javascript">
                    var preguntas = document.getElementsByName("pregunta"), numSelec = 0;
                    var formulario = document.getElementById("cuestform");
                    initInputs();
                    
                    
                    function getSelectedQuestions(){
                        var selection = new Array();
                        var aux = 0;
                        for (var i = 0; i < preguntas.length; i++) {
                            if(preguntas[i].checked){
                                selection[aux] = parseInt(preguntas[i].value);
                                aux++;
                            }
                        }
                        return selection;
                    }
                    function validateQuestions(){
                        if(numSelec>=formulario["numPreguntas"].value){
                            $("#msgDiv").html("");
                            return true;
                        }
                        else{
                            $("#msgDiv").html("No has seleccionado suficientes preguntas, restan "+(formulario["numPreguntas"].value-numSelec)+" por seleccionar");
                        }
                    }
                    function count(index){
                        if(preguntas[index].checked){
                            numSelec++;
                        }
                        else{
                            numSelec--;
                        }
                        $("#selec").text("Selección ("+numSelec+")");
                    }
                    function initInputs(){
                        var f = new Date();
                        var mes = f.getMonth()+1;
                        var today = f.getFullYear() + "-" + (mes<10?"0":"")+ mes + "-" + ((f.getDate()<10?"0":"")+ f.getDate());
                        $("#fechaIni").val(today);
                        $("#fechaIni").attr("min",today);
                        f.setDate(f.getDate() + 1);
                        var tomorrow = f.getFullYear() + "-" + (mes<10?"0":"")+ mes + "-" + ((f.getDate()<10?"0":"")+ f.getDate());
                        f.setDate(f.getDate() + 14);
                        mes = f.getMonth()+1;
                        $("#fechaFin").val(f.getFullYear() + "-" + (mes<10?"0":"") + mes + "-" + ((f.getDate()<10?"0":"")+ f.getDate()));
                        $("#fechaFin").attr("min",tomorrow);
                        for (var i = 0; i < preguntas.length; i++) {
                                if(preguntas[i].checked){
                                    numSelec++;
                            }
                        }
                        <?php 
                            echo $mody?"$('#fechaFin').val('$fechaFin');":"";
                            echo $mody?"$('#fechaIni').val('$fechaIni');":"";
                        ?>
                    }
                    function addC(){
                        if(formulario.reportValidity() && validateQuestions()){
                            $("#selecP").val(JSON.stringify(getSelectedQuestions()));
                            $("#cuestform").submit();
                       }
                    }
                    function serialize(arr)
                    {
                        var res = 'a:'+arr.length+':{';
                        for(i=0; i<arr.length; i++){
                            res += 'i:'+i+';i:'+arr[i]+';';
                        }
                        res += '}';
                        return res;
                    }
                    function cleanAndSearch(){
                        $("#setAll").attr("selected", true);
                        search();
                    }
                </script>
                <br>
                <button type="button" onclick="addC()" class="btn btn-lg btn-primary btn-block btn-signin"><?php echo $mody?"Modificar":"Agregar";?></button>
        </div>
    </body>
</html>

