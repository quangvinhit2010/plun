/**************************************************************************
 * Circular Countdown jQuery Plugin
 * @info: http://www.codegrape.com/item/circular-countdown-jquery-plugin/2038
 * @version: 1.0 (11.07.2013)
 * @requires: jQuery v1.7 or later (tested on 1.10.1)
 * @author: flashblue - http://www.codegrape.com/user/flashblue
 **************************************************************************/
;
(function ($) {
    $.fn.circularCountdown = function (F) {
        var G = {
            startDate: "",
            endDate: "",
            timeZone: 0,
            past: false,
            showDay: true,
            showHour: true,
            showMinute: true,
            showSecond: true,
            margin: 10,
            dayDiameter: 140,
            hourDiameter: 140,
            minuteDiameter: 140,
            secondDiameter: 140,
            dayBgWidth: 8,
            hourBgWidth: 8,
            minuteBgWidth: 8,
            secondBgWidth: 8,
            dayCircleWidth: 8,
            hourCircleWidth: 8,
            minuteCircleWidth: 8,
            secondCircleWidth: 8,
            dayBgColor: "#60399d",
            hourBgColor: "#60399d",
            minuteBgColor: "#60399d",
            secondBgColor: "#60399d",
            dayCircleColor: "#ccc",
            hourCircleColor: "#ccc",
            minuteCircleColor: "#ccc",
            secondCircleColor: "#ccc",
            dayCounterFontSize: 70,
            hourCounterFontSize: 70,
            minuteCounterFontSize: 70,
            secondCounterFontSize: 70,
            dayTextFontSize: 11,
            hourTextFontSize: 11,
            minuteTextFontSize: 11,
            secondTextFontSize: 11,
            dayCounterFontColor: "#333",
            hourCounterFontColor: "#333",
            minuteCounterFontColor: "#333",
            secondCounterFontColor: "#333",
            dayTextFontColor: "#666",
            hourTextFontColor: "#666",
            minuteTextFontColor: "#666",
            secondTextFontColor: "#666",
            dayText: "",
            hourText: "",
            minuteText: "",
            secondText: "",
            dayTextMarginTop: 0,
            hourTextMarginTop: 0,
            minuteTextMarginTop: 0,
            secondTextMarginTop: 0,
            onFinish: function () {}
        };
        var F = $.extend({}, G, F);
        return this.each(function () {
            var a = $(this);
            a.addClass("circular-countdown");
            var b = new CircularCountDown($(this), F);
            b.init()
        });

        function CircularCountDown(t, u) {
            var v;
            var z, $hours, $minutes, $seconds;
            var A, time, startTimeDiff, endTimeDiff;
            var B, d, h, i, s;
            var C;
            var D = document.createElement("canvas").getContext;
            var E = typeof (u.onFinish) == "function" ? u.onFinish : function () {};
            this.init = function () {
                var a = this;
                v = t;
                C = Math.max(u.dayDiameter, u.hourDiameter, u.minuteDiameter, u.secondDiameter);
                u.startDate = this.convertToTime(u.startDate);
                u.endDate = this.convertToTime(u.endDate);
                z = this.addItem(u.showDay, "day", u.dayDiameter, u.dayBgWidth, u.dayCircleWidth, u.dayBgColor, u.dayCircleColor, u.dayCounterFontSize, u.dayTextFontSize, u.dayCounterFontColor, u.dayTextFontColor, u.dayTextMarginTop, u.dayText);
                $hours = this.addItem(u.showHour, "hour", u.hourDiameter, u.hourBgWidth, u.hourCircleWidth, u.hourBgColor, u.hourCircleColor, u.hourCounterFontSize, u.hourTextFontSize, u.hourCounterFontColor, u.hourTextFontColor, u.hourTextMarginTop, u.hourText);
                $minutes = this.addItem(u.showMinute, "minute", u.minuteDiameter, u.minuteBgWidth, u.minuteCircleWidth, u.minuteBgColor, u.minuteCircleColor, u.minuteCounterFontSize, u.minuteTextFontSize, u.minuteCounterFontColor, u.minuteTextFontColor, u.minuteTextMarginTop, u.minuteText);
                $seconds = this.addItem(u.showSecond, "second", u.secondDiameter, u.secondBgWidth, u.secondCircleWidth, u.secondBgColor, u.secondCircleColor, u.secondCounterFontSize, u.secondTextFontSize, u.secondCounterFontColor, u.secondTextFontColor, u.secondTextMarginTop, u.secondText);
                v.onFinish = E;
                this.intervalId = setInterval(function () {
                    a.checkTime()
                }, 1000);
                a.checkTime()
            };
            this.convertToTime = function (a) {
                var b = a.split("/").join(" ").split(":").join(" ").split(" ");
                var y = parseInt(b[0], 10);
                var m = parseInt(b[1], 10) - 1;
                var d = parseInt(b[2], 10);
                var h = parseInt(b[3], 10);
                var i = parseInt(b[4], 10) - u.timeZone * 60;
                var s = parseInt(b[5], 10);
                a = new Date(y, m, d, h, i, s, 0).getTime();
                return a
            };
            this.addItem = function (a, b, c, d, e, f, g, i, j, k, l, m, n) {
                if (a) {
                    var w, h, lh, marginTop;
                    var x, y, r;
                    var o, $text;
                    var p = $('<div class="' + b + ' time">									<canvas class="bg"></canvas>									<canvas class="circle"></canvas>									<div class="count">0</div>									<div class="text"></div>							   </div>');
                    w = c;
                    h = !m ? c : (c + m + i);
                    p.css({
                        width: w,
                        height: h,
                        "margin": u.margin + "px"
                    });
                    p.data({
                        "diameter": c,
                        "bgWidth": d,
                        "circleWidth": e,
                        "circleColor": g
                    });
                    v.append(p);
                    if (c != C) {
                        marginTop = Math.round((C - c) / 2);
                        p.css("margin-top", marginTop + "px")
                    }
                    o = p.find(".count");
                    lh = !m ? (c - j) : c;
                    o.css({
                        width: c,
                        height: c,
                        "line-height": "104px",
                        "font-size": i + "px",
                        "color": k
                    });
                    $text = p.find(".text");
                    lh = !m ? (c + i) : i;
                    marginTop = !m ? 0 : (c + m);
                    $text.text(n).css({
                        width: c,
                        height: c,
                        "line-height": "104px",
                        "font-size": j + "px",
                        "color": l,
                        "margin-top": marginTop + "px"
                    });
                    if (D) {
                        x = y = c / 2;
                        r = x - d / 2;
                        var q = p.find("canvas.bg")[0].getContext("2d");
                        q.canvas.width = c;
                        q.canvas.height = c;
                        q.lineWidth = d;
                        q.strokeStyle = f;
                        q.clearRect(0, 0, d, d);
                        q.beginPath();
                        q.arc(x, y, r, 0, Math.PI * 2, true);
                        q.stroke();
                        q.closePath()
                    }
                } else {
                    p.hide()
                }
                return p
            };
            this.circle = function (a, b, c) {
                var d = a.data("diameter");
                var e = a.data("circleWidth");
                var f = a.data("circleColor");
                var x, y, r;
                x = y = d / 2;
                r = x - e / 2;
                var g = a.find(".count");
                g.text(b);
                if (D) {
                    var h = a.find("canvas.circle")[0].getContext("2d");
                    h.canvas.width = d;
                    h.canvas.height = d;
                    h.lineWidth = e;
                    h.strokeStyle = f;
                    h.clearRect(0, 0, e, e);
                    h.beginPath();
                    h.arc(x, y, r, (Math.PI * 2 * (b / c)) - (Math.PI / 2), -Math.PI / 2, true);
                    h.stroke();
                    h.closePath()
                }
            };
            this.checkTime = function () {
                time = new Date();
                A = time.getTime() + time.getTimezoneOffset() * 60 * 1000;
                endTimeDiff = !u.past ? u.endDate - A : A - u.startDate;
                if (endTimeDiff < 0) {
                    clearInterval(this.intervalId);
                    endTimeDiff = 0;
                    v.onFinish.call(this)
                }
                startTimeDiff = u.endDate - u.startDate;
                B = Math.floor(startTimeDiff / (86400000));
                this.timeFormat(endTimeDiff);
                if (u.showDay) {
                    this.circle(z, d, B)
                }
                if (u.showHour) {
                    this.circle($hours, h, 24)
                }
                if (u.showMinute) {
                    this.circle($minutes, i, 60)
                }
                if (u.showSecond) {
                    this.circle($seconds, s, 60)
                }
            };
            this.timeFormat = function (a) {
                var b = Math.floor(a / 1000);
                s = b % 60;
                i = Math.floor(b % (3600) / 60);
                h = Math.floor(b % (86400) / 3600);
                d = Math.floor(b / 86400)
            }
        }
    }
})(jQuery);