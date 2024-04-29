package logic;

import java.io.File;

public class Constantes {
    public static final String URLCREATE = "jdbc:mysql://localhost:3306?allowMultiQueries=true";
    public static final String URL = "jdbc:mysql://localhost:3306/logsData";   // 3306  <--->  3307

    public static final String USER = "root";
    public static final String PASSWORD = "";  // ""  <---> "1234"
    public static final File directorio = new File("..\\..\\..\\logs"); //logs   <--->  ..\\..\\..\\logs
}
