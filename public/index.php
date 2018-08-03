<?php

    require_once "const.php";
    require_once "autoload.php";
    
    //cuando incluyo un archivo, buscar en este directorio
    //antes de explotar.
    set_include_path(VENDOR.PATH_SEPARATOR.get_include_path());

    //instancio aplicacion
    $application = new Zend_Application(APPLICATION_ENV,array('config'=>CONFIGURACION));

    //run bootstrap for application
    $application->bootstrap()->run();

?>

