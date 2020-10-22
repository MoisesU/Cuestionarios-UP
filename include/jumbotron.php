<div class="container des-container">
            <div class="jumbotron text-center">
                <h1>¡Gracias por participar!</h1>
                <p>
                    Tu respuesta ha sido agregada exitosamente. <?php if(isset($points)){echo "tu puntuación es de $points";}?> 
                </p>
                <h2>Tu calificación es:</h2>
                <p class='calif'><?php echo (isset($calif)?round($calif, 1):" ");?></p>
            </div>    
        </div>