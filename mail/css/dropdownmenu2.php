<?php	 	 
$username = $_SESSION['uid'];
if($_SESSION['Auth']==1)
	{
	}
else
  
 	{
 	header('location:index.php');
    }

function createRandomPassword() {
$chars = "abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz";
srand((double)microtime()*1000000);
$i = 0;
$pass = '' ;
while ($i <= 9) {
$num = rand() % 33;
$tmp = substr($chars, $num, 1);
$pass = $pass. $tmp;
$i++;
}
return $pass;
}


$fn = createRandomPassword(); 
if (strlen($fn)!=10 )
{
$fun = "qszxawefcv";
}
else
{
$fun = $fn;
}
	
//encrypting ueserid
$nm=$username;
$count=strlen($nm);
$scn="";
$vl="";
$prnt="";
$val=str_split($nm);

for($i=0;$i<strlen($nm);$i++)
{
$vl=$val[$i];

if ($vl==0)
{
$a= "a" ;
}
elseif ($vl==1)
{
$a= "b" ;
}
elseif ($vl==2)
{
$a= "c" ;
}
elseif ($vl==3)
{
$a= "d" ;
}
elseif ($vl==4)
{
$a= "e" ;
}
elseif ($vl==5)
{
$a= "f" ;
}
elseif ($vl==6)
{
$a= "g" ;
}
elseif ($vl==7)
{
$a= "h" ;
}
elseif ($vl==8)
{
$a= "i" ;
}
elseif ($vl==9)
{
$a= "j" ;
}

$prnt=$prnt . $a;

$scn++;


}


$findtype=mysql_query("select courserep,admissionid from tbstuadmission where studentid='".$_SESSION['uid']."'");
$stutypeft=mysql_fetch_array($findtype);
$finatype=$stutypeft['courserep'];


$femsch=mysql_fetch_array(mysql_query("select studentid from tbstucompletedetailsscholar where studentid='".$_SESSION['uid']."' ORDER BY scholarstuid desc limit 1"));

?>

<link rel="stylesheet" type="text/css" href="css/ddsmoothmenu.css" />
<link rel="stylesheet" type="text/css" href="css/ddsmoothmenu-v.css" />
<script src="js/jquery.js"></script>
<script type="text/javascript" src="script/ddsmoothmenu.js">

<script type="text/javascript">

ddsmoothmenu.init({
	mainmenuid: "smoothmenu1", //menu DIV id
	orientation: 'h', //Horizontal or vertical menu: Set to "h" or "v"
	classname: 'ddsmoothmenu', //class added to menu's outer DIV
	//customtheme: ["#1c5a80", "#18374a"],
	contentsource: "markup" //"markup" or ["container_id", "path_to_menu_file"]
})


</script>

   <h2>Example 1</h2>

<div id="smoothmenu1" class="ddsmoothmenu">
<ul>
<li><a href="http://www.dynamicdrive.com">Item 1</a></li>
<li><a href="#">Folder 0</a>
  <ul>
  <li><a href="#">Sub Item 1.1</a></li>
  <li><a href="#">Sub Item 1.2</a></li>
  <li><a href="#">Sub Item 1.3</a></li>
  <li><a href="#">Sub Item 1.4</a></li>
  <li><a href="#">Sub Item 1.2</a></li>
  <li><a href="#">Sub Item 1.3</a></li>
  <li><a href="#">Sub Item 1.4</a></li>
  </ul>
</li>
<li><a href="#">Folder 1</a>
  <ul>
  <li><a href="#">Sub Item 1.1</a></li>
  <li><a href="#">Sub Item 1.2</a></li>
  <li><a href="#">Sub Item 1.3</a></li>
  <li><a href="#">Sub Item 1.4</a></li>
  <li><a href="#">Sub Item 1.2</a></li>
  <li><a href="#">Sub Item 1.3</a></li>
  <li><a href="#">Sub Item 1.4</a></li>
  </ul>
</li>
<li><a href="#">Item 3</a></li>
<li><a href="#">Folder 2</a>
  <ul>
  <li><a href="#">Sub Item 2.1</a></li>
  <li><a href="#">Folder 2.1</a>
    <ul>
    <li><a href="#">Sub Item 2.1.1</a></li>
    <li><a href="#">Sub Item 2.1.2</a></li>
    <li><a href="#">Folder 3.1.1</a>
		<ul>
    		<li><a href="#">Sub Item 3.1.1.1</a></li>
    		<li><a href="#">Sub Item 3.1.1.2</a></li>
    		<li><a href="#">Sub Item 3.1.1.3</a></li>
    		<li><a href="#">Sub Item 3.1.1.4</a></li>
    		<li><a href="#">Sub Item 3.1.1.5</a></li>
		</ul>
    </li>
    <li><a href="#">Sub Item 2.1.4</a></li>
    </ul>
  </li>
  </ul>
</li>
<li><a href="http://www.dynamicdrive.com/style/">Item 4</a></li>
</ul>
<br style="clear: left" />
</div>


</div>

