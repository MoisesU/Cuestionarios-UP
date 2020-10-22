                <form>
                    <div class="form-group">
                        <label class="control-label col-sm-3" for="unidad" style="text-align: left">Unidad de aprendizaje</label>
                        <div class="col-sm-3">
                            <select class="form-control" name="unidad" id="unidad" onchange="filter()">
                                <?php
                                    $sql = "SELECT * FROM UNIDAD_DE_APRENDIZAJE";
                                    $result = mysqli_query($linker, $sql);
                                    if (!$result) {
                                        echo "Error de BD, no se pudo consultar la base de datos\n";
                                        echo "Error MySQL:" . mysqli_error($linker);
                                        exit;
                                    }
                                    if (mysqli_num_rows($result)!=0){
                                        echo "\n\t\t\t\t\t\t\t\t<option id='setAll' value='all'>TODAS</option>";
                                        while($fila = mysqli_fetch_assoc($result)){
                                             echo "\n\t\t\t\t\t\t\t\t<option value='".$fila["ID_UNIDAD"]."'>".$fila["NOM_UNIDAD"]."</option>";
                                        }
                                    }
                                    else {
                                        echo "\n\t\t\t\t\t\t\t\t<option>No hay unidades registradas</option>";
                                    }
                                    mysqli_free_result($result);
                                    echo "\n";
                                ?>
                            </select>
                        </div>
                        <label class="control-label col-sm-2" for="buscar" style="text-align: left">Buscar</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" name="buscar" id="buscar" onkeyup="cleanAndSearch()">
                        </div>
                        <input name="distractor" class="hide">
                    </div>
                </form>