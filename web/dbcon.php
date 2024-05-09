<?php

function conexionBD() {

    $host="127.0.0.1:3307";
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

function botonNodos($fecha) {

    $conexion = conexionBD();

    $sql = "SELECT nombreNodo FROM nodo GROUP BY nombreNodo";
    $result = mysqli_query($conexion, $sql);

    $lineaDeBotones = '<div class="row">';

    while ($row = mysqli_fetch_assoc($result)) {
        $url = 'index.php?fecha=' . $fecha . '&nodo=' . $row['nombreNodo'];
        $lineaDeBotones .= '<div class="col"><a href="' . $url . '" class="btn btn-primary">' . $row['nombreNodo'] . '</a></div>';
    }

    $lineaDeBotones .= '</div><br>';

// Ahora puedes usar $lineaDeBotones donde lo necesites
    echo $lineaDeBotones;
}

function tablaNodo($nodo, $fecha) {

    $conexion = conexionBD();

    $sql = "SELECT nodo.nombreNodo, palabra_clave.nombre, informacion.fechaInfo, informacion.tiempoTrans 
    FROM informacion 
    LEFT JOIN palabra_clave ON informacion.codClave = palabra_clave.codClave 
    LEFT JOIN nodo ON informacion.codLog = nodo.codLog
    LEFT JOIN fecha_registro ON fecha_registro.codFecha = informacion.codFecha
    WHERE nodo.nombreNodo = '$nodo' AND informacion.codFecha = $fecha";

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

function tablaOrden($nodo, $orden, $direccion, $fecha) {

    $conexion = conexionBD();

    $sql = "SELECT nodo.nombreNodo, palabra_clave.nombre, informacion.fechaInfo, informacion.tiempoTrans 
    FROM informacion 
    LEFT JOIN palabra_clave ON informacion.codClave = palabra_clave.codClave 
    LEFT JOIN nodo ON informacion.codLog = nodo.codLog
    LEFT JOIN fecha_registro ON fecha_registro.codFecha = informacion.codFecha
    WHERE nodo.nombreNodo = '$nodo' AND informacion.codFecha = $fecha
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

function generarListado() {

    $conexion = conexionBD();


    $sql = "SELECT fechaRegistro, codFecha
            FROM fecha_registro";

    $result = mysqli_query($conexion, $sql);

    echo "<option value=\"\" disabled selected>Selecciona una fecha</option>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<option value=\"" . $row['codFecha'] . "\">" . $row['fechaRegistro'] . "</option>";
    }

    
}

function tituloLog($fecha) {
    $conexion = conexionBD();


    $sql = "SELECT fechaRegistro
            FROM fecha_registro
            WHERE codFecha = $fecha";

    $result = mysqli_query($conexion, $sql);

    while ($row = mysqli_fetch_assoc($result)) {
        echo $row['fechaRegistro'];
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