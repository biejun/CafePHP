(function(a) {
    
    var conf = window.__comment_conf__;
   
    ko.bindingHandlers.editor = {
    	init: function(element, valueAccessor, allBindings, viewModel) {
            var binding = ko.unwrap(valueAccessor());
            var value = ko.unwrap(binding.value());
            var editor = new Simditor({
                textarea: $(element),
                toolbar: ['emoji'],
                emoji: {
                    imagePath: conf.emojiPath
                },
                toolbarFloat: false,
                mention:{
                    path: conf.path,
                    url: conf.path + 'user/at-search'
                },
            });
            
            if(value !== '') {
                editor.setValue(value);
            }
            
            editor.on('valuechanged', function() {
                binding.value(editor.getValue());
            });
    	}
    };
    
    var CommentEditor = function() {
        
        var submitPath = conf.path + 'post/comment/add';
        var self = this;
        
        this.comment_content = ko.observable('');
        this.post_id = conf.post_id;
        this.submit = function() {
            var content = self.comment_content();
            a.http(submitPath).data({
                post_id: self.post_id,
                comment_content: content
            }).post(function(res) {
                alert('回复成功!')
                window.location.reload();
            });
        }
    }
    
    ko.applyBindings(new CommentEditor(), document.getElementById('comment-form'));
    
    var ReplyEditor = function(commentID, replyID, replyUID) {
        
        var self = this;
        
        this.visible = ko.observable(false);
        this.content = ko.observable('');
        this.commentID = commentID;
        this.replyID   = replyID || 0;
        this.replyUID  = replyUID || 0;
        this.showEditor = function() {
            if(self.visible()) {
                self.visible(false);
                return;
            }
            self.visible(true);
        }
        this.submit = function() {
            var submitPath = conf.path + 'post/comment/reply/add';
            var content = self.content();
            console.log(content)
            a.http(submitPath).data({
                comment_id: self.commentID,
                reply_content: content,
                to_reply_id: self.replyID,
                to_reply_uid: self.replyUID
            }).post(function(res) {
                alert('回复成功!')
                window.location.reload();
            })
        }
        this.statusText = ko.computed(function() {
           return self.visible() ? '取消回复' : '回复';
        });
    }
    
    $('.comment-item').each(function() {
        var $item = $(this);
        var commentID = $item.data('comment-id');
        var koBindID = document.getElementById('comment-ko-'+commentID);
        
        if( koBindID ) {
            ko.applyBindings(new ReplyEditor(commentID), koBindID);
        }
        
        $item.find('.subComment-item').each(function() {
            var $childItem = $(this);
            var replyID = $childItem.data('reply-id');
            var replyUID = $childItem.data('reply-uid');
            
            var koBindID = document.getElementById('reply-ko-'+replyID);
            
            if( koBindID ) {
                ko.applyBindings(new ReplyEditor(commentID, replyID, replyUID), koBindID);
            }
        })
    })
    
})(new Ajax);