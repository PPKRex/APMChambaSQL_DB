document.addEventListener("DOMContentLoaded", function() {
  
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

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.toggle-column').forEach(function (header) {
        header.addEventListener('click', function () {
            const content = this.nextElementSibling;
            if (content.style.display === "none") {
                content.style.display = "block";
            } else {
                content.style.display = "none";
            }
        });
    });

    document.querySelectorAll('.botonNodos').forEach(function (button) {
        button.addEventListener('click', function (event) {
             // Evitar la redirección

            const nodoName = this.getAttribute('data-nodo');
            const container = this.parentNode;

            if (!container.querySelector('.nested-buttons')) {
                const nestedDiv = document.createElement('div');
                nestedDiv.classList.add('nested-buttons');

                // Aquí generamos los nuevos botones
                

                container.appendChild(nestedDiv);
            }
        });
    });
});