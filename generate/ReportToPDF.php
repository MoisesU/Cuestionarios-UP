<?php
  ob_start();
  session_start();
    if (!isset($_SESSION['user'])) 
    {
        header("Location:index.php");
    }
    require '../connect.php';
    extract($_POST);
?>
<style>
<!--
    body {font-family: Arial, Helvetica, sans-serif;}

table {     
    font-size: 12px;    margin: 45px;     width: 480px; text-align: left;    border-collapse: collapse; }

th {     font-size: 13px;     font-weight: normal;     padding: 8px;     background: #b9c9fe;
    border-top: 4px solid #aabcfe;    border-bottom: 1px solid #fff; color: #039; }

td {    padding: 8px;     background: #e8edff;     border-bottom: 1px solid #fff;
    color: #669;    border-top: 1px solid transparent; }
p{ font-size: 16px; }
-->
</style>
<page backtop="10mm" backbottom="10mm" backleft="20mm" backright="20mm">
 <h1>Reporte de resultados</h1><br><br>
  <b>Consultado el <?php echo date("d/m/Y") ?></b><br>
  <br><?php 
    if(isset($desc)){
      echo "<p>$desc</p>";
      $resultado = mysqli_query($linker, $consult);
      if($resultado){
        $columns = mysqli_fetch_fields($resultado);
        //  DIBUJA LAS TABLAS
        $tables = getTables(count($columns)-1);
        $body = [];
        $x = 0;
        while($fila = mysqli_fetch_array($resultado, MYSQLI_NUM)){
            $body[$x] = $fila;
            $x++;
        }
        mysqli_free_result($resultado);
        foreach ($tables as $t) {
          drawTable($columns, $t[0], $t[1], $body);
        }
        
      }
    }
   ?>
</page>

<?php

  $content = ob_get_clean();
  require_once(dirname(__FILE__).'/../vendor/autoload.php');
  use Spipu\Html2Pdf\Html2Pdf;
  try
  {
      $html2pdf = new HTML2PDF('P', 'A4', 'es', true, 'UTF-8', 3);
      $html2pdf->pdf->SetDisplayMode('fullpage');
      $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
      $html2pdf->Output('PDF-CF.pdf');
  }
  catch(HTML2PDF_exception $e) {
      echo $e;
      exit;
  }

  function drawTable($head, $init, $end, $res){
    echo "<table id='datos' class='table table-striped'>";
          echo    "<thead>";
          echo        "<tr class='fila'>";
                          echo "\n<th>".$head[0]->name."</th>";
                          for ($i=$init; $i < $end; $i++) { 
                              echo "\n<th>".$head[$i]->name."</th>";
                          }
          echo        "</tr>"
              ."</thead>"
              ."<tbody>";
                      for ($i=0; $i<count($res); $i++){
                          $fila = $res[$i];
                          echo "\n<tr>";
                          echo "\n\t<td>".$fila[0]."</td>";
                          for ($j=$init; $j < $end; $j++) {
                              echo "\n\t<td>".($fila[$j]==""?"-":$fila[$j])."</td>";
                          }
                          echo "\n</tr>";
                      }
                      
          echo    "</tbody>"
          ."</table>";
  }

  function getTables($col){
      $res = [];
      $tabs = ceil($col/3);
      $i = 0;
      for ($i=0; $i <$tabs -1 ; $i++) { 
          $res[$i] = array($i+($i*2+1), $i+($i*2+4));
      }
      if($col%3 == 0){
        $res[$i] = array($i+($i*2+1), ($i+($i*2+1))+3);
      }
      else{
        $res[$i] = array($i+($i*2+1), ($i+($i*2+1))+$col%3);
      }
      
      /*echo "tabs($tabs) = ";
      foreach ($res as $r) {
        echo "{".$r[0].", ".$r[1]."} ";
      }*/
      return $res;
  }