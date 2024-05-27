<?php

// Función que realiza la conexión a la base de datos
function conexionBD() {

    $host="127.0.0.1";
    $usuario="root";
    $pass="";
    $nom_db = "logsdata";

    //Se le pasa los parámetros para la conexión con MySQL
    $conexion = mysqli_connect($host, $usuario, $pass);
    mysqli_select_db($conexion, $nom_db);

    // Control de errores
    if(!$conexion){
        echo "<script>console.log('Fallo en la conexión');</script>";;
    }
    else {
        return $conexion;
    }

}

//Función que controla los usuarios de la web
function login($usuario, $email, $pass) {

    //Pasamos a la función los 3 datos del registro, y, si el usuario es null (ya que se inicia sesion con el email)
    // Entonces detecta como un inicio de sesion en lugar de un registro
    $conexion = conexionBD();

    //Si es null = login
    if ($usuario == null) {

        $sql = "SELECT email 
                FROM usuario 
                WHERE email = '$email' AND passW = '$pass';"; 

    $result = mysqli_query($conexion, $sql);

    //Si el resultado da alguna coincidencia, se crea la variable de sesion del usuario y se redirige a la pagina principal
    if ($result) {
        $_SESSION['usuario'] = $usuario;
        header("Location: ../index.php");
    }
    //Si no, devuelve a la pagina principal sin mas
    else {
        header("Location: ../index.php");
    }

    }
    //Si usuario != null, entonces lo interpreta como un registro y lo añade a la base de datos.
    else {

        $sql = "INSERT INTO usuario(email, userName, passW) VALUES ('$email', '$usuario', '$pass')";
        $result = mysqli_query($conexion, $sql);
        
        if ($result) {
            $_SESSION['usuario'] = $email;
            header("Location: ../index.php");
        }
        //Si no, devuelve a la pagina principal sin mas
        else {
            header("Location: ../index.php");
        }
        
    }

}

//Función que muestra todos los nodos de la base de datos en forma de botón.
function botonNodos($fecha) {

    $conexion = conexionBD();

    //Buscamos todos los nodos y los ordenamos en función de su nombre para que no se repitan nombres (Center 1 y Center 2 pej)
    $sql = "SELECT nombreNodo FROM nodo GROUP BY nombreNodo";
    $result = mysqli_query($conexion, $sql);

    //Inicializamos la etiqueta para que se ordenen en fila en ese contenedor
    $lineaDeBotones = '<div class="row">';

    // Bucle que recorre todo el array de resultados de la sentencia anterior y lo escribe
    while ($row = mysqli_fetch_assoc($result)) {
        //Creamos la URL para que tenga los datos que nos hacen falta para los GET: la fecha de registro y el nombre del nodo a mostrar
        $url = 'index.php?fecha=' . $fecha . '&nodo=' . $row['nombreNodo'];
        //Escribimos la etiqueta para que cada botón rediriga a la URL especificada arriba, y como nombre visible de este botón
        //Será el nombre del nodo recogido anteriormente.
        $lineaDeBotones .= '<div class="col"><a href="' . $url . '"  class="botonNodos btn btn-primary">' . $row['nombreNodo'] . '</a></div>';
    }
    //Y cerramos el contenedor del botón.
    $lineaDeBotones .= '</div><br>';

// Como hemos ido añadiendo a la variable, al final nos queda una variable unica con todos los resultados de la consulta
// Y la escribimos en la página.
    echo $lineaDeBotones;
}

//Función que crea la tabla con todos los datos pasandole el nodo a buscar y la fecha registro.
function tablaNodo($nodo, $fecha) {

    $conexion = conexionBD();

    //Sentencia para buscar segun los parametros pasados a la funcion
    $sql = "SELECT nodo.nombreNodo, palabra_clave.nombre, informacion.fechaInfo, informacion.tiempoTrans 
    FROM informacion 
    LEFT JOIN palabra_clave ON informacion.codClave = palabra_clave.codClave 
    LEFT JOIN nodo ON informacion.codLog = nodo.codLog
    LEFT JOIN fecha_registro ON fecha_registro.codFecha = informacion.codFecha
    WHERE nodo.nombreNodo = '$nodo' AND informacion.codFecha = $fecha";

    $result = mysqli_query($conexion, $sql);

    //Muestra haciendo un bucle todos los resultados en la tabla para cada fila
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['nombreNodo'] . "</td>";
        echo "<td>" . $row['nombre'] . "</td>";
        echo "<td>" . $row['fechaInfo'] . "</td>";
        echo "<td>" . $row['tiempoTrans'] . "</td>";
        echo "</tr>";
    }

}

// Igual que la anterior pero ordenada
function tablaOrden($nodo, $orden, $direccion, $fecha) {

    $conexion = conexionBD();

    $sql = "SELECT nodo.nombreNodo, palabra_clave.nombre, informacion.fechaInfo, informacion.tiempoTrans 
    FROM informacion 
    LEFT JOIN palabra_clave ON informacion.codClave = palabra_clave.codClave 
    LEFT JOIN nodo ON informacion.codLog = nodo.codLog
    LEFT JOIN fecha_registro ON fecha_registro.codFecha = informacion.codFecha
    WHERE nodo.nombreNodo = '$nodo' AND informacion.codFecha = $fecha
    ORDER BY $orden $direccion"; // Según a qué campo de la tabla pulses, se ordenará en función de ese campo (orden)
    // Y si vuelve a pulsar, irá cambiando su direccion entre ascendente y descendente, haciendo que actualice la tabla

    $result = mysqli_query($conexion, $sql);

    //Muestra la tabla ya ordenada
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['nombreNodo'] . "</td>";
        echo "<td>" . $row['nombre'] . "</td>";
        echo "<td>" . $row['fechaInfo'] . "</td>";
        echo "<td>" . $row['tiempoTrans'] . "</td>";
        echo "</tr>";
    }

}

// Función que genera el listado del desplegable de fecha registro
function generarListado() {

    $conexion = conexionBD();

    // Buscamos todas las fechas con su código que tengamos en la base de datos
    $sql = "SELECT fechaRegistro, codFecha
            FROM fecha_registro";

    $result = mysqli_query($conexion, $sql);

    // Creamos una de las opciones del desplegable que no se pueda elegir con el atributo disabled
    echo "<option value=\"\" disabled selected>Selecciona una fecha</option>";

    // Y mostramos los resultados de la consulta, donde el valor será el código de la fecha y el texto visible, la fecha del registro
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<option value=\"" . $row['codFecha'] . "\">" . $row['fechaRegistro'] . "</option>";
    }

    
}

// Función que recoge de la URL la fecha registro del desplegable y la pasa por parámetro para buscarla en la base de datos
// Devuelve la clave primaria y la escribe en el encabezado de la tabla
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

// Función que cambia la variable de sesión de ASC a DESC y viceversa cuando se le llama para la sentencia de ordenar.
function cambiodireccion() {

    //Si es ASC cambia a DESC y si es DESC cambia a ASC
    if($_SESSION['direccion'] === 'ASC') {

        $_SESSION['direccion'] = 'DESC';

    } else {

        $_SESSION['direccion'] = 'ASC';

    }

}


?>