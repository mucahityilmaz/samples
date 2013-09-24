	jQuery.fn.center_vertical = function(){
		var inner = this.attr("id").substring(6);
		//this.css("position","absolute");
		this.find("#popInner_"+inner).css("top", (($(window).height() - this.find("#popInner_"+inner).outerHeight()) / 2) + $(window).scrollTop() + "px");
		//this.css("left", (($(window).width() - this.outerWidth()) / 2) + $(window).scrollLeft() + "px");
		return this;
	}
	jQuery.fn.centerit = function(){
		var inner = this.attr("id").substring(6);
		this.find(".popup_foto").css("max-height", (($(window).height() - 60)) + "px");
		this.find(".popup_foto").css("max-width", (this.find(".popup_ara").innerWidth() - 60) + "px");
		this.find("#popInner_"+inner).css("top", (($(window).height() - this.find("#popInner_"+inner).outerHeight()) / 2) + $(window).scrollTop() + "px");
		this.find("#popInner_"+inner).css("left", ((this.find(".popup_ara").innerWidth() - this.find("#popInner_"+inner).outerWidth()) / 2) + $(window).scrollLeft() + "px");
		return this;
	}
