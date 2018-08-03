<?php
namespace patterns\factory;

//ej.
//$ClienteDao = Factory::create(new FactoryDao(), "Cliente");
//$DeviceDao = Factory::create(new FactoryDao(), "Device");
//$FacturaDao = Factory::create(new FactoryDao(), "Factura");

//$ClienteDao = Factory::create(FactoryDao::getInstance(), "Cliente");
//$DeviceDao = Factory::create(FactoryDao::getInstance(), "Device");
//$FacturaDao = Factory::create(FactoryDao::getInstance(), "Factura");

//$Cliente = Factory::create(new FactoryModel(), "Cliente");
//$Device = Factory::create(new FactoryModel(), "Device");
class Factory {
    public static function create(FactoryMethod $F, $class){
        return $F->create($class);
    }
}
