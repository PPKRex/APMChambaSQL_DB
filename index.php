<?php

    session_start();
    include('includes/header.php');
    require('dbcon.php');
    require('funciones.php');

?>


<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>
                        Análisis de logs 
                        <div id="drop_zone" class="btn btn-primary float-end"> Añadir fichero </div>
                    </h4>
                </div>

                <div class="card-body">

                    <?php botonNodos(); ?>
                    <table id="tabla" class="table table-sm table-striped center" 
                    data-toggle="table" 
                    data-search="true"
                    data-show-toggle="true"
                    data-pagination="false"
                    data-height="500"
                    data-show-columns="true"
                    data-toolbar="#toolbar">
                    <?php if(isset($_GET['nodo'])) { ?>
                        <tr>
                            <th><a href="<?php if(isset($_GET['nodo'])) { echo "?nodo=" . $_GET['nodo'];}?>&orden=nombreNodo&cambiar_direccion"> Nodo </a></th>
                            <th><a href="<?php if(isset($_GET['nodo'])) { echo "?nodo=" . $_GET['nodo'];}?>&orden=nombre&cambiar_direccion"> Información </a></th>
                            <th><a href="<?php if(isset($_GET['nodo'])) { echo "?nodo=" . $_GET['nodo'];}?>&orden=fechaInfo&cambiar_direccion"> Fecha </a></th>
                            <th><a href="<?php if(isset($_GET['nodo'])) { echo "?nodo=" . $_GET['nodo'];}?>&orden=tiempoTrans&cambiar_direccion"> Tiempo ejecución </a></th>
                        </tr>

                        
                            <?php

                                   

                                if(isset($_GET['cambiar_direccion'])) {
                                    // Cambiar la dirección almacenada en la sesión
                                    cambiodireccion();
                                }
                                
                                if(isset($_GET['orden'])) {
                                    
                                    tablaOrden($_GET['nodo'], $_GET['orden'], $_SESSION['direccion']);
                                }
                                else {
                                    tablaNodo($_GET['nodo']);
                                }
                                
                        } ?>

                    </table>

                </div>
            </div>
        </div>
    </div>
</div>



<?php

    include('includes/footer.php');

?>