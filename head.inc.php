<head>
<title>
<?=$user_title?>
</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<meta name="keywords" content="<?=$user_keywords?>">
<meta name="description" content="<?=$user_description?>">
<link href="http://<?=$_SERVER['HTTP_HOST']."/".$dir?>default.css" rel="stylesheet" type="text/css" media="screen" />
</head>
<!-- start header -->
<div id="header">
  <div id="logo">
    <h1><a href="http://www.kurs/">Каталог услуг</a></h1> 
  </div>
  <div id="rss"> <br>
  </div>
  <div id="search">
    <form id="searchform" method=post action="http://<?=$_SERVER['HTTP_HOST'];?>/search/">
      <fieldset>
      <input type=text size=26 id="s" name=search_word value="Поиск &rarr;" onBlur="if (this.value=='') {this.value='Поиск &rarr;';}" onClick="if (this.value=='Поиск &rarr;') {this.value='';}">
      <input type=submit id="x">
      </fieldset>
    </form>
  </div>
</div>
<!-- end header -->
<!-- star menu -->
<div id="menu">
  <ul>
    <li <? echo ($_SERVER['SCRIPT_NAME'] == "/index.php") ? " class=current_page_item " : "" ; ?>> <a href="http://<?=$_SERVER['HTTP_HOST']."/".$dir?>">Главная</a></li>
    
  </ul>
</div>
<!-- end menu -->
<br>
<!-- start page -->
<div id="page">
