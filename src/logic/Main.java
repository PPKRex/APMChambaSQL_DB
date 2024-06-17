package logic;

import java.io.*;
import java.sql.*;
import java.time.LocalDateTime;
import java.util.*;

import static logic.Constantes.*;

public class Main {

    public void logic(){

        //Variables de las Consultas SQL
        String insertFecha = "INSERT INTO fecha_registro (email,fechaRegistro) VALUES (?,?)";
        String insertNodo = "INSERT IGNORE INTO nodo (codLog, codTerminal, nombreNodo) VALUES (?,?,?);";
        String insertInfo = "INSERT IGNORE INTO informacion (codLog,codTerminal, codFecha, codInf, codClave, fechainfo, tiempoTrans) VALUES (?,?,?,?,?,?,?);";
        String selectClave = "SELECT codClave, nombre FROM palabra_clave WHERE email is null;";
        String selectClaveUsuario = "SELECT codClave, nombre FROM palabra_clave WHERE email = ?;";
        String selectUsers = "SELECT email FROM usuario;";
        int users = 0, userSelected = 0;
        String password = "", passwordUser;
        String selectDATABASES = "SHOW DATABASES;";
        String selectMaxFecha = "SELECT codFecha FROM logsdata.fecha_registro ORDER BY codFecha desc limit 1;";
        String selectPassword = "SELECT passW FROM usuario Where email = ?;";
        String selectTerminal = "SELECT codTerminal, nombreTerminal FROM terminal WHERE codTerminal = ? AND nombreTerminal = ?";
        String[] urlcreates = new String[2];
        urlcreates[0] = URLCREATE;
        urlcreates[1] = URLCREATEIVAN;
        String[] urls = new String[2];
        urls[0] = URL;
        urls[1] = URLIVAN;
        int usuario = definirUsuario(USUARIOMIN,USUARIOMAX);

        // Mapa encargado de contener el resultado de la consultaSQL que palabrasClave
        Map<String, String> palabrasBusqueda = new HashMap<>();

        // Mapa encargado de contener el resultado de la consultaSQL de usuarios
        Map<Integer, String> usuariosDatabase = new HashMap<>();

        // Variables encargadas de guardar y formatear la fecha exacta de ejecución del programa.
        // Así como saber que codFecha tendrá en la base de datos
        LocalDateTime fecha;
        fecha = LocalDateTime.now();
        String [] fechaRecortada;
        fechaRecortada = fecha.toString().split(",");
        final String fechaFinal = fechaRecortada[0];
        String codFecha = null;

        // Variables para contar las lineas,numeroLinea será para guardar orden en la base de datos
        // y numeroLineasTotal será para informar al usuario
        int numeroLinea = 0, numeroLineasTotal = 0;

        // Variables encargadas del proceso de multihilo.
        List<ThreadClass> hilos = new ArrayList<>();
        File [] arrayFile = directorio.listFiles();
        File [] terminalFiles;
        File [] logFiles;
        String ficheroName;

        // Variables encargadas de la conexión con la base de datos.
        Connection connection = null;
        Statement statement = null;
        PreparedStatement preparedStatement = null;
        ResultSet resultSet= null;
        boolean databaseExists = false;

        // Variables encargadas de formatear debidamente los nombres de los logs para guardarlos correctamente en la database.
        String[] logNameParts, lineParts;
        String logName;

        //Comprobación de existencia del directorio logs
        if (!directorio.exists()){
            directorio.mkdir();
        }
        // Comprobación de existencia de ficheros en el directorio logs
        if(arrayFile!= null) {

            // Comprobación de existencia de la BASEse de datos, si no existe la crearemos.

            try{
                connection = DriverManager.getConnection(urlcreates[usuario],USER,PASSWORD);
                statement = connection.createStatement();
                resultSet = statement.executeQuery(selectDATABASES);
                while (resultSet.next()){
                    if (DATABASE_NAME.equals(resultSet.getString(1))){
                        databaseExists = true;
                    }

                }
                if (!databaseExists){
                    createDatabase(urlcreates, urls,usuario);
                }

            } catch (SQLException e) {
            }

            try{
                // Iniciación de la base de datos
                Class.forName("com.mysql.cj.jdbc.Driver");
                connection = DriverManager.getConnection(urls[usuario],USER,PASSWORD);
                statement = connection.createStatement();
                resultSet = statement.executeQuery(selectUsers);
                while(resultSet.next()){
                    // Consultamos en la base de datos si existen usuarios, si no existen el programa abortará, si existen se pedirá uno.
                    users += 1;
                    usuariosDatabase.put(users, resultSet.getString("email"));
                }
                if (users == 0){
                    throw new IllegalArgumentException("NO PUEDES EJECUTAR EL PROGRAMA SIN USUARIOS REGISTRADOS");
                }else{
                    System.out.println("--------Lista de usuarios--------\n");
                    for(Map.Entry<Integer,String> entry: usuariosDatabase.entrySet()){
                        System.out.printf("    %d -> %s\n\n", entry.getKey(), entry.getValue());
                    }
                    System.out.println("Escribe el número del usuario que deseas.");
                    do {
                        userSelected = readInt();
                    } while (userSelected < 1 || userSelected > users);
                    String userEmail = usuariosDatabase.get(userSelected);
                    PreparedStatement sentence = connection.prepareStatement(selectPassword);
                    sentence.setString(1,userEmail);
                    ResultSet resultSet2 = sentence.executeQuery();
                    while(resultSet2.next()){
                        password = resultSet2.getString("passW");
                    }
                    do {
                        System.out.printf("Por favor, introduce la contraseña para '%s' \n", usuariosDatabase.get(userSelected));
                        passwordUser = readString();
                        if (!Objects.equals(password, passwordUser)){
                            System.out.println("Contraseña incorrecta");
                        }else{
                            System.out.println("Contraseña correcta, leyendo archivos ...");
                        }
                    } while (!Objects.equals(password, passwordUser));
                }

            }catch (ClassNotFoundException e) {
                System.err.println("Error en el Driver");
            } catch (SQLException e) {
                System.err.println("Error en el SQL, quizá el servidor se encuentra cerrado");
                throw new RuntimeException(e);
            }finally{
                try {
                    resultSet.close();
                    statement.close();
                    connection.close();
                } catch (SQLException e) {
                    System.err.println("Error en el cerrado");
                    throw new RuntimeException(e);
                }
            }

            try{
                // Iniciación de la base de datos
                Class.forName("com.mysql.cj.jdbc.Driver");
                connection = DriverManager.getConnection(urls[usuario],USER,PASSWORD);
                statement = connection.createStatement();
                resultSet = statement.executeQuery(selectClave);
                while(resultSet.next()){  // Consultamos en la base de datos las palabras clave comunes y llenamos el mapa con ellas.
                    palabrasBusqueda.put(resultSet.getString("codClave"), resultSet.getString("nombre"));
                }
                PreparedStatement sentence = connection.prepareStatement(selectClaveUsuario);
                sentence.setString(1,usuariosDatabase.get(userSelected));
                resultSet = sentence.executeQuery();
                while(resultSet.next()){  // Consultamos en la base de datos las palabras clave de usuario y llenamos el mapa con ellas.
                    palabrasBusqueda.put(resultSet.getString("codClave"), resultSet.getString("nombre"));
                }
                for (Map.Entry<String, String> entrada : palabrasBusqueda.entrySet()) {
                    System.out.println("Criterio de busqueda: " + entrada.getValue());
                }
                // Guardamos en la base de datos la fecha en la que se ejecutó el programa
                preparedStatement = connection.prepareStatement(insertFecha);
                preparedStatement.setString(1,usuariosDatabase.get(userSelected));
                preparedStatement.setString(2,fechaFinal);
                preparedStatement.executeUpdate();

                // Hacemos un select para saber que código de fecha tiene la ejecución actual.
                resultSet = statement.executeQuery(selectMaxFecha);
                while(resultSet.next()){
                    codFecha =  resultSet.getString("codFecha") ;
                }
            }catch (ClassNotFoundException e) {
                System.out.println("Error en el Driver");
            } catch (SQLException e) {
                throw new RuntimeException(e);
            }finally{
                try {
                    resultSet.close();
                    statement.close();
                    connection.close();
                } catch (SQLException e) {
                    throw new RuntimeException(e);
                }
            }

            // Creación de hilos para lectura eficiente de logs, 1 hilo = 1 log
            for (int i = 0; i < arrayFile.length; i++){
                String terminal = "";
                if (arrayFile[i].isDirectory()){
                    terminalFiles = arrayFile[i].listFiles();
                    for (int j = 0; j < terminalFiles.length; j++){
                        if (terminalFiles[j].toString().contains(usuariosDatabase.get(userSelected))){
                            logFiles = terminalFiles[j].listFiles();
                            try{
                                Class.forName("com.mysql.cj.jdbc.Driver");
                                connection = DriverManager.getConnection(urls[usuario],USER,PASSWORD);
                                String terminalName = arrayFile[i].toString();
                                String [] terminalNames = terminalName.split("_");
                                PreparedStatement sentence = connection.prepareStatement(selectTerminal);
                                sentence.setString(1,terminalNames[0]);
                                sentence.setString(2,terminalNames[1]);
                                ResultSet resultSet2 = sentence.executeQuery();
                                while(resultSet2.next()){
                                    terminal = resultSet2.getString("codTerminal");
                                }
                            } catch (SQLException e) {
                                throw new RuntimeException(e);
                            } catch (ClassNotFoundException e) {
                                throw new RuntimeException(e);
                            }
                            for (int k = 0; k < logFiles.length; k++){
                                ficheroName = logFiles[k].getName();
                                if (ficheroName.contains(".log")) {
                                    System.out.println("\t"+ficheroName);
                                    hilos.add(new ThreadClass(logFiles[k], palabrasBusqueda, terminal));
                                    hilos.get(hilos.size()-1).start();
                                }
                            }

                        }
                    }
                    }

                /*
                ficheroName = arrayFile[i].getName();
                if (ficheroName.contains(".log")) {
                    hilos[i] = new ThreadClass(arrayFile[i],palabrasBusqueda);
                    hilos[i].start();

                }
                */
            }

            try{
                Class.forName("com.mysql.cj.jdbc.Driver");
                connection = DriverManager.getConnection(urls[usuario],USER,PASSWORD);
                for(int i = 0; i < hilos.size(); i++){ // Iteramos cada hilo para obtener su información
                    numeroLinea = 0;
                    ficheroName = hilos.get(i).getLog().getName();
                    String terminal = hilos.get(i).getTerminal();
                    if (ficheroName.contains(".log")) {
                        hilos.get(i).join();
                        if (hilos.get(i).getListaLocal() != null){ //Obtenemos de los hilos una lista con toda la información en lineas
                            logNameParts = ficheroName.split("\\."); //Obtenemos el nombre formateado del fichero de este hilo
                            System.out.println(ficheroName);
                            if (logNameParts[0].contains("-")) {
                                logNameParts = logNameParts[0].split("-");
                                logName = logNameParts[1];
                            }else{
                                logName = logNameParts[0];
                            }


                            // Si es la primera vez que el log es leido añadiremos su nombre a la base de datos
                            preparedStatement = connection.prepareStatement(insertNodo);
                            preparedStatement.setString(1,ficheroName);
                            preparedStatement.setString(2,terminal);
                            preparedStatement.setString(3,logName);
                            preparedStatement.executeUpdate();

                            for (String linea: hilos.get(i).getListaLocal()){ //Iteramos la lista del hilo para subir cada linea a la base de datos
                                numeroLinea++;
                                numeroLineasTotal++;
                                if (linea  != null){
                                    lineParts = linea.split("/"); // Separamos el contenido de la linea y añadimos cada parte a una instrucción de la SQL
                                    preparedStatement = connection.prepareStatement(insertInfo);
                                    preparedStatement.setString(1,ficheroName);
                                    preparedStatement.setString(2,terminal);
                                    preparedStatement.setInt(3, Integer.parseInt(codFecha));
                                    preparedStatement.setString(4, String.valueOf(numeroLinea));
                                    preparedStatement.setString(5,lineParts[0]);
                                    preparedStatement.setString(6,lineParts[1]);
                                    preparedStatement.setString(7,lineParts[2]);
                                    preparedStatement.executeUpdate();
                                    System.out.println("Sube");
                                    System.out.println(ficheroName + codFecha + lineParts[0] +  lineParts[1]  + lineParts[2]);
                                }
                            }
                        }
                    }
                }
                // El programa finaliza mostrando al usuario cuantas coincidencias encontró en total
                System.out.println(numeroLineasTotal + " Coincidencias encontradas");
                connection.close();
            } catch (InterruptedException e) {
                throw new RuntimeException(e);
            } catch (SQLException e) {
                throw new RuntimeException(e);
            } catch (ClassNotFoundException e) {
                throw new RuntimeException(e);
            }
        }else{
            System.out.println("NO HAY NINGÚN FICHERO .LOG EN LA CARPETA LOGS.");
        }

    }

    public int definirUsuario(int usuarioMin, int usuarioMax){
        int usuario = 0;
        if (usuarioMax < usuarioMin) {
            throw new ArithmeticException("Número fuera de rango1");
        } else if (usuarioMax <= Byte.MIN_VALUE) {
            throw new ArithmeticException("Número fuera de rango2");
        } else if (usuarioMin >= Byte.MAX_VALUE) {
            throw new ArithmeticException("Número fuera de rango3");
        }
        do {
            System.out.printf("Introduce un usuario en rango -> %d-%d\n", usuarioMin, usuarioMax);
            usuario = readInt();
        } while (usuario < usuarioMin || usuario > usuarioMax);

        return usuario;
    }

    public String readString(){
        Scanner keyboard = new Scanner(System.in);
        return keyboard.nextLine();
    }
    public int readInt(){
        Scanner keyboard = new Scanner(System.in);
        boolean exit = false;
        int numero = 0;
        do {
            try {
                numero = keyboard.nextInt();
                exit = true;
            } catch (InputMismatchException e) {
                System.out.println("Usuario no válido\n");
            } finally {
                keyboard.nextLine();
            }

        } while (!exit);

        return numero;
    }


    public void createTerminals(String [] urls, int usuario){
        String selectTerminal = "SELECT codTerminal, nombreTerminal FROM terminal";
        Connection connexion;
        Statement statement;
        ResultSet resultSet;
        String folderName;
        File carpeta = null;
        try {
            Class.forName("com.mysql.cj.jdbc.Driver");
            connexion = DriverManager.getConnection(urls[usuario], USER, PASSWORD);
            statement = connexion.createStatement();
            resultSet = statement.executeQuery(selectTerminal);
            while(resultSet.next()){  // Consultamos en la base de datos las terminales, para crear las carpetas
                folderName = String.format("%s_%s", resultSet.getString("codTerminal"), resultSet.getString("nombreTerminal"));
                carpeta = new File(String.format("%s/%s",String.format(String.valueOf(directorio)), folderName));
                carpeta.mkdir();
            }
        } catch (ClassNotFoundException | SQLException e) {
            throw new RuntimeException(e);
        }
    }
    public void createDatabase(String [] urlCreates, String [] urls, int usuario){ // Este método se usa para crear la base de datos en caso de que no exista.
        File script = new File(DATABASE_CREATE);
        Connection connexion;
        StringBuilder stringBuilder = new StringBuilder();
        String linea , consulta, salto = System.getProperty("line.separator");
        int res ;
        FileReader fr = null;
        try {
            fr = new FileReader(script);
        } catch (FileNotFoundException e) {
            throw new RuntimeException(e);
        }
        BufferedReader br = new BufferedReader(fr);
        while (true) {
            try {
                if (!((linea = br.readLine()) != null)) break;
            } catch (IOException e) {
                throw new RuntimeException(e);
            }
            stringBuilder.append(linea);
            stringBuilder.append(salto);
        }
        consulta = stringBuilder.toString();
        System.out.println(consulta);
        try {
            Class.forName("com.mysql.cj.jdbc.Driver");
            connexion = DriverManager.getConnection(urlCreates[usuario], USER, PASSWORD);
            Statement sentencia = connexion.createStatement();
            res = sentencia.executeUpdate(consulta);
            System.out.println("funca, " + res);
            createTerminals(urls,usuario);
            connexion.close();
            sentencia.close();
            br.close();
            fr.close();
        } catch (ClassNotFoundException e) {
            throw new RuntimeException(e);
        } catch (SQLException e) {
            throw new RuntimeException(e);
        } catch (IOException e) {
            throw new RuntimeException(e);
        }
    }
    public static void main(String[] args) {
        new Main().logic();
    }
}
