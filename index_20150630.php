<?
session_start();
include("includes/db.inc.php");
include("includes/common.php");
$common = new commonObj();
if ($_SESSION['sts-username'] != "") {
	header("location: main_index.php");
}
if ($_GET['action']=='login') {
	$arrLogIn = $common->Login($_GET['username'],$_GET['password']);
	if ($arrLogIn['userId'] != '') {
		$_SESSION['sts-userId']		 	= $arrLogIn['userId'];
		$_SESSION['sts-userLevel']	 	= $arrLogIn['userLevel'];
		$_SESSION['sts-username']	 	= $_GET['username'];
		$_SESSION['sts-grpCode']	 	= $arrLogIn['grpCode'];
		$_SESSION['sts-fullName']	 	= $arrLogIn['fullName'];
		$_SESSION['sts-strCode']	 	= $arrLogIn['strCode'];
		echo '$("#login").val("Redirecting..");';
		echo " location.href='main_index.php';";
	} else {
		echo '$("#login").val("Log In");';
		echo '$("#login").attr("disabled",false);';
		echo "dialogmsg('Invalid username/password','dialogAlert','dialogMsg',150);";
	}
	exit();
	break;
}



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>STS Online</title>
<link type="text/css" href="includes/css/menu.css" rel="stylesheet" />
<link type="text/css" href="includes/jquery/css/redmond/jquery-ui-1.8.16.custom.css" rel="stylesheet" />
<link type="text/css" href="includes/jquery/development-bundle/demos/demos.css" rel="stylesheet" />

<script type="text/javascript" src="includes/jquery/js/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="includes/jquery/js/jquery-ui-1.8.16.custom.min.js"></script>
<style type="text/css">
		body {
	font-size: 62.5%;
	background-color: #AADDFB;
}
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
<script type="text/javascript" src="includes/main_index.js"></script>
<script type="text/javascript">
		/*needToConfirm = false;
		window.onbeforeunload = askConfirm;
		
		function askConfirm(){
			var ans;
			if (needToConfirm){
				return true;
			}	
		}*/
	$(function() {
		// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
		$('#login')
			.button()
			.click(function() {
				userLogin();
			});				
	});
	
	
	
	function validateString(ObjName,errDiv){
		errDiv = $("#"+errDiv);
		//if it's NOT valid
		if(ObjName.val().length == 0){
			ObjName.addClass("error");
			errDiv.text("Required input!");
			errDiv.addClass("error");
			return false;
		}
		else{
			errDiv.text("");
			ObjName.removeClass("error");
			return true;
		}
	}
	
	function validateFixedString(ObjName,errvalue,errDiv){
		errDiv = $("#"+errDiv);
		//if it's NOT valid
		if(ObjName.val()== errvalue){
			ObjName.addClass("error");
			errDiv.text("Required input!");
			errDiv.addClass("ui-state-error");
			return false;
		}
		//if it's valid
		else{
			errDiv.text("");
			ObjName.removeClass("ui-state-error");
			return true;
		}
	}	
	
	function resetInputs(){
		$('#username').val("");
		$('#password').val=("");
	}	
	
	
	function userLogin() {
		var username = $("#username");
		var password = $("#password");
		username = validateString(username,'errUsername');
		password = validateString(password,'errpword');
		
		if (username && password) {
			$.ajax({
				url: "index.php?action=login",
				type: "GET",
				data: $("#LoginForm").serialize(),
				beforeSend: function() {
					$("#login").val('Validating..');
					$("#login").attr('disabled',true);
					
				},
				success: function(msg){
					eval(msg);
				}				
			});
		}		
	}
	
</script>
<style type="text/css">
<!--
.dvTop {
	height: 100px;
	background-image: url(images/bg_top.jpg);
	background-repeat:no-repeat;
	background-color: #F3EFEE;
}
.dvStatus {
	height: 25px;
}
.dvLayer {
	height: 25px;
	background-image:url(images/status_line.jpg);
	background-repeat:repeat-x;
}
.dvMenu {
	height: 700px;
	width: 200px;
	float:left;
	border:1px solid #D6D4D4;
}
.dvBody {
	height:700px;
	

}
.dvFooter {
	height: 60px;
	width: 1000px;
}
#error{
	margin-bottom: 20px;
	border: 1px solid #efefef;
}
#error ul{
	list-style: square;
	padding: 5px;
	font-size: 11px;
}
#error ul li{
	list-style-position: inside;
	line-height: 1.6em;
}
#error ul li strong{
	color: #e46c6d;
}
#error.valid ul li strong{
	color: #93d72e;
}


#dialog-login {
	height: 320px;
	background:#CCCCCC;
	color: #E6E6E6;
 
}
.style13 {color: #000000; text-align:left; font-family: Verdana; font-size: 11px; font-weight:bold;}
#LoginForm{
	padding: 0 10px 10px;
}
#LoginForm label{
	display: block;
	color: #797979;
	font-weight: 700;
	line-height: 1.4em;
}
#LoginForm input{
	width: 220px;
	padding: 6px;
	color: #000000;
	font-family: Arial,  Verdana, Helvetica, sans-serif;
	font-size: 11px;
	border: 1px solid #cecece;
}
#LoginForm select{
	width: 235px;
	height: 28px;
	padding: 4px;
	color: #000000;
	font-family: Arial,  Verdana, Helvetica, sans-serif;
	font-size: 11px;
	border: 1px solid #cecece;
}
#logintable {
	font-family: Arial, Helvetica, sans-serif;
	background-color: #fefefe;
	border: 1px solid #CCC;
	color: #333;
	box-shadow:0px 0px 8px #cccccc;
	-moz-box-shadow:0px 0px 8px #cccccc;
	-webkit-box-shadow:0px 0px 8px #cccccc;
	-webkit-border-radius: 10px;
	-moz-border-radius: 10px;
	border-radius: 10px;
	padding:10px;
}

#LoginForm input.error{
	background: #000000;
	border-color: #e77776;
}
#LoginForm select.error{
	background: #000000;
	border-color: #e77776;
}
.pgTitle {
	font-family: "Berlin Sans FB Demi";
	color:#003300;
	font-size:34px;
}
.pgSubTitle {
	font-family: "Trebuchet MS";
	color:#000000;
	font-size:28px;
}
.errSpan {
	font-family: Verdana;
	font-size: 11px;
	color: #FF8080;
}
.txtcolor {
	color:#000000;
}
body,td,th {
	font-family: "Trebuchet MS", Helvetica, Arial, Verdana, sans-serif;
}
.bg {
	background: #56789a;
	
}
-->
</style>
</head>

<body class="bg" leftmargin="0" topmargin="0">
<div id="indicator1" align="center">
        <br />
        <br />
        <br />
        <br />
        <br />
        <br />
        <br />
        <br />
        <br />

<form name="LoginForm" id="LoginForm" >
        <table width="480" border="0" cellspacing="0" class="ui-corner-all ui-dialog-content" style="background:#FFFFFF; padding:20px;" cellpadding="0" id = "logintable">
          <tr>
            <td height="30" colspan="2" align="center"><span class="pgTitle">PUREGOLD </span><span class="pgSubTitle">STS Online</span></td>
            </tr>		  
		  
		  <tr>
            <td width="330" height="10"></td>
            <td width="150">&nbsp;</td>
          </tr>
          
          <tr>
            <td height="25"><span class="style13 ">User Name </span></td>
            <td width="150" rowspan="4"><img align="absmiddle" src="images/Unlock-icon.png" width="100"/></td>
          </tr>
          <tr>
            <td height="25"><input type="text" name="username" class="style13" id="username" /></td>
            </tr>
          <tr>
            <td height="25"><span class="style13">Password </span> <span id="errpword" class="errSpan" name="errpword"></span>
              <span id="errpword" name="errpword"></td>
            </tr>
          <tr>
            <td height="25"><input type="password" name="password" class="style13" onkeydown="if(event.keyCode==13) userLogin();" id="password" value=""  /></td>
            </tr>
          <tr>
            <td height="25">&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td height="25"><input type="button" class="ui-button ui-corner-all ui-state-hover" name="login" style="width:100px;" id="login" value="Log In" />
				<input type="button" class="ui-button ui-corner-all ui-state-hover" name="clear" style="width:100px;" id="clear" value="Clear" onclick= "resetInputs();"/>
			</td>
      			
			<td>&nbsp;</td>
          </tr>
        </table>
  </form>
</div>  




<div  id='dialogAlert' title='Supplier Transaction System'>
	<p id='dialogMsg'></p>
</div>






</body>
</html>
