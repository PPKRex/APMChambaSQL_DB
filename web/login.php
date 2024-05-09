<?php

    session_start();
    include('includes/header.php');
    require_once('dbcon.php');
    require('funciones.php');
    

?>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <!-- Formulario de Login -->
                <div class="card">
                    <div class="card-header">
                        <h4>Iniciar sesión</h4>
                    </div>
                    <div class="card-body">
                        <form action="funciones.php" method="POST">
                            <div class="mb-3">
                                <label for="loginEmail" class="form-label">Correo electrónico</label>
                                <input type="email" class="form-control" id="loginEmail" name="loginEmail" required>
                            </div>
                            <div class="mb-3">
                                <label for="loginPassword" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="loginPassword" name="loginPassword" required>
                            </div>
                            <button type="submit" name="loginSubmit" class="btn btn-primary">Iniciar sesión</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Formulario de Registro -->
                <div class="card">
                    <div class="card-header">
                        <h4>Registro</h4>
                    </div>
                    <div class="card-body">
                        <form action="funciones.php" method="POST">
                            <div class="mb-3">
                                <label for="registroNombre" class="form-label">Usuario</label>
                                <input type="text" class="form-control" id="registroNombre" name="registroNombre" required>
                            </div>
                            <div class="mb-3">
                                <label for="registroEmail" class="form-label">Correo electrónico</label>
                                <input type="email" class="form-control" id="registroEmail" name="registroEmail" required>
                            </div>
                            <div class="mb-3">
                                <label for="registroPassword" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="registroPassword" name="registroPassword" required>
                            </div>
                            <button type="submit" name="registroSubmit" class="btn btn-primary">Registrarse</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php

    include('includes/footer.php');

?>