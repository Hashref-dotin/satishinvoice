<?php	 	
ob_start();

set_time_limit('0');
include('config.php');
if(isset($_GET['domainid'])) {
    $domid = base64_decode($_GET['domainid']);
	
}
?>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="http://www.hitbullseye.com/includes/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" src=
"http://cdn.datatables.net/plug-ins/e9421181788/integration/bootstrap/3/dataTables.bootstrap.js"></script>
<script>
$(document).ready(function(){
	$('#filter').change(function() {
		var fVal = $(this).val();
		if(fVal == "institutes") {
			<?php if($domid == 6) { ?>
			window.location = "index.php?action=latestbschoolinsititue&domainid=<?php echo $_GET['domainid']; ?>&filter="+$(this).val();
			<?php } else { ?>
			window.location = "index.php?action=latestbschoolinsititues&domainid=<?php echo $_GET['domainid']; ?>&filter="+$(this).val();
			<?php } ?>
		}  else if(fVal == "all") {
			<?php if($domid == 6) { ?>
			window.location = "index.php?action=latestbschool-tests-inst&domainid=<?php echo $_GET['domainid']; ?>&filter="+$(this).val();
			<?php } else { ?>
			window.location = "index.php?action=latestbschool-tests-insts&domainid=<?php echo $_GET['domainid']; ?>&filter="+$(this).val();
			<?php } ?>
		}
		else if(fVal == "tests") {
			<?php if($domid == 6) { ?>
			window.location = "index.php?action=latestbschooltest&domainid=<?php echo $_GET['domainid']; ?>&filter="+$(this).val();
			<?php } else { ?>
			window.location = "index.php?action=latestbschooltests&domainid=<?php echo $_GET['domainid']; ?>&filter="+$(this).val();
			<?php } ?>
		}
	});
	$('#boottests').dataTable({
		"bSort": false,
		"iDisplayLength": -1
	});
});
</script>
<div class="navigation-bg">
  <table cellspacing="0" cellpadding="0" border="0" align="center" style="margin-top:5px; width: 90%;">
            <tbody><tr>
                <td width="16%" valign="middle" align="right" style="text-align: left; height: 26px;" class="other1">
                  <span style="display:inline-block;color:White;font-weight:normal;width:142px;" class="logoutnew" id="ctl00_lblcity"><?php echo $_SESSION['fullname'];?></span>              </td>
                <td width="10%" valign="middle" align="left" style="text-align: left; height: 26px;" class="other1">
                    <span style="color:White;" class="logoutnew" id="ctl00_Label2">Student ID :</span></td>
              <td valign="middle" style="text-align: left; height: 26px; width: 146px;" class="other1">
                                <span style="color:White;" class="logoutnew" id="ctl00_lblidnew"><?php echo $_SESSION['uid'];?></span>
                  &nbsp;</td>
                <td valign="middle" style="width: 56px; height: 26px; text-align: center" class="other1">
                    <span style="color:White;" class="logoutnew" id="ctl00_Label1">Batch :</span></td>
                <td valign="middle" style="text-align: left; height: 26px;" class="other1">
                    <span style="color:White;" class="logoutnew" id="ctl00_batch"><?php echo $_SESSION['batchname'];?></span></td>
                <td valign="middle" style="text-align: right; height: 26px;" class="other1">
                </td>
                <td valign="middle" style="text-align: center; height: 26px;" class="other1">
					<a href="index.php?action=logout">Logout</a></td>			
              </tr>
        </tbody></table>
  </div>
  <div style="clear:both;"></div>
  <table class="mid-container">
  <tr>
    <td valign="top" bgcolor="#FFFFFF">
	<table width="984" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td valign="top"  align="right" style="padding-right:40px;"></td>
      </tr>
	  <tr>
        <td valign="top"  align="right" style="padding-right:40px;">&nbsp;</td>
      </tr>
      <tr>
        <td valign="top" align="center" style="padding:10px 0px 10px 5px;"><?php include_once('includes/dropdownmenutest.php');?></td>
      </tr>
</table>
		</td>
		</tr>
		<tr><td>
		<form method="post" name="form1" action="">
		<input type="hidden" value="<?php echo $domid ;?>" name="dom" >
		<div class="bschoolinsititue-search" style="float:left;">
			&emsp;<select name="filter" id="filter">
			<option value="all" <?php if($_GET['filter']=='all') { echo "selected='selected'";}?>>- ALL -</option>
			<option value="tests" <?php if($_GET['filter']=='tests' || $_GET['action']=='bschooltest') { echo "selected='selected'";}?>>-Latest Test Alerts-</option>
			<option value="tests" <?php if($_GET['filter']=='institutes' || $_GET['action']=='bschooltest') { echo "selected='selected'";}?>>-Latest Institute Alerts-</option>
			</select>
		</div>
		<div class="bschoolinsititue-search" style="float:left;">
			<select name="sort" id="sort">
				<option value="">- Sort -</option>
			</select>
		</div>
		<table id="boottests" class="table table-striped table-bordered" width="100%">
			<thead>
			  <tr>
				  <th></th>
			  </tr>
			</thead>
			<tbody>
			<?php 
			$perpage = 20;
			if(isset($_GET["page"])) {
			$page = intval($_GET["page"]);
			}
			else{
			$page = 1;
			}
			$calc = $perpage * $page;
			$start = $calc - $perpage;
			
			$sQuery = "SELECT 'tbl1' AS table1 ,c.testname AS tiname,'' AS zone,
			'' AS rating,'' AS Cost,c.app_form AS appform,c.notifi,c.website,c.status ,
			c.testid,c.appl_cost,c.created_date AS created_date,
			CASE c.test_image WHEN '' THEN 'no-image.jpg' ELSE c.test_image END AS image,
			DATE_FORMAT(DATE(STR_TO_DATE(c.last_date,'%d-%m-%YY')),'%d %M, %Y') AS lastdates,
			DATE_FORMAT(DATE(STR_TO_DATE(c.test_date,'%d-%m-%YY')),'%d %M, %Y')  AS teststartdates ,DATE_FORMAT(DATE(STR_TO_DATE(c.testto_date,'%d-%m-%YY')),'%d %M, %Y') AS testenddates FROM test_domain_relation a INNER JOIN bschool_testtbl c ON c.testid = a.test_id WHERE a.domainid = 2 AND c.status != 2 UNION ALL	SELECT 'tbl2' AS table1 ,c.inst_name AS tiname,c.zone,c.rank  AS rating ,c.study_cost AS Cost,c.appform,c.notifi,c.website,c.active as status, c.testid,c.appl_cost, c.created_date AS created_date,	CASE c.inst_image WHEN '' THEN 'no-image.jpg' ELSE c.inst_image END AS image,'' AS teststartdates, '' AS testenddates ,	DATE_FORMAT(DATE(STR_TO_DATE(c.last_date,'%d-%m-%YY')),'%d %M, %Y') AS lastdates
			FROM inst_domain_relation a INNER JOIN bschool_institute c ON c.inst_id = a.insti_id
			WHERE a.domainid = 2 AND c.active != 2 ORDER BY created_date DESC Limit $start, $perpage ";

			$SQL = mysql_query($sQuery);
			$count = mysql_num_rows($SQL);
			if($count >0) { 
				while($res = mysql_fetch_object($SQL)):
					if($res->status ==1 && $res->notifi != "") {
						$nPath = '<a href="/includes/files/'.$res->notifi.'" target="_blank" title = "Click here to download notification"><i class="fa fa-download fa-2x"></i></a>';
					}
					else {
						$nPath = '';
					}
					if($res->status ==1 && $res->appform != "") {
						$aPath = '<a href="/includes/testmaster_pdffiles/'.$res->appform.'" target="_blank" title = "Click here to download Application Form"><i class="fa fa-download fa-2x"></i></a>';
					}
					else {
						$aPath = '';
					}
					if($res->table1 == "tbl1" ) { ?>
					<tr><td>
						<div class="bschooltests-cont">
							<div class="bschooltests-lft-div"><img src="/includes/testmaster_pdffiles/<?php echo $res->image; ?>" border="0" alt="Logo Image"/><div class="dwl-icon"><?php echo $nPath; ?></div>
							</div>
							<div class="bschooltests-rft-div">
							<p><strong><?php echo ucwords($res->tiname); ?></strong></p>
							<ul class="bschooltests-ul">
							<?php if($res->status == 1) { ?>
								<li><strong>Website:</strong> <a href="http://<?php echo $res->website; ?>" target="_blank"><?php echo $res->website; ?></a></li>
								<li><strong>Application Form:</strong> <?php echo ( ($res->appl_cost !="" && $res->appl_cost != 0) ? "<i class='fa fa-inr'></i>$res->appl_cost" : "N/A"); ?></li>
								</ul>
								<ul class="bschooltests-ul"><li><strong>Test Date:</strong> <?php echo ($res->testenddates =="" ? $res->teststartdates : $res->teststartdates." to ".$res->testenddates); ?></li>
								<li><strong>Last Date to Apply:</strong> <?php echo $res->lastdates; ?> &nbsp;&nbsp;<?php echo $aPath; ?></li>
							<?php } else { ?>
								<li><strong>Website:</strong> <a href="http://<?php echo $res->website; ?>" target="_blank"><?php echo $res->website; ?></a></li>
							<?php }?>
							</ul>
							</div>
						</div>
					</td></tr>
				<?php } else { ?>			
				<tr><td>
					<div class="bschoolinsititue-cont">
					<div class="bschoolinsititue-lft-div"><img src="/includes/testmaster_pdffiles/<?php echo $res->image; ?>" border="0" />
					<div class="dwl-icon"><?php echo $nPath; ?></div>
					</div>
					<div class="bschoolinsititue-rft-div">
					<p><strong><?php echo $res->tiname; ?></strong></p>
					<ul class="bschoolinsititue-zone-ul"><li>Zone: <?php echo $res->zone; ?></li><li>Our Rating: <?php echo $res->rating; ?></li><li>Study Cost: <?php echo $res->Cost; ?></li></ul>
					<ul class="bschoolinsititue-test-ul">
						<li>Test/s: 
						<?php 
						$qry = "select testname from bschool_testtbl where testid IN ($res->testid) ";
						$tsql = mysql_query($qry);						
						$sTring='';
						while($trow = mysql_fetch_object($tsql) ):
							$sTring .= $trow->testname .' / ';
						endwhile;
						echo substr($sTring ,0,-2);
						?></li>
						<?php if($res->status == 1) { ?>
						<li class="width24">Last Date to Apply: <?php echo $res->lastdates; ?></li>
						<?php } ?>
					</ul>
					<ul class="bschoolinsititue-test-ul">
						<li>Website: <a href="http://<?php echo $res->website; ?>" target="_blank"><?php echo $res->website; ?></a></li>
						<?php if($res->status == 1) { ?>
							<li class="width24">Application Form: <?php echo ( ($res->appl_cost !="" && $res->appl_cost != 0) ? "<i class='fa fa-inr'></i>$res->appl_cost" : "N/A"); ?> &nbsp;&nbsp;<?php echo $aPath; ?></li>
						<?php } ?>
					</ul>
					</div>
					</div>
				</td></tr>
				<?php } ?>	
			<?php endwhile; ?>
				</tbody>
			</table>
			<div id="pagination">
					<?php
					$addstring = '&domainid='.$_GET['domainid'];
					
					$bSQL = "SELECT SUM(rc) AS total FROM (SELECT COUNT(testid) AS rc FROM test_domain_relation a INNER JOIN bschool_testtbl c ON c.testid = a.test_id
					WHERE a.domainid = 2 AND c.status != 2 UNION ALL SELECT COUNT(*) AS total
					FROM inst_domain_relation a INNER JOIN bschool_institute c ON c.inst_id = a.insti_id WHERE a.domainid = 2 AND c.active != 2) AS counts "; 

					$SQLpage = mysql_query($bSQL);
					
					$rows = mysql_num_rows($SQLpage);
					
					if(isset($page)){
						$rs = mysql_fetch_object($SQLpage);
						$total = $rs->total; 
						$totalPages = ceil($total / $perpage);

						if($page <=1 ){
							echo "<span id='previous-l'>&laquo; Previous</span>";
						}
						else {
							$j = $page - 1;
							echo "<span><a id='page_a_link' href='index.php?action=latestbschool-tests-insts&page=$j$addstring'>&laquo; Previous</a></span>";
						}
						for($i=1; $i <= $totalPages; $i++){
							if($i<>$page){
								echo "<span><a href='index.php?action=latestbschool-tests-insts&page=$i$addstring' id='page_a_link'>$i</a></span>";
							}
							else {
								echo "<span id='page_links' >$i</span>";
							}
						}
						if($page == $totalPages ){
							echo "<span id='previous-l'>Next &raquo;</span>";
						}
						else{
							$j = $page + 1;
							echo "<span><a href='index.php?action=latestbschool-tests-insts&page=$j$addstring' id='page_a_link'>Next &raquo;</a></span>";
						}
					}?>
				</div>
			<?php } ?>
		</form >
		
		</td></tr>
	</table>