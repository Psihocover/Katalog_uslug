<? 
session_start(); // �������� ������ 
define('SITE', true);
include("mysql.php");// ���������� ���� � ����������� ��� �������� � �� 
include("config.php");

// ���������� �����
include ("head.inc.php"); 
// ���������� ������� ��������
?>
<!-- start content -->

<div id="content">
  <div class="post">
    <div class="title">
      <center>
        <h2>����� ����������<br />
� ���������������� ���������</h2>
      </center>
    </div>
    <div class="entry">
<?

### ���� ��������� ������ �� ���_����� ### 
if(@$_GET['action'] == "logout") // ���� � �������� ������ ���������� action ����� "logout" 
{                                             
    if(isset($_SESSION['login']) && isset($_SESSION['password'])) // ���� ���������� ���������� ���������� login � password 
    { 
        session_unregister("login"); // ������� 
        session_unregister("password"); // ������� 
        unset ($_SESSION['login'],$_SESSION['password']);// ������� 
        session_destroy();// ������� ������ 
    } 
} 
### ����� ����� ��������� ������ �� ���_����� ### 

### ���� ��������� ������, ��������� �� ����� ����������� ### 
//���� � ����� ����������� ���� �������� ����� � ������ � ���� ���������� ���������� ������������������ 
if(isset($_POST['login']) && isset($_POST['password']) && !isset($_SESSION['login']) && !isset($_SESSION['password'])) 
{ 
	$_POST['login'] = addslashes($_POST['login']);
    // ���� � �� ������, ��������� ��������� �������, � ����������� �� ����� 
    $admins = mysql_query("SELECT * FROM jic_admin WHERE login = '". $_POST['login']."' AND password = '". md5($_POST['password'])."'");      
    // ���� ������� ���� ���� ������ 
    if(mysql_num_rows($admins))  
    { 
        // ������������ ���������� ���������� 
        $login = $_POST['login']; 
        $password = $_POST['password']; 
        session_register("login"); 
        session_register("password"); 
    } 
	
################ php 5 #################	
/*	
// ���� ������� ���� ���� ������ 
    if(mysql_num_rows($admins))  
    { 
        // ������������ ���������� ���������� 
        session_register('login'); 
        session_register('password'); 
        $_SESSION['login'] = $_POST['login']; 
		$_SESSION['password'] = $_POST['password']; 
    } 
*/
################ php 5 #################	
	
    // ����� ����� ������ ��������, � ����� ������� ����� ����������� 
    else echo "<center>�������������� � ������� ����������� ����� �� ����������!<br><br>$admin_login_form</center>"; 
} 
// ����� ��� ������, ������ ������� ����� ����������� 
else if(!isset($_SESSION['login']) && !isset($_SESSION['password'])) echo "<center>".$admin_login_form."</center>"; 
### ����� ����� ��������� ������, ��������� �� ����� ����������� ### 

### ���� ���������� ��������� ### 
// ���� ���� ���������� ���������� 
if(isset($_SESSION['login']) && isset($_SESSION['password'])) 
{ 
        // ���� � �� ������, ��������� ��������� �������, � ����������� ����������� 
        $admins = mysql_query("SELECT * FROM jic_admin WHERE login = '".$_SESSION['login']."' AND password = '".md5($_SESSION['password'])."'"); 
    if(mysql_num_rows($admins) == 1) // ���� ������� ���� ���� ������ 
    { 
        // ������� ������ ��� ���������� ������ 
        echo "<table align=center cellpadding=15 cellspacing=0 border=0><tr>"; 
        echo "<td><a href=\"http://".$_SERVER['HTTP_HOST']."/".$dir."administrator/\">�� ������� �������� ����� �������</a></td>"; 
        echo "<td><a href=\"http://".$_SERVER['HTTP_HOST']."/".$dir."admin/profile/\">�������</a></td>"; 
        echo "<td><a href=\"http://".$_SERVER['HTTP_HOST']."/".$dir."admin/logout/\">�����</a></td>"; 
        echo "</tr></table>"; 
		echo "<center>
		<img src=\"http://".$_SERVER['HTTP_HOST']."/".$GLOBALS['dir']."/images/addcat.gif\">
		<a href=\"http://".$_SERVER['HTTP_HOST']."/".$dir."admin/cat/add_category/\"> �������� ��������� </a>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<img src=\"http://".$_SERVER['HTTP_HOST']."/".$GLOBALS['dir']."/images/additem.gif\"> 
		<a href=\"http://".$_SERVER['HTTP_HOST']."/".$dir."admin/cat/add_item/\"> �������� ������������ ������ </a></center><br />";
        // ���� ���������� action ����������, � � ��� ��� �� ����, �� ���������� ����������� ����� 
        if(isset($_GET['action'])) 
        { 
			if($_GET['action'] == "cat") include("admin/scat.php"); 
			if($_GET['action'] == "profile") include("admin/sprofile.php"); 
        }
		else
		{
		    ###############    ����� ���������    ###############    
			echo " <table border=0 width=100% align=center cellspacing=3 cellpadding=10>
			<tr bgcolor=#FAFAFA>
			<td align=center><strong>�������� ���������</strong></td>
			<td align=center><strong>������ ��� ���������</strong></td>
			<td align=center><strong>������������� ������ ���������</strong></td>
			<td align=center><strong>������� ���������</strong></td>
			</tr>";
			// �-�� ������ ������ ���������
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
			<a href=\"http://".$_SERVER['HTTP_HOST']."/".$dir."admin/cat/add_category/\"> �������� ��������� </a>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<img src=\"http://".$_SERVER['HTTP_HOST']."/".$GLOBALS['dir']."/images/additem.gif\"> 
			<a href=\"http://".$_SERVER['HTTP_HOST']."/".$dir."admin/cat/add_item/\"> �������� ������������ ������ </a></center>";
		}
		###############    ����� ������ ���������    ###############    
    } 
    // ����� ����� ������ ��������, � ����� ������� ����� ����������� 
    else echo "<center>�������������� � ������� ����������� ����� �� ����������!<br><br>$admin_login_form</center>"; 
} 
### ����� ����� ���������� ��������� ### 
?>
    </div>
  </div>
</div>
<!-- end content -->
<?
// ���������� ������� ��������

// ���������� ����
include ("menu.inc.php");

// ���������� ������ �����
include ("foot.inc.php"); 
?>
