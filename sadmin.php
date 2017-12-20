<? 
session_start(); // стартуем сессию 
define('SITE', true);
include("mysql.php");// подключаем файл с настройками для коннекта к БД 
include("config.php");

// подключаем шапку
include ("head.inc.php"); 
// Содержимое главной страницы
?>
<!-- start content -->

<div id="content">
  <div class="post">
    <div class="title">
      <center>
        <h2>Добро пожаловать<br />
в Административный интерфейс</h2>
      </center>
    </div>
    <div class="entry">
<?

### Блок обработки выхода из Бэк_Офиса ### 
if(@$_GET['action'] == "logout") // если в адресной строке переменная action равна "logout" 
{                                             
    if(isset($_SESSION['login']) && isset($_SESSION['password'])) // если существуют сессионные переменные login и password 
    { 
        session_unregister("login"); // удаляем 
        session_unregister("password"); // удаляем 
        unset ($_SESSION['login'],$_SESSION['password']);// удаляем 
        session_destroy();// убиваем сессию 
    } 
} 
### Конец блока обработки выхода из Бэк_Офиса ### 

### Блок обработки данных, пришедших из формы авторизации ### 
//если в форму авторизации были занесены логин и пароль И если сессионные переменные НЕзарегистрированы 
if(isset($_POST['login']) && isset($_POST['password']) && !isset($_SESSION['login']) && !isset($_SESSION['password'])) 
{ 
	$_POST['login'] = addslashes($_POST['login']);
    // Ищем в бд строку, сравнивая имеющиеся даннные, с полученными из формы 
    $admins = mysql_query("SELECT * FROM jic_admin WHERE login = '". $_POST['login']."' AND password = '". md5($_POST['password'])."'");      
    // если найдена хоть одна строка 
    if(mysql_num_rows($admins))  
    { 
        // регистрируем сессионные переменные 
        $login = $_POST['login']; 
        $password = $_POST['password']; 
        session_register("login"); 
        session_register("password"); 
    } 
	
################ php 5 #################	
/*	
// если найдена хоть одна строка 
    if(mysql_num_rows($admins))  
    { 
        // регистрируем сессионные переменные 
        session_register('login'); 
        session_register('password'); 
        $_SESSION['login'] = $_POST['login']; 
		$_SESSION['password'] = $_POST['password']; 
    } 
*/
################ php 5 #################	
	
    // Иначе очень сильно ругаемся, и снова выводим форму авторизации 
    else echo "<center>Администратора с данными параметрами входа не существует!<br><br>$admin_login_form</center>"; 
} 
// Иначе без ругани, просто выводим форму авторизации 
else if(!isset($_SESSION['login']) && !isset($_SESSION['password'])) echo "<center>".$admin_login_form."</center>"; 
### Конец блока обработки данных, пришедших из формы авторизации ### 

### Блок управления каталогом ### 
// Если есть сессионные переменные 
if(isset($_SESSION['login']) && isset($_SESSION['password'])) 
{ 
        // Ищем в бд строку, сравнивая имеющиеся даннные, с сессионыыми переменными 
        $admins = mysql_query("SELECT * FROM jic_admin WHERE login = '".$_SESSION['login']."' AND password = '".md5($_SESSION['password'])."'"); 
    if(mysql_num_rows($admins) == 1) // если найдена хоть одна строка 
    { 
        // выводим ссылки для управления сайтом 
        echo "<table align=center cellpadding=15 cellspacing=0 border=0><tr>"; 
        echo "<td><a href=\"http://".$_SERVER['HTTP_HOST']."/".$dir."administrator/\">На главную страницу Админ раздела</a></td>"; 
        echo "<td><a href=\"http://".$_SERVER['HTTP_HOST']."/".$dir."admin/profile/\">Профайл</a></td>"; 
        echo "<td><a href=\"http://".$_SERVER['HTTP_HOST']."/".$dir."admin/logout/\">Выйти</a></td>"; 
        echo "</tr></table>"; 
		echo "<center>
		<img src=\"http://".$_SERVER['HTTP_HOST']."/".$GLOBALS['dir']."/images/addcat.gif\">
		<a href=\"http://".$_SERVER['HTTP_HOST']."/".$dir."admin/cat/add_category/\"> Добавить категорию </a>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<img src=\"http://".$_SERVER['HTTP_HOST']."/".$GLOBALS['dir']."/images/additem.gif\"> 
		<a href=\"http://".$_SERVER['HTTP_HOST']."/".$dir."admin/cat/add_item/\"> Добавить наименование товара </a></center><br />";
        // если переменная action существует, и в ней что то есть, то подключаем необходимые файлы 
        if(isset($_GET['action'])) 
        { 
			if($_GET['action'] == "cat") include("admin/scat.php"); 
			if($_GET['action'] == "profile") include("admin/sprofile.php"); 
        }
		else
		{
		    ###############    ВЫВОД КАТЕГОРИЙ    ###############    
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
				$categories = mysql_query("SELECT * FROM jic_category WHERE root_cat = $id ORDER by name_cat ASC");
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
			$categories = mysql_query("SELECT * FROM jic_category WHERE root_cat = 0 ORDER by name_cat ASC");
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
    } 
    // Иначе очень сильно ругаемся, и снова выводим форму авторизации 
    else echo "<center>Администратора с данными параметрами входа не существует!<br><br>$admin_login_form</center>"; 
} 
### Конец блока управления каталогом ### 
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
