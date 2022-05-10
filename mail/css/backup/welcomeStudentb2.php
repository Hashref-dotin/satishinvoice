<?php		 
ob_start();
session_start();
include('config.php');
if($_SESSION['Auth']==1)
	{
	}
else
  
 	{
 	header('location:index.php');
    }
	?><head>
	<script type="text/javascript">


  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-20676820-5']);
  _gaq.push(['_trackPageview']);


  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();


</script>
	
	
	</head>
	
	<?php		
//functions of setting read,star and delete
if(isset($_POST['delete']))  //delete function
{ 
$total=10;
for($i=0;$i<$total;$i++)
{ 
$del_id=$_POST['checkbox'][$i];
$del=substr($del_id,0,1);
$id_del=substr($del_id,1);
if($del=='g')  //update deltstatus for group
{
$sqldelete = "UPDATE `generalmsgstudent` SET `deletestatus` = '1' WHERE `studentid` ='".$_SESSION['uid']."' AND `sentmsgid` ='$id_del'";
$result = mysql_query($sqldelete);
}
else //individual delete
{
$sql = "DELETE FROM studentgeneralmsg WHERE msgid ='".$del_id."' and studentid='".$_SESSION['uid']."'"; 
$result = mysql_query($sql); 
}
}
}


/// to mark Star
if(isset($_POST['flag']))
{ 
$total=10;
for($i=0;$i<$total;$i++)
{ 
$flag_id=$_POST['checkbox'][$i];
$flag=substr($flag_id,0,1);
$id_flag=substr($flag_id,1);
if($flag=='g')  //group msg
{
$sqlflag = "UPDATE generalmsgstudent  SET flag = '1' WHERE `studentid` ='".$_SESSION['uid']."' AND  sentmsgid='".$id_flag."'";
$result= mysql_query($sqlflag);
}
else //individual delete
{
$sqlupdate = "UPDATE studentgeneralmsg  SET flag = '1' WHERE msgid='".$flag_id."' and studentid='" .$_SESSION['uid'] . "'";
$result= mysql_query($sqlupdate); 
}
}
}

//to remove star

if(isset($_POST['rflag']))
{ 
$total=10;
for($i=0;$i<$total;$i++)
{ 
$flag_id=$_POST['checkbox'][$i];
$flag=substr($flag_id,0,1);
$id_flag=substr($flag_id,1);
if($flag=='g')  //group msg
{
$sqlflag = "UPDATE generalmsgstudent  SET flag = '0' WHERE `studentid` ='".$_SESSION['uid']."' AND  sentmsgid='".$id_flag."'";
$result[] = mysql_query($sqlflag);
}
else //individual delete
{
$sqlupdate = "UPDATE studentgeneralmsg  SET flag = '0' WHERE msgid='".$flag_id."' and studentid='".$_SESSION['uid']."'";
$result[]= mysql_query($sqlupdate); 
}
}
}

//to mark as read
if(isset($_POST['read']))
{ 
$total=10;
 for($i=0;$i<$total;$i++)
{ 
$read_id=$_POST['checkbox'][$i];
$read=substr($read_id,0,1);
$id_read=substr($read_id,1);
if($read=='g')  //group msg
{
$sqlflag = "UPDATE generalmsgstudent  SET read_msg = '1' WHERE `studentid` ='".$_SESSION['uid']."' AND  sentmsgid='".$id_read."'";
$result[] = mysql_query($sqlflag);
}
else //individual 
{
$sqlupdate = "UPDATE studentgeneralmsg  SET read_msg = '0' WHERE msgid='".$read_id."'  and studentid='".$_SESSION['uid']."'";
$result[] = mysql_query($sqlupdate); 
}
}
}
?>

<?php		
$finalmsg=array();


$query = "SELECT a.msgdate,a.sentmsgid,b.read_msg,b.flag FROM generalmsgsent a, generalmsgstudent b where a.sentmsgid=b.sentmsgid and b.studentid='".$_SESSION['uid']."' and b.deletestatus!=1 order by a.msgdate desc";
$result = mysql_query($query) or die('Error, query failed,all');
while ($data1 = mysql_fetch_array($result))
{

//get the date of account created
$created_on=mysql_query("select ondate from tbstuadmission where studentid='".$_SESSION['uid']."';");
$create_date=mysql_fetch_array($created_on);
$so_date=$create_date['ondate'];
if($data1['sentmsgid']=='2')
{
$getmsg=$so_date.',G,'.$data1['sentmsgid'].','.$data1['read_msg'].','.$data1['flag'];
}
else
{
$getmsg=$data1['msgdate'].',G,'.$data1['sentmsgid'].','.$data1['read_msg'].','.$data1['flag'];
}
$message=array_push($finalmsg,$getmsg);
}	 
	
	
				 
$query = "SELECT a.msgdate,a.msgid,b.read_msg,b.flag FROM generalmsg a,studentgeneralmsg b where a.msgid=b.msgid and b.studentid='".$_SESSION['uid']."' order by a.msgdate desc";
$result = mysql_query($query) or die('Error, query failed single');
while ($data1 = mysql_fetch_array($result)) 
{
$getmsg=$data1['msgdate'].',S,'.$data1['msgid'].','.$data1['read_msg'].','.$data1['flag'];
 $message=array_push($finalmsg,$getmsg);
}
 rsort($finalmsg);
 
                      $color=1;

	/////////////////////START OF ARRAY PAGINATION CODE/////////////////////
$ptemp="http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
$pt=explode('&',$ptemp);
if (strpos($ptemp,'pageno'))
 array_pop($pt);
$pt=implode('&',$pt);
$ptemp=$pt;
$array=$finalmsg; // REPLACE $KEY WITH YOUR ARRAY VARIABLE
$page = $_REQUEST['pageno'];

$currentpage = isset($page) ? (integer)$page : 1;
$numperpage = 15; //NUMBER OF RECORDS TO BE DISPLAYED PER PAGE

$total = count($array);
$numofpages = ceil($total / $numperpage); //TOTAL NUMBER OF PAGES


if ($currentpage != 1) 
{ 
 $previous_page = $currentpage - 1;
 $previous = '<a href="'.$ptemp.'&pageno='.$previous_page.'"></a> ';    
}    
$pages = '';
for ($a=1; $a<=$numofpages; $a++)
{
  if ($a == $currentpage) 
 $pages .= '<font color="#0066CC">'.$a .'</font>&nbsp;';
  else 
 $pages .= '<a href="'.$ptemp.'&pageno='.$a.'" ><font color="#000">'. $a .'</font></a> ';
}
$pages = substr($pages,0,-1); 
if ($currentpage != $numofpages) 
{ 
 $next_page = $currentpage + 1;
 $next = ' <a href="'.$ptemp.'&pageno='.$next_page.'"></a>';
}



?>
<script src="Scripts/swfobject_modified.js" type="text/javascript"></script>


	<meta http-equiv="X-UA-Compatible" content="IE=7" />
<meta http-equiv="X-UA-Compatible" content="IE=8" />


<script src="Scripts/AC_RunActiveContent.js" type="text/javascript"></script>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="42" valign="middle" background="images/content_title.jpg"><table width="97%" border="0" align="right" cellpadding="0" style="margin-top:5px;" cellspacing="0">
      <tr>
        <td width="40" valign="middle" class="title_heading"><img src="images/title_arrow.jpg" width="30" height="28" /></td>
        <td valign="middle" class="section_heading1"><strong>Control Panel </strong></td><td><font color="#FFFFFF"><?php		 echo $_SESSION['fullname'].'&nbsp;&nbsp;&nbsp;&nbsp;<strong>ID:</strong>'.$_SESSION['uid'].'&nbsp;&nbsp;&nbsp;&nbsp;<strong>Batch:</strong>'.$_SESSION['batchname']; ?></font></td>
		<td width="97" class="logout"><a href="index.php?action=view_profile">My Profile</a></td>
		
        <td width="97" class="logout"><a href="index.php?action=logout">Logout</a></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td valign="top" background="images/content_mid.jpg"><table width="984" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td align="right" valign="top"></td>
      </tr>
      <tr>
        <td valign="top" colspan="2" align="center" style="padding:10px 0px 10px 5px;"><?php		 include_once('includes/dropdownmenu2.php');?></td>
      </tr>


<?php			  
if($_SESSION['uid']!='')
{
?>
      <link rel="STYLESHEET" type="text/css" href="css/popup-contact.css" />

<body onLoad="openpopup();"></body>
<script type="text/javascript">
function openpopup()
{
fg_popup_form("fg_formContainer","fg_form_InnerContainer","fg_backgroundpopup");
}
</script>


<?php		
include_once('includes/contactform-code.php');
}
?>
	  
      <tr>
        <td colspan="2" valign="top"><table width="98%" border="0" cellspacing="0" cellpadding="0" align="center">
            <tr>
              <td width="24%" align="left">
                    <object  type="application/x-shockwave-flash" data="flashstu/asia-pacific-new.swf" width="240" height="100">
                          <param name="movie" value="flashstu/asia-pacific-new.swf" />
                          <param name="wmode" value="transparent" />
                    </object>
				</td>
              <td width="1%"></td>
              <td width="24%"><a href="http://www.isbf.edu.in/lp/mba/be/" target="_blank"><img src="images/ISBFBanner.gif" width="234" height="100" border="0"/></a></td>
              <td width="1%"></td>
              <td width="24%"><!--<img src="images/placement-banner.jpg" width="234" height="100" />-->
                <object  type="application/x-shockwave-flash" data="flashstu/law.swf" width="234" height="100">
                  <param name="movie" value="flashstu/law.swf" />
                  <param name="wmode" value="transparent" />
                </object></td>
              <td width="1%"></td>
              <td width="24%" align="right"><object  type="application/x-shockwave-flash" data="flashstu/placement.swf" width="234" height="100">
                  <param name="movie" value="flashstu/placement.swf" />
                  <param name="wmode" value="transparent" />
                </object></td>
            </tr>
        </table></td>
        </tr>
      
      <tr>
        <td valign="top"><table border="0" cellspacing="0" cellpadding="0" align="center" width="234" style="float:left; margin-left:10px;">
            <tr>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td height="100" align="center" valign="top" width="177">
              <object  type="application/x-shockwave-flash" data="flashstu/infinity-new.swf" width="234" height="100">
                <param name="movie" value="flashstu/infinity-new.swf" />
                <param name="wmode" value="transparent" />
              </object></td>
            </tr>
            <tr>
              <td height="25"></td>
            </tr>
            <tr>
              <td height="100" align="center" valign="top" width="177"><a href="http://www.naukri.com/mailers/infinity-march-new/" target="_blank">
                
              </a>
                <object  type="application/x-shockwave-flash" data="flashstu/niilm-new.swf" width="234" height="100">
                  <param name="movie" value="flashstu/niilm-new.swf" />
                  <param name="wmode" value="transparent" />
                </object>
                <!--
              <script type="text/javascript">
AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0','width','177','height','239','src','inbussflashfile/Bulls Eye_','quality','high','pluginspage','http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash','movie','inbussflashfile/Bulls Eye_' ); //end AC code
</script><noscript><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="177" height="239">
                <param name="movie" value="inbussflashfile/Bulls Eye_.swf" />
                <param name="quality" value="high" />
                <embed src="all_ads4.swf" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="177" height="239"></embed>
              </object></noscript>
              
              <!-- Edited on 3rd of Nov starts 

   <div style="width:177px; height:239px; position:relative; display:block;">
       <a target="_blank" style="position:absolute; width:177px; height:239px;" href="http://inbuss.com/HTML_EMails/LP/lp.html"></a>
   

   <object width="177" height="239" type="application/x-shockwave-flash" data="stu-banner.swf">
    <param name="movie" value="stu-banner.swf">
    <param name="wmode" value="transparent">
   </object>
</div>
              Edited on 3rd of Nov ends -->             </td>
            </tr>
            <tr>
              <td height="25" align="center" valign="top"></td>
            </tr>
            <tr>
              <td height="100" align="center" valign="top"><object  type="application/x-shockwave-flash" data="flashstu/symbiosis.swf" width="234" height="100">
                <param name="movie" value="flashstu/symbiosis.swf" />
                <param name="wmode" value="transparent" />
              </object></td>
            </tr>
            <tr>
              <td height="25" align="center" valign="top"></td>
            </tr>
            <tr>
              <td height="100" align="center" valign="top"><object  type="application/x-shockwave-flash" data="flashstu/clat.swf" width="234" height="100">
                <param name="movie" value="flashstu/clat.swf" />
                <param name="wmode" value="transparent" />
              </object></td>
            </tr>
        </table></td>
        <td valign="top" width="80%">
            <form name="form1" method="post" action="">
              <table border="0" cellspacing="0" cellpadding="1" style="margin-top:20px; width:99%;" align="right">
                <tr>
                  <td align="left"><input name="read" type="submit"  id="read" value="Mark as Read" />
                  
                    <input name="flag" type="submit"  id="flag" value="Add Star" />
                   
                    <input name="rflag" type="submit"  id="rflag" value="Remove Star" />
                  
                    <input name="delete" type="submit"  id="delete" value="Delete" onClick="return clk();" />
                    <div align="right" style="font:11px Verdana, Geneva, sans-serif; color:#0066CC;"></div></td>
                  <td align="left"><span style="font:11px Verdana, Geneva, sans-serif; color:#0066CC;">Pages <?php		 echo ''. $previous .''. $pages . ''.$next.''; ?></span></td>
                </tr>
                
                <tr>
                  <td colspan="2"><table border="1" cellspacing="0" cellpadding="3" bgcolor="#ffffff" width="100%">
                      <tr bgcolor="#CCFFFF">
                        <td width="5%" align="center"><input type="checkbox" name="Check_All" value="Check All"
onclick="Check(document.form1.checkbox)" /></td>
                        <td width="78%"><strong>Subject</strong></td>
                        <td width="17%"><b>Date</b></td>
                      </tr>
<?php		

if(isset($array))
{
    if (($currentpage > 0) && ($currentpages <= $numofpages))
 {
            $start = ($currentpage-1) * $numperpage;
        for($i=$start;$i<=($numperpage+$start-1);$i++) 
  {
            $value=$array[$i];
			if($color%2==0)
{
$bcolor='#FFEDDC';
}
else 
{
$bcolor='#FFFFCC';
}
           // echo $value.'<br>';
$split=explode(',',$value);
$date=$split[0];
$mailid=$split[2];
$from=$split[1];
$read=$split[3];
$flag=$split[4];


$dob = explode('-',$date);
	$year = $dob[0];
	$month = $dob[1];
	$day = $dob[2];
	
	
switch ($month)
{
case '01':
 $monthname = "Jan";
  break;
case '02':
 $monthname = "Feb";
  break;
case '03':
 $monthname = "Mar";
  break;
case '04':
 $monthname = "Apr";
  break;
case '05':
 $monthname = "May";
  break;
case '06':
 $monthname = "Jun";
  break;
case '07':
 $monthname = "July";
  break;
case '08':
 $monthname = "Aug";
  break;
case '09':
 $monthname = "Sep";
  break;
case '10':
 $monthname = "Oct";
  break;
case '11':
 $monthname = "Nov";
  break;
case '12':
 $monthname = "Dec";
  break;      
default:
  //echo "No number between 1 and 3";
}

if($flag==0)
{
$imagepath='images/flag_black.jpg';
}
else
{
$imagepath='images/flag_yellow.jpg';
}
	
if(($read==0 && $from=='G')||($read==1 && $from=='S'))
{
$strongst='<strong>';
$strongend='</strong>';
}
else
{
$strongst='';
$strongend='';
}
	
if($from=='G')
{
$msgsubject=mysql_query("select msg_subject from generalmsgsent where sentmsgid='$mailid'");
$getmessage=mysql_fetch_array($msgsubject);
$subject=$getmessage[0];
echo "<tr bgcolor = '$bcolor'>";
echo "<td valign=top class = small align='center'><input name='checkbox[]' type='checkbox' id='checkbox' value='g$mailid'> </td>";
		echo "<td valign=top align=left class = text>";
		echo "<img src='$imagepath' align = 'absbottom' />&nbsp;";
		echo "<a href = 'index.php?action=viewmail&k2=$mailid'>$strongst$subject$strongend</a></td>";
		echo "<td valign=top align=left class = text>$strongst$monthname $day, $year$strongend </td>";
	   echo "</tr>";
	   $color++;
}


else if($from=='S')
{
$msgsubject=mysql_query("select msg_subject from generalmsg where msgid='$mailid'");
$getmessage=mysql_fetch_array($msgsubject);
$subject=$getmessage[0];
echo "<tr bgcolor = '$bcolor'>";
echo "<td valign=top class = small align='center'><input name='checkbox[]' type='checkbox' id='checkbox' value='$mailid'> </td>";
		echo "<td valign=top align=left class = text>";
		echo "<img src='$imagepath' align = 'absbottom' />&nbsp;";
		echo "<a href = 'index.php?action=viewmailk&k2=$mailid'>$strongst$subject$strongend</a></td>";
		echo "<td valign=top align=left class = text>$strongst$monthname $day, $year$strongend</td>";
	   echo "</tr>";
	   $color++;
}
}



         
}
 }

?>
                  </table></td>
                </tr>
              </table>
            </form></td>
      </tr>
      
      <tr>
        <td colspan="3" align="center"></td>
      </tr>
      <tr>
        <td colspan="3" align="center" valign="top">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
 
 
  <tr>
  	<td valign="top" background="images/content_bottom.jpg" bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
</table>
<script language="JavaScript" type="text/javascript">

function Check(chk)
{
if(document.form1.Check_All.value=="Check All"){
for (i = 0; i < chk.length; i++)
chk[i].checked = true ;
document.form1.Check_All.value="UnCheck All";
}else{

for (i = 0; i < chk.length; i++)
chk[i].checked = false ;
document.form1.Check_All.value="Check All";
}
}


    </script>
            <script type="text/javascript">
function clk() {
 return confirm("Are you sure to delete the record ")
	
}
            </script>
            
            <script type="text/javascript">
			swfobject.registerObject("FlashID");
            </script>
