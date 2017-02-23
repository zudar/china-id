<?php
namespace Zudar\ChinaID\Exception;

class NotValidIdException extends ChinaIDException
{
    public function __construct($idNum=''){
        parent::__construct("The ID [$idNum] is not a valid China ID.");
    }
}

?>