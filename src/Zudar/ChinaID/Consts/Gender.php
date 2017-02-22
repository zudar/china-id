<?php
namespace Zudar\ChinaID\Consts;

class Gender
{
    // 男
    const MALE = 1;
    // 女
    const FEMALE = 2;

    public static function getDesc($gender = 1)
    {
        switch ($gender) {
            case static::MALE:
                return "男";
            case static::FEMALE:
                return "女";
        }
        return '';
    }
}

?>