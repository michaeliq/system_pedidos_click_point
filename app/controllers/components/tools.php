<?php

class ToolsComponent extends Object {
    
    function rrmdir($dir) { 
        if (is_dir($dir)) { 
          $objects = scandir($dir);
          foreach ($objects as $object) { 
            if ($object != "." && $object != "..") { 
              if (is_dir($dir. DIRECTORY_SEPARATOR .$object) && !is_link($dir."/".$object))
                rmdir($dir. DIRECTORY_SEPARATOR .$object);
              else
                unlink($dir. DIRECTORY_SEPARATOR .$object); 
            } 
          }
          rmdir($dir); 
        } 
      }

    function existsTable(){
      
      $tablename = "ajustes";
      $db = ConnectionManager::getDataSource("default");
      $query = "SELECT EXISTS (SELECT 1 FROM pg_tables where tablename = '$tablename') as table_exists;";
      $sql_response = $db->query($query);

      if(!$sql_response[0][0]["table_exists"]){
        $query_create_table = "CREATE TABLE IF NOT EXISTS $tablename (id int NOT NULL PRIMARY KEY)";
        $query_create_seq = "CREATE SEQUENCE IF NOT EXISTS $tablename"."_id_seq";
        $query_alter_table_id = "ALTER TABLE $tablename ALTER COLUMN id SET DEFAULT nextval('$tablename"."_id_seq'::regclass)";
        $db->query($query_create_table);
        $db->query($query_create_seq);
        $db->query($query_alter_table_id);
      }
    }

    function execPythonPDFReader($nombre_archivo, $ruta){
      $command = escapeshellcmd("python3 py/read_pdf.py ". $nombre_archivo . " " . $ruta);
      $output = shell_exec($command);
      return $output;
    }
}

?>