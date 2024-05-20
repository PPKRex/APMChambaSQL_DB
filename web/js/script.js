// Verificar si el elemento de selección existe
var seleccion = document.getElementById("elemento");

if (seleccion) {
    // Si existe, agregar el event listener
    seleccion.addEventListener('change', (event) => {
        
        // Obtener el valor seleccionado
        const selectedValue = event.target.value;
        
        // Actualizar la URL del formulario cuando cambia la selección
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('fecha', selectedValue);
        
        // Redireccionar a la nueva URL
        window.location.href = currentUrl;
        console.log(currentUrl);
    });
}



    // Función para manejar el evento de soltar
    function handleFileDrop(event) {
        event.preventDefault();
        var file = event.dataTransfer.files[0];
        uploadFile(file);
      }
  
      // Función para subir el archivo al servidor
      function uploadFile(file) {
        var formData = new FormData();
        formData.append('file', file);
  
        fetch("index.php", {
          method: 'POST',
          body: formData
        })
        .then(response => {
          if (!response.ok) {
            throw new Error('Error en la solicitud: ' + response.status + ' ' + response.statusText);
          }
          alert('Archivo subido exitosamente');
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Hubo un error al subir el archivo: ' + error.message);
        });
      }
  
      // Asociar el evento de soltar al documento
      document.addEventListener('dragover', function(event) {
        event.preventDefault();
      }, false);
      document.addEventListener('drop', handleFileDrop, false);



      document.getElementById('searchInput').addEventListener('input', function (event) {
        const searchTerm = event.target.value.toLowerCase();
        const tableRows = document.querySelectorAll('tr');
    
        tableRows.forEach(function (row) {
            // Excluimos los elementos <th> para que no se vean afectados por la búsqueda
            if (!row.querySelector('th')) {
                let rowContainsSearchTerm = false;
                const rowData = row.querySelectorAll('td');
    
                rowData.forEach(function (item) {
                    const itemText = item.textContent.toLowerCase();
                    if (itemText.includes(searchTerm)) {
                        rowContainsSearchTerm = true;
                    }
                });
    
                if (rowContainsSearchTerm) {
                    row.style.display = 'table-row';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    });


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
  