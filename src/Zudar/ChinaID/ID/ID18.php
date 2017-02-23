<?php
namespace Zudar\ChinaID\ID;

use Zudar\ChinaID\AreaCode\ChinaAreaCode;
use Zudar\ChinaID\Consts\Gender;
use Zudar\ChinaID\Exception\IdStringException;
use Zudar\ChinaID\Exception\NotValidIdException;

/**
 * 第二代身份证
 *
 * @author zhang
 */
class ID18 implements IDInterface
{

    /**
     *
     * @var array 加权因子
     */
    protected $weight = [
        7,
        9,
        10,
        5,
        8,
        4,
        2,
        1,
        6,
        3,
        7,
        9,
        10,
        5,
        8,
        4,
        2,
        1
    ];

    /**
     *
     * @var array 校验码换算对应表
     */
    protected $validateNumTable = [
        1,
        0,
        'X',
        9,
        8,
        7,
        6,
        5,
        4,
        3,
        2
    ];

    public function __construct($idNum = '')
    {
        if (! empty($idNum))
            $this->setIdNum($idNum);
    }
    // 身份证号码
    private $idNum;

    public function setIdNum($idNum)
    {
        if (! is_string($idNum)) {
            throw new IdStringException();
        }
        $this->idNum = trim(strtoupper($idNum));
        if(!$this->isValidate()){
            throw new NotValidIdException($this->idNum);
        }
    }

    public function isValidate()
    {
        if (strlen($this->idNum) != 18)
            return false;
        if (! preg_match("/\\d{17}[X\\d]/", $this->idNum))
            return false;
        $areaCode = $this->getAreaCode();
        if (! ChinaAreaCode::checkAreaCode($areaCode)) {
            return false;
        }
        $birthdayTime = $this->getBirthday();
        if ($birthdayTime > time() || $birthdayTime < time() - 200 * 365 * 24 * 3600) {
            return false;
        }
        if ($this->calcValidateCode() != $this->idNum{17})
            return false;
        return true;
    }

    public function getArea()
    {
        return ChinaAreaCode::getAreaString($this->getAreaCode());
    }

    public function getGender()
    {
        $gender = substr($this->idNum, 16, 1);
        return $gender & 1 == 1 ? Gender::MALE : Gender::FEMALE;
    }

    public function getBirthday()
    {
        $birthdayCode = $this->getBirthdayCode();
        $year=substr($birthdayCode, 0, 4);
        $mon=substr($birthdayCode, 4, 2);
        $day=substr($birthdayCode, 6, 2);
        if(checkdate($mon, $day, $year)){
            return "$year-$mon-$day";
        }
        return '';
    }

    public function generate($areaCode = '', $birthday = 0, $gender = -1)
    {
        static $selectAreas;
        if (empty($areaCode)) {
            if (empty($selectAreas)) {
                $selectAreas = array_values(array_filter(array_keys(ChinaAreaCode::getAreas()), function ($code)
                {
                    return $code % 100 != 0;
                }));
            }
            $areaCode = $selectAreas[rand(0, count($selectAreas) - 1)];
        }
        if (empty($birthday)) {
            $birthday = time() - rand(16 * 365 , 100 * 365 )* 24 * 3600;
        }
        if (empty($gender)) {
            $genders = [
                Gender::FEMALE,
                Gender::MALE
            ];
            $gender = $genders[rand(0, 1)];
        }
        $seqNum = rand(2, 900);
        if ($gender == Gender::FEMALE) {
            if ($seqNum & 1 == 1) {
                $seqNum += 1;
            }
        } else {
            if ($seqNum & 1 == 0) {
                $seqNum += 1;
            }
        }
        $idNum = '' . $areaCode . date('Ymd', $birthday) . str_pad($seqNum, 3,'0',STR_PAD_LEFT);
        return $idNum . $this->calcValidateCode($idNum);
    }

    public function getAreaCode()
    {
        return substr($this->idNum, 0, 6);
    }

    public function getBirthdayCode()
    {
        return substr($this->idNum, 6, 8);
    }

    private function calcValidateCode($idNum = '')
    {
        if (empty($idNum)) {
            $idNum = $this->idNum;
        }
        $sum = 0;
        for ($i = 0; $i < 17; $i ++) {
            $sum += $idNum{$i} * $this->weight{$i};
        }
        $result = $sum % 11;
        return $this->validateNumTable{$result};
    }

    public function __toString()
    {
        return "ID:" . $this->idNum . "\r\n"."Gender:" . Gender::getDesc($this->getGender()) . "\r\n" . "Area:" . $this->getArea() . "\r\n" . "Birthday:" . $this->getBirthday();
    }
}

?>