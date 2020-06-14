! function(e, t, n, r, c) {
    var i = function(e, t) {
        var n, r = "IE",
            c = document.createElement("B"),
            i = document.documentElement;
        return e && (r += " " + e, t && (r = t + " " + r)), c.innerHTML = "<!--[if " + r + ']><b id="iecctest"></b><![endif]-->', i.appendChild(c), n = !!document.getElementById("iecctest"), i.removeChild(c), n
    };
    if (!e[n] && (e[n] = {
            process: function(t) {
                (e[n].q = e[n].q || []).push(t)
            },
            baseURL: "https://embedr.flickr.com"
        }, !i(8, "lte"))) {
        var d = t.createElement(r),
            m = t.getElementsByTagName(r)[0];
        d.async = 1, d.src = c, m.parentNode.insertBefore(d, m)
    }
}(window, document, "FlickrEmbedr", "script", "https://embedr.flickr.com/assets/embedr-loader.js"), window.FlickrEmbedr.process("inline");