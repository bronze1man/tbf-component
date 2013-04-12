<?php
namespace Tbf\Component\Io;
use Tbf\Component\Io\Buffer\StringReaderBuffer;
class Io{
    static function NewStringReaderBuffer(StringReaderInterface $origin){
        return new StringReaderBuffer($origin);
    }
}