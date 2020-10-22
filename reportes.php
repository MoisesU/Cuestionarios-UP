<?php
    session_start();
    if (!isset($_SESSION['user'])) 
    {
        header("Location:index.php");
    }
    extract($_POST);
?>
<html>
    <head>
        <?php include ('include/header.php');
              require 'connect.php';
              $mody = isset($result);
        ?>
        <title>Reporte</title>
    </head>
    <body>
        <?php include ('include/navbar.php');?>
        
        <div class="container">
            <h1>Crear reportes</h1>
            <br>
            
            <form class="form-horizontal"action="reportes.php" method="POST">
                <div class="form-group">
                    <label class="control-label col-sm-offset-1 col-sm-2" for="consulta" style="text-align: left">Consultar</label>
                        <div class="col-sm-3">
                            <select name="consulta" class="form-control">
                                <option value="0" <?php echo ($mody && $consulta == 0?"selected":""); ?>>Escuelas</option>
                                <option value="1" <?php echo ($mody && $consulta == 1?"selected":""); ?>>Semestres</option>
                                <option value="2" <?php echo ($mody && $consulta == 2?"selected":""); ?>>Carreras</option>
                                <option value="3" <?php echo ($mody && $consulta == 3?"selected":""); ?>>Edades</option>
                                <option value="4" <?php echo ($mody && $consulta == 4?"selected":""); ?>>G&eacute;neros</option>
                                <option value="5" <?php echo ($mody && $consulta == 5?"selected":""); ?>>Tipo de escuela</option>
                            </select>
                        </div>
                    <label class="control-label col-sm-1" for="por" style="text-align: left">Por</label>
                        <div class="col-sm-4">
                            <select id="selComo" class="form-control" name="por" onchange="setPattern()">
                                <option value="1" <?php echo ($mody && $por == 1?"selected":""); ?>>Encuestas</option>
                                <option value="0" <?php echo ($mody && $por == 0?"selected":""); ?>>Periodos</option>
                            </select>
                        </div>
                </div>
                <div class="form-group">
                    <label id="lblRes" class="control-label col-sm-offset-1 col-sm-2" for="result" style="text-align: left">Encuestas</label>
                        <div class="col-sm-3">
                            <input id="inRes" required type="text" class="form-control" name="result" <?php if($mody){echo "value='$result'";} ?>>
                        </div>
                    <label class="control-label col-sm-1" for="como" style="text-align: left">Ver</label>
                        <div class="col-sm-2">
                            <select class="form-control" name="como">
                                <option value="0" <?php echo ($mody && $como == 0?"selected":""); ?>>Aciertos</option>
                                <option value="1" <?php echo ($mody && $como == 1?"selected":""); ?>>Calificación</option>
                            </select>
                        </div>
                    <div class="col-sm-2">
                        <button type="submit" class="btn btn-lg btn-primary btn-block btn-signin">Consultar</button>
                    </div>
                </div>
            </form>
            <br>
            <br>
            <script type="text/javascript">
                setPattern();
                function setPattern(){
                    if($("#selComo").val() === "1"){
                        $("#lblRes").html("Encuestas");
                        $("#inRes").attr("pattern","([1-9][0-9]*)(([ ]*([,]|[-])[ ]*([1-9][0-9]*))*[ ]*)");
                        $("#inRes").attr("placeholder", "1, 2, 4-8, ...");
                    }
                    else{
                        $("#lblRes").html("Periodo");
                        $("#inRes").attr("pattern","([1-2][0-9]{3}-[1-2])([ ]*)(([ ]*[,][ ]*)([1-2][0-9]{3}-[1-2]))*([ ]*)");
                        $("#inRes").attr("placeholder", "2019-1, 2019-2, ...");
                    }
                }
            </script>
            <?php
                if(isset($result)){
                    $select = "*";
                    $from = "ENCUESTADO";
                    $group = ";";
                    $where = "";
                    if($consulta == 0){
                        $select = "(CASE WHEN ESCUELA = 0 THEN 'Preparatoria o CCH (UNAM)' WHEN ESCUELA = 1 THEN 'CECyT o CET (IPN)'"
                                  ." WHEN ESCUELA = 2 THEN 'CONALEP' WHEN ESCUELA = 3 THEN 'DGTI'"
                                  ." WHEN ESCUELA = 4 THEN 'Colegio de Bachilleres' WHEN ESCUELA = 5 THEN 'DGB'"
                                  ." WHEN ESCUELA = 6 THEN 'Preparatoria abierta' ELSE 'Otra' END) AS 'ESCUELA DE PROCEDENCIA', ";
                        $group = "GROUP BY ENCUESTADO.ESCUELA;";
                    }
                    if($consulta == 1){
                        $select = "SEMESTRE, ";
                        $group = "GROUP BY ENCUESTADO.SEMESTRE;";
                    }
                    if($consulta == 2){
                        $select = "(CASE WHEN CARRERA = 'II' THEN 'Ingeniería Industrial'"
                                    ." WHEN CARRERA = 'IN' THEN 'Ingeniería en Informatica'"
                                    ." WHEN CARRERA = 'AI' THEN 'Administración Industrial'"
                                    ." WHEN CARRERA = 'IT' THEN 'Ingeniería en Transporte'"
                                    ." WHEN CARRERA = 'CI' THEN 'Ciencias de la Informática' END) AS 'CARRERA', ";
                        $group = "GROUP BY ENCUESTADO.CARRERA;";
                    }
                    if($consulta == 3){
                        $select = "EDAD, ";
                        $group = "GROUP BY ENCUESTADO.EDAD;";
                    }
                    if($consulta == 4){
                        $select = "(IF(GENERO = 0, 'MASCULINO', 'FEMENINO')) AS GÉNERO, ";
                        $group = "GROUP BY ENCUESTADO.GENERO;";
                    }
                    if($consulta == 5){
                        $select = "(IF(TIPO = 1, 'PRIVADA', 'PÚBLICA')) AS 'TIPO DE ESCUELA', ";
                        $group = "GROUP BY ENCUESTADO.TIPO;";
                    }
                    if($por == 1){//------Encuestas
                        //$enc = getIDEncuestas($result);
                        $allEnc = getColumn("SELECT ID_ENCUESTA AS ID FROM ENCUESTA", "ID", $linker);
                        $enc = getIDEncuestas($result);
                        
                        //var_dump($enc);
                        if(!$enc){
                            echo "<h3><center>Su consulta contiene un rango inválido</h3></center>";
                            echo "</div></body></html>";
                            exit;
                        }
                        $enc = array_values(array_unique($enc));
                        $enc = encuestasExist($enc, $allEnc);

                        if(!$enc){
                            echo "<h3><center>La encuestas que seleccionó no se encuentran registradas</h3></center>";
                            echo "</div></body></html>";
                            exit;
                        }

                        if($como == 0){//------Aciertos
                            foreach($enc as $e){
                                $select = $select."CONCAT(ROUND(AVG(IF(ENCUESTA.ID_ENCUESTA = ".(int)$e.", ENCUESTADO.ACIERTOS, NULL)),0),'/', ROUND(AVG(IF(ENCUESTA.ID_ENCUESTA = ".(int)$e.", ENCUESTA.NUM_PREGUNTAS, NULL)),0)) AS 'ACIERTOS E-".(int)$e."',";
                            }
                        }
                        else{//------Calificacion
                            foreach($enc as $e){
                                $select = $select."ROUND(AVG(IF(ENCUESTA.ID_ENCUESTA = ".(int)$e.", ENCUESTADO.CALIF, NULL)),2) AS 'CALIF E-".(int)$e."',";
                            }
                        }
                        $select[strlen($select)-1]=" ";
                        $where = "WHERE (ENCUESTA.ID_ENCUESTA = ".(int)$enc[0].") ";
                        for ($i=1; $i < count($enc); $i++) { 
                        	$where = $where."OR (ENCUESTA.ID_ENCUESTA = ".(int)$enc[$i].") ";
                        }
                    }
                    else{//------Periodos
                        //$periodos = getPeriodos($result);
                        $periodos = array_values(array_unique(getPeriodos($result)));
                        if($como == 0){//------Aciertos
                            foreach($periodos as $p){
                            	$fecha = getFechas($p);
                                $select = $select."CONCAT(ROUND(AVG(IF(ENCUESTA.FECHA_FIN BETWEEN '".$fecha[0]."' AND '".$fecha[1]."', ENCUESTADO.ACIERTOS, NULL)),0),'/',CEIL(AVG(ENCUESTA.NUM_PREGUNTAS))) AS 'ACIERTOS ".$p."',";
                            }
                        }
                        else{//------Calificacion
                            foreach($periodos as $p){
                            	$fecha = getFechas($p);
                                $select = $select."ROUND(AVG(IF(ENCUESTA.FECHA_FIN BETWEEN '".$fecha[0]."' AND '".$fecha[1]."', ENCUESTADO.CALIF, NULL)),2) AS 'CALIF ".$p."',";
                            }
                        }
                        $select[strlen($select)-1]=" ";
                        $fecha = getFechas($periodos[0]);
                        $where = "WHERE (ENCUESTA.FECHA_FIN BETWEEN '".$fecha[0]."' AND '".$fecha[1]."') ";
                        for ($i=1; $i < count($periodos); $i++) { 
                        	$fecha = getFechas($periodos[$i]);
                        	$where = $where."OR (ENCUESTA.FECHA_FIN BETWEEN '".$fecha[0]."' AND '".$fecha[1]."') ";
                        }
                    }

                    $from = "ENCUESTADO INNER JOIN ENCUESTA ON ENCUESTADO.ID_ENCUESTA =  ENCUESTA.ID_ENCUESTA";
                    $sql = "SELECT $select FROM $from $where $group";
                    //echo $sql;
                    include "include/tableRep.php";
                }
            ?>
            <br>
            <form class="form-horizontal <?php echo isset($result)?"":"hide" ?>" action="generate/ReportToPDF.php" method="POST">
                <?php 
                    $descrip = "El siguiente reporte muestra ";
                    //los resultados promedio obtenidos por de las encuestas 1, 2 
                    switch ($como) {
                        case 1:
                            $descrip = $descrip."la calificación promedio obtenida por ";
                            break;
                        case 0:
                            $descrip = $descrip."el promedio de aciertos obtenidos por ";
                            break;
                    }

                    switch ($consulta) {
                        case 0:
                            $descrip = $descrip."cada escuela";
                            break;
                        case 1:
                            $descrip = $descrip."semestre";
                            break;
                        case 2:
                            $descrip = $descrip."cada carrera";
                            break;
                        case 3:
                            $descrip = $descrip."edades";
                            break;
                        case 4:
                            $descrip = $descrip."género";
                            break;
                         case 4:
                            $descrip = $descrip."tipo de escuela";
                            break;
                    }

                    switch ($por) {
                        case 1:
                            $descrip = $descrip." de las encuestas: ";
                            $max = count($enc);
                            if($max == 1){
                                $descrip = $descrip."$enc[0].";
                            }
                            else{
                                for ($i=0; $i < $max-1; $i++) { 
                                    $descrip = $descrip."$enc[$i], ";
                                }
                                $descrip = $descrip."y ".$enc[$max-1].".";
                            }
                            break;
                        case 0:
                            $descrip = $descrip." de los periodos: ";
                            $max = count($periodos);
                            if($max == 1){
                                $descrip = $descrip."$periodos[0].";
                            }
                            else{
                                for ($i=0; $i < $max-1; $i++) { 
                                    $descrip = $descrip."$periodos[$i], ";
                                }
                                $descrip = $descrip."y ".$periodos[$max-1].".";
                            }
                            break;
                    }
                ?>

                <input  class="hide" type="text" name="desc" value="<?php echo $descrip; ?>">
                <input  class="hide" type="text" name="consult" value="<?php echo $sql; ?>">
                <div class="col-sm-offset-4 col-sm-4">
                        <button type="submit" class="btn btn-lg btn-primary btn-block btn-signin">Exportar</button>
                </div>
            </form>
            <?php
                function getPeriodos($str){
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

                function getIDEncuestas($str){
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
                
                function getFechas($str){
                	$res = [];
                	$anio = substr($str, 0, 4);
                	if($str[5]==1){
                			$res[0] = ((int)$anio-1)."-07-10";
                			$res[1] = $anio."-01-09";
                	}
                	else{
                		    $res[0] = $anio."-01-10";
                			$res[1] = $anio."-07-9";
                	}
                	return $res;
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
            
        </div>
    </body>
</html> 