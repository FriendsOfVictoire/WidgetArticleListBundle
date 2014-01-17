(function($jq) {
  $jq.fn.extend({
    hasScrollBar: function() {
      return $jq(this).get(0).scrollHeight > $jq(this).height();
    },
    lionbars: function(options) {
      return $jq(this).each(function(){
        if (!$jq(this).hasClass('noLionBars')) {
          return new LionBar(this, options);
        }
      })
    }
  });

  LionScroll = function (lionBar, symbol) {
    this.lionBar = lionBar;
    this.symbol = symbol || "v";
    this.barKlass = 'lb-'+this.symbol+'-scrollbar';
    this.sliderKlass = 'lb-'+this.symbol+'-scrollbar-slider';
    this.insertOrUpdate();
  }
  LionScroll.prototype.insertOrUpdate = function() {
    var self = this, scrollReady = self.exist(),
      scrollable = self.checkScroll();
    if (!scrollReady && scrollable) {
      $jq(self.lionBar.obj).prepend('<div class="'+self.barKlass+'"><div class="'+self.sliderKlass+'"></div></div>')
      self.setSliderSize();
      self.eventHandle();
    } else if (scrollReady && scrollable) {
      self.setSliderSize();
    } else if (scrollReady && !scrollable) {
      self.destroy();
      return;
    }
  }
  LionScroll.prototype.destroy = function() {
    var self = this, el = $jq(this.lionBar.obj);
    if (self.exist()) {
      el.find("."+self.sliderKlass).unbind("mousedown");
      el.find("."+self.barKlass).unbind("mousedown").remove();
    }
  }
  LionScroll.prototype.exist = function() {
    if (!$jq(this.lionBar.obj).find("."+this.barKlass).length == 0) { return true; }
  }
  LionScroll.prototype.checkVScroll = function() {
    var target = $jq(this.lionBar.obj).find(".lb-wrap");
    return target.get(0).scrollHeight > target.get(0).clientHeight ? true : false;
  }
  LionScroll.prototype.checkHScroll = function() {
    var target = $jq(this.lionBar.obj).find(".lb-wrap");
    return target.get(0).scrollWidth > target.get(0).clientWidth ? true : false;
  }
  LionScroll.prototype.checkScroll = function() {
    var fun = "check" + this.symbol.toUpperCase() + "Scroll";
    return LionScroll.prototype[fun].call(this);
  }
  LionScroll.prototype.setSliderSize = function() {
    var self = this, prop = self.symbol == "v" ? "height" : "width",
      versa = self.symbol == "v" ? "h" : "v",
      borderProp = $jq.camelCase("border-"+self.symbol),
      offsetProp = $jq.camelCase("offset-"+prop),
      scrollProp = $jq.camelCase("scroll-"+prop),
      el = $jq(this.lionBar.obj), self = this;
    self.barSize = $jq(el).find('.lb-'+versa+'-scrollbar').length != 0 || self["check"+versa.toUpperCase()+"Scroll"].apply(self) ? $jq.fn[prop].apply(el) - 12 : $jq.fn[prop].apply(el) - 4;
    el.find("."+self.barKlass).css(prop, self.barSize);
    var sizeMin = 20,
      sizeMax = $jq.fn[prop].apply(el.find("."+self.barKlass)) - sizeMin;
    self.sliderSize = Math.round(el.find(".lb-wrap")[0][offsetProp] / el.find(".lb-wrap")[0][scrollProp] * sizeMax );
    self.sliderSize = ( self.sliderSize < sizeMin ) ? sizeMin : self.sliderSize;
    el.find("."+self.sliderKlass).css(prop, self.sliderSize);
    self.ratio = (el.find(".lb-wrap")[0].scrollHeight - self.lionBar[offsetProp] - self.lionBar[borderProp])/(self.barSize - self.sliderSize)
  }
  LionScroll.prototype.eventHandle = function() {
    var self = this, prop = self.symbol == "v" ? "top" : "left",
      scrollProp = $jq.camelCase("scroll-"+prop),
      el = $jq(this.lionBar.obj), self = this;
    el.find("."+self.sliderKlass).mousedown(function(e) {
      self.eventY = e.pageY;
      self.dragging = true;
      self.initPos = $jq(this).position()[prop];
      return false;
    });
    el.find("."+self.barKlass).mousedown(function(e) {
      if (!$jq(e.target).hasClass(self.sliderKlass)) {
        el.find('.lb-wrap').scrollTop((e.pageY - $jq(this).offset()[prop] - $jq(this).find("."+self.sliderKlass).height()/2) * Math.abs(self.ratio));
      }
      return false;
    });
  }

  LionBar = function(obj, options) {
    this.obj = obj;
    this.options = options != null ? options : {};
    $jq.extend(options);
    this.setDimensions();
    this.scrollBars = [new LionScroll(this, "v"), new LionScroll(this, "h")]
    this.eventHandle();
  }

  LionBar.prototype.setDimensions = function() {
    var el = $jq(this.obj), self = this, container = el, init = false;
    if (el.find(".lb-wrap").length == 0) {
      el.wrapInner('<div class="lb-wrap"><div class="lb-content"></div></div>');
      init = true;
    } else {
      container = el.find(".lb-wrap");
    }

    self.paddingTop = parseInt(container.css('padding-top'));
    self.paddingLeft = parseInt(container.css('padding-left'));
    self.paddingBottom = parseInt(container.css('padding-bottom'));
    self.paddingRight = parseInt(container.css('padding-right'));

    self.borderV = parseInt(container.css('border-top-width'))+parseInt(container.css('border-bottom-width'));
    self.borderH = parseInt(container.css('border-right-width'))+parseInt(container.css('border-left-width'));

    var elDom = container.get(0);
    self.scrollHeight = elDom.scrollHeight;
    self.scrollWidth = elDom.scrollWidth;
    self.clientHeight = elDom.clientHeight;
    self.clientWidth = elDom.clientWidth;
    self.offsetHeight = elDom.offsetHeight;
    self.offsetWidth = elDom.offsetWidth;

    if (init) {
      el.css({
        "overflow" : "hidden",
        "padding" : 0,
        "width" : el.width() + self.paddingLeft + self.paddingRight,
        "height" : el.height() + self.paddingTop + self.paddingBottom,
        "position" : el.css("position") == "static" ? "relative" : el.css("position")
      });
      el.find(".lb-wrap").css({
        "padding-top" : self.paddingTop + "px",
        "padding-left" : self.paddingLeft + "px",
        "padding-right" : self.paddingRight + "px",
        "padding-bottom" : self.paddingBottom + "px"
      });
    }
    el.find(".lb-wrap").css({
      "width" : el.width() - self.paddingLeft - self.paddingRight + self.offsetWidth - self.clientWidth - self.borderH,
      "height" : el.height() - self.paddingTop - self.paddingBottom + self.offsetHeight - self.clientHeight - self.borderV
    });
  }

  LionBar.prototype.eventHandle = function() {
    var el = $jq(this.obj), self = this;
    el.find('.lb-wrap').scroll(function(e) {
      var obj = $jq(this);
      $jq.each(self.scrollBars, function(){
        var prop = this.symbol == "v" ? "top" : "left",
          scrollProp = $jq.camelCase("scroll-"+prop);
        el.find("."+this.sliderKlass).css(prop, $jq.fn[scrollProp].apply($jq(obj))/this.ratio + "px");
      });
      if (self.autohide) {
        el.find('.lb-v-scrollbar, .lb-h-scrollbar').fadeIn(150);
        clearTimeout(this.autohide_timeout);
        this.autohide_timeout = setTimeout(function() {
          el.find('.lb-v-scrollbar, .lb-h-scrollbar').fadeOut(150);
        }, 2000);
      }
    });

    el.mousemove(function(e) {
      $jq.each(self.scrollBars, function(){
        if (this.dragging) {
          var prop = this.symbol == "v" ? "top" : "left",
            scrollProp = $jq.camelCase("scroll-"+prop);
          el.find(".lb-wrap")[scrollProp]((this.initPos + e.pageY - this.eventY) * Math.abs(this.ratio));
        }
      });
    });

    el.bind("updateScroll", function(){
      self.setDimensions();
      $jq.each(self.scrollBars, function(){
        this.insertOrUpdate();
      })
    })

    $jq(document).mouseup(function(e) {
      $jq.each(self.scrollBars, function(){
        if (this.dragging) this.dragging = false;
      });
    });

    if (self.autohide) {
      el.find('.lb-v-scrollbar, .lb-h-scrollbar').hide();
      el.hover(function() {
        el.find('.lb-v-scrollbar, .lb-h-scrollbar').fadeIn(150);
      }, function() {
        el.find('.lb-v-scrollbar, .lb-h-scrollbar').fadeOut(150);
      });
    }
  }
})(jQuery);
