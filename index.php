<?
// подключаем файлы, необходимые
// для функционирования нашей программы
require_once("mysql.php");
require_once("config.php");

// подключаем шапку
include ("head.inc.php"); 

// Содержимое главной страницы
?>
<!-- start content -->

<div id="content">
  <div class="post">
    <div class="title">
      <center>
        <h2>Мы рады приветствовать Вас в нашем каталоге!</h2>
      </center>
    </div>
    <div class="entry">
      <table width=100% align=center cellpadding=10 cellspacing=10>
        <tr valign=top>
          <td bgColor="#FAFAFA" onMouseOut=bgColor="#FAFAFA" onMouseOver=bgColor="#FFFFFF"><img src="http://<?=$_SERVER['HTTP_HOST']?>/<?=$dir?>images/ind.jpg" align="left" hspace="10" vspace="1"> Здравствуйте.<br />
            В этом разделе располагается каталог услуг.<br />
            Вы можете посмотреть все услуги<strong>*</strong>, их стоимость и описания.<br />
            Просто выберите подходящую услугу или воспользуйтесь поиском.<br />
			Если Вам что-то понравится и Вы надумаете это купить - просто заполните форму на странице Контакты и отправьте информацию к нам и мы обязательно свяжемся с Вами и более детально обговорим детали сделки.
            <br />
            Удачных покупок! Будем рады сотрудничеству!<br />
<br />
<small><strong>* Все услуги здесь представлены в качестве тестовых наименований и мною на этом сайте не продаются!</strong></small></td>
        </tr>
      </table>
    </div>
    <div class="entry">
      <?
	$td = 0;
	// выводим случайные услуги
	$query = mysql_query ("SELECT * FROM jic_item WHERE print_to_index = 'yes' ORDER by RAND() LIMIT $COUNT_SHOW_ITEMS_IN_INDEX");
	if ($query)
	{
		echo "<table width=100% align=center cellpadding=10 cellspacing=10><tr valign=top>";
		while ($list_cat = mysql_fetch_assoc ($query))
		{
			echo "<td bgColor=\"#FAFAFA\" onMouseOut=bgColor=\"#FAFAFA\" onMouseOver=bgColor=\"#FFFFFF\">";
			if ($list_cat['money_type'] == "D") $money_type = "$";
			elseif ($list_cat['money_type'] == "E") $money_type = "&euro;";
			elseif ($list_cat['money_type'] == "G") $money_type = "гр.";
			else $money_type = "руб.";
			echo "<h4><a href=\"http://".$_SERVER['HTTP_HOST']."/".$dir."cat/".$list_cat['id_category']."/".$list_cat['id']."/\">".$list_cat['title']."</a></h4><strong>".$list_cat['price']." ".$money_type."</strong><br />";	
			
			echo substr(nl2br($list_cat['description']), 0, 100);
			echo "</td>";
			$td++; if ($td % $COUNT_SHOW_IN_LINE_IN_INDEX == 0) echo "</tr><tr valign=top>";
		}
		echo "</tr></table>";
	}
	?>
    </div>
  </div>
</div>
<!-- end content -->
<?
// Содержимое главной страницы


// подключаем меню
include ("menu.inc.php");

// подключаем подвал сайта
include ("foot.inc.php"); 
?>
