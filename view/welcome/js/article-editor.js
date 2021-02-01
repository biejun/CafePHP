(function(c,r){

  var koEditor = document.getElementById('ko-editor');

  if(!koEditor) return;

  ko.bindingHandlers.enterkey = {
    init: function (element, valueAccessor, allBindings, viewModel) {
        var callback = valueAccessor();
        $(element).keypress(function (event) {
            var keyCode = (event.which ? event.which : event.keyCode);
            if (keyCode === 13) {
              callback.call(viewModel, event);
              return false;
            }
            return true;
        });
    }
  };

  ko.bindingHandlers.widgetPopover = {
    init: function (element, valueAccessor, allBindings, viewModel) {
        var binding = ko.unwrap(valueAccessor());
        $(element).popup({
          popup : $(binding.popup),
          on    : 'click',
          onShow: function() {
            $(element).addClass('widget-has-popover');
          },
          onHidden: function() {
            binding.hide(false);
          }
        });
    }
  };

  ko.bindingHandlers.editor = {
    init: function (element, valueAccessor, allBindings, viewModel, bindingContext) {
        var binding = ko.unwrap(valueAccessor());
        var value = ko.unwrap(binding.value());
        $(element).html(value);
        $(element).keyup(function (event) {
          var keyCode = (event.which ? event.which : event.keyCode);
          var text = $(this).html();
          if (keyCode === 8) {
            var index = bindingContext.$index();
            if(text === '') binding.deleteKey.call(viewModel, event, index);
            return false;
          }else{
            binding.value(text);
          }
          return true;
        });
    }
  };

  var Row = function(type, content, style) {
    var self = this;
    // type : text | todolist
    this.type = ko.observable(type);
    this.content = ko.observable(content);
    this.isFocus = ko.observable(false);
    this.displayWidget = ko.observable(false);
    this.style = style || null;
    this.showWidget = function(data) {
      if(!self.isFocus() || !self.displayWidget()){
        self.displayWidget(true);
        $('#ko-editor .tooltip').popup();
      }
    }
    this.hideWidget = function(data, event) {
      var $target = $(event.currentTarget);
      if($target.find('.widget-has-popover').length === 0 && self.displayWidget()) {
        $('#ko-editor .tooltip').popup('destroy');
        self.displayWidget(false);
      }
    }
  }

  var Editor = function(){

    var self = this;
    this.currentPath = r.path;
    this.title = ko.observable('标题');
    this.articleContent = ko.observableArray([
      new Row('text', '这是一段演示内容,<b>这是加粗文字演示</b>')
    ])
    this.addRow = function(e) {
      var $target = $(e.target);
      var $parent = $target.parent();
      if(!$parent) return;
      var selection = getSelection();
      console.log(selection)
      // if($target.data('root')) {
      //   self.articleContent.unshift(new Row('text', ''));
      //   $parent.next().find('.editor-block:first-child').find('div').last().focus();
      // }else{
      //   self.articleContent.push(new Row('text', ''));
      //   $parent.next().find('div').last().focus();
      // }
    }
    this.deleteRow = function(e, index) {
      if(!index) return;
      var $prev = $(e.target).parent().prev().find('div').last();
      $prev.focus();

      var selection = getSelection();
      // 判断选定对象范围是编辑框还是文本节点
      if (selection.anchorNode.nodeName === '#text') {
          // 如果是文本节点则先获取光标对象
          var range = selection.getRangeAt(0);
          var textNode = range.startContainer;
          range.setStart(textNode, textNode.length);
          // 光标开始和光标结束重叠
          range.collapse(true)
          // 清除选定对象的所有光标对象
          selection.removeAllRanges()
          // 插入新的光标对象
          selection.addRange(range)
      }
      self.articleContent.splice(index, 1);
    }
  }

  ko.applyBindings(new Editor(),koEditor);

})(_CONFIG_,new UrlRequest);