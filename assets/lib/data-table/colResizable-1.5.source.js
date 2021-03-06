(function($) {
    var d = $(document);
    var h = $("head");
    var drag = null;
    var tables = [];
    var count = 0;
    var ID = "id";
    var PX = "px";
    var SIGNATURE = "JColResizer";
    var FLEX = "JCLRFlex";
    var I = parseInt;
    var M = Math;
    var ie = navigator.userAgent.indexOf('Trident/4.0') > 0;
    var S;
    try {
        S = sessionStorage;
    } catch (e) {}
    h.append("<style type='text/css'>  .JColResizer{/*table-layout:fixed;*/} .JColResizer td, .JColResizer th{overflow:hidden;}  .JCLRgrips{ height:0px; position:relative;} .JCLRgrip{margin-left:-5px; position:absolute; z-index:5; } .JCLRgrip .JColResizer{position:absolute;background-color:red;filter:alpha(opacity=1);opacity:0;width:10px;height:100%;cursor: e-resize;top:0px} .JCLRLastGrip{position:absolute; width:1px; } .JCLRgripDrag{ border-left:1px dotted black;	} .JCLRFlex{width:auto!important;}</style>");
    var init = function(tb, options) {
        var t = $(tb);
        t.opt = options;
        if (t.opt.disable) return destroy(t);
        var id = t.id = t.attr(ID) || SIGNATURE + count++;
        t.p = t.opt.postbackSafe;
        if (!t.is("table") || tables[id] && !t.opt.partialRefresh) return;
        t.addClass(SIGNATURE).attr(ID, id).before('<div class="JCLRgrips"/>');
        t.g = [];
        t.c = [];
        t.w = t.width();
        t.gc = t.prev();
        t.f = t.opt.fixed;
        if (options.marginLeft) t.gc.css("marginLeft", options.marginLeft);
        if (options.marginRight) t.gc.css("marginRight", options.marginRight);
        t.cs = I(ie ? tb.cellSpacing || tb.currentStyle.borderSpacing : t.css('border-spacing')) || 2;
        t.b = I(ie ? tb.border || tb.currentStyle.borderLeftWidth : t.css('border-left-width')) || 1;
        tables[id] = t;
        createGrips(t);
    };
    var destroy = function(t) {
        var id = t.attr(ID),
            t = tables[id];
        if (!t || !t.is("table")) return;
        t.removeClass(SIGNATURE + " " + FLEX).gc.remove();
        delete tables[id];
    };
    var createGrips = function(t) {
        var th = t.find(">thead>tr>th,>thead>tr>td");
        if (!th.length) th = t.find(">tbody>tr:first>th,>tr:first>th,>tbody>tr:first>td, >tr:first>td");
        th = th.filter(":visible");
        t.cg = t.find("col");
        t.ln = th.length;
        if (t.p && S && S[t.id]) memento(t, th);
        th.each(function(i) {
            var c = $(this);
            var g = $(t.gc.append('<div class="JCLRgrip"></div>')[0].lastChild);
            g.append(t.opt.gripInnerHtml).append('<div class="' + SIGNATURE + '"></div>');
            if (i == t.ln - 1) {
                g.addClass("JCLRLastGrip");
                if (t.f) g.html("");
            }
            g.bind('touchstart mousedown', onGripMouseDown);
            g.t = t;
            g.i = i;
            g.c = c;
            c.w = c.width();
            t.g.push(g);
            t.c.push(c);
            c.width(c.w).removeAttr("width");
            g.data(SIGNATURE, {
                i: i,
                t: t.attr(ID),
                last: i == t.ln - 1
            });
        });
        t.cg.removeAttr("width");
        syncGrips(t);
        t.find('td, th').not(th).not('table th, table td').each(function() {
            $(this).removeAttr('width');
        });
        if (!t.f) {
            t.removeAttr('width').addClass(FLEX);
        }
    };
    var memento = function(t, th) {
        var w, m = 0,
            i = 0,
            aux = [],
            tw;
        if (th) {
            t.cg.removeAttr("width");
            if (t.opt.flush) {
                S[t.id] = "";
                return;
            }
            w = S[t.id].split(";");
            tw = w[t.ln + 1];
            if (!t.f && tw) t.width(tw);
            for (; i < t.ln; i++) {
                aux.push(100 * w[i] / w[t.ln] + "%");
                th.eq(i).css("width", aux[i]);
            }
            for (i = 0; i < t.ln; i++)
                t.cg.eq(i).css("width", aux[i]);
        } else {
            S[t.id] = "";
            for (; i < t.c.length; i++) {
                w = t.c[i].width();
                S[t.id] += w + ";";
                m += w;
            }
            S[t.id] += m;
            if (!t.f) S[t.id] += ";" + t.width();
        }
    };
    var syncGrips = function(t) {
        t.gc.width(t.w);
        for (var i = 0; i < t.ln; i++) {
            var c = t.c[i];
            t.g[i].css({
                left: c.offset().left - t.offset().left + c.outerWidth(false) + t.cs / 2 + PX,
                height: t.opt.headerOnly ? t.c[0].outerHeight(true) : t.outerHeight(true)
            });
        }
    };
    var syncCols = function(t, i, isOver) {
        var inc = drag.x - drag.l,
            c = t.c[i],
            c2 = t.c[i + 1];
        var w = c.w + inc;
        var w2 = c2.w - inc;
        c.width(w + PX);
        t.cg.eq(i).width(w + PX);
        if (t.f) {
            c2.width(w2 + PX);
            t.cg.eq(i + 1).width(w2 + PX);
        }
        if (isOver) {
            c.w = w;
            c2.w = t.f ? w2 : c2.w;
        }
    };
    var applyBounds = function(t) {
        var w = $.map(t.c, function(c) {
            return c.width();
        });
        t.width(t.width()).removeClass(FLEX);
        $.each(t.c, function(i, c) {
            c.width(w[i]).w = w[i];
        });
        t.addClass(FLEX);
    };
    var onGripDrag = function(e) {
        if (!drag) return;
        var t = drag.t;
        var oe = e.originalEvent.touches;
        var ox = oe ? oe[0].pageX : e.pageX;
        var x = ox - drag.ox + drag.l;
        var mw = t.opt.minWidth,
            i = drag.i;
        var l = t.cs * 1.5 + mw + t.b;
        var last = i == t.ln - 1;
        var min = i ? t.g[i - 1].position().left + t.cs + mw : l;
        var max = t.f ? i == t.ln - 1 ? t.w - l : t.g[i + 1].position().left - t.cs - mw : Infinity;
        x = M.max(min, M.min(max, x));
        drag.x = x;
        drag.css("left", x + PX);
        if (last) {
            var c = t.c[drag.i];
            drag.w = c.w + x - drag.l;
        }
        if (t.opt.liveDrag) {
            if (last) {
                c.width(drag.w);
                t.w = t.width();
            } else {
                syncCols(t, i);
            }
            syncGrips(t);
            var cb = t.opt.onDrag;
            if (cb) {
                e.currentTarget = t[0];
                cb(e);
            }
        }
        return false;
    };
    var onGripDragOver = function(e) {
        d.unbind('touchend.' + SIGNATURE + ' mouseup.' + SIGNATURE).unbind('touchmove.' + SIGNATURE + ' mousemove.' + SIGNATURE);
        $("head :last-child").remove();
        if (!drag) return;
        drag.removeClass(drag.t.opt.draggingClass);
        var t = drag.t;
        var cb = t.opt.onResize;
        var i = drag.i;
        var last = i == t.ln - 1;
        var c = t.g[i].c;
        if (last) {
            c.width(drag.w);
            c.w = drag.w;
        } else {
            syncCols(t, i, true);
        }
        if (!t.f) applyBounds(t);
        syncGrips(t);
        if (cb) {
            e.currentTarget = t[0];
            cb(e);
        }
        if (t.p && S) memento(t);
        drag = null;
    };
    var onGripMouseDown = function(e) {
        var o = $(this).data(SIGNATURE);
        var t = tables[o.t],
            g = t.g[o.i];
        var oe = e.originalEvent.touches;
        g.ox = oe ? oe[0].pageX : e.pageX;
        g.l = g.position().left;
        d.bind('touchmove.' + SIGNATURE + ' mousemove.' + SIGNATURE, onGripDrag).bind('touchend.' + SIGNATURE + ' mouseup.' + SIGNATURE, onGripDragOver);
        h.append("<style type='text/css'>*{cursor:" + t.opt.dragCursor + "!important}</style>");
        g.addClass(t.opt.draggingClass);
        drag = g;
        if (t.c[o.i].l)
            for (var i = 0, c; i < t.ln; i++) {
                c = t.c[i];
                c.l = false;
                c.w = c.width();
            }
        return false;
    };
    var onResize = function() {
        for (t in tables) {
            var t = tables[t],
                i, mw = 0;
            t.removeClass(SIGNATURE);
            if (t.f && t.w != t.width()) {
                t.w = t.width();
                for (i = 0; i < t.ln; i++) mw += t.c[i].w;
                for (i = 0; i < t.ln; i++) t.c[i].css("width", M.round(1000 * t.c[i].w / mw) / 10 + "%").l = true;
            }
            syncGrips(t.addClass(SIGNATURE));
        }
    };
    $(window).bind('resize.' + SIGNATURE, onResize);
    $.fn.extend({
        colResizable: function(options) {
            var defaults = {
                draggingClass: 'JCLRgripDrag',
                gripInnerHtml: '',
                liveDrag: false,
                fixed: true,
                minWidth: 15,
                headerOnly: false,
                hoverCursor: "e-resize",
                dragCursor: "e-resize",
                postbackSafe: false,
                flush: false,
                marginLeft: null,
                marginRight: null,
                disable: false,
                partialRefresh: false,
                onDrag: null,
                onResize: null
            }
            var options = $.extend(defaults, options);
            return this.each(function() {
                init(this, options);
            });
        }
    });
})(jQuery);