package logic;

import java.io.*;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

public class ThreadClass extends Thread {

    // Variables locales y constructor de cada hilo
    private List<String> listaLocal = new ArrayList<>();
    private File log;
    private String nombreTerminal;
    private Map<String, String> palabrasBusqueda;
    public ThreadClass(File log, Map<String, String> palabrasBusqueda, String nombreTerminal){
        this.log = log;
        this.palabrasBusqueda = new HashMap<>(palabrasBusqueda);
        this.nombreTerminal = nombreTerminal;
    }

    @Override
    public void run() {
        //Variables para lectura de logs y conteo de lineas
        FileReader reader = null;
        BufferedReader bufR;
        String line, lineToWrite = null;
        int numeroLinea = 0;

        // Variables para la lectura de cada linea del log, extraen la información necesaria
        Pattern patternDate = Pattern.compile("(\\d{4}-\\w{2}-\\d{2} \\d{2}:\\d{2}:\\d{2}\\,\\d{3})");
        Pattern patternMS = Pattern.compile("in (\\d+) ms");
        Matcher matcherMS;
        Matcher matcher;

        // Variables para el formateado de las lineas
        String[] parts;

        // Comprobación de lectura del log
        try{
            reader = new FileReader(log);
            bufR = new BufferedReader(reader);
        }catch (FileNotFoundException e) {
            System.out.println("El archivo no se puede leer");
            throw new RuntimeException(e);
        }
        try{
            while ((line = bufR.readLine()) != null) { // Iteramos el fichero log
                numeroLinea++;
                for (Map.Entry<String, String> entrada : palabrasBusqueda.entrySet()) { // Iteramos el mapa de palabrasClave
                    // Comprobamos si la linea contiene con una palabra clave, si la contiene guardaremos esa linea con el siguiente formato
                    // CÓDIGO DE LA PALABRA CLAVE/FECHA DE EJECUCIÓN DE LA LINEA/TIEMPO DE EJECUCIÓN DE LA LINEA ("en caso de ser necesario)
                    if (line.contains(entrada.getValue())) {
                        lineToWrite = entrada.getKey()+"/";
                        matcher = patternDate.matcher(line);
                        matcherMS = patternMS.matcher(line);
                        if (matcher.find()) {
                            parts = matcher.group(1).split(",");
                            lineToWrite = lineToWrite + parts[0] + "/";
                        } else {
                            lineToWrite = lineToWrite + "/0000-00-00 00:00:00/";
                        }
                        if (matcherMS.find()){
                            lineToWrite = lineToWrite + matcherMS.group(0) + "\n";
                        }else{
                            lineToWrite = lineToWrite + "No especificado\n";
                        }
                        listaLocal.add(lineToWrite); // Añadimos todas las coincidencias a la lista, serán recogidas por el main y subidas a la base de datos.

                    }
                }
            }
            for (int i = 0; i < listaLocal.size(); i++){
                //System.out.println(listaLocal.get(i));
            }
            bufR.close();
        }catch(IOException e){
            e.printStackTrace();
        }
    }

    //Método para retornar la lista local sin romper la concurrencia del hilo.
    public List<String> getListaLocal(){
        return listaLocal;
    }

    public File getLog(){return log;}
    public String getTerminal(){return nombreTerminal;}
}
