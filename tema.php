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
                $sql       = "SELECT * FROM TEMA WHERE ID_TEMA = '".$id."'";
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
                    $nomTema = $registro['NOM_TEMA'];
                    $claveUA = $registro['ID_TEMA'];
                }
                mysqli_free_result($resultado); 
            }
        ?>
        <title><?php echo $mody?"Modificar":"Agregar";?> tema</title>
    </head>
    <body>
        <?php include ('include/navbar.php');
        ?>
        <div class="container">
            <h1><?php echo $mody?"Modificar":"Agregar";?> tema</h1>
            <br>
            <br>
            <form  id="formulario" class="form-horizontal" action="addTema.php<?php echo $mody?"?id=".$id:"";?>" method="POST">
                <div class="text-danger" id="msgDiv"></div><br>
                <div class="form-group">
                    <label class="control-label col-sm-offset-2 col-sm-4" for="claveUA" style="text-align: left">Unidad de aprendizaje</label>
                    <div class="col-sm-4">
                        <select class="form-control" name="claveUA">
                            <?php
                                $sql       = 'SELECT * FROM UNIDAD_DE_APRENDIZAJE';
                                $resultado = mysqli_query($linker, $sql);
                                if (!$resultado) {
                                    echo "Error de BD al obtener 'UNIDADES', no se pudo consultar la base de datos\n";
                                    echo "Error MySQL:" .  mysqli_error($linker);
                                    exit;
                                }
                                if($mody){
                                    while ($fila = mysqli_fetch_assoc($resultado)) {
                                        echo "<option value='".$fila['ID_UNIDAD']."' ".($fila['ID_UNIDAD']==$claveUA?"selected":"").">".$fila['NOM_UNIDAD']."</option>";
                                    }
                                }
                                else{
                                    while ($fila = mysqli_fetch_assoc($resultado)) {
                                        echo "<option value='".$fila['ID_UNIDAD']."'>".$fila['NOM_UNIDAD']."</option>";
                                    }
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <br>
                <br>
                <div class="form-group">
                    <label class="control-label col-sm-offset-2 col-sm-4" for="nomTema" style="text-align: left">Nombre del tema</label>
                    <div class="col-sm-4">
                        <input required type="text" class="form-control" name="nomTema" id="nombre" value="<?php echo $mody?$nomTema:"";?>">
                    </div>
                </div>
                <br>
                <br>
                <div class="form-group">        
                    <div class="col-sm-offset-2 col-sm-8">
                        <button type="button" onclick="addP()" class="btn btn-lg btn-primary btn-block btn-signin"><?php echo $mody?"Modificar":"Agregar";?></button>
                    </div>
                </div>
            </form>
        </div>
        <script type="text/javascript">
            function addP(){
                $("#msgDiv").html("");
                var formulario = document.getElementById("formulario");
                if(formulario.reportValidity()){
                    if(fieldsAreNotFilled()){
                        $("#msgDiv").html("<b>Advertencia: </b>No puedes dejar campos en blanco.");
                    }
                    else{
                        $("#msgDiv").html("");
                        $("#formulario").submit();
                    }
                }
            }
            function fieldsAreNotFilled(){
                var nombre = $("#nombre").val().trim() === "";
                return (nombre);
            }
        </script>
    </body>
</html>

