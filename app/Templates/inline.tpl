{% if alertMessage is not empty %}
$('.notifications').notify({

	{% if alertType is not empty %}
	type: '{{ alertType }}',
	{% else %}
	type: 'bangTidy',
	{% endif %}

	message: {html: "{{ alertMessage|raw }}" },

	{% if alertTimeout is not empty %}
	fadeOut: { enabled: true, delay: {{ alertTimeout }} },
	{% endif %}

}).show();
{% endif %}

{% if parseCode == true %}
var codeParseable = document.getElementsByClassName('codeParseable');

for (i=0;i<codeParseable.length;i++) {
	var codeParseableElem = codeParseable[i];
	var editor = CodeMirror.fromTextArea(codeParseableElem, {
		lineNumbers: false
	});
	editor.setOption('theme', 'monokai');
}
{% endif %}

{% if getData.harlem is not empty %}
setTimeout(function(){
	harlemShake();
},100);
{% endif %}

{% if allowEditor %}
// Saver
var saveArticle = function(data,callback) {
	$.ajax({
		type:"POST",
		url:"/provider/article",
		data:data,
		success: callback(data)
	})
}

// Prepend editable element
$('[data-provide="input-editable-article"]').prepend('<a href="#!" class="btn-input-article btn btn-mini btn-primary pull-right">Edit</a>')
$('[data-provide="markdown-editable-article"]').prev().prepend('<br/><a href="#!" class="btn-markdown-article btn btn-mini btn-primary pull-right">Edit</a><br/>')
// Editable trigger
$('.btn-input-article').click(function(){
	var btnInput = $(this),
		target = $(document).find('[data-provide="input-editable-article"]').find('a').last(),
		nodeId = $(document).find('[data-provide="input-editable-article"]').attr('data-node'),
		replaceableInput,
		nodeTitle = target.html(),
		postData

	target.replaceWith('<input type="text" class="span5 replaceable-input" value="'+target.html()+'"/>')

	$('.replaceable-input').focus()
	$('.replaceable-input').on('keypress',function(e){
		var blocked = false
	      switch(e.keyCode) {
	        case 40: // down arrow
	        case 38: // up arrow
	        case 16: // shift
	        case 17: // ctrl
	        case 18: // alt
	          break

	        case 9: // tab
	          blocked = true
	          break

	        case 13: // enter
	          replaceableInput = $(this)
	          nodeTitle = $(this).val()
			  postData = {id:nodeId,title:nodeTitle}

			  replaceableInput.attr('disabled')

			  saveArticle(postData,function(data){
					replaceableInput.blur()
			  })
			  
	          blocked = false
	          break;

	        case 27: // escape
	          blocked = true
	          break

	        default:
	          blocked = false
	      }

	      if (blocked) {
	        e.stopPropagation()
	        e.preventDefault()
	      }
	})
	$('.replaceable-input').blur(function(){
		$(this).replaceWith('<a href="/community/article/'+nodeId+'">'+nodeTitle+'</a>')
		btnInput.removeAttr('disabled')
	})

	$(this).attr('disabled','disabled')
	return false
})
$('.btn-markdown-article').click(function(){
	var btnMarkdown = $(this),
		target = $(document).find('[data-provide="markdown-editable-article"]'),
		nodeId = target.attr('data-node'),
		nodeContent,
		postData

	// Get the original node content
	
	target.markdown({
		hideable:true,
		savable:true,
		height:'400',
		onSave: function(e) {
			nodeContent = e.getContent()
			postData = {id:nodeId,content:nodeContent}
			saveArticle(postData,function(data){
				e.blur()
			})
		},
		onBlur: function(e) {
			btnMarkdown.removeAttr('disabled')
		}
	})

	$(this).attr('disabled','disabled')
	return false
})
{% endif %}