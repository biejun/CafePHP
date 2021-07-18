// 统计字数
$.fn.inputCount = function(wrap, max){
    var $wrap = $(wrap);
    var showCount = function(count) {
        $wrap.html('<span>'+count+'</span>/'+max);
    }
	$(this).on('keyup change',function(){
        var value = $.trim($(this).val());
		var count = value.length;
		if(count > max){
            value = value.slice(0, max);
            count = max;
            $(this).val(value);
        }
        showCount(count);
        return this;
	});
    showCount(0);
};
(function(a) {
    
    function initEditor(type) {
        var toolbar = ['emoji', 'image'];
        if( !isTrends ) {
            toolbar = toolbar.concat([
                '|'
                ,'title'
                ,'bold'
                ,'fontScale'
                ,'color'
                ,'italic'
                ,'underline'
                ,'strikethrough'
                ,'|'
                ,'alignment'
                ,'indent'
                ,'outdent'
                ,'ol'
                ,'ul'
                ,'blockquote'
                ,'code'
                ,'table'
                , '|'
                ,'link'
                ,'hr'
                ,'checklist'
            ]);
        }
        
        var settings = {
            textarea: $('#editor'),
            placeholder: "说点什么",
            toolbar: toolbar,
            toolbarFloat: true,
            emoji: {
                imagePath: conf.emojiPath
            },
            imageButton: 'upload',
            toolbarScrollContainer: 'body',
            mention:{
                path: conf.path,
                url: conf.path + 'user/at-search'
            },
            upload: {
                url : conf.path + 'post/upload/image'
            }
        }
        
        return new Simditor(settings);
    }
    
    // 收集表单数据
    function collection() {
        var form = {
            post_title: !isTrends ? $.trim($('#write-title').val()) : '',
            post_content: editor.getValue(),
            post_type: type,
            post_privacy: Number($('input[name="post_privacy"]:checked').val()),
            post_lock: Number($('input[name="post_lock"]:checked').val()),
            post_pass: $.trim($('input[name="post_pass"]').val()),
            post_topics: $.trim($('input[name="post_topics"]').val())
        }
        if(conf.post_id) {
            form.post_id = conf.post_id;
        }
        return form;
    }
    // 验证表单数据
    function validation() {
        var form = collection();
        if(!isTrends && form.post_title === '') {
            alert('标题不能空！');
            return false;
        }
        if(form.post_content === '') {
            alert('内容不能空！');
            return false;
        }
        if(form.post_privacy === 3 && form.post_pass === '') {
            alert('请设置密码！');
            return false;
        }
        return true;
    }
    
    function extraAt(content) {
        var uid = [], matchStr;
        var re = /data-uid="([^"]*)"/g;
        while( (matchStr = re.exec(content)) != null) {
            uid.push(matchStr[1]);
        }
        return uid.join(',');
    }
    
    function extraIMG(content) {
        var images = [], matchStr, imagePath;
        var re = /src="([^"]*)"/g;
        while( (matchStr = re.exec(content)) != null) {
            imagePath = matchStr[1];
            if(imagePath.match(/post\/_tmp/)) {
                images.push(imagePath);
            }
        }
        return images.join(',');
    }
    
    function bindSubmitFormEvent() {
        var $submitBtn = $('#submit-post');
        var defaultText = $submitBtn.text();
        
        $submitBtn.click(function () {
            
            var validate = validation();
            if(!validate) return;
            var form = collection();
            if(conf.post_id) {
                form.post_at_old_ids = extraAt(conf.post_content);
            }
            form.post_at_ids = extraAt(form.post_content);
            form.post_images = extraIMG(form.post_content);
            var postUrl = conf.path + 'post/' +(form.post_id ? 'update' : 'add');
            $submitBtn.prop("disabled",true).addClass('loading');
            a.http(postUrl).data(form).post(function(res) {
                $submitBtn
                 .prop("disabled",false)
                 .removeClass('loading')
                 .text(defaultText);
                 
                 // 删除草稿
                 removeDraft();
                 
            }, function() {
                $submitBtn
                 .prop("disabled",false)
                 .removeClass('loading')
                 .text(defaultText);
            });
        });
    }
        
    var conf = window.__write_conf__;
    var type = conf.type,
        csrf = conf.csrf;
    
    // 类型是否为动态
    var isTrends = type === 1;
    var CACHE_KEY = conf.post_id ? 'POST-' + conf.post_id : 'NEW_POST';
    
    // 初始化编辑器
    var editor = initEditor();
    
    var getDraft = function() {
        var storage = window.localStorage;
        if(storage.getItem(CACHE_KEY)) {
            try{
              var form = JSON.parse(storage.getItem(CACHE_KEY));
              setFormContent(form);
              $('#draft-msg').text('已自动读取草稿');  
            }catch(e) {
              removeDraft();
            }
        }
    }
    
    var setFormContent = function(form) {
        if(form.post_title) {
            $('#write-title').val(form.post_title);
        }
        if(form.post_content) {
            editor.setValue(form.post_content);
        }
        if(form.post_privacy) {
            $('input[name="post_privacy"]')
                .eq(form.post_privacy)
                .prop("checked", true)
        }
        if(form.post_lock) {
            $('input[name="post_lock"]')
                .eq(form.post_lock)
                .prop("checked", true)
        }
        if(form.post_topics) {
            $('input[name="post_topics"]')
                .val(form.post_topics)
        }
    }
    
    var setDraft = function() {
        var storage = window.localStorage;
        var date = new Date();
        var zero = function(val) {
            return val < 10 ? '0' + val : val;
        }
        var form = collection();
        var time = zero(date.getHours()) + ':' + zero(date.getMinutes());
        storage.setItem(CACHE_KEY, JSON.stringify(form));
        $('#draft-msg').text( time +' 已自动保存草稿');
    }
    
    var removeDraft = function() {
        var storage = window.localStorage;
        if(storage.getItem(CACHE_KEY)) {
            storage.removeItem(CACHE_KEY);
        }
    }
    
    if( !isTrends ) {
        // type 不为1时存在标题，为标题增加字数限制
        $('#write-title').inputCount('.write-titleLimit', 50);
    }
    
    $('.ui.radio.checkbox').checkbox();
    
    $('input[name="post_privacy"]').change(function() {
        var value = Number($(this).val());
        $('input[name="post_pass"]')
         .val('')
         .closest('.inline.field')
         [value === 3 ? 'show' : 'hide']();
    });
    
    if(conf.post_id) {
        setFormContent(conf.post);
    }
    
    // 获取草稿
    getDraft();
    
    setInterval(setDraft, 1000 * 60);
    
    editor.on('valuechanged', setDraft);
    
    // 提交表单数据
    bindSubmitFormEvent();
    
    $('.remote.filter.topic .ui.dropdown')
      .dropdown({
        //minCharacters : 2,
        apiSettings: {
          url: conf.path + 'post/topic?q={query}',
          onResponse: function(response){
              response.results = response.data;
              return response;
          }
        },
      })
    ;
    
})(new Ajax);