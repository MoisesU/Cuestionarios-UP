<?php
    session_start();
    if (!isset($_SESSION['user'])){
        header("Location:index.php");
    }
    extract($_POST);
?>
<html>
    <head>
        <?php 
            include ('include/header.php');
            require 'connect.php';
            $mody = isset($result);
            $notData = true;
        ?>
        <title>Predicci&oacute;n</title>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">
          google.charts.load('current', {'packages':['line']});
        </script>
    </head>
    <body>
        <?php include ('include/navbar.php');?>
        <div class="container">
            <h1>Crear predicción</h1>
            <br>
            
            <form class="form-horizontal" action="prediccion.php" method="POST">
                <div class="form-group">
                    <label class="control-label col-sm-2" for="filtro" style="text-align: left">Filtrar por</label>
                        <div class="col-sm-3">
                            <select class="form-control" name="filtro" id="selFiltro" onchange="changeFilter()">
                                <option value="0" <?php echo ($mody && $filtro == 0?"selected":""); ?>>Carrera</option>
                                <option value="1" <?php echo ($mody && $filtro == 1?"selected":""); ?>>Escuela de procedencia</option>
                                <option value="2" <?php echo ($mody && $filtro == 2?"selected":""); ?>>Semestre</option>
                            </select>
                        </div>
                    <label class="control-label col-sm-2" for="result" style="text-align: left">Resultados</label>
                        <div class="col-sm-3">
                            <select class="form-control" name="result" id="selResult" onchange="showCustomInput()">
                                <option value="0" <?php echo ($mody && $result == 0?"selected":""); ?>>Periodos</option>
                                <option value="1" <?php echo ($mody && $result == 1?"selected":""); ?>>Encuestas</option>
                                <option value="2" <?php echo ($mody && $result == 2?"selected":""); ?>>Encuestas (Perzonalizado)</option>
                                <!--<option value="3" <?php echo ($mody && $result == 3?"selected":""); ?>>Periodos (Perzonalizado)</option>-->
                            </select>
                        </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2" for="sub" style="text-align: left" id="lblSub">Carrera</label>
                    <div class="col-sm-3">
                        <select class="form-control" name="sub" id="selSub">
                            <option>Ingenier&iacute;a Industrial</option>
                        </select>
                    </div>
                    <div class="col-sm-5">
                        <button type="submit" class="btn btn-lg btn-primary btn-block btn-signin">Calcular</button>
                    </div>
                </div>
                <div class="form-group hide" id="divCustom">
                    <label class="control-label col-sm-2" for="custom" style="text-align: left" id="lblCustom">Encuestas: </label>
                    <div class="col-sm-3">
                        <input required class="form-control" name="custom" id="custom" <?php if($mody&&isset($custom)){echo "value='$custom'";} ?>>
                    </div>
                </div>
            </form>
            <script type="text/javascript">
                var auxsel = <?php echo ($mody?"'$sub';\n":"-1;\n"); ?>
                var opc = [];
                changeFilter();
                showCustomInput();
                function changeFilter(){
                    var selected = $("#selFiltro").val();
                    if(selected === "0"){
                        opc = [
                        ["Ingeniería Industrial",
                        "Ingeniería en Informatica",
                        "Administración Industrial",
                        "Ingeniería en Transporte",
                        "Ciencias de la Informática"], 
                        ["II", "IN", "AI", "IT", "CI"]
                        ];
                        $("#lblSub").html("Carrera");
                    }
                    if(selected === "1"){
                        opc = [[
                        "Preparatoria o CCH (UNAM)",
                        "CECyT o CET (IPN)",
                        "CONALEP",
                        "DGTI",
                        "Colegio de Bachilleres",
                        "DGB",
                        "Preparatoria abierta",
                        "Escuela privada",
                        "Otras"
                        ],[0, 1, 2, 3, 4, 5, 6, 8, 7]];
                        $("#lblSub").html("Escuela");
                    }
                    if(selected === "2"){
                        opc = [[1, 2, 3, 4, 5, 6, 7, 8],[1, 2, 3, 4, 5, 6, 7, 8]];
                        $("#lblSub").html("Semestre");
                    }
                    $("#selSub").html(setOptions(opc[0], opc[1]));
                }
                function setOptions(options, values){
                    var res = "";
                    if(auxsel === -1){
                        for (var i = 0, max= options.length; i <max; i++) {
                            res += "<option value='"+values[i]+"'>"+options[i]+"</option>";
                        }
                    }
                    else{
                        for (var i = 0, max= options.length; i <max; i++) {
                            var opsel = auxsel == values[i]?" selected ":" ";
                            res += "<option"+opsel+"value='"+values[i]+"'>"+options[i]+"</option>";
                        }
                        auxsel = -1;
                    }
                    return res;
                }
                function showCustomInput(){
                    if($("#selResult").val()>1){
                        $("#divCustom").removeClass("hide");
                        //console.log("muestra");
                        $("#custom").attr("required", true);
                        if($("#selResult").val() === "2"){
                            $("#lblCustom").html("Encuestas");
                            $("#custom").attr("pattern","([1-9][0-9]*)(([ ]*([,]|[-])[ ]*([1-9][0-9]*))*[ ]*)");
                            $("#custom").attr("placeholder", "1, 2, 4-8, ...");

                            
                        }
                        else if($("#selResult").val() === "3"){
                            $("#lblCustom").html("Periodo");
                            $("#custom").attr("pattern","([1-2][0-9]{3}-[1-2])([ ]*)(([ ]*[,][ ]*)([1-2][0-9]{3}-[1-2]))*([ ]*)");
                            $("#custom").attr("placeholder", "2019-1, 2019-2, ...");

                        }
                    }
                    else{
                    //console.log("oculto");
                        $("#divCustom").addClass("hide");
                        $("#custom").attr("required", false);
                        $("#custom").val("");
                    }
                  }
            </script>
            <br>
            <br>
            <?php
                if($mody){
                    $select = "*";
                    $from = "ENCUESTADO";
                    $group = ";";
                    $where = "";
                    $colfilter = ["ENCUESTADO.CARRERA", "ENCUESTADO.ESCUELA", "ENCUESTADO.SEMESTRE"];
                    if($filtro == 1){
                        $select = "(CASE WHEN ESCUELA = 0 THEN 'Preparatoria o CCH (UNAM)' WHEN ESCUELA = 1 THEN 'CECyT o CET (IPN)'"
                                  ." WHEN ESCUELA = 2 THEN 'CONALEP' WHEN ESCUELA = 3 THEN 'DGTI'"
                                  ." WHEN ESCUELA = 4 THEN 'Colegio de Bachilleres' WHEN ESCUELA = 5 THEN 'DGB'"
                                  ." WHEN ESCUELA = 6 THEN 'Preparatoria abierta' "
                                  ." WHEN ESCUELA = 8 THEN 'Escuela Privada' "
                                  ."ELSE 'Otra' END) AS 'ESCUELA DE PROCEDENCIA', "
                                  ."COUNT(ENCUESTADO.ID_ENCUESTADO) AS 'NUM ALUMNOS', ";
                        $group = "GROUP BY ENCUESTADO.ESCUELA;";
                    }
                    if($filtro == 2){
                        $select = "SEMESTRE, COUNT(ENCUESTADO.ID_ENCUESTADO) AS 'NUM ALUMNOS',";
                        $group = "GROUP BY ENCUESTADO.SEMESTRE;";
                    }
                    if($filtro == 0){
                        $select = "(CASE WHEN CARRERA = 'II' THEN 'Ingeniería Industrial'"
                                    ." WHEN CARRERA = 'IN' THEN 'Ingeniería en Informatica'"
                                    ." WHEN CARRERA = 'AI' THEN 'Administración Industrial'"
                                    ." WHEN CARRERA = 'IT' THEN 'Ingeniería en Transporte'"
                                    ." WHEN CARRERA = 'CI' THEN 'Ciencias de la Informática' END) AS 'CARRERA', "
                                    ."COUNT(ENCUESTADO.ID_ENCUESTADO) AS 'ALUMNOS', ";
                        $group = "GROUP BY ENCUESTADO.CARRERA;";
                    }
                    if($result == 1 || $result == 2){
                        $sql = "SELECT * FROM ENCUESTA ORDER BY ID_ENCUESTA DESC LIMIT 4";
                        $encuestas = ($result == 1?getColumn($sql, "ID_ENCUESTA", $linker):getIDEncuestasCustom($custom));
                        if($result == 2 && !$encuestas){
                            echo "<h3><center>El intervalo de preguntas seleccionado no es v&aacute;lido</center></h3><br><br>";
                            echo "</div></body></html>";
                            exit;
                        }
                        if($result == 2){
                            $allEnc = getColumn("SELECT ID_ENCUESTA AS ID FROM ENCUESTA", "ID", $linker);
                            $encuestas = array_values(array_unique($encuestas));
                            $encuestas = encuestasExist($encuestas, $allEnc);
                            if(!$encuestas){
                                echo "<h3><center>La encuestas que seleccion&oacute; no se encuentran registradas</h3></center>";
                                echo "</div></body></html>";
                                exit;
                            }
                            if(count($encuestas)<3){
                                echo "<h3><center>Debes seleccionar al menos 3 encuestas v&aacute;</center></h3><br><br>";
                                echo "</div></body></html>";
                                exit;
                            }
                            $encuestas = toInteger($encuestas);
                        }
                        if (count($encuestas)==0){
                            echo "<h3><center>No hay encuestas registradas para realizar la predicci&oacute;n</center></h3><br><br>";
                            echo "<script>console.log(\"Error en la consulta inicial\");</script>\n";
                            echo "</div></body></html>";
                            exit;
                        }
                        $maxEnc = count($encuestas);
                        for ($i = $maxEnc - 1; $i >= 0; $i--) {
                            $select = $select."ROUND(AVG(IF(A.ID_ENCUESTA = ".(int)$encuestas[$i].", ENCUESTADO.ACIERTOS, NULL)),0) AS 'E$encuestas[$i]',";
                        }
                        $select[strlen($select)-1]=" ";
                        $from = "ENCUESTADO INNER JOIN ".($result == 1?"(SELECT * FROM ENCUESTA ORDER BY ID_ENCUESTA DESC LIMIT 4)":"ENCUESTA")." A ON ENCUESTADO.ID_ENCUESTA =  A.ID_ENCUESTA";
                        $where = "WHERE $colfilter[$filtro] = ".($filtro == 0?"'$sub'":(int)$sub)." ";
                    }
                    if($result == 0 || $result == 3){
                        $periodos = ($result == 0?getPeriodos():getPeriodosCustom($custom));
                        $numP = count($periodos);
                        for($i=0; $i < $numP-3; $i++){
                            $fecha = getFechas($periodos[$i]);
                            $select = $select."ROUND(AVG(IF(ENCUESTA.FECHA_FIN BETWEEN '".$fecha[0]."' AND '".$fecha[1]."', ENCUESTADO.ACIERTOS, NULL)),0) AS '".$periodos[$i]."',";
                        }
                        $select[strlen($select)-1]=" ";
                        $fecha = getFechas($periodos[0]);
                        $where = "WHERE ((ENCUESTA.FECHA_FIN BETWEEN '".$fecha[0]."' AND '".$fecha[1]."') ";
                        for ($i=1; $i < $numP-3; $i++) { 
                            $fecha = getFechas($periodos[$i]);
                            $where = $where."OR (ENCUESTA.FECHA_FIN BETWEEN '".$fecha[0]."' AND '".$fecha[1]."') ";
                        }
                        $from = "ENCUESTADO INNER JOIN ENCUESTA ON ENCUESTADO.ID_ENCUESTA =  ENCUESTA.ID_ENCUESTA";
                        $where = $where.") AND ($colfilter[$filtro] = ".($filtro == 0?"'$sub'":(int)$sub).") ";
                    }
                    $sql = "SELECT $select FROM $from $where $group";
                    $q = mysqli_query($linker, $sql);
                    if (!$q) {
                        echo "<h3><center>Error al ejecutar la consulta para la predicci&oacute;n</h3></center>";
                        echo "<script>console.log(\"Consulta = $sql Error: ".mysqli_error($linker)."\");</script>\n";
                        echo "</div></body></html>";
                        exit;
                    }
                    $fila = mysqli_fetch_array($q, MYSQLI_NUM);
                    mysqli_free_result($q);
                    if(!is_null($fila)){
                        $table = [[],[]];
                        $notData = true;
                        for ($i=2; $i < count($fila); $i++) { 
                            $notData = false;
                            $table[0][$i-2] = $i-1;
                            if($fila[$i] == NULL){
                                $notData = true;
                                break;
                            }
                            $table[1][$i-2] = $fila[$i];
                        }
                        if($notData){
                            echo "<h3><center>No hay datos suficientes para calcular la predicci&oacute;n</h3></center><br><br>";
                        }
                        else{
                            $x0 = count($table[0]);
                            $reg = regrex($table);
                            $y1 = round($reg[0] * ($x0+1) + $reg[1], 2);
                            $y2 = round($reg[0] * ($x0+2) + $reg[1], 2);
                            $y3 = round($reg[0] * ($x0+3) + $reg[1], 2);
                            if($result == 1 || $result == 2){
                                $lastEnc = (int)$encuestas[0];
                                $select = $select.", $y1 as E".($lastEnc+1).", $y2 as E".($lastEnc+2).", $y3 as E".($lastEnc+3)." ";
                            }
                            else{
                                $select = $select.", $y1 as '".$periodos[3]."', $y2 as '".$periodos[4]."', $y3 as '".$periodos[5]."' ";
                            }
                        }
                    }
                    $sql = "SELECT $select FROM $from $where $group";
                    //echo($sql);
                    include "include/tableRep.php";

                }
            ?>
            <br>
            <div class="table-responsive text-center"><div class="graphic" id="curve_chart"></div></div>
        </div>
        <script type="text/javascript">
            <?php echo (!$mody || $notData ?"":"google.charts.setOnLoadCallback(drawChart);"); ?>
            function drawChart() {
            var table = [<?php
                if(!$notData){
                    if($result == 1 || $result == 2){
                        echo "['Encuestas', 'Aciertos Registrados', 'Aciertos Esperados'],";
                        for ($i=$x0-1; $i>=0; $i--) {
                            $ny = round($reg[0] * $table[0][$x0-1-$i] + $reg[1], 2);
                            echo "['E".$encuestas[$i]."', ".$table[1][$x0-1-$i].", $ny],";
                        }

                        echo "['E".($lastEnc+1)."', ".$y1.", ".$y1."],";
                        echo "['E".($lastEnc+2)."', ".$y2.", ".$y2."],";
                        echo "['E".($lastEnc+3)."', ".$y3.", ".$y3."]";
                    }
                    else{
                        echo "['Periodos', 'Aciertos Registrados', 'Aciertos Esperados'],";
                        for ($i=0; $i<$x0; $i++) {
                            echo "['".$periodos[$i]."', ".$table[1][$i]."],";
                        }
                        echo "['".$periodos[3]."', ".$y1."],";
                        echo "['".$periodos[4]."', ".$y2."],";
                        echo "['".$periodos[5]."', ".$y3."]";
                    }
                }
            ?>];
            var data = google.visualization.arrayToDataTable(table);
            <?php
                if($mody || !$notData){
                    $subtitle = " ";
                    switch ($filtro) {
                        case 0:
                            $subtitle = $subtitle."De la carrera de ";
                            switch ($sub) {
                                case 'II':
                                    $subtitle = $subtitle."Ingeniería Industrial ";
                                    break;
                                case 'IN':
                                    $subtitle = $subtitle."Ingeniería Informática ";
                                    break;
                                case 'CI':
                                    $subtitle = $subtitle."Ciencias de la Informática ";
                                    break;
                                case 'IT':
                                    $subtitle = $subtitle."Ingeniería en Transporte ";
                                    break;
                                case 'AI':
                                    $subtitle = $subtitle."Administración Industrial ";
                                    break;
                            }
                            break;
                        case 1:
                           $subtitle = $subtitle."Procedentes de ";
                            switch ($sub) {
                                case 0:
                                    $subtitle = $subtitle."Preparatoria o CCH ";
                                    break;
                                case 1:
                                    $subtitle = $subtitle."CECyT o CET ";
                                    break;
                                case 2:
                                    $subtitle = $subtitle."CONALEP ";
                                    break;
                                case 3:
                                    $subtitle = $subtitle."DGTI ";
                                    break;
                                case 4:
                                    $subtitle = $subtitle."Colegio de Bachilleres ";
                                    break;
                                case 5:
                                    $subtitle = $subtitle."DGB ";
                                    break;
                                case 6:
                                    $subtitle = $subtitle."Preparatoria abierta ";
                                    break;
                                case 7:
                                    $subtitle = $subtitle."otras preparatorias ";
                                    break;
                                case 8:
                                    $subtitle = $subtitle."Escuela privada ";
                                    break;
                            }
                            break;
                        case 2:
                            $subtitle = $subtitle."De ".$sub."o semestre ";
                            break;
                    }
                    switch ($result) {
                        case 0:
                            $subtitle = $subtitle."por periodos";
                            break;
                        
                        default:
                            $subtitle = $subtitle."por encuestas";
                            break;
                    }
                }
            ?>

            var options = {
                chart: {
                  title: 'Predicción de aciertos promedio de los alumnos',
                  subtitle: '<?php echo(isset($subtitle)?$subtitle:"..."); ?>',
                  legend: { position: 'bottom' }
                },
                width: 720,
                height: 480
            };

            var chart = new google.charts.Line(document.getElementById('curve_chart'));
            chart.draw(data, google.charts.Line.convertOptions(options));
          }
        </script>
        <?php
            function regrex($values){
                $x = array_sum($values[0]);
                $y = array_sum($values[1]);
                $xy = 0;
                $x2 = 0;
                $aux1 = [];
                $aux2 = [];
                $n = count($values[0]);
                for ($i=0; $i < $n; $i++) { 
                    $aux1[$i]=$x[$i]*$y[$i];
                    $aux2[$i]=$x[$i]*$x[$i];
                }
                $xy = array_sum($aux1);
                $x2 = array_sum($aux2);
                $a = (($n*$xy)-($x*$y))/(($n*$x2)-($x*$x));
                $b = ($n*$x2*$y-$x*$xy)/($n*$x2-$x*$x);
                return array($a, $b);
            }

            function getFechas($str){
                    $res = [];
                    $anio = substr($str, 0, 4);
                    if($str[5]==1){
                            $res[0] = ((int)$anio-1)."-07-10";
                            $res[1] = $anio."-01-09";
                    }
                    else{
                            $res[0] = $anio."-01-10";
                            $res[1] = $anio."-07-09";
                    }
                    return $res;
            }

            function getPeriodos(){
                $fecha_actual = strtotime(date("d-m-Y",time()));
                $fecha_1 = strtotime("10-01-".((int)date("Y")-1));
                $fecha_2 = strtotime("09-07-".date("Y"));
                $anio = (int)date("Y");
                $mes = (int)date("n");
                $per = 1;
                $ant = 2;
                if($fecha_actual>=$fecha_1 && $fecha_actual<=$fecha_2){
                    $per = 2;
                    $ant = 1;
                }
                else{
                    $anio++;
                }
                return array(
                    ($anio-($per==2?1:1))."-".$per, 
                    ($anio-($per==2?0:1))."-".$ant, 
                    $anio."-".$per, 
                    ($anio+($per==2?1:0))."-".$ant,
                    ($anio+($per==2?1:1))."-".$per,
                    ($anio+($per==2?2:1))."-".$ant
                );
            }

            function getPeriodosCustom($str){
                $res = [];
                $num = "";
                $aux = 0;
                for($i=0;$i<strlen($str);$i++){
                    //echo "\n".$str[$i];
                    if(is_numeric($str[$i])||$str[$i] === "-"){
                        $num = $num."".$str[$i];
                    }
                    else{
                        if($str[$i] === ","){
                            $res[$aux] = $num;
                            $num = "";
                            $aux++;
                        }
                    }
                }
                $res[$aux] = $num;
                return $res;
            }

            function getIDEncuestasCustom($str){
                $res = [];
                $num = "";
                $aux = 0;
                $isRange = false;
                for($i=0;$i<strlen($str);$i++){
                    //echo "\n".$str[$i];
                    if(is_numeric($str[$i])){
                        $num = $num."".$str[$i];
                    }
                    else{
                        if($str[$i] === ","){
                            $res[$aux] = $num;
                            $num = "";
                            $aux++;
                        }
                        if($str[$i] === "-"){
                            $res[$aux] = $num;
                            $num = "";
                            $aux++;
                            $res[$aux] = "*";
                            $aux++;
                            $num = "";
                            $isRange = true;
                        }
                    }
                }
                $res[$aux] = $num;
                if($isRange){
                    $res = convertRange($res);
                }
                return $res;
            }
            
            function convertRange($arr){
                $res = [];
                $i=0;
                for($a =0; $a<count($arr); $a++){
                    if($arr[$a] == "*"){
                        if((int)$arr[$a+1] > (int)$arr[$a-1]){
                            $fields = abs((int)$arr[$a+1] - (int)$arr[$a-1])-1;
                            $menor = (int)$arr[$a+1]<(int)$arr[$a-1]?(int)$arr[$a+1]:(int)$arr[$a-1];
                            for($x =1; $x<= $fields; $x++){
                                $res[$i] = ($menor+$x)."";
                                $i++;
                            }
                            $a++;
                        }
                        else{
                            return false;
                        }
                    }
                    $res[$i] = $arr[$a];
                    $i++;
                }
                return $res;
            }
            function toInteger($arrstr){
                $res = [];
                $max = count($arrstr);
                for ($i=0; $i < $max; $i++) { 
                    $res[$i] = (int)$arrstr[$i];
                }
                return burbuja($res, $max);;
            }
            function burbuja($A,$n)
            {
                for($i=1;$i<$n;$i++)
                {
                        for($j=0;$j<$n-$i;$j++)
                        {
                                if($A[$j]<$A[$j+1])
                                {$k=$A[$j+1]; $A[$j+1]=$A[$j]; $A[$j]=$k;}
                        }
                }
         
              return $A;
            }
            function encuestasExist($arr, $comp){
                    $res = [];
                    $x = 0;
                    for ($i=0; $i < count($arr) ; $i++) { 
                        if(in_array($arr[$i], $comp)){
                            $res[$x] = $arr[$i];
                            $x++;
                        }
                    }
                    return $res;
                }
        ?>
    </body>
</html> 