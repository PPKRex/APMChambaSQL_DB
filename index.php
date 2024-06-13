<?php

    
    include('web/includes/header.php');
    require_once('web/dbcon.php');
    require('web/funciones.php');

    
    if (isset($_SESSION['usuario'])) {
?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
        <form action="#" method="GET">
                <div class="mb-3 d-flex align-items-center">
                <select class="form-select" id="fechaSelect" name="fecha">
                        <?php generarListado(); // Función en dbcon.php ?>
                    </select>
                    
                    <select class="form-select ms-2" id="terminalSelect" name="terminal">
                        <?php listadoTerminales(); // Función en dbcon.php ?>
                    </select>
                </div>
            </form>
            <div class="card">
                <div class="card-header">
                    <h4>
                        
                        <?php tituloLog(); //Función en dbcon.php ?>
                        
                        <div id="drop_zone" class="btn btn-primary float-end"> Añadir palabra clave </div>
                        
                    </h4>
                </div>

                <div class="card-body">

                
                    <?php 

                        if(isset($_GET['fecha']) && isset($_GET['terminal'])) { botonNodos($_GET['fecha'], $_GET['terminal']);  } // Funcion en dbcon.php?>
                    <input type="text" id="searchInput" class="form-control" placeholder="Buscar..." list="suggestions">
                    <datalist id="suggestions">
                        <option value="ERROR"></option>
                        <option value="WARNING"></option>
                    </datalist>
                    <table id="tabla" class="table table-sm table-striped center" 
                    data-toggle="table" 
                    data-search="true"
                    data-show-toggle="true"
                    data-pagination="false"
                    data-height="500"
                    data-show-columns="true"
                    data-toolbar="#toolbar">
                    <?php if(isset($_GET['nodo']) && isset($_GET['fecha'])) { //Controla que existan en la URL para escribirlo ?>
                        <tr>
                            <th><a href="<?php if(isset($_GET['nodo'])) { echo "?nodo=" . $_GET['nodo']; echo "&fecha=" . $_GET['fecha']; echo "&terminal=" . $_GET['terminal']; }?>&orden=nombre&cambiar_direccion"> Información </a></th>
                            <th><a href="<?php if(isset($_GET['nodo'])) { echo "?nodo=" . $_GET['nodo']; echo "&fecha=" . $_GET['fecha']; echo "&terminal=" . $_GET['terminal'];}?>&orden=fechaInfo&cambiar_direccion"> Fecha </a></th>
                            <th><a href="<?php if(isset($_GET['nodo'])) { echo "?nodo=" . $_GET['nodo']; echo "&fecha=" . $_GET['fecha']; echo "&terminal=" . $_GET['terminal'];}?>&orden=tiempoTrans&cambiar_direccion"> Tiempo ejecución </a></th>
                        </tr>

                        
                            <?php

                                if(isset($_GET['cambiar_direccion'])) {
                                    // Cambiar la dirección almacenada en la sesión con la funcion en dbcon.php
                                    cambiodireccion();
                                }
                                
                                //Si existe en la URL el orden, llamará a la funcion que escribe la tabla ordenada, si no, la escribirá normal.
                                if(isset($_GET['orden'])) {
                                    
                                    tablaOrden($_GET['nodo'], $_GET['orden'], $_SESSION['direccion'], $_GET['fecha'], $_GET['terminal']);
                                }
                                else {
                                    tablaNodo($_GET['nodo'], $_GET['fecha'], $_GET['terminal']);
                                }
                                
                        } ?>

                    </table>

                </div>
            </div>
        </div>
    </div>
</div>


<?php
    }
    //Si no hay usuario (variable de sesion creada al logearse) muestra solo que no está logeado.
    else {
        echo "<h1 align='center'> No ha iniciado sesión </h1>";
    }
    include('web/includes/footer.php');


    if(isset($_POST['palabraClaves'])) {
        
        anadirPalabraclave($_POST['palabraClaves']);
    }
?>