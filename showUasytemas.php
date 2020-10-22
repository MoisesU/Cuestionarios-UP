<?php
    session_start();
    if (!isset($_SESSION['user'])) 
    {
        header("Location:index.php");
    }
?>
<html>
    <head>
        <?php include ('include/header.php');
              require 'connect.php';
        ?>
        <title>Agregar pregunta</title>
    </head>
    <body>
        <?php 
            include ('include/navbar.php');
            include ('uasytemasvars.php');
            $uasIsEmpty = count($uas) == 0;
            
        ?>
        <div class="container" >
            <h1>Unidades de aprendizaje y temas</h1>
            <br>
            <h2>Unidades de aprendizaje</h2>
            <br>
            <br>
            <table class="table table-hover<?php echo $uasIsEmpty?" hide":"";?>">
                <thead>
                    <tr>
                        <th>Clave</th>
                        <th>Nombre</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody id="uasbody">
                </tbody>
            </table>
            <?php echo $uasIsEmpty?"<h3><center>No hay unidades de aprendizaje registradas</center></h3>":"";?>
            <br>
            <div class="col-sm-offset-3 col-sm-6">
                <button type="button" onclick="window.location='unidadaprendizaje.php'" class="btn btn-lg btn-primary btn-block btn-big">Agregar Unidad de aprendizaje</button>
            </div>
            <br>
            <h2 <?php echo $uasIsEmpty?"class='hide'":"";?>>Temas</h2>
            <table class="table table-hover<?php echo $uasIsEmpty?" hide":"";?>" id="tableTemas">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody id="temabody">
                </tbody>
            </table>
            <div id="msgDiv"></div>
            <script type='text/javascript'>
                <?php 
                    echo "\t\t\t\tvar temas = ".json_encode($temas).";\n";
                    echo "\n\t\t\t\tvar unidades = ".json_encode($uas).";\n";
                ?>
                var tableU = document.getElementById("uasbody");
                var tableT = document.getElementById("temabody");
                var tableTemas = document.getElementById("tableTemas");
                var msgDiv = document.getElementById("msgDiv");
                
                for (var i = unidades.length - 1; i >= 0; i--) {
//                    tableU.innerHTML+="<tr onclick='setTemas("+i+")'><td>"+unidades[i][0]+"</td><td>"+unidades[i][1]+"</td>\
//                    <td><a href='unidadaprendizaje.php?id="+unidades[i][0]+"'><span class='glyphicon glyphicon-pencil'></span></a>\n\
//                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='delUA.php?id="+unidades[i][0]+"'><span class='glyphicon glyphicon-trash'></span></a></td></tr>";
                      tableU.innerHTML+="<tr onclick='setTemas("+i+")'><td>"+unidades[i][0]+"</td><td>"+unidades[i][1]+"</td>\
                      <td><a href='unidadaprendizaje.php?id="+unidades[i][0]+"'><span class='glyphicon glyphicon-pencil'></span></a>\n\
                      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a onclick=\"sure('delUA.php?id="+unidades[i][0]+"')\"><span class='glyphicon glyphicon-trash'></span></a></td></tr>";
		
                }
                setTemas(0);
		function setTemas(index){
                    tableT.innerHTML = "";
                    if(temas[index] === undefined){
                        tableTemas.className = "hide";
                        msgDiv.innerHTML = "<h3><center>No hay temas registrados en "+unidades[index][1]+"</center></h3>"
                    }
                    else{
                        tableTemas.className = "table table-hover";
                        msgDiv.innerHTML = "";
                        for (var i = temas[index].length - 1; i >= 0; i--) {
                                tableT.innerHTML+="<tr><td>"+temas[index][i][0]+"</td><td>"+temas[index][i][1]+"</td>\
                                <td><a  href='tema.php?id="+temas[index][i][0]+"'><span class='glyphicon glyphicon-pencil'></span></a>\n\
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a onclick=\"sure('delTema.php?id="+temas[index][i][0]+"')\"><span class='glyphicon glyphicon-trash'>\n\
                                </span></a></td></tr>";

                        }
                    }
		}
                function sure(value){
                    //alert("fui presionado\n"+value);
                    if(confirm("¿Está seguro de que quiere eliminar este registro?")){
                        location.href = value;
                    }   
                }
            </script>
            
            
          <div class="col-sm-offset-3 col-sm-6<?php echo $uasIsEmpty?" hide":"";?>">
                <button type="button" onclick="window.location='tema.php'" class="btn btn-lg btn-primary btn-block btn-big">Agregar Tema</button>
          </div>
            <br>
        </div>
    </body>
</html> 