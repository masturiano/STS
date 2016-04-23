<?
session_start();
include("includes/db.inc.php");
include("includes/common.php");
$common = new commonObj();
if ($_SESSION['sts-username'] == "") {
	header("location: index.php");
}
if ($_GET['action']=='logout') {
	unset($_SESSION['sts-userId'],$_SESSION['sts-userLevel'],$_SESSION['sts-username'],$_SESSION['sts-minCode'],$arrLogIn['fullName']);	
	echo "location.href='index.php';";
	exit();
}
$arrModules = $common->GetModules();
$arrUserPages = $common->getuserPages($_SESSION['sts-userId']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>STS Online</title>

<LINK rel="SHORTCUT ICON" href="images/logo-pg-si.png">
<link type="text/css" href="includes/css/menu.css" rel="stylesheet" />	
<link type="text/css" href="includes/jquery/development-bundle/demos/demos.css" rel="stylesheet" />
<link type="text/css" href="includes/jquery/css/redmond/jquery-ui-1.8.16.custom.css" rel="stylesheet" />
<link rel="stylesheet" href="includes/menu/memu-0.1.css" type="text/css">


<script type="text/javascript" src="includes/jquery/js/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="includes/jquery/js/jquery-ui-1.8.16.custom.min.js"></script>


		<style type="text/css">
		body { font-size: 62.5%; }
		label, input { display:block; }
		input.text { margin-bottom:12px; width:95%; padding: .4em; }
		fieldset { padding:0; border:0; margin-top:25px; }
		h1 { font-size: 1.2em; margin: .6em 0; }
		div#users-contain { width: 350px; margin: 20px 0; }
		div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
		div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
		.ui-dialog .ui-state-error { padding: .3em; }
		.validateTips { border: 1px solid transparent; padding: 0.3em; }
		
	</style>

	<script type="text/javascript" src="includes/main_index.js">


	</script>
<script type="text/javascript">
	/*needToConfirm = false;
	window.onbeforeunload = askConfirm;
	
	function askConfirm(){
		var ans;
		if (needToConfirm){
			return true;
		}	
	}*/
</script>


<style type="text/css">
<!--
.dvTop {
	height: 70px;
	border:1px solid  #000;
	background-repeat: repeat-x;
	background-color:#ddd;
	width:100%;

}
.dvTopContent {
	height: 70px;
	width: 950px;
	padding-left:5px;
}
.dvStatus {
	height: 25px;
}
.dvLayer {
	height: 25px;
}
.dvMenu {
	height: 700px;
	width: 130px;
	float:left;
	border:1px solid #D6D4D4;
}
.dvBody {
	height:700px;
	border:1px solid  #000;
	background-color:#fff;
	margin-top:5px;
	width:100%;
}
.dvFooter {
	height: 60px;
	width: 1000px;
}

.style10 {color: #0000CC}
.style4 {font-family: verdana; font-size: 11px; }
.style9 {font-family: verdana}
.style11 {	color: #FF0000;
	font-weight: bold;
}
.style5 {	font-family: Tahoma;
	font-size: 12px;
}
.textBox {
	color: #999999; 
	border: solid; 
	border-width: 1px; 
	width:270px; 
	height:20px;
}

.selectBox {
	color: #999999; 
	border: solid; 
	border-width: 1px; 
	width:272px; 
	height:25px;
}
.topLabel {
	font-family: Verdana;
	font-size: 10px;
	font-weight:bold;
	height: 20px;
	color: #008;
}
.topText {
	font-family: Verdana;
	font-size: 10px;
	font-weight:bold;
	height: 20px;
	color: #666666;
}
.topLogout {
	font-family: Verdana;
	font-size: 10px;
	font-weight:bold;
	height: 20px;
	color: #CA4200;
	cursor:pointer;
}
.bg {
	background: #56789a;
	color: #fff;
	font-family: segoe ui, verdana, sans-serif;
	font-size: 1.6em;
}
pre { font-size: 0.6em; }	
.menu-container {
	margin: 0 auto;
	padding: 0;
	height: 28px;
	width: 100%;
	background: #fff;
	border: 1px solid #222;
}		
.container {
	border-top: 6px solid #fff;
	margin: 0 auto;
	padding: 0;
	padding-top: 10px;
	width: 721px;
	text-align: left;
	font-size: 1.0em;
}
-->
</style>
</head>

<body topmargin="0" onload="startTime();" leftmargin="0" style="background-color: #fff;">
<div class="dvTop ui-corner-top"><div class="dvTopContent">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="32%"><img src="images/stslogo.png"/></td>
      <td width="68%"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="15%" class="topLabel">MODULE NAME</td>
          <td width="2%" class="topText">:</td>
          <td width="51%" class="topText"><span id="ModName" name="ModName"></span></td>
          <td width="10%"  class="topLabel">DATE</td>
          <td width="2%" class="topText">:</td>
          <td width="20%" class="topText"><?=strtoupper(date("F d, Y"))?></td>
        </tr>
        <tr>
          <td class="topLabel">USER</td>
          <td class="topText">:</td>
          <td class="topText">
		  <?php
		  	if((int)$_SESSION['sts-userId']==29){
				echo "GANDANG ".strtoupper($_SESSION['sts-fullName']);
			}else{
				echo strtoupper($_SESSION['sts-fullName']);
			}
		  	
		  ?></td>
          <td class="topLabel">TIME</td>
          <td class="topText">:</td>
          <td class="topText"><span id="currTime"></span></td>
        </tr>
        <tr>
          <td class="topLogout"><span id='logOut'>LOG OUT</span></td>
          <td class="topText">&nbsp;</td>
          <td class="topText">&nbsp;</td>
          <td class=""></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      </table></td>
    </tr>
  </table>
</div>
</div>
<div class="dvLayer">
	
   
		<div class="menu-container">
			<ul class="memu">
            	<?
					$ctr = 0;
					foreach($arrModules as $valMod) {
						$modID = ($ctr==0) ? "": $ctr;
				?>
						<li class="memu-root">
						<a href="#"><?=$valMod['moduleName']?></a>
                        <ul>
                        	<?
							$arrSubModule = $common->GetSubModules($valMod['moduleName']);
							foreach($arrSubModule as $valSub) {
								if (in_array($valSub['modueID'], $arrUserPages)) {
							?>
								<li><a href="#" onclick="menu('<?=$valSub['page']?>','<?=strtoupper($valSub['label'])?>')"><?=$valSub['label']?></a></li>
							<?  } 
							} ?>
                        </ul>
                        </li>
				 <? 
					$ctr++;
				}
				?>
                	<li class="memu-root">
						<a href="#"></a>
                    </li>
			</ul>
			
		</div>
		<br>
		<br>
</div>


<div class="dvBody ui-corner-bottom bg" id="dvBody">
	<center>
    	<iframe style="height:694px; border:none;" id="bodyFrame" width="99%" class="bg"></iframe></div>
    </center>
    <div id="indicator1" align="center"></div>  
    <div  id='dialogAlert' title='STS'>
        <p id='dialogMsg'></p>
</div>

</body>
</html>
