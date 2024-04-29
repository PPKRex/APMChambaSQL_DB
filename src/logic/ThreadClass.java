package logic;

import java.io.*;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

public class ThreadClass extends Thread {
    private List<String> listaLocal = new ArrayList<>();
    private File log;
    private Map<String, String> palabrasBusqueda;
    public ThreadClass(File log, Map<String, String> palabrasBusqueda){
        this.log = log;
        this.palabrasBusqueda = new HashMap<>(palabrasBusqueda);
    }

    @Override
    public void run() {
        FileReader reader = null;
        BufferedReader bufR;
        int numeroLinea = 0;
        String line, lineToWrite = null;
        Pattern patternDate = Pattern.compile("(\\d{4}-\\w{2}-\\d{2} \\d{2}:\\d{2}:\\d{2}\\,\\d{3})");
        Pattern patternMS = Pattern.compile("in (\\d+) ms");
        Matcher matcherMS;
        Matcher matcher;
        String[] parts, logNameParts;
        String logName;

        try{
            reader = new FileReader(log);
            bufR = new BufferedReader(reader);
        }catch (FileNotFoundException e) {
            System.out.println("El archivo no se puede leer");
            throw new RuntimeException(e);
        }

        try{
            logNameParts = log.getName().split("\\.");
            logName = logNameParts[0];
            while ((line = bufR.readLine()) != null) {
                numeroLinea++;
                for (Map.Entry<String, String> entrada : palabrasBusqueda.entrySet()) {
                    if (line.contains(entrada.getValue())) {
                        lineToWrite = logName +"/"+ entrada.getKey()+"/";
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
                        listaLocal.add(lineToWrite);

                    }
                }
            }
            bufR.close();
        }catch(IOException e){
            e.printStackTrace();
        }
    }

    public List<String> getListaLocal(){
        return listaLocal;
    }
}
