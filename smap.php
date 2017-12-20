<?
include("mysql.php");
include("config.php");

// подключаем шапку
require_once ("head.inc.php"); 
?>
<!-- start content -->
<div id="content">
  <div class="post">
    <div class="title">
      <center>
        <h2>Карта быстрого доступа<br />
ко всем разделам и категориям нашего каталога</h2>
      </center>
    </div>
    <div class="entry"><br />
<table align="center"><tr valign="top"><td>
<h4>.01 <a href="http://<?=$_SERVER['HTTP_HOST']?>/<?=$dir?>">Главная страница каталога</a></h4>
<h4>.02 <a href="http://<?=$_SERVER['HTTP_HOST']?>/<?=$dir?>cat/">Каталог товаров</a></h4>
<div style="margin-left:60px">
<?
// выводим меню
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
<h4>.03 <a href="http://<?=$_SERVER['HTTP_HOST']?>/<?=$dir?>about/">О сайте</a></h4>
<h4>.04 <a href="http://<?=$_SERVER['HTTP_HOST']?>/<?=$dir?>contacts/">Контакты</a></h4>
</td></tr></table>
    </div>
  </div>
</div>
<!-- end content -->
<?
// подключаем меню
include ("menu.inc.php");

// подключаем подвал сайта
include ("foot.inc.php"); 
?>
