<?
// папка, в которой будет стоять скрипт.
// если ставите в корень сайта, то значение переменной оставьте пустым...
$papka = "";

### Мета_Данные, вставляемые при отсутствии динамической информации ###
//Заголовок
$user_title = "Витрина товаров";

//Мета_Ключевики
$user_keywords = "Витрина, товары, описания, цены";

//Мета_Описание
$user_description = "Наша витрина товаров, в которой Вы найдете массу нужных товаров, описаний к ним и цен";


//e-mail для контактов
$admin_mail = "admin@google.com";
#####################################################################


//Форма фхода админа
$admin_login_form = "<form method=post> 
<input name=login value=Логин onBlur=\"if (this.value=='') {this.value='Логин';}\" onClick=\"if (this.value=='Логин') {this.value='';}\"><br />
<input name=password value=Пароль onBlur=\"if (this.value=='') {this.value='Пароль';}\" onClick=\"if (this.value=='Пароль') {this.value='';}\"><br /> 
<input type=submit value=\"Попробовать войти \"> 
</form>";

// Количество товаров, выводимых в админ-панели на одной странице
$COUNT_SHOW_ITEMS_IN_ADMINPAGE = 5; 

// Поля для сортировки в админ-панели: id - по номеру; title - по названию; price - по цене; hits - по популярности
$SORT_FIELD_ITEMS_IN_ADMINPAGE = "id";

// Сортировка товаров в админ-панели по возрастанию - ASC, наоборот - DESC
$DESC_ASC_ITEMS_IN_ADMINPAGE = "DESC"; 

// Количество случайных товаров, выводимых на главной странице
$COUNT_SHOW_ITEMS_IN_INDEX = 4; 

// Количество случайных товаров, выводимых в одной линии на главной странице
$COUNT_SHOW_IN_LINE_IN_INDEX = 2; 

// Количество случайных товаров, выводимых на странице каталога
$COUNT_SHOW_ITEMS = 6; 

// Количество товаров, выводимых в одной линии  на странице каталога
$COUNT_SHOW_IN_LINE = 2; 

// Количество товаров на странице при выводе категории
$COUNT_SHOW_ITEMS_IN_CAT = 4; 

########################################################################################
########################################################################################
########################################################################################
################## 
##################         Дальше ничего не трогать !!!
################## 
########################################################################################
########################################################################################
########################################################################################

if ($papka !="") $dir = $papka."/"; else $dir = "";
$GLOBALS['dir'] = $dir;
function subcategory($id, $sub)
{
	$categories = mysql_query("SELECT * FROM jic_category 
	WHERE root_cat = $id");
	while($category = mysql_fetch_array($categories)) 
	{	
		$tit_cat = ($category['descr']) ? $category['descr'] : "" ;
		for($i = 0; $i < $sub; $i++) 
		echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		echo "<a href=\"http://".$_SERVER['HTTP_HOST']."/".$GLOBALS['dir']."cat/".$category['cat_id']."/\" title=\"".$tit_cat."\">".$category['name_cat']."</a><br />";
		subcategory($category['cat_id'], $sub+1);
	}
}

function page_list ($page, $num, $count_on_page)
{
	if (!isset($page) && intval($page) == 0) $page = 1;
	$count_pages = intval($num / $count_on_page);
	$ostatok = $num % $count_on_page;
	if($ostatok > 0) $count_pages++;
	$start = $count_on_page * $page - $count_on_page;
	return $start;
}

function show_page_list ($page, $num, $count_on_page, $id_cat, $path_to_page)
{
	echo "<table align=center><tr><td class=\"y b\">";
	if (!isset($page) && intval($page) == 0) $page = 1;
	$count_pages = intval($num / $count_on_page);
	$ostatok = $num % $count_on_page;
	if($ostatok > 0) $count_pages++;
	if ($page>1) echo " <a href = \"http://". $_SERVER['HTTP_HOST'] ."/".$GLOBALS['dir']."$path_to_page/$id_cat/page/".($page - 1)."/\" title = \"Предыдущая страница\">&larr;</a> ";
	$diapazon = 10;
	$page_from = $page - $diapazon; 
	if($page_from < 1) $page_from = 1;
	$page_to = $page + $diapazon; 
	if($page_to > $count_pages) $page_to = $count_pages;

	for ($i = $page_from; $i <= $page_to; $i++)
	{
		if ($i == $page) echo " [$i] ";
		else echo "<a href=\"http://".$_SERVER['HTTP_HOST']."/".$GLOBALS['dir']."$path_to_page/$id_cat/page/$i/\" title = \"$i страница\">[$i]</a>";
	}
	if ($page<$count_pages) echo " <a href = \"http://". $_SERVER['HTTP_HOST'] ."/".$GLOBALS['dir']."$path_to_page/$id_cat/page/".($page + 1)."/\" title = \"Следующая страница\">&rarr;</a> ";
	echo "</td></tr></table>";
}

function show_page_list_index ($page, $num, $count_on_page, $path_to_page)
{
	echo "<table align=center><tr><td class=\"y b\">";
	if (!isset($page) && intval($page) == 0) $page = 1;
	$count_pages = intval($num / $count_on_page);
	$ostatok = $num % $count_on_page;
	if($ostatok > 0) $count_pages++;
	if ($page>1) echo " <a href = \"http://". $_SERVER['HTTP_HOST'] ."/".$GLOBALS['dir']."$path_to_page/page/".($page - 1)."/\" title = \"Предыдущая страница\">&larr;</a> ";
	$diapazon = 1;
	$page_from = $page - $diapazon; 
	if($page_from < 1) $page_from = 1;
	$page_to = $page + $diapazon; 
	if($page_to > $count_pages) $page_to = $count_pages;

	for ($i = $page_from; $i <= $page_to; $i++)
	{
		if ($i == $page) echo " [$i] ";
		else echo "<a href =\"http://". $_SERVER['HTTP_HOST'] ."/".$GLOBALS['dir']."$path_to_page/page/$i/\" title = \" $i страница \">[$i]</a>";
	}
	if ($page<$count_pages) echo " <a href = \"http://". $_SERVER['HTTP_HOST'] ."/".$GLOBALS['dir']."$path_to_page/page/".($page + 1)."/\" title = \"Следующая страница\">&rarr;</a> ";
	echo "</td></tr></table>";
}
?> 