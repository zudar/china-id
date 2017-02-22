<?php
namespace Zudar\ChinaID\Exception;

class IdStringException extends ChinaIDException
{
    public function __construct(){
        parent::__construct("China Id must be a String value.");
    }
}

?>