<? 
$host="localhost";        #Хост 
$login_mysql="root";      #Логин 
$password_mysql="";       #Пароль 
$baza_name="catalogue";   #Имя базы 
$db = @mysql_connect("$host", "$login_mysql", "$password_mysql"); 
mysql_query("set names cp1251");
if (!$db) exit("<p>К сожалению, не доступен сервер MySQL</p>"); 
if (!@mysql_select_db($baza_name,$db)) exit("<p>К сожалению, не доступна база данных</p>");       
?> 

