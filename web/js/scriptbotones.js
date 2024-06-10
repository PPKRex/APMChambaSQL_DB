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



function toggleForm(event) {
    // Prevenir la propagación del evento para no ocultar el formulario cuando se haga clic en su interior
    event.stopPropagation();

    var dropZone = document.getElementById('drop_zone');
    var existingForm = document.getElementById('dynamicForm');

    if (existingForm) {
        // Si el formulario ya existe, alternar su visibilidad
        existingForm.classList.toggle('hidden');
    } else {
        // Crear un nuevo formulario
        var form = document.createElement('form');
        form.id = 'dynamicForm';
        form.action = ''; // Enviar a la página actual
        form.method = 'POST';

        // Crear un nuevo elemento input de tipo texto
        var input = document.createElement('input');
        input.type = 'text';
        input.placeholder = 'Escribe aquí...';
        input.name = 'palabraClaves';

        // Prevenir que el clic en el input esconda el formulario
        input.onclick = function(event) {
            event.stopPropagation();
        };

        // Crear un botón de envío
        var submitButton = document.createElement('button');
        submitButton.type = 'submit';
        submitButton.textContent = 'Enviar';

        // Prevenir que el clic en el botón de envío esconda el formulario
        submitButton.onclick = function(event) {
            event.stopPropagation();
        };

        // Añadir el input y el botón al formulario
        form.appendChild(input);
        form.appendChild(submitButton);

        // Añadir el formulario al div
        dropZone.appendChild(form);
    }
}

// Asignar la función toggleForm al evento onclick del div
document.getElementById('drop_zone').onclick = toggleForm;

// Prevenir la propagación del evento para el documento entero para que no cierre el formulario al hacer clic en cualquier lugar
document.body.onclick = function(event) {
    // Verificar si el clic ocurrió fuera del drop_zone
    if (!document.getElementById('drop_zone').contains(event.target)) {
        var existingForm = document.getElementById('dynamicForm');
        if (existingForm && !existingForm.classList.contains('hidden')) {
            existingForm.classList.add('hidden');
        }
    }
};