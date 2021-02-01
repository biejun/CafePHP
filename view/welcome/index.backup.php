<?php echo $this->tpl('tpl/start');?>
<?php echo $this->tpl('tpl/common');?>

<section class="page-main" role="main">

  <?php echo $this->tpl('header');?>
  <article class="page-article">
    <div class="article-editor-wrapper">
      <div id="ko-editor" class="article-editor">
        <div class="editor-wrapper">
          <div class="editor-header">
            <input type="text" data-bind="value: title, enterkey: addRow" data-root="true" placeholder="添加标题">
          </div>
          <div class="editor-selectable" data-bind="foreach: articleContent">
            <div class="editor-block" data-bind="event: { mouseenter: showWidget, mouseleave: hideWidget}">
              <!-- ko if:isFocus() || displayWidget() -->
              <div class="editor-widget">
                <div class="some-wrapping-div">
                  <div class="tooltip" data-content="点击新建一个" data-variation="mini inverted" data-position="bottom center">
                    <div class="ui mini button" data-variation="basic" data-position="bottom left" data-offset="50" data-bind="widgetPopover: { popup: '.block-add.popup', hide: displayWidget}">+</div>
                  </div>
                  <div class="ui block-add custom popup">
                    Basic Block
                  </div>
                </div>
              </div>
              <!-- /ko -->
              <div class="text text-wrap" 
                contenteditable="true" 
                spellcheck="true" 
                data-bind="enterkey: $root.addRow, 
                  editor: {value: content, deleteKey: $root.deleteRow}, hasFocus: isFocus"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </article>
</section>

<?php echo $this->tpl('tpl/scripts');?>
<?php echo $this->tpl('tpl/scripts-page');?>
<?php echo $this->tpl('tpl/end');?>