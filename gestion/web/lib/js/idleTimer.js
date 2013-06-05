(function (e) {
  e.idleTimer = function (t, n, r) {
    r = e.extend({startImmediately: true, idle: false, enabled: true, timeout: 3e4, events: "mousemove keydown DOMMouseScroll mousewheel mousedown touchstart touchmove"}, r);
    n = n || document;
    var i = e(n), s = i.data("idleTimerObj") || {}, o = function (t) {
      if (typeof t === "number") {
        t = undefined
      }
      var i = e.data(t || n, "idleTimerObj");
      i.idle = !i.idle;
      var s = +(new Date) - i.olddate;
      i.olddate = +(new Date);
      if (i.idle && s < r.timeout) {
        i.idle = false;
        clearTimeout(e.idleTimer.tId);
        if (r.enabled) {
          e.idleTimer.tId = setTimeout(o, r.timeout)
        }
        return
      }
      var u = e.Event(e.data(n, "idleTimer", i.idle ? "idle" : "active") + ".idleTimer");
      e(n).trigger(u)
    }, u = function (e) {
      var t = e.data("idleTimerObj") || {};
      t.enabled = false;
      clearTimeout(t.tId);
      e.off(".idleTimer")
    };
    s.olddate = s.olddate || +(new Date);
    if (typeof t === "number") {
      r.timeout = t
    } else if (t === "destroy") {
      u(i);
      return this
    } else if (t === "getElapsedTime") {
      return+(new Date) - s.olddate
    }
    i.on(e.trim((r.events + " ").split(" ").join(".idleTimer ")), function () {
      var t = e.data(this, "idleTimerObj");
      clearTimeout(t.tId);
      if (t.enabled) {
        if (t.idle) {
          o(this)
        }
        t.tId = setTimeout(o, t.timeout)
      }
    });
    s.idle = r.idle;
    s.enabled = r.enabled;
    s.timeout = r.timeout;
    if (r.startImmediately) {
      s.tId = setTimeout(o, s.timeout)
    }
    i.data("idleTimer", "active");
    i.data("idleTimerObj", s)
  };
  e.fn.idleTimer = function (t, n) {
    if (!n) {
      n = {}
    }
    if (this[0]) {
      e.idleTimer(t, this[0], n)
    }
    return this
  }
})(jQuery)