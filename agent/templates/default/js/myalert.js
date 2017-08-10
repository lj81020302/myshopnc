function showAlerts(classname, contents){
	$(".my-alert>div").removeClass();
	$(".my-alert>div").addClass('alert');
	$("#tips_info").html(contents);
	
	var addclass = '';
	if(classname == 'warning'){
		addclass = '';
	}else if(classname == 'success'){
		addclass = 'alert-success';
	}else if(classname == 'info'){
		addclass = 'alert-info';
	}else if(classname == 'errors'){
		addclass = 'alert-error';
	}
	$(".my-alert>div").addClass(addclass);
	$(".my-alert>div").fadeIn();
	setTimeout(function(){
		$(".my-alert>div").fadeOut("slow");
	},3000);
}