<? 
$host="localhost";        #���� 
$login_mysql="root";      #����� 
$password_mysql="";       #������ 
$baza_name="catalogue";   #��� ���� 
$db = @mysql_connect("$host", "$login_mysql", "$password_mysql"); 
mysql_query("set names cp1251");
if (!$db) exit("<p>� ���������, �� �������� ������ MySQL</p>"); 
if (!@mysql_select_db($baza_name,$db)) exit("<p>� ���������, �� �������� ���� ������</p>");       
?> 

