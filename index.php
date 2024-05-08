<?php

    session_start();
    include('web/includes/header.php');
    require('web/dbcon.php');
    require('web/funciones.php');
    

?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
        <form action="tu_archivo.php" method="GET">
                <div class="mb-3">
                    <select class="form-select" id="elemento" name="elemento">

                        <?php

                            generarListado();
                        
                        ?>

                    </select>
                </div>
            </form>
            <div class="card">
                <div class="card-header">
                    <h4>
                        
                        Análisis de logs <?php if(isset($_GET['fecha'])) { tituloLog($_GET['fecha']); } ?>
                        
                        <div id="drop_zone" class="btn btn-primary float-end"> Añadir fichero </div>
                    </h4>
                </div>

                <div class="card-body">

                
                    <?php 

                        if(isset($_GET['fecha'])) { botonNodos($_GET['fecha']); } ?>
                    <input type="text" id="searchInput" class="form-control" placeholder="Buscar...">
                    <table id="tabla" class="table table-sm table-striped center" 
                    data-toggle="table" 
                    data-search="true"
                    data-show-toggle="true"
                    data-pagination="false"
                    data-height="500"
                    data-show-columns="true"
                    data-toolbar="#toolbar">
                    <?php if(isset($_GET['nodo']) && isset($_GET['fecha'])) { ?>
                        <tr>
                            <th><a href="<?php if(isset($_GET['nodo'])) { echo "?nodo=" . $_GET['nodo']; echo "&fecha=" . $_GET['fecha'];}?> &orden=nombreNodo&cambiar_direccion"> Nodo </a></th>
                            <th><a href="<?php if(isset($_GET['nodo'])) { echo "?nodo=" . $_GET['nodo']; echo "&fecha=" . $_GET['fecha'];}?>&orden=nombre&cambiar_direccion"> Información </a></th>
                            <th><a href="<?php if(isset($_GET['nodo'])) { echo "?nodo=" . $_GET['nodo']; echo "&fecha=" . $_GET['fecha'];}?>&orden=fechaInfo&cambiar_direccion"> Fecha </a></th>
                            <th><a href="<?php if(isset($_GET['nodo'])) { echo "?nodo=" . $_GET['nodo']; echo "&fecha=" . $_GET['fecha'];}?>&orden=tiempoTrans&cambiar_direccion"> Tiempo ejecución </a></th>
                        </tr>

                        
                            <?php

                                if(isset($_GET['cambiar_direccion'])) {
                                    // Cambiar la dirección almacenada en la sesión
                                    cambiodireccion();
                                }
                                
                                if(isset($_GET['orden'])) {
                                    
                                    tablaOrden($_GET['nodo'], $_GET['orden'], $_SESSION['direccion'], $_GET['fecha']);
                                }
                                else {
                                    tablaNodo($_GET['nodo'], $_GET['fecha']);
                                }
                                
                        } ?>

                    </table>

                </div>
            </div>
        </div>
    </div>
</div>



<?php

    include('web/includes/footer.php');

?>