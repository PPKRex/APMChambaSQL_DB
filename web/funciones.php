<?php
// Directorio donde se guardará el archivo
$uploadDir = 'logs/';

// Verificar si se ha subido algún archivo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    // Ruta completa del archivo en el servidor
    $uploadFile = $uploadDir . basename($_FILES['file']['name']);

    // Intentar mover el archivo subido al directorio deseado
    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
        // Archivo subido correctamente
        http_response_code(200);
    } else {
        // Error al subir el archivo
        http_response_code(500);
    }
} else {
    // No se ha subido ningún archivo o la solicitud no es POST
    http_response_code(400);
}
?>