<?php
session_start();
require_once 'dbcon.php';
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

    $conexion = conexionBD();
    
    $sql = "SELECT email 
            FROM usuario 
            WHERE email like '$loginEmail' AND passW like '$loginPassword'"; 
    
            $result = mysqli_query($conexion, $sql);
    
            if ($result) {
                $_SESSION['usuario'] = $loginEmail;
                // Debug: Verify if session is set
                if (isset($_SESSION['usuario'])) {
                    echo "Session set: " . $_SESSION['usuario'];
                } else {
                    echo "Session not set";
                }
                // Redirect after setting session
                header("Location: ../index.php");
                exit();
            } else {
                // Debug: Check for MySQL errors
                echo "Error: " . mysqli_error($conexion);
                // Redirect if login fails
                header("Location: ../index.php");
                exit();
            }
    
    // Aquí puedes realizar la validación y autenticación del usuario
}

// Procesar formulario de registro
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['registroSubmit'])) {
    // Recoger los datos del formulario
    $registroNombre = $_POST["registroNombre"];
    $registroEmail = $_POST["registroEmail"];
    $registroPassword = $_POST["registroPassword"];

    login($registroNombre, $registroEmail, $registroPassword);
        
    // Aquí puedes realizar el registro del nuevo usuario
}








?>