
<?
// выводим товар,
// идентификатор которого пришел
// через адресную строку
$query = mysql_query ("SELECT * FROM jic_item as A, jic_category as B WHERE A.id_category = B.cat_id AND A.id = '".intval($_GET['id_item'])."'");

// если запрос прошел удачно
if ($query)
{
	// если есть товар с данным идентификатором
	if (mysql_num_rows($query))
	{
		// лепим ассоциативный массив
		$list_cat = mysql_fetch_assoc ($query);

		$user_title = htmlspecialchars($list_cat['title']);
		$user_keywords = htmlspecialchars($list_cat['title']." | ".$list_cat['description']);
		$user_description = htmlspecialchars($list_cat['description']);
		// подключаем шапку
		require_once ("head.inc.php"); 
		?>
	  
	  
	<!-- start content -->
	<div id="content">
	  <div class="post">
		<div class="title">
		  <h2><a href="http://<?=$_SERVER['HTTP_HOST']."/".$dir."cat/".$list_cat['cat_id']?>/"><?=$list_cat['name_cat']?></a></h2>
		</div>
		<div class="entry">
		<?
		// выводим
		echo "<div align=right><small>Артикул: #0000".$list_cat['id']."</small></div><h4>".$list_cat['title']."</h4>";	
		echo "Описание товара: ".nl2br($list_cat['description'])."<br />";
		// приводим тип валюты в человечачий вид
		if ($list_cat['money_type'] == "D") $money_type = "$";
		elseif ($list_cat['money_type'] == "E") $money_type = "&euro;";
		elseif ($list_cat['money_type'] == "G") $money_type = "гр.";
		else $money_type = "руб.";
		echo "<strong>Цена: ".$list_cat['price']." ".$money_type."</strong>";
	}
	// иначе ругаемся
	else
	{
		// подключаем шапку
		require_once ("head.inc.php"); 
		echo "<strong><center>Нет такого товара</center></strong>";
	}
}
?>
    </div>
  </div>
</div>
<!-- end content -->
