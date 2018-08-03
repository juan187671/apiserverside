<?php
    //defino constantes

    define("DS",DIRECTORY_SEPARATOR); // Si tengo varios proyectos en un mismo root.    
    define("ROOT",realpath(__DIR__.'/..'));

    // Api acutal. Puede existir mas de una.
    $app = "api";
    define("APP",ROOT.DS.$app);

    define("PROJECT",ROOT.DS."project"); // Donde esta project.    
    define("MODELS",PROJECT.DS."models"); // Donde estan models.
    define("DAOS",PROJECT.DS."daos"); // Donde estan daos.
    define("VENDOR",ROOT.DS."vendor2"); // Donde esta la carpeta vendor. 
    
    define("BOOTSTRAP",APP.DS."bootstrap.php"); // Donde esta archivo Bootstrap.
    define("CONFIGURACION",APP.DS.'config.ini'); // Donde se encuentra el archivo config.ini
    define("CONTROLLERS",APP.DS."controllers"); // Donde estan los controladores del API.
    define("APPLICATION_ENV","mi_maquina"); // Entorno actual (local,test,referencia,produccion,etc..)
    
?>