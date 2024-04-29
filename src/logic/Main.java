package logic;

import java.io.*;
import java.sql.*;
import java.time.LocalDateTime;
import java.util.*;

import static logic.Constantes.*;

public class Main {


    public void createDatabase(){
        File script = new File("scriptAPMDatabaseBlanco.sql");
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
            connexion = DriverManager.getConnection(URLCREATE, USER, PASSWORD);
            Statement sentencia = connexion.createStatement();
            res = sentencia.executeUpdate(consulta);
            System.out.println("funca, " + res);
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
    public void logic(){

        String insertFecha = "INSERT INTO fecha_registro (fechaRegistro) VALUES (?)";
        String insertNodo = "INSERT IGNORE INTO nodo (codLog, nombreNodo) VALUES (?,?);";
        String insertInfo = "INSERT IGNORE INTO informacion (codLog, codFecha, codInf, codClave, fechainfo, tiempoTrans) VALUES (?,?,?,?,?,?);";
        String selectClave = "SELECT codClave, nombre FROM palabra_clave;";
        String selectMaxFecha = "SELECT codFecha FROM logsdata.fecha_registro ORDER BY codFecha desc limit 1;";

        List<String> listaFinal = new ArrayList<>();
        Map<String, String> palabrasBusqueda = new HashMap<>();

        LocalDateTime fecha;
        fecha = LocalDateTime.now();
        String [] fechaRecortada;
        fechaRecortada = fecha.toString().split(",");
        final String fechaFinal = fechaRecortada[0];
        String codFecha = null;

        int numeroLinea = 0;
        ThreadClass[] hilos;
        File [] arrayFile = directorio.listFiles();
        String ficheroName;
        Connection connection = null;
        Statement statement = null;
        PreparedStatement preparedStatement = null;
        ResultSet resultSet= null;

        String[] logNameParts, lineParts;
        String logName;

        if (!directorio.exists()){
            directorio.mkdir();
        }
        if(arrayFile!= null) {
            hilos = new ThreadClass[arrayFile.length];
        }else{
            hilos = new ThreadClass[0];
        }

        // COMPROBAMOS SI EXISTE LA BASE DE DATOS, SI NO EXISTE LA CREAMOS.
        try{
            connection = DriverManager.getConnection(URL,USER,PASSWORD);
            statement = connection.createStatement();
            resultSet = statement.executeQuery(selectClave);
            if (!resultSet.next()){
                createDatabase();
            }

        } catch (SQLException e) {
            throw new RuntimeException(e);
        }
        try{
            Class.forName("com.mysql.cj.jdbc.Driver");
            connection = DriverManager.getConnection(URL,USER,PASSWORD);
            statement = connection.createStatement();
            resultSet = statement.executeQuery(selectClave);
            while(resultSet.next()){
                palabrasBusqueda.put(resultSet.getString("codClave"), resultSet.getString("nombre"));
            }
            //Estas tres lineas son las encargadas de los update de las tablas :)
            preparedStatement = connection.prepareStatement(insertFecha);
            preparedStatement.setString(1,fechaFinal);
            preparedStatement.executeUpdate();

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



        // Creación de hilos para lectura y subida de filas para la tabla nodo
        for (int i = 0; i < hilos.length; i++){
            ficheroName = arrayFile[i].getName();
            if (ficheroName.contains(".log")) {
                hilos[i] = new ThreadClass(arrayFile[i],palabrasBusqueda);
                hilos[i].start();

            }
        }


        try{
            Class.forName("com.mysql.cj.jdbc.Driver");
            connection = DriverManager.getConnection(URL,USER,PASSWORD);
            for(int i = 0; i < hilos.length; i++){
                numeroLinea = 0;
                ficheroName = arrayFile[i].getName();
                if (ficheroName.contains(".log")) {
                    hilos[i].join();
                    if (hilos[i].getListaLocal() != null){
                        logNameParts = ficheroName.split("\\.");
                        ficheroName = logNameParts[0];
                        logNameParts = logNameParts[0].split("-");
                        logNameParts = logNameParts[1].split(" ");
                        if (logNameParts.length > 1){
                            logName = logNameParts[1];
                        }else{
                            logName = logNameParts[0];
                        }
                        //Estas tres lineas son las encargadas de los update de las tablas :)
                        preparedStatement = connection.prepareStatement(insertNodo);
                        preparedStatement.setString(1,ficheroName);
                        preparedStatement.setString(2,logName);
                        preparedStatement.executeUpdate();

                        for (String linea: hilos[i].getListaLocal()){
                            numeroLinea++;
                            if (linea  != null){
                                lineParts = linea.split("/");

                                preparedStatement = connection.prepareStatement(insertInfo);
                                preparedStatement.setString(1,ficheroName);
                                preparedStatement.setInt(2, Integer.parseInt(codFecha));
                                preparedStatement.setString(3, String.valueOf(numeroLinea));
                                preparedStatement.setString(4,lineParts[1]);
                                preparedStatement.setString(5,lineParts[2]);
                                preparedStatement.setString(6,lineParts[3]);
                                preparedStatement.executeUpdate();
                                listaFinal.add(linea);
                            }
                        }
                    }
                }
            }
            connection.close();
        } catch (InterruptedException e) {
            throw new RuntimeException(e);
        } catch (SQLException e) {
            throw new RuntimeException(e);
        } catch (ClassNotFoundException e) {
            throw new RuntimeException(e);
        }

        for (String linea : listaFinal){
            System.out.println(linea);
        }


    }
    public static void main(String[] args) {
        new Main().logic();
    }
}

/*
try (Connection connection = DriverManager.getConnection("jdbc:mysql://127.0.0.1:3306/logsData", "root", "1234");
                PreparedStatement sentencia = connection.prepareStatement(sql);


                ResultSet resultSet = sentencia.executeQuery()) {
                while (resultSet.next()) {
                    palabrasBusqueda.put(resultSet.getString("codClave"), resultSet.getString("nombre"));
                }

            } catch (SQLException e) {
                throw new RuntimeException(e);
            }
            PreparedStatement psfecha = connection.prepareStatement(insertFecha);
            psfecha.setString(1,fechaFinal);
 */
