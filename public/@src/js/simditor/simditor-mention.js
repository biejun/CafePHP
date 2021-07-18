(function (root, factory) {
  if (typeof define === 'function' && define.amd) {
    // AMD. Register as an anonymous module unless amdModuleId is set
    define('simditor-mention', ["jquery","simditor","simple-module"], function (a0,b1,c2) {
      return (root['SimditorMention'] = factory(a0,b1,c2));
    });
  } else if (typeof module === 'object' && module.exports) {
    // Node. Does not work with strict CommonJS, but
    // only CommonJS-like environments that support module.exports,
    // like Node.
    module.exports = factory(require("jquery"),require("simditor"),require("simple-module"));
  } else {
    root['SimditorMention'] = factory(root["jQuery"],root["Simditor"],root["SimpleModule"]);
  }
}(this, function ($, Simditor, SimpleModule) {

var SimditorMention,
  extend = function(child, parent) { for (var key in parent) { if (hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; },
  hasProp = {}.hasOwnProperty;

SimditorMention = (function(superClass) {
  extend(SimditorMention, superClass);

  function SimditorMention() {
    return SimditorMention.__super__.constructor.apply(this, arguments);
  }

  SimditorMention.pluginName = 'Mention';

  SimditorMention.prototype.opts = {
    mention: false
  };

  SimditorMention.prototype.active = false;

  SimditorMention.prototype.keys = {
    BACKSPACE: 8,
    TAB: 9,
    ENTER: 13,
    SHIFT: 16,
    CTRL: 17,
    ALT: 18,
    ESC: 27,
    SPACE: 32,
    LEFT: 37,
    UP: 38,
    RIGHT: 39,
    DOWN: 40
  };

  SimditorMention.prototype._init = function() {
    if (!this.opts.mention) {
      return;
    }
    this.editor = this._module;
    this.opts.mention = $.extend({
      url: ''
    }, this.opts.mention);
    if (this.opts.mention.url === "") {
      throw new Error("Must provide items or source url");
    }
    this.items = [];
    if (this.editor.formatter._allowedAttributes['a']) {
      this.editor.formatter._allowedAttributes['a'].push('data-mention');
      this.editor.formatter._allowedAttributes['a'].push('data-uid');
    } else {
      this.editor.formatter._allowedAttributes['a'] = ['data-mention', 'uid'];
    }
    $('<div class=\'simditor-mention-popover\'></div>').appendTo(this.editor.el)
    this._bind();
  };

  SimditorMention.prototype._bind = function() {
    this.editor.on('decorate', (function(_this) {
      return function(e, $el) {
        return $el.find('a[data-mention]').each(function(i, link) {
          return _this.decorate($(link));
        });
      };
    })(this));
    this.editor.on('undecorate', (function(_this) {
      return function(e, $el) {
        $el.find('a[data-mention]').each(function(i, link) {
          return _this.undecorate($(link));
        });
        return $el.find('simditor-mention').children().unwrap();
      };
    })(this));
    this.editor.on('pushundostate', (function(_this) {
      return function(e) {
        if (_this.editor.body.find('span.simditor-mention').length > 0) {
          return false;
        }
        return e.result;
      };
    })(this));
    this.editor.on('keydown.simditor-mention', $.proxy(this._onKeyDown, this));
    this.editor.on('keyup.simditor-mention', $.proxy(this._onKeyUp, this));
    this.editor.on('blur', (function(_this) {
      return function() {
        if (_this.active) {
          return _this.hide();
        }
      };
    })(this));
    this.editor.body.on('mousedown', 'a.simditor-mention', (function(_this) {
      return function(e) {
        return false;
      };
    })(this));
  };

  SimditorMention.prototype._renderPopover = function() {
    var $itemEl, $itemsEl, item, j, name;
    this.popoverEl = $('.simditor-mention-popover');
    this.popoverEl.html('');
    var len = this.items.length;
    if(len === 0) return;
    $itemsEl = $('<div class="items"></div>');
    for (j = 0; j < len; j++) {
      item = this.items[j];
      $itemEl = $("<a class=\"item\" href=\"javascript:;\">\n <img class=\"user-avatar\" src="+item.avatar+">\n<div class=\"user-nickname\">"+item.nickname+"</div><div class=\"user-name\">"+item.name+"</div>\n</a>");
      $itemEl.appendTo($itemsEl).data('item', item);
    }
    this.popoverEl.html($itemsEl);
    this.popoverEl.on('mouseenter', '.item', function(e) {
      return $(this).addClass('selected').siblings('.item').removeClass('selected');
    });
    this.popoverEl.on('mousedown', '.item', (function(_this) {
      return function(e) {
        _this.selectItem();
        return false;
      };
    })(this));
    $itemsEl.on('mousewheel', function(e, delta) {
      $(this).scrollTop($(this).scrollTop() - 10 * delta);
      return false;
    });
    this.popoverEl.find('.item:first').addClass('selected').siblings('.item').removeClass('selected');
    this.popoverEl.show();
    this.popoverEl.find('.item').show();
    this.popoverEl.find('.items').scrollTop(0);
    this.refresh();
  };

  SimditorMention.prototype._changeFocus = function(type) {
    var itemEl, itemH, parentEl, parentH, position, selectedItem;
    selectedItem = this.popoverEl.find('.item.selected');
    if (selectedItem.length < 1) {
      this.popoverEl.find('.item:first').addClass('selected');
      return false;
    }
    itemEl = selectedItem[type + 'All']('.item:visible').first();
    if (itemEl.length < 1) {
      return false;
    }
    selectedItem.removeClass('selected');
    itemEl.addClass('selected');
    parentEl = itemEl.parent();
    parentH = parentEl.height();
    position = itemEl.position();
    itemH = itemEl.outerHeight();
    if (position.top > parentH - itemH) {
      parentEl.scrollTop(itemH * itemEl.prevAll('.item:visible').length - parentH + itemH);
    }
    if (position.top < 0) {
      return parentEl.scrollTop(itemH * itemEl.prevAll('.item:visible').length);
    }
  };

  SimditorMention.prototype._onKeyDown = function(e) {
    var node;
    if (!this.active) {
      return;
    }
    switch (e.which) {
      case this.keys.UP:
      case this.keys.DOWN:
      case this.keys.TAB:
      case this.keys.ENTER:
        e.preventDefault();
        break;
      case this.keys.BACKSPACE:
        if (this.target.text() === '@' || this.target.text() === '') {
          node = document.createTextNode('@');
          this.target.replaceWith(node);
          this.hide();
          this.editor.selection.setRangeAtEndOf(node);
        } else {
          this.filterItem();
        }
    }
    return e.stopPropagation();
  };

  SimditorMention.prototype._onKeyUp = function(e) {
    var $closestBlock, node, range, selectedItem;
    range = this.editor.selection.range();
    if (!((range != null) && range.collapsed)) {
      return;
    }
    range = range.cloneRange();
    range.setStart(range.startContainer, Math.max(range.startOffset - 1, 0));
    if (range.toString() === '@' && !this.active) {
      $closestBlock = this.editor.selection.blockNodes().last();
      if ($closestBlock.is('pre')) {
        return;
      }
      range = this.editor.selection.range();
      if (range == null) {
        return;
      }
      range = range.cloneRange();
      range.setStart(range.startContainer, Math.max(range.startOffset - 2, 0));
      if (/^[A-Za-z0-9]@/.test(range.toString())) {
        return;
      }
      this.show();
    }
    if (!this.active) {
      return;
    }
    switch (e.which) {
      case this.keys.SHIFT:
      case this.keys.CTRL:
      case this.keys.ALT:
        return e.preventDefault();
      case this.keys.LEFT:
      case this.keys.RIGHT:
      case this.keys.ESC:
        this.editor.selection.save();
        this.hide();
        return this.editor.selection.restore();
      case this.keys.UP:
        return this._changeFocus('prev');
      case this.keys.DOWN:
        return this._changeFocus('next');
      case this.keys.TAB:
      case this.keys.ENTER:
        selectedItem = this.popoverEl.find('.item.selected');
        if (selectedItem.length) {
          return this.selectItem();
        } else {
          node = document.createTextNode(this.target.text());
          this.target.before(node).remove();
          this.hide();
          return this.editor.selection.setRangeAtEndOf(node);
        }
        break;
      default:
        this.filterItem();
    }
  };

  SimditorMention.prototype.getItems = function(name) {
    return $.ajax({
      type: 'get',
      url: this.opts.mention.url + (name ? '?name='+ name : '')
    }).done((function(_this) {
      return function(result) {
        if(result.success) {
           _this.items = result.data;
           _this._renderPopover(); 
        }
      };
    })(this));
  };

  SimditorMention.prototype.show = function($target) {
    var range;
    this.active = true;
    if ($target) {
      this.target = $target;
    } else {
      this.target = $('<span class="simditor-mention" />');
      range = this.editor.selection.range();
      range.setStart(range.startContainer, range.endOffset - 1);
      range.surroundContents(this.target[0]);
    }
    this.editor.selection.setRangeAtEndOf(this.target, range);
  };

  SimditorMention.prototype.refresh = function() {
    var popoverH, targetOffset, top, wrapperOffset;
    wrapperOffset = this.editor.wrapper.offset();
    targetOffset = this.target.offset();
    popoverH = this.popoverEl.height();
    top = targetOffset.top - wrapperOffset.top + this.target.height() + 2;
    if (targetOffset.top - $(document).scrollTop() + popoverH > $(window).height()) {
      top = targetOffset.top - wrapperOffset.top - popoverH;
    }
    return this.popoverEl.css({
      top: top,
      left: targetOffset.left - wrapperOffset.left + this.target.width()
    });
  };

  SimditorMention.prototype.decorate = function($link) {
    return $link.addClass('simditor-mention');
  };

  SimditorMention.prototype.undecorate = function($link) {
    return $link.removeClass('simditor-mention');
  };

  SimditorMention.prototype.hide = function() {
    if (this.target) {
      this.target.contents().first().unwrap();
      this.target = null;
    }
    if(this.popoverEl) this.popoverEl.hide().find('.item').removeClass('selected');
    this.active = false;
    return null;
  };

  SimditorMention.prototype.selectItem = function() {
    var $itemLink, $selectedItem, data, href, range, spaceNode;
    $selectedItem = this.popoverEl.find('.item.selected');
    if (!($selectedItem.length > 0)) {
      return;
    }
    data = $selectedItem.data('item');
    href = this.opts.mention.path + data.name;
    $itemLink = $('<a/>', {
      'class': 'simditor-mention',
      text: '@' + data.nickname + '('+data.name+')',
      'data-uid': data.uid, 
      href: href,
      'data-mention': true
    });
    this.target.replaceWith($itemLink);
    this.editor.trigger("mention", [$itemLink, data]);
    if (this.target.hasClass('edit')) {
      this.editor.selection.setRangeAfter($itemLink);
    } else {
      spaceNode = $('<span>&nbsp;</span>');
      $itemLink.after(spaceNode);
      range = document.createRange();
      this.editor.selection.setRangeAtEndOf(spaceNode, range);
    }
    this.editor.sync();
    return this.hide();
  };

  SimditorMention.prototype.filterItem = function() {
    var $itemEls, e, results, val;
    val = this.target.text().toLowerCase().substr(1).replace(/'/g, '');
    val = val.replace(String.fromCharCode(12288), '');
    val = val.replace(String.fromCharCode(160), '');
    val = val.split(' ').join('');
    
    this.getItems(val);
  };

  return SimditorMention;

})(SimpleModule);

Simditor.connect(SimditorMention);

return SimditorMention;

}));
