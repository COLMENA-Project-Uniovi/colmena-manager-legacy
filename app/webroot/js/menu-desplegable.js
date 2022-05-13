
function loadMenu(){
	$(".close-nav").click(function(){
		var boton = $(this);
		var logout = $(".logout");
		var menu = $("nav");
		var position = menu.position().left;
		var contenedor = $("#flow-content");

		if(menu.hasClass("hidden")){
			logout.fadeOut(200, function(){
				menu.removeClass("hidden");
				menu.addClass("no-hidden");
				boton.removeClass("hidden");
				boton.addClass("no-hidden");
			});			
		}else if (menu.hasClass("no-hidden")){
			menu.removeClass("no-hidden");
			menu.addClass("hidden");
			boton.removeClass("no-hidden");
			boton.addClass("hidden");
		
			logout.delay(500).fadeIn(200);
			
		}
	});
}