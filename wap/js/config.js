var SiteUrl = "http://"+window.location.host+"/shop";//"http://s7.shoptest.top/shop";
var ApiUrl = "http://"+window.location.host+"/mobile";//"http://s7.shoptest.top/mobile";
var pagesize = 10;
var WapSiteUrl = "http://"+window.location.host+"/wap";//"http://s7.shoptest.top/wap";
var IOSSiteUrl = "http://"+window.location.host+"/app.ipa";//"http://s7.shoptest.top/app.ipa";
var AndroidSiteUrl = "http://"+window.location.host+"/app.apk";//"http://s7.shoptest.top/app.apk";

// auto url detection
(function() {
    var m = /^(https?:\/\/.+)\/wap/i.exec(location.href);
    if (m && m.length > 1) {
        SiteUrl = m[1] + '/shop';
        ApiUrl = m[1] + '/mobile';
        WapSiteUrl = m[1] + '/wap';
    }
})();
