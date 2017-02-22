# china-id
中国身份证号码解析，验证及生成

身份证号码验证
$id=new ID18('320706199108093125');
$id->isValidate();

身份证号码解析
$id=new ID18();
$id->setIdNum('320706199108093125');
$id->getBirthday();//出生日期时间戳
$id->getArea();//地区
Gender::getDesc($id->getGender());//性别

身份证号码生成
$id=new ID18();
for($i=0;$i<10;$i++){
    $idNum= $id->generate();//生成身份证号码
    $id->setIdNum($idNum);
    echo $id;
}
