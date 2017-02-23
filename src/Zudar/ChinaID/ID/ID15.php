<?php
namespace Zudar\ChinaID\ID;

use Zudar\ChinaID\AreaCode\ChinaAreaCode;
use Zudar\ChinaID\Consts\Gender;
use Zudar\ChinaID\Exception\IdStringException;
use Zudar\ChinaID\Exception\NotValidIdException;

/**
 * 第一代身份证
 *
 * @author zhang
 */
class ID15 implements IDInterface
{

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
        if (strlen($this->idNum) != 15)
            return false;
        if (! preg_match("/\\d{15}/", $this->idNum))
            return false;
        $areaCode = $this->getAreaCode();
        if (! ChinaAreaCode::checkAreaCode($areaCode)) {
            return false;
        }
        $birthdayTime = $this->getBirthday();
        if ($birthdayTime > time() || $birthdayTime < time() - 200 * 365 * 24 * 3600) {
            return false;
        }
        return true;
    }

    public function getArea()
    {
        return ChinaAreaCode::getAreaString($this->getAreaCode());
    }

    public function getGender()
    {
        $gender = substr($this->idNum, 14, 1);
        return $gender & 1 == 1 ? Gender::MALE : Gender::FEMALE;
    }

    /**
     * 出生日期都是2000年前
     */
    public function getBirthday()
    {
        $birthdayCode = $this->getBirthdayCode();
        $year=($this->isOver100Age() ? "18" : "19") . substr($birthdayCode, 0, 2);
        $mon=substr($birthdayCode, 2, 2);
        $day=substr($birthdayCode, 4, 2);
        if(checkdate($mon, $day, $year)){
            return "$year-$mon-$day"; 
        }
        return '';
    }

    private function isOver100Age()
    {
        return in_array($this->getSeqNum(), array(
            999,
            998,
            997,
            996
        ));
    }

    private function getSeqNum()
    {
        return substr($this->idNum, 12, 3);
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
            $selectIndex=rand(0, count($selectAreas) - 1);
            $areaCode = $selectAreas[$selectIndex];
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
            if (date("Y", $birthday) > 1900) {
                if ($seqNum & 1 == 1) {
                    $seqNum += 1;
                }
            } else {
                $female = [
                    996,
                    998
                ];
                $seqNum = $female[rand(0, 1)];
            }
        } else {
            if (date("Y", $birthday) > 1900) {
                if ($seqNum & 1 == 0) {
                    $seqNum += 1;
                }
            } else {
                $female = [
                    997,
                    999
                ];
                $seqNum = $female[rand(0, 1)];
            }
        }
        $idNum = '' . $areaCode . date('ymd', $birthday) . str_pad($seqNum, 3, '0', STR_PAD_LEFT);
        return $idNum;
    }

    public function getAreaCode()
    {
        return substr($this->idNum, 0, 6);
    }

    public function getBirthdayCode()
    {
        return substr($this->idNum, 6, 6);
    }

    public function __toString()
    {
        return "ID:" . $this->idNum . "\r\n" . "Gender:" . Gender::getDesc($this->getGender()) . "\r\n" . "Area:" . $this->getArea() . "\r\n" . "Birthday:" .  $this->getBirthday();
    }
}

?>