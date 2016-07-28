//FUNÇÃO para carregar os e-mail recebidos
function loadEmails(op){
	$.ajax({
		method:"POST",
		url:"loadEmails.php",
		data:{operation: op},
		success:function(data){
			$("#emails").html(data);
		}
	});
}
//FUNÇÃO para Arquivar, Excluir ou marcar como SPAM um e-mail
function actionEmail(cod_action){
	var ids = [];
	$("tbody input[type='checkbox']:checked").each(function(){
		ids.push($(this).parent().parent().parent().prop("id"));
	});
 	$.ajax({
		method:"post",
		url:"ActionEmail.php",
		datatype:"json",
		data:{
			idEmail: ids,
			codAction:cod_action
		},
		success:function(data){
			$(".log").fadeIn("fast");
			var obj_log = JSON.parse(data);
			$("#logEmail").html(obj_log.log);
			$(".log").prop("class", "log "+obj_log.logStatus);
		}
	});
}
//FUNÇÃO para escrever um e-mail
function newEmail(id, response){
	//Limpa a janela caso ja haja algum email sendo escrito
	$("#closeNewEmail").trigger("click");
	//Caso seja um email pre-formatado(resposta)/rascunho (Uso do NaN ja que o botão New Email passa um Object por si mesmo)
	if(!(isNaN(id))){
		//Caso não seja DESKTOP
		if(window.innerWidth < 915){
			window.location.href = "newEmail.php?idRascunho="+id;
		}else{
			//Para DESKTOP
			$("editGroup").addClass("invisible");
			//Caso seja para montar uma resposta:
			if(!(isNaN(response))){
				$("#newEmail").load("newEmail.php",{idResposta:id},function(response){
				$(".headerNewEmail").css({
					"background-color":"rgb(210,210,210)",
					"border":"1px solid rgb(210,210,210)",
					"border-radius":"4px"
				});
				$(".voltarNewEmail").css("display","none");
				$("footer .head h4").html("Response Email");
			});
			}else{
				//Caso seja para montar um Rascunho
				$("#newEmail").load("newEmail.php",{idRascunho:id},function(response){
				$(".headerNewEmail").css({
					"background-color":"rgb(210,210,210)",
					"border":"1px solid rgb(210,210,210)",
					"border-radius":"4px"
				});
				$(".voltarNewEmail").css("display","none");
				$("footer .head h4").html("Draft Email");
			});
			}
			$("footer").fadeIn("fast");
		}
	}else{
		//Caso seja um novo e-mail
		//Caso não seja DESKTOP
		if(window.innerWidth < 915){
			window.location.href = "newEmail.php";
		}else{
			//Para DESKTOP
			$("editGroup").addClass("invisible");
			$("#newEmail").load("newEmail.php",function(response){
				$(".headerNewEmail").css({
					"background-color":"rgb(210,210,210)",
					"border":"1px solid rgb(210,210,210)",
					"border-radius":"4px"
				});
				$(".voltarNewEmail").css("display","none");
			});
			$("footer .head h4").html("New Email");
			$("footer").fadeIn("fast");
		}
	}
}


//FUNÇÃO apra abrir um e-mail
function openEmail(idEmail,lido){
	//Caso não seja DESKTOP
	if(window.innerWidth < 915){
		window.location.href = "openEmail.php?id_email="+idEmail+"&lido="+lido;
	}else{
		//Para DESKTOP
		$("editGroup").addClass("invisible");
		$("aside").load("openEmail.php",{id_email:idEmail, lido:lido},function(){
			$(".headerOpenEmail").css({
				"background-color":"rgb(210,210,210)",
				"border":"1px solid rgb(210,210,210)",
				"border-radius":"4px"
			});
			$(".voltar").on('click', function(){
				loadEmails(1);
			});
		});
		$("#btnSearch").fadeOut("fast");
	}
}


$(document).ready(function(){
	loadEmails(1);
	/* ------------------------------------
		EVENTOS DA GUIA DE NAVEGAÇÃO 
	---------------------------------------*/
	//Evento CLICK que exibe a barra de navegação lateral (MOBILE)
	$(".btnMenu").on('click',function(){
		if(window.innerWidth < 915){
			$("nav").toggleClass("activeNav");
		}
	});
	$("#inboxPage").on('click',function(){
		loadEmails(1);
	});
	$("#sentPage").on('click',function(){
		loadEmails(2);
	});
	$("#archivePage").on('click',function(){
		loadEmails(3);
	});
	$("#draftPage").on('click',function(){
		loadEmails(4);
	});
	$("#spamPage").on('click',function(){
		loadEmails(5);
	});
	$("#trashPage").on('click',function(){
		loadEmails(6);
	});
	$("#logoutPage").on('click',function(){
		window.location.href = "index.php?logout=1";
	});
	/* ------------------------------------
		EVENTOS DO CABEÇALHO 
	---------------------------------------*/
	$("#btnArchive").on('click',function(){
		actionEmail(1);
	});
	$("#btnSpam").on('click',function(){
		actionEmail(3);
	});
	$("#btnDelete").on('click',function(){
		actionEmail(4);
	});
	
	
	//Eevnto CLICK para fecahr a barra de LOG
	$(".quitLog").on('click',function(){
		$(".log").fadeOut("fast");
	});
	
	//Evento CLICK para exibir campo de pesquisa
	$("#btnSearch").on('click',function(){
		$(".search").fadeToggle("fast");
	});
	
	//Evento CLICK para abrir e-mails
	$("tbody").on('click','.show', function(){
		openEmail($(this).parent().prop("id"));
	});
	
	//Evento CLICK escrever nos rescunhos
	$("tbody").on('click','.draft', function(){
		newEmail($(this).parent().prop("id"));
	});
	
	//Evento CLICK para ativar ou desativar a janela de novo email
	$(".head").on('click',function(){
		$("footer").toggleClass("activeFooter");
	});
	
	//Evento CLICK para marcar todas as CHECKBOX da tabela
	$("#selectAll").on('click',function(){
		$("tbody input[type='checkbox']").trigger("click");
	});
	
	//Evento CLICK para abrir a janela de novo email
	$("#btnNew").on('click',newEmail);
	
	//Evento CLICK para fechar a janela de novo email
	$("#closeNewEmail").on('click',function(){
		$("footer").fadeOut("fast",function(){
			$("#newEmail").html("");
		});
	});
	
	//Evento CLICK nas CHECKBOX do corpo da tabela
	$("tbody").on('click','input[type="checkbox"]',function(){
		$(this).parent().parent().parent().toggleClass("selected");
		/*Se a checkbox foi marcada então o grupo de botões de edição
			deve ser exibido, mas somente se ele ja não estiver visível*/
		if(this.checked){
			if(!($("editGroup").is(":visible"))){
				$("editGroup").toggleClass("invisible");
			}
		}else{
			/*Se a checkbox foi desmarcada então o grupo de botões de edição
			deve ser escondido, mas somente se não ouver nunhuma outra checkbox marcada*/
			var n = $("tbody input[type='checkbox']:checked").length;
			if(n <= 0){
				$("editGroup").toggleClass("invisible");
			}
		}
	});
});