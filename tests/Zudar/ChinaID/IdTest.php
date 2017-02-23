<?php

namespace Zudar\ChinaID;
use Zudar\ChinaID\ID\ID18;
use Zudar\ChinaID\Consts\Gender;
use Zudar\ChinaID\ID\ID15;
use Zudar\ChinaID\Exception\NotValidIdException;
class IdTest extends \PHPUnit_Framework_TestCase
{
    public function  testId18(){
        $id=new ID18('320706199108093125');
        $this->assertEquals(true, $id->isValidate());
        $this->assertEquals('1991-08-09', $id->getBirthday());
        $this->assertEquals(Gender::FEMALE, $id->getGender());
        $this->assertEquals('江苏省连云港市海州区', $id->getArea());
    }
    public function  testId15(){
        $id=new ID15('360924030429491');
        $this->assertEquals(true, $id->isValidate());
        $this->assertEquals('1903-04-29', $id->getBirthday());
        $this->assertEquals(Gender::MALE, $id->getGender());
        $this->assertEquals('江西省宜春市宜丰县', $id->getArea());
    }
    public function testGenerate18(){
        $id=new ID18();
        for($i=0;$i<10;$i++){
            $idNum= $id->generate(360924);
            $id->setIdNum($idNum);
//             echo $id;
        }
    }
    public function testGenerate15(){
        $id=new ID15();
        for($i=0;$i<10;$i++){
            $idNum= $id->generate();
            $id->setIdNum($idNum);
//             echo $id;
        }
    }
    public function testException(){
        try{
            $id=new ID18('320706199108093122');
        }catch (\Exception $e){
            $this->assertTrue($e instanceof NotValidIdException);
        }
    }
}