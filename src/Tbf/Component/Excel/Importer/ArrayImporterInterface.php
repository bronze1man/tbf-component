<?php
namespace Tbf\Component\Excel\Importer;
use Tbf\Component\Io\StringReaderInterface;
use Tbf\Component\Io\ArrayWriterInterface;
interface ArrayImporterInterface{
    function import(StringReaderInterface $src,ArrayWriterInterface $dest);
}