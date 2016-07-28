$(document).ready(function(){
	$("#quitLog").click(function(){
		$(".log").fadeOut("fast");
	});
	$("form").on("submit",function(){
		$.ajax({
			method:$(this).prop("method"),
			url:$(this).prop("action"),
			datatype: "json",
			data: $(this).serialize(),
			success: function(data){
				//Caso o retorno autentique o login
				if(data === "loginAuth"){
					window.location.href = "email.php";
				}else{
					//Caso haja algum outro retorno que não exija redirect
					$(".log").fadeIn("fast");
					var obj_log = JSON.parse(data);
					$("#logIndex").html(obj_log.log);
					$(".log").prop("class", "log "+obj_log.logStatus);
				}
			}
		});
		event.preventDefault(); // == return false;
	});
});