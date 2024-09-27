<?php
$env = parse_ini_file('.env');
define("ENV", $env["ENV"]);

/**
 * Mixed function to process data the isolated way. Extends from Object Cake Class.
 * @method rrmdir 
 * @method existsTable 
 * @method execPythonPDFReader
 * 
 */

class ToolsComponent extends Object
{

  /**
   * Delete all subdir on parent dir.
   * @param string $dir path to analize for subdir folders
   * @return void
   */
  function rrmdir($dir)
  {
    if (is_dir($dir)) {
      $objects = scandir($dir);
      foreach ($objects as $object) {
        if ($object != "." && $object != "..") {
          if (is_dir($dir . DIRECTORY_SEPARATOR . $object) && !is_link($dir . "/" . $object))
            rmdir($dir . DIRECTORY_SEPARATOR . $object);
          else
            unlink($dir . DIRECTORY_SEPARATOR . $object);
        }
      }
      rmdir($dir);
    }
  }

  /**
   * 
   * Validate if tables exist on Database used. If database don't exist will be created. The columns' table don't will be created
   * 
   * @return void
   */
  function existsTable()
  {

    $tablename = "ajustes";
    $db = ConnectionManager::getDataSource("default");
    $query = "SELECT EXISTS (SELECT 1 FROM pg_tables where tablename = '$tablename') as table_exists;";
    $sql_response = $db->query($query);

    if (!$sql_response[0][0]["table_exists"]) {
      $query_create_table = "CREATE TABLE IF NOT EXISTS $tablename (id int NOT NULL PRIMARY KEY)";
      $query_create_seq = "CREATE SEQUENCE IF NOT EXISTS $tablename" . "_id_seq";
      $query_alter_table_id = "ALTER TABLE $tablename ALTER COLUMN id SET DEFAULT nextval('$tablename" . "_id_seq'::regclass)";
      $db->query($query_create_table);
      $db->query($query_create_seq);
      $db->query($query_alter_table_id);
    }
  }

  /**
   * Script shell function to trigger python scriipt. This python script read the PDF data text and return array text
   * @param string $nombre_archivo  file name to read
   * @param string $ruta path to find the file set above
   * @return array(string)
   */

  function execPythonPDFReader($nombre_archivo, $ruta)
  {
    if (ENV == "DEV") {
      $command = escapeshellcmd("python py/read_pdf.py " . $nombre_archivo . " " . $ruta);
      $output = shell_exec($command);
      return $output;
    } else if (ENV == "PROD") {
      $command = escapeshellcmd("python3 py/read_pdf.py " . $nombre_archivo . " " . $ruta);
      $output = shell_exec($command);
      return $output;
    }
  }
}
