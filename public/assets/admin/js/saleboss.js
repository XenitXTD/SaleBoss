$(function(){
	
	$('.dd').nestable();
    $('.dd').nestable('collapseAll');
	$('.dd-item').mousedown(function(){return false});
	$('.dd-handle a').on('mousedown', function(e){
	   e.stopPropagation();
	});


});