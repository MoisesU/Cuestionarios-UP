<?php
   if(isset($sql)){
        $resultado = mysqli_query($linker, $sql);
        if(!$resultado){
            echo "<h3><center>Error al ejecutar la consulta</h3></center>";
            echo "<script>console.log(\"Consulta = $sql Error: ".mysqli_error($linker)."\");</script>\n";
            echo "</div></body></html>";
            exit;
        }
        if (mysqli_num_rows($resultado)==0){
            echo "<h3><center>La consulta no arrojó ningún resultado, verifique que haya información registrada en las encuestas</center></h3>";
            echo "</div></body></html>";
            exit;
        }
    }
    else{
        echo "<h3><center>La tabla no puede mostrarse porque no se ha definido el contenido</h3></center>";
        exit;
    }
?>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <?php
                    $columna = mysqli_fetch_fields($resultado);
                    foreach($columna as $c){
                        echo "\n<th>".$c->name."</th>";
                    }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php
                while($fila = mysqli_fetch_array($resultado, MYSQLI_NUM)){
                    echo "\n<tr>";
                    foreach($fila as $f){
                        echo "\n\t<td>".($f==""?"-":$f)."</td>";
                    }
                    echo "\n</tr>";
                }
                mysqli_free_result($resultado);
            ?>
        </tbody>
    </table>
</div> 

