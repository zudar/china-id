# china-id
中国身份证号码解析，验证及生成

身份证号码验证
<pre><code>
$id=new ID18('320706199108093125');
$id->isValidate();
</code></pre>
身份证号码解析
<pre><code>
$id=new ID18();
$id->setIdNum('320706199108093125');
$id->getBirthday();//出生日期
$id->getArea();//地区
Gender::getDesc($id->getGender());//性别
</code></pre>
身份证号码生成
<pre><code>
$id=new ID18();
for($i=0;$i<10;$i++){
    $idNum= $id->generate();//生成身份证号码
    $id->setIdNum($idNum);
    echo $id;
}
</code></pre>
