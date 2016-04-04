/*
 * Combined with:
 * http://www.terminally-incoherent.com/blog/2008/11/25/serializing-javascript-objects-into-cookies/
 * With:
 * https://code.google.com/p/jquery-json/
 *
 */
(function ($) {
    $.fn.extend({
        cookieList: function (cookieName, expireTime) {

            var cookie = $.cookie(cookieName);              
            var items = cookie ? $.secureEvalJSON(cookie) : [];

            return {
                add: function (val) {
                    var index = items.indexOf(val);
                    // Note: Add only unique values.
                    if (index == -1) {
                        items.push(val);
                        $.cookie(cookieName, $.toJSON(items), { expires: expireTime, path: '/' });
                    }
                },
                remove: function (val) {
                    var index = items.indexOf(val);

                    if (index != -1) {
                        items.splice(index, 1);
                        $.cookie(cookieName, $.toJSON(items), { expires: expireTime, path: '/' });
                    }
                },
                indexOf: function (val) {
                    return items.indexOf(val);
                },
                clear: function () {
                    items = null;
                    $.cookie(cookieName, null, { expires: expireTime, path: '/' });
                },
                items: function () {
                    return items;
                },
                length: function () {
                    return items.length;
                },
                join: function (separator) {
                    return items.join(separator);
                }
            };
        }
    });
})(jQuery);