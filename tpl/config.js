(function($){
	window.evote_modules_copy = function(btn, pfx)
	{
		var idx = Number($(btn).attr('data-modules')) + 1;
		$(btn).attr('data-modules', idx);
		$(btn).before('<div> <input name="'+pfx+'minutes_'+idx+'" type="number" value="" style="width:50px;" /> 분 동안 모듈(=mid) <input name="'+pfx+'mid_'+idx+'" type="text" value="" style="width:100px;" /> 에 한해 추천 수 합 <input name="'+pfx+'counts_'+idx+'" type="number" value="" style="width:50px;" /> 개 까지 제한 (예: 24시간 동안 5개) / 모듈(=mid) 값이 정규식 <input type="checkbox" name="'+pfx+'regex_'+idx+'" value="Y" id="'+pfx+'regex_'+idx+'" /></div>');
	}
})(jQuery);