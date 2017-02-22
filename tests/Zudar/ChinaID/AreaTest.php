<?php
namespace Zudar\ChinaID;
use Zudar\ChinaID\AreaCode\ChinaAreaCode;
class AreaTest extends \PHPUnit_Framework_TestCase
{
    public function  testAreaString(){
        $this->assertEquals('上海市宝山区', ChinaAreaCode::getAreaString(310113));
        $this->assertEquals('新疆维吾尔自治区伊犁哈萨克自治州阿勒泰地区吉木乃县', ChinaAreaCode::getAreaString(654326));
    }
}

?>