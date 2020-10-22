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
                $sql       = "SELECT * FROM UNIDAD_DE_APRENDIZAJE WHERE ID_UNIDAD = '".$id."'";
                $resultado = mysqli_query($linker, $sql);
                if (!$resultado) {
                    echo "Error de BD al obtener 'UNIDADES', no se pudo consultar la base de datos\n";
                    echo "Error MySQL:" .  mysqli_error($linker);
                    exit;
                }
                $registro = mysqli_fetch_assoc($resultado);
                if($registro == null){
                    echo "No se pudo encontrar el registro ".$id;
                    exit;
                }
                else{
                    $nombre = $registro['NOM_UNIDAD'];
                }
                mysqli_free_result($resultado); 
            }
        ?>
        <title><?php echo $mody?"Modificar":"Agregar";?> unidad de aprendizaje</title>
    </head>
    <body>
        <?php include ('include/navbar.php');
        ?>
        <div class="container">
            <h1><?php echo $mody?"Modificar":"Agregar";?> unidad de aprendizaje</h1>
            <br>
            <br>
            <form id="formulario" class="form-horizontal" action="addUA.php<?php echo $mody?"?mody=1":"";?>" method="POST">
                <div class="text-danger" id="msgDiv"></div><br>
                <div class="form-group">
                    <label class="control-label col-sm-offset-2 col-sm-4" for="id" style="text-align: left">Clave de la unidad de aprendizaje</label>
                    <div class="col-sm-4">
                        <input required type="text" class="form-control" name="id" id="idUnidad" value="<?php echo $mody?$id:"";?>">
                    </div>
                </div>
                <br>
                <br>
                <div class="form-group">
                    <label class="control-label col-sm-offset-2 col-sm-4" for="nombre" style="text-align: left">Nombre de la unidad</label>
                    <div class="col-sm-4">
                        <input required type="text" class="form-control" name="nombre" id="nombre" value="<?php echo $mody?$nombre:"";?>">
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
                var idUnidad = $("#idUnidad").val().trim() === "";
                var nombre = $("#nombre").val().trim() === "";
                return (idUnidad || nombre);
            }
        </script>
    </body>
</html>

