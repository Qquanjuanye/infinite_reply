<?php
$conn = new COM('ADODB.Connection');
$conn->Open("DRIVER={Microsoft Access Driver (*.mdb)}; DBQ=db.mdb");

function strCut($str,$length=50)
{
$str = strip_tags($str);
$str = trim($str);
$string = "";

if(strlen($str) > $length)
{
for($i = 0 ; $i<$length ; $i++)
{
if(ord($str) > 127)
{
$string .= $str[$i] . $str[$i+1] . $str[$i+2]; $i = $i + 2;
}else{
$string .= $str[$i];
}
}
$string .= "..."; return $string;
}
 return $str;
}

?>

<!DOCTYPE html>
<html>
<head>
<title>���޻ظ�</title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<meta name="viewport" content="width=device-width; initial-scale=1.0">
<style type="text/css">
body{font: 13px ΢���ź�,Arial;}
html,body,h1,h2,h3,hr,p{margin:0;padding:0;}
a{text-decoration:none}
a:link,
a:visited,
a:hover,
a:active{color:#fff;}

textarea{overflow-y:visible;width:98%;}
div{display:inline-block;background:#219ECE;margin:2px;padding:2px;border-radius:4px 4px 4px 4px;}
section{padding:5px;background:#188A84;color:#fff;}
section h1{margin:13px;font-size:2em;}
footer{margin: 0 0 0 15px;color:#fff;}
footer a:link,
footer a:visited{color:#fff;border-bottom:1px dotted #fff;}
</style>


</head>
<body>
<?php
if($id=$_SERVER['QUERY_STRING']){
//�г���ǰֽƬ������
$rs = $conn->Execute("select * from infinite where id=".$id);
$rs2 = $conn->Execute("select * from infinite where parents_id=". $rs->Fields['parents_id']->Value);
?>
<section>
<h1><?= $rs->Fields['content']->Value ?></h1>


<footer>
<?= $rs->Fields['reply_count']->Value ?>���ظ�&nbsp;&nbsp;
����<a href="?<?=$rs2->Fields['parents_id']->Value?>"><?= strCut($rs2->Fields['content']->Value)  ?></a>
</footer>

</section>


<?

//�г���idΪ $id ��ֽƬ
$rs = $conn->Execute("select * from infinite where parents_id=".$id." order by id desc");
while(!$rs->EOF)
{
echo "<div><a href='?".$rs->Fields['id']->Value ."' >".strCut( $rs->Fields['content']->Value,40) ."</a> "
		 . $rs->Fields['reply_count']->Value ."</div>";
$rs->MoveNext();
}


?>
<form action="" method="post">
<textarea name="content" rows="5" cols="30" onscroll="this.rows++;"></textarea>
<br>
<input type="submit" value="��Ҳ˵һ��">

</form>
<?

if(isset($_POST['content']))
{
$content=$_POST['content'];
$content = str_replace("\n", "<br>",$content);
//���¸�ֽ���Ļظ���
//���ȶ�ȡ��ֽ���Ļظ���
$rs = $conn->Execute("select * from infinite where id=".$id);
// + 1
$reply_count= $rs->Fields['reply_count']->Value + 1 ;
//���и���
$rs = $conn->Execute("update infinite set reply_count='".$reply_count."' where id=".$id);




//Ȼ�󴴽��¼�¼
$rs = $conn->Execute("insert into infinite (parents_id ,content ,reply_count) values ('$id' ,'$content' ,'0')");
echo'<script>location.replace(document.referrer);</script>';
} //end if($content=$_POST['content'])




}
else
{
//$id=$_SERVER['QUERY_STRING']Ϊ�پ����10������

     $rs = $conn->Execute('SELECT * FROM infinite order by id desc'); //asc

 for ($i=1; $i<=40; $i++)
 {
      echo "<div><a alt='' href='?".$rs->Fields['id']->Value ."' >" . strCut( $rs->Fields['content']->Value) ." "
		 . $rs->Fields['reply_count']->Value ."</a></div>";

	 $rs->MoveNext();
 }

	  /*
	  �ͷ���Դ
	  ����ȫ,Ҫ���ϲ��ͷ���Դ
	  
	  */
	  $rs->Close();
      $conn->Close();
      $rs = null;
      $conn = null;



} //end if($id=$_GET['id'])

?>
</body>
</html>