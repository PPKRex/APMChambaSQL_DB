        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        
        
      </body>
      <script src="web/js/script.js"> </script>
      <script>
        document.addEventListener("DOMContentLoaded", function() {
        console.log("Script cargado correctamente.");  // Mensaje para verificar la carga del script
  
        var botones = document.querySelectorAll(".botonNodos");
  
            console.log("Número de botones encontrados:", botones.length);  // Verifica que los botones se seleccionan correctamente
        
            botones.forEach(function(celda) {
                console.log("Texto del botón:", celda.textContent);  // Verifica el texto de cada botón
        
                if (celda.textContent.includes("XPS")) {
                    celda.style.setProperty('background-color', 'blue', 'important');
                }
                else if (celda.textContent === "ecn4" || celda.textContent.includes("Not connected")) {
                    celda.style.setProperty('background-color', 'orange', 'important');
                }
                else if (celda.textContent === "Center" || celda.textContent.includes("Cluster") || celda.textContent.includes("up") || celda.textContent.includes("READY")) {
                    celda.style.setProperty('background-color', 'green', 'important');
                }
            });
        });
      </script>
</html>