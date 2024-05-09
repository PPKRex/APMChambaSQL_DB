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
} else if (isset($_FILES['file'])){
    // No se ha subido ningún archivo o la solicitud no es POST
    http_response_code(400);
}

// Procesar formulario de inicio de sesión
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['loginSubmit'])) {
    // Recoger los datos del formulario
    $loginEmail = $_POST["loginEmail"];
    $loginPassword = $_POST["loginPassword"];

    login(null, $loginEmail, $loginPassword);

    header("Location: login.php");
    exit();
    // Aquí puedes realizar la validación y autenticación del usuario
}

// Procesar formulario de registro
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['registroSubmit'])) {
    // Recoger los datos del formulario
    $registroNombre = $_POST["registroNombre"];
    $registroEmail = $_POST["registroEmail"];
    $registroPassword = $_POST["registroPassword"];

    echo $registroNombre . $registroEmail . $registroPassword;

    login($registroNombre, $registroEmail, $registroPassword);

    header("Location: login.php");
    exit();    
    // Aquí puedes realizar el registro del nuevo usuario
}








?>