	$(function() {
		var icons = {
			header: "ui-icon-circle-arrow-e",
			headerSelected: "ui-icon-circle-arrow-s"
		};

		$("#menu").accordion({
			icons: icons,
			autoHeight: true,
			navigation: true,
		});
		$("#menu").accordion("option", "icons", false);
		$("#logOut").click(function() {
				LogOut();
			});
		function LogOut() {
			$("#dialog").dialog("destroy");
			$("#dialogMsg").html('Are you sure you want to sign out?');
				$("#dialogAlert").dialog({
				height: 140,
				modal: true,
				closeOnEscape: false,
				buttons: {
					
					'Yes': function() {
							$.ajax({
								url: "main_index.php",
								type: "GET",
								data: "action=logout",
								success: function(msg){
										eval(msg);
									}				
							   });
							
						},
					'No': function() {
							$(this).dialog('close');
						}
					}			
			});		
		}
	});
	
	
		function dialogmsg(msg,dialogname,msgContainer,ht) {
			$("#"+dialogname).dialog("destroy");
			$("#"+msgContainer).html(msg)
				$("#"+dialogname).dialog({
				height: ht,
				modal: true,
				closeOnEscape: false,
				buttons: {
					'Ok': function() {
						$("#"+dialogname).dialog('close');
							}
						}
					});
		}
	
	
	function menu(url,label)	{
		$("#bodyFrame").attr('src',url);
		$("#ModName").html(label);
	}
	
	function startTime(){
		var today=new Date();
		var h=today.getHours();
		var m=today.getMinutes();
		var s=today.getSeconds();
		// add a zero in front of numbers<10
		m=checkTime(m);
		s=checkTime(s);
		document.getElementById('currTime').innerHTML=h+":"+m+":"+s;
		t=setTimeout('startTime()',500);
	}
	
	
	function checkTime(i){
		if (i<10){
		  i="0" + i;
		}
		return i;
	}	
	
	function TogglePrint(obj,action,status,hdValue) {
		if (status=='off') {
			$('#'+obj).attr('onClick','');
			$('#hdCode').val('');
			$("#"+obj).removeClass("link");
			$("#"+obj).addClass("disable");
		} else {
			$('#'+obj).attr('onClick',action);
			$('#hdCode').val(hdValue);
			$("#"+obj).removeClass("disable");
			$("#"+obj).addClass("link");
		}
		
	}
		


	
