<?
// �����, � ������� ����� ������ ������.
// ���� ������� � ������ �����, �� �������� ���������� �������� ������...
$papka = "";

### ����_������, ����������� ��� ���������� ������������ ���������� ###
//���������
$user_title = "������� �������";

//����_���������
$user_keywords = "�������, ������, ��������, ����";

//����_��������
$user_description = "���� ������� �������, � ������� �� ������� ����� ������ �������, �������� � ��� � ���";


//e-mail ��� ���������
$admin_mail = "admin@google.com";
#####################################################################


//����� ����� ������
$admin_login_form = "<form method=post> 
<input name=login value=����� onBlur=\"if (this.value=='') {this.value='�����';}\" onClick=\"if (this.value=='�����') {this.value='';}\"><br />
<input name=password value=������ onBlur=\"if (this.value=='') {this.value='������';}\" onClick=\"if (this.value=='������') {this.value='';}\"><br /> 
<input type=submit value=\"����������� ����� \"> 
</form>";

// ���������� �������, ��������� � �����-������ �� ����� ��������
$COUNT_SHOW_ITEMS_IN_ADMINPAGE = 5; 

// ���� ��� ���������� � �����-������: id - �� ������; title - �� ��������; price - �� ����; hits - �� ������������
$SORT_FIELD_ITEMS_IN_ADMINPAGE = "id";

// ���������� ������� � �����-������ �� ����������� - ASC, �������� - DESC
$DESC_ASC_ITEMS_IN_ADMINPAGE = "DESC"; 

// ���������� ��������� �������, ��������� �� ������� ��������
$COUNT_SHOW_ITEMS_IN_INDEX = 4; 

// ���������� ��������� �������, ��������� � ����� ����� �� ������� ��������
$COUNT_SHOW_IN_LINE_IN_INDEX = 2; 

// ���������� ��������� �������, ��������� �� �������� ��������
$COUNT_SHOW_ITEMS = 6; 

// ���������� �������, ��������� � ����� �����  �� �������� ��������
$COUNT_SHOW_IN_LINE = 2; 

// ���������� ������� �� �������� ��� ������ ���������
$COUNT_SHOW_ITEMS_IN_CAT = 4; 

########################################################################################
########################################################################################
########################################################################################
################## 
##################         ������ ������ �� ������� !!!
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
	if ($page>1) echo " <a href = \"http://". $_SERVER['HTTP_HOST'] ."/".$GLOBALS['dir']."$path_to_page/$id_cat/page/".($page - 1)."/\" title = \"���������� ��������\">&larr;</a> ";
	$diapazon = 10;
	$page_from = $page - $diapazon; 
	if($page_from < 1) $page_from = 1;
	$page_to = $page + $diapazon; 
	if($page_to > $count_pages) $page_to = $count_pages;

	for ($i = $page_from; $i <= $page_to; $i++)
	{
		if ($i == $page) echo " [$i] ";
		else echo "<a href=\"http://".$_SERVER['HTTP_HOST']."/".$GLOBALS['dir']."$path_to_page/$id_cat/page/$i/\" title = \"$i ��������\">[$i]</a>";
	}
	if ($page<$count_pages) echo " <a href = \"http://". $_SERVER['HTTP_HOST'] ."/".$GLOBALS['dir']."$path_to_page/$id_cat/page/".($page + 1)."/\" title = \"��������� ��������\">&rarr;</a> ";
	echo "</td></tr></table>";
}

function show_page_list_index ($page, $num, $count_on_page, $path_to_page)
{
	echo "<table align=center><tr><td class=\"y b\">";
	if (!isset($page) && intval($page) == 0) $page = 1;
	$count_pages = intval($num / $count_on_page);
	$ostatok = $num % $count_on_page;
	if($ostatok > 0) $count_pages++;
	if ($page>1) echo " <a href = \"http://". $_SERVER['HTTP_HOST'] ."/".$GLOBALS['dir']."$path_to_page/page/".($page - 1)."/\" title = \"���������� ��������\">&larr;</a> ";
	$diapazon = 1;
	$page_from = $page - $diapazon; 
	if($page_from < 1) $page_from = 1;
	$page_to = $page + $diapazon; 
	if($page_to > $count_pages) $page_to = $count_pages;

	for ($i = $page_from; $i <= $page_to; $i++)
	{
		if ($i == $page) echo " [$i] ";
		else echo "<a href =\"http://". $_SERVER['HTTP_HOST'] ."/".$GLOBALS['dir']."$path_to_page/page/$i/\" title = \" $i �������� \">[$i]</a>";
	}
	if ($page<$count_pages) echo " <a href = \"http://". $_SERVER['HTTP_HOST'] ."/".$GLOBALS['dir']."$path_to_page/page/".($page + 1)."/\" title = \"��������� ��������\">&rarr;</a> ";
	echo "</td></tr></table>";
}
?> 