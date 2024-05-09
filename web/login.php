<?php

    
    require_once('dbcon.php');
    require('funciones.php');
    

?>

<!doctype html>

<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title> APM Terminals </title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    
    

</head>

<body>

<nav class="navbar navbar-expand-lg bg-body-tertiary">

<div class="container">

  <a class="navbar-brand" href="../index.php"> APM Terminals </a>
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">

      <li class="nav-item">
        <a class="nav-link" href="login.php"> Login </a>
      </li>
      
      
    </ul>
  </div>
</div>
</nav>
    <div class="py-4">

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

</div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        
        
      </body>
</html>