<?
if (!defined('SITE')) die();
// Содержимое главной страницы


    ###############    УДАЛЕНИЕ КАТЕГОРИЙ    ###############    
if(@$_GET['op'] == "drop_category")
{
	// Проверяем на наличие дочергих категорий. Если они есть, то начинаем очень сильно ругаться
	if (mysql_num_rows(mysql_query("SELECT * FROM jic_category WHERE root_cat = '".$_GET['id_cat']."'")))
	die("<br /><br /><center><strong>Нельзя удалить категорию-родителя. Сначала убейте дочерние подкатегории!!!</strong></center>");
	// удаляем товары из этой категории
	$delete_item_to_category = mysql_query("DELETE FROM jic_item WHERE id_category = ". $_GET['id_cat']);
	if (!$delete_item_to_category) die("<br /><br /><center><strong>Не удалось удалить товары из этой категории</strong></center>");
	//удаляем категорию
	$delete_category = mysql_query("DELETE FROM jic_category WHERE cat_id = ". $_GET['id_cat']);
	//если успешно, то...
	if ($delete_category) echo "<br /><br /><center><strong>Категория успешно удалена и товары из этой категории тоже</strong></center>";
	else echo "<br /><br /><center><strong>Не удалось удалить категорию!</strong></center>";
}
    ###############    КОНЕЦ БЛОКА УДАЛЕНИЯ КАТЕГОРИЙ    ###############    

    ###############    РЕДАКТИРОВАНИЕ КАТЕГОРИЙ    ###############    
elseif (@$_GET['op'] == "edit_category")
{
	// если существует название
	if(@$_POST['name_cat'])
	{
		// преобразовываем зарезервированные в HTML символы
		$name_cat = htmlspecialchars($_POST['name_cat']);
		$descr  = htmlspecialchars($_POST['descr']);
		// экранируем кавычки
		if (!get_magic_quotes_gpc())
		{
			 $name_cat = mysql_escape_string($name_cat);
			 $descr = mysql_escape_string($descr);
		}
		else
		{
			$name_cat = str_replace("'","`",$name_cat);
			$descr = str_replace("'","`",$descr);
		}
		//  обновляем БД
		$query = mysql_query("
		UPDATE jic_category 
		SET root_cat = '".$_POST['id_category']."', 
		name_cat = '".$name_cat."', 
		descr = '".$descr."' 
		WHERE cat_id = '".intval($_GET['id_cat'])."'
		");
		// если успешно, то...
		if ($query) echo "<br /><br /><center><strong>Обновление данных успешно завершено</strong></center>";
		else echo "<br /><br /><center><strong>Не удалось обновить данные</strong></center>";
	}
	// иначе выводим форму с данными для редактирования
	else
	{
		// достаем данные из БД
		$query = mysql_query("SELECT * FROM jic_category WHERE cat_id = '".intval($_GET['id_cat'])."'");
		// лепим ассоциативный массив
		$line = mysql_fetch_assoc($query);
		echo "<table align=center cellspacing=2 cellpadding=2 border=0 width=60%><tr><td><br />
		<FORM ACTION=\"http://".$_SERVER['HTTP_HOST']."/".$dir."admin/cat/$id_cat/edit_category/\" METHOD=POST>
		Название:<br /><input type=text size=60 name=name_cat value=\"".$line['name_cat']."\"><br /><br />
		Описание: <input type=text size=60  name=descr value=\"".$line['descr']."\"><br /><br />
		Выберите категорию:<br /><select name=id_category>";
		echo "<option value=0>--------  Корневая директория  -----</option>";
		// ф-ия обхода дерева категорий
		function subcategory_cat($id, $sub)
		{
			$categories = mysql_query("SELECT * FROM jic_category WHERE root_cat = $id ORDER by name_cat ASC");
			while($category = mysql_fetch_array($categories)) 
			{	
				echo "<option value=".$category['cat_id']."".( $category['cat_id'] == @$line['root_cat'] ? " selected " : "" ).">";
				for($i = 0; $i < $sub; $i++) echo "&nbsp;&nbsp;&nbsp;&nbsp;";
				echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$category['name_cat']." ";
				subcategory_cat($category['cat_id'], $sub+1);
			}
		}
		$categories = mysql_query("SELECT * FROM jic_category WHERE root_cat = 0 ORDER by name_cat ASC");
		while($category = mysql_fetch_array($categories)) 
		{
			$sub = 1;
			echo "<option value=".$category['cat_id']."".( $category['cat_id'] == @$line['root_cat'] ? " selected " : "" ).">>>>".$category['name_cat']." ";
			subcategory_cat($category['cat_id'], $sub);
		}
		echo "</select><br /><br /><input type=submit value=Изменить></form></td></tr></table>";
	}
}
    ###############    КОНЕЦ БЛОКА РЕДАКТИРОВАНИЯ КАТЕГОРИЙ    ###############    

    ###############    СОЗДАНИЕ КАТЕГОРИЙ    ###############    
elseif (@$_GET['op'] == "add_category")
{
	// если существует название
	if(@$_POST['name_cat'])
	{
		// преобразовываем зарезервированные в HTML символы
		$name_cat = htmlspecialchars($_POST['name_cat']);
		$descr  = htmlspecialchars($_POST['descr']);
		// экранируем кавычки
		if (!get_magic_quotes_gpc())
		{
			 $name_cat = mysql_escape_string($name_cat);
			 $descr = mysql_escape_string($descr);
		}
		else
		{
			$name_cat = str_replace("'","`",$name_cat);
			$descr = str_replace("'","`",$descr);
		}
		// вставляем данные в БД
		$query = mysql_query("
		INSERT jic_category 
		SET root_cat = '".$_POST['id_category']."', 
		name_cat = '".$name_cat."', 
		descr = '".$descr."'
		");
		// если удачно, то...
		if($query) echo "<br /><br /><center><strong>Категория добавлена</strong></center>";
		// если не удачно, то
		else echo "<br /><br /><center><strong>Ошибка при добавлении категории</strong></center>";
	}
	// иначе выводим форму для заполнения
	else
	{
		echo "<table cellspacing=2 cellpadding=2 border=0 width=70%><tr><td>
		<FORM ACTION=\"http://".$_SERVER['HTTP_HOST']."/".$dir."admin/cat/add_category/\" METHOD=POST>
		Название: <input type=text size=60  name=name_cat><br /><br />
		Описание: <input type=text size=60  name=descr><br /><br />
		Выберите категорию: <select name=id_category>";
		// ф-ия обхода дерева категорий
		echo "<option value=0>----------- Корневая директория -----------</option>";
		function subcategory_sub($id, $sub)
		{
			$categories = mysql_query("SELECT * FROM jic_category WHERE root_cat = $id ORDER by name_cat ASC");
			while($category = mysql_fetch_array($categories)) 
			{	
				echo "<option value=\"".$category['cat_id']."\">";
				for($i = 0; $i < $sub; $i++) echo "&nbsp;&nbsp;&nbsp;&nbsp;";
				echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$category['name_cat']." ";
				subcategory_sub($category['cat_id'], $sub+1);
			}
		}
		$categories = mysql_query("SELECT * FROM jic_category WHERE root_cat = 0 ORDER by name_cat ASC");
		while($category = mysql_fetch_array($categories)) 
		{
			$sub = 1;
			echo "<option value=\"".$category['cat_id']."\">>>>".$category['name_cat']."<br>";
			subcategory_sub($category['cat_id'], $sub);
		}
		echo "</select><br /><br /><input type=submit value=Добавить категорию></form></td></tr></table>";
	}
}
    ###############    КОНЕЦ БЛОКА СОЗДАНИЯ КАТЕГОРИЙ    ###############    

    ###############    БЛОК ДОБАВЛЕНИЯ ТОВАРОВ    ###############    
elseif (@$_GET['op'] == "add_item")
{
	if (@$_POST['title']) // если POST массив не пуст
	{
		// преобразовываем зарезервированные в HTML символы
		// и устанавливаем тип переменных в числа
		$id_category = intval			($_POST['id_category']);
		$price		 = intval			($_POST['price']);
		$title		 = htmlspecialchars	($_POST['title']);
		$description = htmlspecialchars	($_POST['description']);

		// Проверим наличие стоимости
		if (!$price) die ("<br /><br /><center><strong>
		Введите нормальную стоимость, без точек и запятых!</strong></center>");

		// экранируем
		if (!get_magic_quotes_gpc())
		{
			 $title = mysql_escape_string($title);
			 $description = mysql_escape_string($description);
		}
		else
		{
			$title = str_replace("'","`",$title);
			$description = str_replace("'","`",$description);
		}

		// вставляем данные в БД
		$query = mysql_query("
		INSERT jic_item  
		SET id_category = '$id_category', 
		title = '$title', 
		description = '$description', 
		price = '$price', 
		money_type = '".$_POST['money_type']."', 
		print_to_index = '".$_POST['print_to_index']."'
		");
		// если удачно, то...
		if($query) echo "<br /><br /><center><strong>Товар добавлен</strong></center>";
		// если не удачно, то
		else echo "<br /><br /><center><strong>Ошибка при добавлении товара</strong></center>";
	}
	// иначе выводим форму для заполнения
	else
	{
		echo "<table align=center cellspacing=2 cellpadding=2 border=0>
		<tr><td align=right><br /><FORM METHOD=POST
		 ACTION=\"http://".$_SERVER['HTTP_HOST']."/".$dir."admin/cat/add_item/\">
		Название: <input type=text size=60 maxlength=250 name=title><br /><br />
		Описание: <textarea rows=6 cols=60 name=description></textarea><br /><br />
		Выберите категорию: <select name=id_category>";
		// ф-ия обхода дерева категорий
		function subcategory_it($id, $sub)
		{
			$categories = mysql_query("SELECT * FROM jic_category WHERE root_cat = $id ORDER by name_cat ASC");
			while($category = mysql_fetch_array($categories)) 
			{	
				echo "<option value=\"".$category['cat_id']."\">";
				for($i = 0; $i < $sub; $i++) echo "&nbsp;&nbsp;&nbsp;&nbsp;";
				echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$category['name_cat']." ";
				subcategory_it($category['cat_id'], $sub+1);
			}
		}
		$categories = mysql_query("SELECT * FROM jic_category WHERE root_cat = 0 ORDER by name_cat ASC");
		while($category = mysql_fetch_array($categories)) 
		{
			$sub = 1;
			echo "<optgroup label=\"".$category['name_cat']."\">";
			subcategory_it($category['cat_id'], $sub);
		}
		echo "</select><br /><br />
		Стоимость: <input type=text size=60 maxlength=9 name=price><br /><br />
		Вид валюты: <select name=money_type>
		<option value=\"R\" selected>Рубли</option>
		<option value=\"D\">Доллары США</option>
		<option value=\"E\">Евро</option>
		<option value=\"G\">Гривны</option>
		</select><br /><br />
		Показывать на главной странице? <select name=print_to_index>
		<option value=\"yes\" selected>Да, показывать</option>
		<option value=\"no\">Нет</option>
		</select><br /><br />
		<input type=submit value=Добавить наименование товара>
		</form></td></tr></table>";
	}
}
    ###############    КОНЕЦ БЛОКА ДОБАВЛЕНИЯ ТОВАРОВ    ###############    

    ###############    БЛОК УДАЛЕНИЯ ТОВАРОВ    ###############    
elseif (@$_GET['op'] == "drop_item")
{
	// делаем запрос на удаление
	$delete = mysql_query("DELETE FROM jic_item 
	WHERE id = '".intval($_GET['id_item'])."' LIMIT 1");
	//если успешно, то...
	if ($delete) echo "<br /><br /><center><strong>
	Наименование успешно удалено</strong></center>";
	//иначе...
	else echo "<br /><br /><center><strong>
	Не удалось удалить наименование!</strong></center>";
}
    ###############    КОНЕЦ БЛОКА УДАЛЕНИЯ ТОВАРОВ    ###############    

    ###############    БЛОК РЕДАКТИРОВАНИЯ ТОВАРОВ    ###############    
elseif (@$_GET['op'] == "edit_item")
{
	if (@$_POST['title']) // если POST массив не пуст
	{
		// преобразовываем зарезервированные в HTML символы
		// и устанавливаем тип переменных в числа
		$id_category = intval			($_POST['id_category']);
		$price		 = intval			($_POST['price']);
		$title		 = htmlspecialchars	($_POST['title']);
		$description = htmlspecialchars	($_POST['description']);

		// Проверим наличие стоимости
		if (!$price) die ("<br /><br /><center><strong>
		Введите нормальную стоимость, без точек и запятых!</strong></center>");

		// экранируем
		if (!get_magic_quotes_gpc())
		{
			 $title = mysql_escape_string($title);
			 $description = mysql_escape_string($description);
		}
		else
		{
			$title = str_replace("'","`",$title);
			$description = str_replace("'","`",$description);
		}

		// обновляем данные в БД
		$query = mysql_query("
		UPDATE jic_item  
		SET id_category = '$id_category', 
		title = '$title', 
		description = '$description', 
		price = '$price', 
		money_type = '".$_POST['money_type']."', 
		print_to_index = '".$_POST['print_to_index']."' 
		WHERE  id = '".intval($_GET['id_item'])."' 
		LIMIT 1 
		");
		// если удачно, то...
		if($query) echo "<br /><br /><center><strong>
		Товар изменен</strong></center>";
		// если не удачно, то
		else echo "<br /><br /><center><strong>
		Ошибка при изменении товара</strong></center>";
	}
	// иначе выводим форму для заполнения
	else
	{
		// достаем данные для этого товара
		$query_edit = mysql_query ("SELECT * FROM jic_item
		 WHERE id = '".intval($_GET['id_item'])."'");
		if (mysql_num_rows ($query_edit))
		{
			// лепим ассоциативный массив
			$items = mysql_fetch_assoc ($query_edit);
			// выводим форму
			echo "<table align=center cellspacing=2 cellpadding=2 border=0>
			<tr><td align=right><FORM METHOD=POST
			 ACTION=\"http://".$_SERVER['HTTP_HOST']."/".$dir."admin/cat/".$items['id_category']."/".$items['id']."/edit_item/\">
			Название: 
			<input type=text size=60 maxlength=250 name=title value=\"".$items['title']."\">
			<br /><br />
			Описание: 
			<textarea name=description rows=6 cols=60>".$items['description']."
			</textarea><br /><br />
			Выберите категорию: <select name=id_category>";
			// ф-ия обхода дерева категорий
			function subcategory_edit($id, $sub)
			{
				$categories = mysql_query("SELECT * FROM jic_category
				 WHERE root_cat = $id ORDER by name_cat ASC");
				while($category = mysql_fetch_array($categories)) 
				{	
					echo "<option value=".$category['cat_id']."	".( $category['cat_id'] == $_GET['id_cat'] ?" selected":"" ).">";
					for($i = 0; $i < $sub; $i++) echo "&nbsp;&nbsp;&nbsp;&nbsp;";
					echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$category['name_cat'];
					subcategory_edit($category['cat_id'], $sub+1);
				}
			}
			$categories = mysql_query("SELECT * FROM jic_category
			 WHERE root_cat = 0 ORDER by name_cat ASC");
			while($category = mysql_fetch_array($categories)) 
			{
				$sub = 1;
				echo "<option value=".$category['cat_id']."
				".( $category['cat_id'] == $_GET['id_cat'] ? " selected " : "" ).">
				".$category['name_cat'];
				subcategory_edit($category['cat_id'], $sub);
			}
			echo "</select><br /><br />
			Стоимость: 
			<input type=text maxlength=9 size=60 name=price value=\"".$items['price']."\">
			<br /><br />
			Вид валюты: <select name=money_type>
			<option value=R".($items['money_type']=="R"?" selected":"").">Рубли
			<option value=D".($items['money_type']=="D"?" selected":"").">Доллары США
			<option value=E".($items['money_type']=="E"?" selected":"").">Евро
			<option value=G".($items['money_type']=="G"?" selected":"").">Гривны
			</select><br /><br />
			Показывать на главной странице? <select name=print_to_index>
			<option value=yes".($items['print_to_index']=="yes"?" selected":"").">
			Да, показывать</option>
			<option value=no".($items['print_to_index']=="no"?" selected":"").">Нет</option>
			</select><br /><br />
			<input type=submit value=Изменить наименование товара>
			</form></td></tr></table>";
		}
	}
}
    ###############    КОНЕЦ БЛОКА РЕДАКТИРОВАНИЯ ТОВАРОВ    ###############    

    ###############    БЛОК ВЫВОДА ТОВАРОВ КАКОЙ-ЛИБО ИЗ КАТЕГОРИЙ    ###############    
elseif (!@$_GET['op'] && intval(@$_GET['id_cat']) > 0 && !@$_GET['id_item'])
{
	// запрашиваем все товары для нужной категории
	$query = mysql_query("SELECT * FROM jic_item as A,
	 jic_category as B
	  WHERE A.id_category = B.cat_id AND
	   A.id_category = ".intval($_GET['id_cat'])."
	    ORDER by A.id DESC");
	// узнаем количество товаров в этой категории	
	$num = mysql_num_rows ($query);
	echo "<br /><strong><center>Количество товаров в данной категории:
	 ".$num."</strong></center><br><br>";
	// используем ф-ию для постраничной навигации
	@$start = page_list ($_GET['page'], $num, $COUNT_SHOW_ITEMS_IN_ADMINPAGE);
	// запрашиваем товары, но уже зная, с какого товара выводить,
	// сколько штук на странице,
	// и как их сортировать
	$query2 = mysql_query("SELECT * FROM jic_item as A,
	 jic_category as B
	  WHERE A.id_category = B.cat_id AND
	   A.id_category = ".intval($_GET['id_cat'])."
	    ORDER by $SORT_FIELD_ITEMS_IN_ADMINPAGE $DESC_ASC_ITEMS_IN_ADMINPAGE
		 LIMIT $start, $COUNT_SHOW_ITEMS_IN_ADMINPAGE");
	
	echo "<table border=0 width=100% align=center cellspacing=3 cellpadding=10>
	<tr bgcolor=#FAFAFAv valign=top>
	<td width=90% align=center><strong>Информация о товаре</strong></td>
	<td align=center><strong>Редактировать данные товара</strong></td>
	<td align=center><strong>Удалить товар</strong></td>
	</tr>";
	// лепим массив в цикле
	while($list = mysql_fetch_assoc($query2))
	{	
		// узнаем, какую валюту печатать на странице у каждого товара
		if 		($list['money_type'] == "D") $money_type = "$";
		elseif 	($list['money_type'] == "E") $money_type = "&euro;";
		elseif 	($list['money_type'] == "G") $money_type = "гривен";
		else $money_type = "руб.";
		//
		if ($list['print_to_index'] == "yes")
		$print_to_index = "выводится"; else $print_to_index = "<strong>не</strong> выводится";
		// выводим данные
		
		echo "<tr bgcolor=#FAFAFA valign=top><td>
		<a href=\"http://".$_SERVER['HTTP_HOST']."/".$dir."cat/".$list['id_category']."/".$list['id']."/\">
		<strong>".$list['title']."</strong></a><br />
		".nl2br($list['description'])."<br />
		<strong>Цена:</strong> ".$list['price']." ".$money_type."<br />
		<strong>Просмотров:</strong> ".$list['hits']."<br />
		На главной <strong>".$print_to_index."</strong>
		</td>";
		
		echo "<td align=center><a href=\"http://".$_SERVER['HTTP_HOST']."/".$dir."admin/cat/".$list['id_category']."/".$list['id']."/edit_item/\"><img src=\"http://".$_SERVER['HTTP_HOST']."/".$GLOBALS['dir']."/images/edit.gif\"></a></td>";
		echo "<td align=center><a href=\"http://".$_SERVER['HTTP_HOST']."/".$dir."admin/cat/".$list['id_category']."/".$list['id']."/drop_item/\"><img src=\"http://".$_SERVER['HTTP_HOST']."/".$GLOBALS['dir']."/images/del.png\"></a></td></tr>";
	}
	echo "</table>";
	// выводим постраничную навигацию
	$path_to_page = "admin/cat";
	@show_page_list($_GET['page'], $num, $COUNT_SHOW_ITEMS_IN_ADMINPAGE, $_GET['id_cat'], $path_to_page);
	echo "<br /><br /><center>
	<a href=\"http://".$_SERVER['HTTP_HOST']."/".$dir."admin/cat/add_item/\"> Добавить товар </a>
	</center>";
}
    ###############    КОНЕЦ БЛОКА ВЫВОДА ТОВАРОВ КАКОЙ-ЛИБО ИЗ КАТЕГОРИЙ    ###############    
    ###############    ВЫВОД КАТЕГОРИЙ    ###############    
else
{
	echo " <table border=0 width=100% align=center cellspacing=3 cellpadding=10>
	<tr bgcolor=#FAFAFA>
	<td align=center><strong>Названия категорий</strong></td>
	<td align=center><strong>Товары это категории</strong></td>
	<td align=center><strong>Редактировать данные категории</strong></td>
	<td align=center><strong>Удалить категорию</strong></td>
	</tr>";
	// ф-ия обхода дерева категорий
	$GLOBALS['dir'] = $dir;
	function subcategory_admin($id, $sub)
	{
		$categories = mysql_query("SELECT * FROM jic_category WHERE root_cat = $id");
		while($category = mysql_fetch_array($categories)) 
		{	
			$count_it = mysql_num_rows(mysql_query("SELECT * FROM jic_item WHERE id_category = '".$category['cat_id']."'"));
			echo "<tr bgcolor=#FAFAFA><td width=100%>";
			for($i = 0; $i < $sub; $i++) echo "&nbsp;&nbsp;&nbsp;";
			echo "<strong>- ".$category['name_cat']."</strong> [".@$count_it."]</td>
			 <td align=center><a href=\"http://".$_SERVER['HTTP_HOST']."/".$GLOBALS['dir']."admin/cat/".$category['cat_id']."/\">";
			 if ($count_it) echo "<img src=\"http://".$_SERVER['HTTP_HOST']."/".$GLOBALS['dir']."/images/items.gif\"></a>";
			 echo "</td>
			<td align=center><a href=\"http://".$_SERVER['HTTP_HOST']."/".$GLOBALS['dir']."admin/cat/".$category['cat_id']."/edit_category/\"><img src=\"http://".$_SERVER['HTTP_HOST']."/".$GLOBALS['dir']."/images/edit.gif\"></a></td>
			 <td align=center><a href=\"http://".$_SERVER['HTTP_HOST']."/".$GLOBALS['dir']."admin/cat/".$category['cat_id']."/drop_category/\"><img src=\"http://".$_SERVER['HTTP_HOST']."/".$GLOBALS['dir']."/images/del.png\"></a></td></tr>";
			subcategory_admin($category['cat_id'], $sub+1);
		}
	}
	$categories = mysql_query("SELECT * FROM jic_category WHERE root_cat = 0");
	while($category = mysql_fetch_array($categories)) 
	{
		$sub = 1;
		echo "<tr bgcolor=#CCCCCC><td align=center colspan=2><strong>".$category['name_cat']."</strong></td>
		<td align=center><a href=\"http://".$_SERVER['HTTP_HOST']."/".$dir."admin/cat/".$category['cat_id']."/edit_category/\"><img src=\"http://".$_SERVER['HTTP_HOST']."/".$GLOBALS['dir']."/images/edit.gif\"></a></td>
		 <td align=center><a href=\"http://".$_SERVER['HTTP_HOST']."/".$dir."admin/cat/".$category['cat_id']."/drop_category/\"><img src=\"http://".$_SERVER['HTTP_HOST']."/".$GLOBALS['dir']."/images/del.png\"></a></td></tr>";
		
		//</tr>";
		//echo "<tr bgcolor=#FAFAFA><td><strong>".$category['name_cat']."</strong> [".@$count_it."]</td>
		// <td align=center><a href=\"http://".$_SERVER['HTTP_HOST']."/".$dir."admin/cat/".$category['cat_id']."/\">";
		// if ($count_it) echo "<img src=\"http://".$_SERVER['HTTP_HOST']."/".$dir."/images/items.gif\"></a>";
		// echo "</td>
		subcategory_admin($category['cat_id'], $sub);
	}
	echo "</table><br />";
	echo "<center>
	<img src=\"http://".$_SERVER['HTTP_HOST']."/".$GLOBALS['dir']."/images/addcat.gif\">
	<a href=\"http://".$_SERVER['HTTP_HOST']."/".$dir."admin/cat/add_category/\"> Добавить категорию </a>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<img src=\"http://".$_SERVER['HTTP_HOST']."/".$GLOBALS['dir']."/images/additem.gif\"> 
	<a href=\"http://".$_SERVER['HTTP_HOST']."/".$dir."admin/cat/add_item/\"> Добавить наименование товара </a></center>";
}
###############    КОНЕЦ ВЫВОДА КАТЕГОРИЙ    ###############    
?>
