<?php
session_start();

include("../../includes/db.inc.php");
include("../../includes/common.php");
include("adminObj.php");
$adminObj = new adminObj();
if ($_SESSION['sts-username'] == "") {
	echo "<SCRIPT>window.parent.location.href='../../index.php';</SCRIPT>";
}

$arrUserPages = $adminObj->getuserPages($_GET["userId"]);

$arrModules = $adminObj->GetModules();
 
?>
		<script type="text/javascript">
			$(function(){
				$('#Modtabs').tabs();
			});
		</script>
<br>		
<div id="Modtabs">
	<ul>
		<?php foreach ($arrModules as $val) {?>
		<li><a href="#<?=$val['moduleName']?>"><?=$val['moduleName']?></a></li>
		<?php }?>
	</ul>
	<?php $x=0;
		 foreach ($arrModules as $val) {
		 	$x++;
	?>
		<div id="<?=$val['moduleName']?>">
			<div class="tabContent ui-corner-all">
			<table>	
			<tr>
				<td colspan="2"><span class="link2"  onclick="checkAll(<?php echo "'hdsub_$x','checkBox$x'";?>)">Check All</span> | <span class="link2"  onclick="unCheckAll(<?php echo "'hdsub_$x','checkBox$x'";?>)">UnCheck All</span> </td>
			</tr>	
			<?php $ctr = 0;
				$arrPages = $adminObj->GetSubModules($val['moduleName']);
				foreach($arrPages as $valPages) {
					$disabled = (in_array($valPages['modueID'], $arrUserPages))	? "checked":"";
			?>
				<tr class="pageLabel">
					<td width="10%"><input type="checkbox" id="checkBox<?php echo $x."_".$ctr;?>" value="<?=$valPages['modueID']?>" <?=$disabled?>></td>
					<td><?=$valPages['label']?></td>
				</tr>
				<?php 
					$ctr++;
				}
			?>
			</table><input type="hidden" id="hdsub_<?php echo $x;?>" value="<?php echo $ctr;?>">
			</div>
		</div>
	<?php }?>
</div> 
<input type="hidden" id="hdmod" value="<?php echo $x;?>">