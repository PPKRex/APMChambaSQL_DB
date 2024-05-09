package logic;

import java.io.File;

public class Constantes {

    public static final String URLCREATE = "jdbc:mysql://localhost:3307?allowMultiQueries=true";
    public static final String URL = "jdbc:mysql://localhost:3307/logsData";   // 3306  <--->  3307
    public static final String URLCREATEIVAN = "jdbc:mysql://localhost:3306?allowMultiQueries=true";
    public static final String URLIVAN = "jdbc:mysql://localhost:3306/logsData";
    public static final int USUARIOMIN = 0;
    public static final int USUARIOMAX = 1;

    public static final String DATABASE_NAME = "logsdata";
    public static final String USER = "root";
    public static final String PASSWORD = "";  // ""  <---> "1234"
    public static final File directorio = new File("..\\..\\..\\logs"); //logs   <--->  ..\\..\\..\\logs
    public static final String DATABASE_CREATE = "..\\..\\..\\scriptAPMDatabaseBlanco.sql";
}
