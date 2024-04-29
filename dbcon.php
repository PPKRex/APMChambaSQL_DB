<?php

function conexionBD() {

    $host="localhost";
    $usuario="root";
    $pass="";
    $nom_db = "logsdata";

    $conexion = mysqli_connect($host, $usuario, $pass);
    mysqli_select_db($conexion, $nom_db);

    if(!$conexion){
        echo "<script>console.log('Fallo en la conexión');</script>";;
    }
    else {
        return $conexion;
    }

}

function botonNodos() {

    $conexion = conexionBD();

    $sql = "SELECT nombreNodo FROM nodo";
    $result = mysqli_query($conexion, $sql);

    $lineaDeBotones = '<div class="row">';

    while ($row = mysqli_fetch_assoc($result)) {
        $url = 'index.php?nodo=' . $row['nombreNodo'];
        $lineaDeBotones .= '<div class="col"><a href="' . $url . '" class="btn btn-primary">' . $row['nombreNodo'] . '</a></div>';
    }

    $lineaDeBotones .= '</div><br>';

// Ahora puedes usar $lineaDeBotones donde lo necesites
    echo $lineaDeBotones;
}

function tablaNodo($nodo) {

    $conexion = conexionBD();

    $sql = "SELECT nodo.nombreNodo, palabra_clave.nombre, informacion.fechaInfo, informacion.tiempoTrans 
    FROM informacion 
    LEFT JOIN palabra_clave ON informacion.codClave = palabra_clave.codClave 
    LEFT JOIN nodo ON informacion.codLog = nodo.codLog
    WHERE nodo.nombreNodo = '$nodo'";

    $result = mysqli_query($conexion, $sql);

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['nombreNodo'] . "</td>";
        echo "<td>" . $row['nombre'] . "</td>";
        echo "<td>" . $row['fechaInfo'] . "</td>";
        echo "<td>" . $row['tiempoTrans'] . "</td>";
        echo "</tr>";
    }

}

function tablaOrden($nodo, $orden, $direccion) {

    $conexion = conexionBD();

    $sql = "SELECT nodo.nombreNodo, palabra_clave.nombre, informacion.fechaInfo, informacion.tiempoTrans 
    FROM informacion 
    LEFT JOIN palabra_clave ON informacion.codClave = palabra_clave.codClave 
    LEFT JOIN nodo ON informacion.codLog = nodo.codLog
    WHERE nodo.nombreNodo = '$nodo'
    ORDER BY $orden $direccion";

    $result = mysqli_query($conexion, $sql);
    

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['nombreNodo'] . "</td>";
        echo "<td>" . $row['nombre'] . "</td>";
        echo "<td>" . $row['fechaInfo'] . "</td>";
        echo "<td>" . $row['tiempoTrans'] . "</td>";
        echo "</tr>";
    }

}

function cambiodireccion() {

    if($_SESSION['direccion'] === 'ASC') {
        $_SESSION['direccion'] = 'DESC';
    } else {
        $_SESSION['direccion'] = 'ASC';
    }

}


?>