/* on window ready */
function bdheight() {
	var winheight = $(window).height();
	$(".main-content").css("min-height",winheight - $("header").outerHeight());

	if($(window).width() < 1023) {
		$(".left-content").appendTo(".topmenu")
	}
	else {
		$(".left-content").appendTo(".wrapper > .container")

	}
}

jQuery(document).ready(function() {
	$(".menubtn").click(function() {
		$("body").toggleClass("push-nav");
	});
	bdheight();

});

/* on window load */
jQuery(window).load(function(){

});

/* on window resize */
jQuery(window).resize(function(){
bdheight()
});