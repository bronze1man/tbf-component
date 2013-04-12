<?php
namespace Tbf\Component\Excel\Exporter;
use Tbf\Component\Io\ArrayReaderInterface;
use Tbf\Component\Io\StringWriterInterface;
interface ArrayExporterInterface{
    function export(ArrayReaderInterface $src,StringWriterInterface $dest);
}