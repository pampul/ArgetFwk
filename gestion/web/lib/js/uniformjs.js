(function (f, n) {
  function h(a, b) {
    for (var c in b)b.hasOwnProperty(c) && (c.replace(/ |$/g, ".uniform"), a.bind(c, b[c]))
  }

  function k(a, b, c) {
    h(a, {focus: function () {
      b.addClass(c.focusClass)
    }, blur: function () {
      b.removeClass(c.focusClass);
      b.removeClass(c.activeClass)
    }, mouseenter: function () {
      b.addClass(c.hoverClass)
    }, mouseleave: function () {
      b.removeClass(c.hoverClass);
      b.removeClass(c.activeClass)
    }, "mousedown touchbegin": function () {
      a.is(":disabled") || b.addClass(c.activeClass)
    }, "mouseup touchend": function () {
      b.removeClass(c.activeClass)
    }})
  }

  function q(a) {
    if (a[0].multiple)return!0;
    a = a.attr("size");
    return a === n || 1 >= a ? !1 : !0
  }

  function r(a, b, c) {
    a = a.val();
    "" === a ? a = c.fileDefaultHtml : (a = a.split(/[\/\\]+/), a = a[a.length - 1]);
    b.text(a)
  }

  function l(a, b) {
    a.removeClass(b.hoverClass + " " + b.focusClass + " " + b.activeClass)
  }

  function i(a, b, c) {
    var d = b.is(":checked");
    b.prop ? b.prop("checked", d) : d ? b.attr("checked", "checked") : b.removeAttr("checked");
    b = c.checkedClass;
    d ? a.addClass(b) : a.removeClass(b)
  }

  function j(a, b, c) {
    c = c.disabledClass;
    b.is(":disabled") ? a.addClass(c) :
      a.removeClass(c)
  }

  function p(a, b, c) {
    switch (c) {
      case "after":
        return a.after(b), a.next();
      case "before":
        return a.before(b), a.prev();
      case "wrap":
        return a.wrap(b), a.parent()
    }
    return null
  }

  function m(a, b, c) {
    var d, e;
    c || (c = {});
    c = f.extend({bind: {}, css: null, divClass: null, divWrap: "wrap", spanClass: null, spanHtml: null, spanWrap: "wrap"}, c);
    d = f("<div />");
    e = f("<span />");
    b.autoHide && !a.is(":visible") && d.hide();
    c.divClass && d.addClass(c.divClass);
    c.spanClass && e.addClass(c.spanClass);
    b.useID && a.attr("id") && d.attr("id", b.idPrefix +
      "-" + a.attr("id"));
    c.spanHtml && e.html(c.spanHtml);
    d = p(a, d, c.divWrap);
    e = p(a, e, c.spanWrap);
    c.css && a.css(c.css);
    j(d, a, b);
    return{div: d, span: e}
  }

  f.uniform = {defaults: {activeClass: "active", autoHide: !0, buttonClass: "button", checkboxClass: "checker", checkedClass: "checked", disabledClass: "disabled", fileButtonClass: "action", fileButtonHtml: "Choose File", fileClass: "uploader", fileDefaultHtml: "No file selected", filenameClass: "filename", focusClass: "focus", hoverClass: "hover", idPrefix: "uniform", radioClass: "radio", resetDefaultHtml: "Reset",
    resetSelector: !1, selectAutoWidth: !1, selectClass: "selector", submitDefaultHtml: "Submit", useID: !0}, elements: []};
  var s = !0, t = [
    {match: function (a) {
      return a.is("button, :submit, :reset, a, input[type='button']")
    }, apply: function (a, b) {
      var c, d;
      d = b.submitDefaultHtml;
      a.is(":reset") && (d = b.resetDefaultHtml);
      if (a.is("a, button"))d = a.html() || d; else if (a.is(":submit, :reset, input[type=button]")) {
        var e;
        e = (e = a.attr("value")) ? f("<span />").text(e).html() : "";
        d = e || d
      }
      c = m(a, b, {css: {display: "none"}, divClass: b.buttonClass,
        spanHtml: d}).div;
      k(a, c, b);
      h(c, {"click touchend": function (b) {
        f(b.target).is("span, div") && (a[0].dispatchEvent ? (b = document.createEvent("MouseEvents"), b.initEvent("click", !0, !0), a[0].dispatchEvent(b)) : a.click())
      }});
      f.uniform.noSelect(c);
      return{remove: function () {
        return a.unwrap().unwrap()
      }, update: function () {
        l(c, b);
        j(c, a, b)
      }}
    }},
    {match: function (a) {
      return a.is(":checkbox")
    }, apply: function (a, b) {
      var c, d, e;
      c = m(a, b, {css: {opacity: 0}, divClass: b.checkboxClass});
      d = c.div;
      e = c.span;
      k(a, d, b);
      h(a, {"click touchend": function () {
        i(e,
          a, b)
      }});
      i(e, a, b);
      return{remove: function () {
        return a.unwrap().unwrap()
      }, update: function () {
        l(d, b);
        e.removeClass(b.checkedClass);
        i(e, a, b);
        j(d, a, b)
      }}
    }},
    {match: function (a) {
      return a.is(":file")
    }, apply: function (a, b) {
      function c() {
        r(a, g, b)
      }

      var d, e, g;
      d = m(a, b, {css: {opacity: 0}, divClass: b.fileClass, spanClass: b.fileButtonClass, spanHtml: b.fileButtonHtml, spanWrap: "after"});
      e = d.div;
      d = d.span;
      g = f("<span />").html(b.fileDefaultHtml);
      g.addClass(b.filenameClass);
      g = p(a, g, "after");
      a.attr("size") || a.attr("size", e.width() /
        10);
      k(a, e, b);
      c();
      f.browser.msie ? h(a, {click: function () {
        a.trigger("change");
        setTimeout(c, 0)
      }}) : h(a, {change: c});
      f.uniform.noSelect(g);
      f.uniform.noSelect(d);
      return{remove: function () {
        a.siblings("span").remove();
        a.unwrap();
        return a
      }, update: function () {
        l(e, b);
        r(a, g, b);
        j(e, a, b)
      }}
    }},
    {match: function (a) {
      return a.is("input") ? (a = a.attr("type").toLowerCase(), 0 <= " color date datetime datetime-local email month number password search tel text time url week ".indexOf(" " + a + " ")) : !1
    }, apply: function (a) {
      var b = a.attr("type");
      a.addClass(b);
      return{remove: function () {
        a.removeClass(b)
      }, update: function () {
      }}
    }},
    {match: function (a) {
      return a.is(":radio")
    }, apply: function (a, b) {
      var c, d, e;
      c = m(a, b, {css: {opacity: 0}, divClass: b.radioClass});
      d = c.div;
      e = c.span;
      k(a, d, b);
      h(a, {"click touchend": function () {
        var c = "." + b.radioClass.split(" ")[0] + " span." + b.checkedClass + ":has([name='" + a.attr("name") + "'])";
        f(c).each(function () {
          var a = f(this), c = a.find(":radio");
          i(a, c, b)
        });
        i(e, a, b)
      }});
      i(e, a, b);
      return{remove: function () {
        return a.unwrap().unwrap()
      }, update: function () {
        l(d,
          b);
        i(e, a, b);
        j(d, a, b)
      }}
    }},
    {match: function (a) {
      return a.is("select") && !q(a) ? !0 : !1
    }, apply: function (a, b) {
      var c, d, e, g;
      g = a.width();
      c = m(a, b, {css: {opacity: 0, left: "2px", width: g + 32 + "px"}, divClass: b.selectClass, spanHtml: (a.find(":selected:first") || a.find("option:first")).html(), spanWrap: "before"});
      d = c.div;
      e = c.span;
      b.selectAutoWidth ? (d.width(f("<div />").width() - f("<span />").width() + g + 25), c = parseInt(d.css("paddingLeft"), 10), e.width(g - c - 15), a.width(g + c), a.css("min-width", g + c + "px"), d.width(g + c)) : (c = a.width(), d.width(c),
        e.width(c - 25));
      k(a, d, b);
      h(a, {change: function () {
        e.html(a.find(":selected").html());
        d.removeClass(b.activeClass)
      }, "click touchend": function () {
        var b = a.find(":selected").html();
        e.html() !== b && a.trigger("change")
      }, keyup: function () {
        e.html(a.find(":selected").html())
      }});
      f.uniform.noSelect(e);
      return{remove: function () {
        a.siblings("span").remove();
        a.unwrap();
        return a
      }, update: function () {
        l(d, b);
        e.html(a.find(":selected").html());
        j(d, a, b)
      }}
    }},
    {match: function (a) {
      return a.is("select") && q(a) ? !0 : !1
    }, apply: function (a) {
      a.addClass("uniform-multiselect");
      return{remove: function () {
        a.removeClass("uniform-multiselect")
      }, update: function () {
      }}
    }},
    {match: function (a) {
      return a.is("textarea")
    }, apply: function (a) {
      a.addClass("uniform");
      return{remove: function () {
        a.removeClass("uniform")
      }, update: function () {
      }}
    }}
  ];
  f.browser.msie && 7 > f.browser.version && (s = !1);
  f.fn.uniform = function (a) {
    var b = this, a = f.extend({}, f.uniform.defaults, a);
    !1 !== a.resetSelector && f(a.resetSelector).mouseup(function () {
      window.setTimeout(function () {
        f.uniform.update(b)
      }, 10)
    });
    return this.each(function () {
      var b =
        f(this), d, e;
      b.data("uniformed") && f.uniform.update(b);
      if (!b.data("uniformed") && s)for (d = 0; d < t.length; d += 1)if (e = t[d], e.match(b, a)) {
        d = e.apply(b, a);
        b.data("uniformed", d);
        f.uniform.elements.push(b.get(0));
        break
      }
    })
  };
  f.uniform.restore = function (a) {
    a === n && (a = f.uniform.elements);
    f(a).each(function () {
      var a = f(this), c;
      if (c = a.data("uniformed"))c.remove(), a.unbind(".uniform"), c = f.inArray(this, f.uniform.elements), 0 <= c && f.uniform.elements.splice(c, 1), a.removeData("uniformed")
    })
  };
  f.uniform.noSelect = function (a) {
    function b() {
      return!1
    }

    f(a).each(function () {
      this.onselectstart = this.ondragstart = b;
      f(this).mousedown(b).css({MozUserSelect: "none"})
    })
  };
  f.uniform.update = function (a) {
    a === n && (a = f.uniform.elements);
    f(a).each(function () {
      var a = f(this), c;
      (c = a.data("uniformed")) && c.update(a, c.options)
    })
  }
})(jQuery);
