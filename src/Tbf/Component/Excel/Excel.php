<?php
namespace Tbf\Component\Excel;
use Tbf\Component\Excel\Driver\DriverInterface;
class Excel{
    private $driver;
    function __construct(DriverInterface $driver){
        $this->driver = $driver;
    }
    function getImporter(){
        return new Importer($this->driver->getReader());
    }
    function getExporter(){
        return new Exporter($this->driver->getWriter());
    }
}