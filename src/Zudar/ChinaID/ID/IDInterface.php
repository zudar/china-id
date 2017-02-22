<?php
namespace Zudar\ChinaID\ID;

interface IDInterface
{

    /**
     * 是否符合身份证规则
     *
     * @return bool
     */
    public function isValidate();

    /**
     * 获取地区
     *
     * @return string
     */
    public function getArea();

    /**
     * 获取性别
     *
     * @return int
     */
    public function getGender();

    /**
     * 获取生日
     *
     * @return int
     */
    public function getBirthday();

    /**
     * 生成身份证号码
     *
     * @return string
     */
    public function generate();

    /**
     * 获取地区码
     *
     * @return int
     */
    public function getAreaCode();

    /**
     * 获取生日码
     *
     * @return int
     */
    public function getBirthdayCode();

    /**
     * 设置身份证号码
     * 
     * @param unknown $idNum
     */
    public function setIdNum($idNum);
}

?>