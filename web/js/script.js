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
  
        fetch('index.php', {
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