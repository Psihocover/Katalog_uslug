<?
session_start();
session_register('qas');
include("mysql.php");
include("config.php");

// ���������� �����
require_once ("head.inc.php"); 
?>
<!-- start content -->

<div id="content">
  <div class="post">
    <div class="title">
      <center>
        <h2>����� �������� �����</h2>
      </center>
    </div>
    <div class="entry">
      <?

echo"<table border=0 cellpadding=5 cellspacing=5 width=100%>
<tr valign=top><td width=100%>";

$adresat = $admin_mail;
$goback = "<a href='javascript:history.back(1)'>&larr; ����� </a>";
$msg = "<div align=center>���� ��������� ���� ����������.<br>
� ��������� ����� �� �������� �����.<br>
$goback</div>";

if (@$submit)
{
	if(isset($HTTP_POST_VARS['securityCode']) && (isset($HTTP_SESSION_VARS['securityCode']) || isset($_SESSION['securityCode'])))
	{
		if(strtolower($HTTP_POST_VARS['securityCode'])==$HTTP_SESSION_VARS['securityCode'] || strtolower($HTTP_POST_VARS['securityCode'])==$_SESSION['securityCode'])
		{
			if($_POST['email'] != "")
			{
				if (!preg_match('/^[-0-9\.a-z_]+@([-0-9\.a-z]+\.)+[a-z]{2,6}$/i',$_POST['email'])) die ("<br /><br /><br /><div align=center>E-mail ������ ����������� ��� �������� ������������ �������.<br>���������� ��������� $goback � ��������� ��� ���� ���������.</div>");
			}
			$message = $_POST['message']; $name = $_POST['name']; $email = $_POST['email']; $site_name = $_SERVER['HTTP_HOST']."/";
			if(empty($name) || empty($message)) die ("��������� ��� ���� �����!");
			$headers  = "Content-type: text/plain; charset=windows-1251 \r\n";
			if (mail("$adresat", "��������� � ����� $site_name", "��: $name\nE-mail: $email\n���������:\n\n$message", $headers)) echo $msg; 
		}
		else echo "<div align=center>������ �������� �������� ���! $goback</div>";
	}
}
else
{
	?>
<script type="text/JavaScript">
function textCounter(field,counter,maxlimit,linecounter) {
	// text width//
	var fieldWidth =  parseInt(field.offsetWidth);
	var charcnt = field.value.length;        

	// trim the extra text
	if (charcnt > maxlimit) { 
		field.value = field.value.substring(0, maxlimit);
	}

	else { 
	// progress bar percentage
	var percentage = parseInt(100 - (( maxlimit - charcnt) * 100)/maxlimit) ;
	document.getElementById(counter).style.width =  parseInt((fieldWidth*percentage)/100)+"px";
	document.getElementById(counter).innerHTML="�����: "+percentage+"%"
	// color correction on style from CCFFF -> CC0000
	setcolor(document.getElementById(counter),percentage,"background-color");
	}
}

function setcolor(obj,percentage,prop){
	obj.style[prop] = "rgb(80%,"+(100-percentage)+"%,"+(100-percentage)+"%)";
}

</script>

<form action="<? echo $PHP_SELF ?>" method=POST>
  <table align="center" cellspacing="10" width="60%">
    <tr valign="top">
      <td align="center"><font size="-1">�������� ��� (4 �������)</font> <br />
        <img src="/images/code.gif" border="0" alt="�������� ���" align="absmiddle">&nbsp;&nbsp;
        <input type="text" size="13" name="securityCode" maxlength="4" title="������� ���, ������� ��������� �� ��������" align="absmiddle">
        <br />
        <br />
        <input type="text" size="41" name=name maxlength="64" value="���� ���" id="name"
onkeydown="textCounter(this,'name1',64)" 
onkeyup="textCounter(this,'name1',64)" 
onfocus="textCounter(this,'name1',64)">
        <br />
        <div id="name1" class="progress"></div>
        <script>textCounter(document.getElementById("name"),"name1",64)</script>
        <br />
        <input type=text name=email size="41" maxlength="32" value="E-Mail ��� �������� �����" id="email"
onkeydown="textCounter(this,'email1',32)" 
onkeyup="textCounter(this,'email1',32)" 
onfocus="textCounter(this,'email1',32)">
        <br />
        <div id="email1" class="progress"></div>
        <script>textCounter(document.getElementById("email"),"email1",32)</script>
        <br>
        <textarea cols="40" rows="6" name=message  id="text"
onkeydown="textCounter(this,'text1',500)" 
onkeyup="textCounter(this,'text1',500)" 
onfocus="textCounter(this,'text1',500)">���� ���������
</textarea>
        <br />
        <div id="text1" class="progress"></div>
        <script>textCounter(document.getElementById("text"),"text1",500)</script>
        <br />
        <font color="#FF0000"><strong>��� ���� ����������� ��� ����������!</strong></font><br /><br />

        <input style="width:100%" class=longok  name="submit" type=submit value="���������">
      </td>
    </tr>
  </table>
</form>
<?
}
echo"</td></tr></table>";
?>
    </div>
  </div>
</div>
<!-- end content -->
<?
// ���������� ����
include ("menu.inc.php");

// ���������� ������ �����
include ("foot.inc.php"); 
?>
