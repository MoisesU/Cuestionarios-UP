<?php
    session_start();
    if (!isset($_SESSION['user'])) 
    {
        header("Location:index.php");
    }
    extract($_GET);
    $filter = isset($unidad);
?>
<html>
    <head>
        <?php include ('include/header.php');
              require 'connect.php';
              
        ?>
        <title>Ver preguntas</title>
    </head>
    <body>
        <?php include ('include/navbar.php');?>
        
        <div class="container">
            <h1>Preguntas</h1>
            <br>
            
            <form class="form-horizontal" action="showPreguntas.php" method="POST" id="filterForm">
                <div class="form-group">
                    <label class="control-label col-sm-3" for="unidad" style="text-align: left">Unidad de aprendizaje</label>
                    <div class="col-sm-3">
                        <select class="form-control" name="unidad" id="unidad" onchange="setTemas()">
                        </select>
                    </div>
                    <label class="control-label col-sm-2" for="buscar" style="text-align: left">Buscar: </label>
                    
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3" for="tema" style="text-align: left">Tema</label>
                    <div class="col-sm-3">
                        <select class="form-control" name="tema" id="tema" onchange="setTarget()">
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" name="buscar" id="buscar" onkeyup="search()">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <button type="submit" class="btn btn-lg btn-primary btn-block btn-signin" id="filtrar">Filtrar</button>
                    </div>
                    <div class="col-sm-6">
                        <button type="button" onclick="window.location='pregunta.php'" class="btn btn-lg btn-primary btn-block btn-signin">Agregar pregunta</button>
                    </div>
                </div>
            </form>
            <script type="text/javascript"><?php   
                    include 'uasytemasvars.php';
                    echo "\n\t\t\t\tvar temas = ".json_encode($temas).";";
                    echo "\n\t\t\t\tvar unidades = ".json_encode($uas).";\n";
                ?>
                var filtrar = document.getElementById("filtrar");
                var temaBox = document.getElementById("tema");
                var unidadBox = document.getElementById("unidad");
                var filterForm = document.getElementById("filterForm");
                var presset = <?php 
                    if($filter){
                        echo "['$unidad','$tema'];";
                    }
                    else{
                        echo "null;";
                    }
                ?>
                
                unidadBox.innerHTML = "<option value='-1 all'>TODAS</option>\n";
                if(presset !== null){
                    for (var i = 0; i < unidades.length; i++) {
                    unidadBox.innerHTML+="<option value='"+i+" "+unidades[i][0]+"'"+(presset[0]===unidades[i][0]?" selected":"")+">"+unidades[i][1]+"</option>\n";
                    }
                }
                else{
                    for (var i = 0; i < unidades.length; i++) {
                        unidadBox.innerHTML+="<option value='"+i+" "+unidades[i][0]+"'>"+unidades[i][1]+"</option>\n";
                    }
                }
                setTemas();
		function setTemas(){
                    setTarget();
                    var index = parseInt(getValues(unidadBox.value)[0]);
                    if(index === -1){
                        temaBox.innerHTML = "<option value='all'<?php if($filter){echo ($unidad=="all"?" selected":"");}?>>TODOS</option>";
                        temaBox.disabled = false;
                    }
                    else if(temas[index] === undefined){
                        temaBox.innerHTML = "<option value='none'<?php if($filter){echo ($unidad=="none"?" selected":"");}?>>No hay temas</option>";
                        temaBox.disabled = true;
                    }
                    else{
                        temaBox.innerHTML = "<option value='all'>TODOS</option>";
                        temaBox.disabled = false;
                        if(presset !== null){
                            for (var i = 0; i < temas[index].length; i++) {
                                temaBox.innerHTML +="<option value='"+temas[index][i][0]+"'"+(presset[1]===temas[index][i][0]?" selected":"")+">"+temas[index][i][1]+"</option>";
                            }
                        }
                        else{
                            for (var i = 0; i < temas[index].length; i++) {
                                temaBox.innerHTML +="<option value='"+temas[index][i][0]+"'>"+temas[index][i][1]+"</option>";
                            }
                        }
                    }
		}
                function getValues(chars){
                    var values = ["", ""], x=0;
                    for (var i = 0; i < chars.length; i++) {
                        if(chars.charAt(i)===' ')
                            x=1;
                        else
                            values[x]+=chars.charAt(i);
                    } 
                    return values;
                }
                function setTarget(){
                    var target = "showPreguntas.php";
                    if(unidadBox.value === "-1 all"){
                        target = "showPreguntas.php";
                    }else{
                        target = "showPreguntas.php?unidad="+getValues(unidadBox.value)[1]+"&tema="+temaBox.value;
                    }
                    filterForm.setAttribute("action", target);
                }
            </script>
            <br>
            <br>
            <?php 
                if(isset ($unidad)){
                    if($tema == "all" || $tema == "none"){
                        $sql = "SELECT ID_PREGUNTA, REDACCION, PREGUNTA.ID_TEMA FROM PREGUNTA 
                            INNER JOIN TEMA ON TEMA.ID_TEMA=PREGUNTA.ID_TEMA 
                            INNER JOIN UNIDAD_DE_APRENDIZAJE ON UNIDAD_DE_APRENDIZAJE.ID_UNIDAD=TEMA.ID_UNIDAD
                            WHERE UNIDAD_DE_APRENDIZAJE.ID_UNIDAD='".$unidad."'";
                    }
                    else{
                        $sql = "SELECT * FROM PREGUNTA WHERE ID_TEMA = '".$tema."'";
                    }
                }
                else{
                    $sql = "SELECT * FROM PREGUNTA";
                }
                $resultado = mysqli_query($linker, $sql);
                
                if (!$resultado) {
                    echo "Error de BD, no se pudo consultar la base de datos\n";
                    echo "Error MySQL:" . mysqli_error($linker);
                    exit;
                }
                if (mysqli_num_rows($resultado)!=0){
                    echo "\n\t\t\t<div class='table-responsive'><table class='table table-hover'>\n\t\t\t\t<thead>\n\t\t\t\t\t<tr>\n\t\t\t\t\t\t<th>ID</th>\n\t\t\t\t\t\t<th>"
                        . "Pregunta</th>\n\t\t\t\t\t\t<th>Opciones</th>\n\t\t\t\t\t\t</tr>\n\t\t\t\t\t\t</thead>\n\t\t\t\t\t\t<tbody id='preguntas'>";
                    while ($fila = mysqli_fetch_assoc($resultado)) {
                        $id_tema = $fila['ID_TEMA'];
                        echo "\n\t\t\t\t\t<tr>"
                        ."\n\t\t\t\t\t\t<td>".$fila['ID_PREGUNTA']."</td>"
                        ."\n\t\t\t\t\t\t<td class='overflow-str'>".$fila['REDACCION']."</td>"
                        ."\n\t\t\t\t\t\t<td class='text-center'><a href='pregunta.php?id=".$fila['ID_PREGUNTA']."'><span class='glyphicon glyphicon-pencil'></span></a></td>"
                        ."\n\t\t\t\t\t</tr>";
                    }
                    echo "\n\t\t\t\t</tbody>\n\t\t\t</table></div>";
                    echo "\n\t\t\t<script src='js/searcher.js' type='text/javascript'></script>";
                }
                else{
                    echo "\n<h3><center>No hay preguntas registradas</center></h3>";
                }
                mysqli_free_result($resultado);
            ?>
        </div>
    </body>
</html> 