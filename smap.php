<?
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
        <h2>����� �������� �������<br />
�� ���� �������� � ���������� ������ ��������</h2>
      </center>
    </div>
    <div class="entry"><br />
<table align="center"><tr valign="top"><td>
<h4>.01 <a href="http://<?=$_SERVER['HTTP_HOST']?>/<?=$dir?>">������� �������� ��������</a></h4>
<h4>.02 <a href="http://<?=$_SERVER['HTTP_HOST']?>/<?=$dir?>cat/">������� �������</a></h4>
<div style="margin-left:60px">
<?
// ������� ����
$categories = mysql_query("SELECT * FROM jic_category 
WHERE root_cat = 0");
while($category = mysql_fetch_array($categories)) 
{
	$sub = 1;
	$tit_cat = ($category['descr']) ? $category['descr'] : "" ;
	echo "<li><strong title=\"".$tit_cat."\">".$category['name_cat']."</strong></li>";
	subcategory($category['cat_id'], $sub);
}
?>
</div>
<h4>.03 <a href="http://<?=$_SERVER['HTTP_HOST']?>/<?=$dir?>about/">� �����</a></h4>
<h4>.04 <a href="http://<?=$_SERVER['HTTP_HOST']?>/<?=$dir?>contacts/">��������</a></h4>
</td></tr></table>
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
