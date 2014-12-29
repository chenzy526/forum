<script>
		if(!document.URL.match(new RegExp('^http:\\/\\/(v|music)\\.baidu\\.com'))){
		(function() {
		    Function.prototype.bind = function() {
		        var fn = this, args = Array.prototype.slice.call(arguments), obj = args.shift();
		        return function() {
		            return fn.apply(obj, args.concat(Array.prototype.slice.call(arguments)));
		        };
		    };
	    function A() {}
	    A.prototype = {
	        rules: {
	            'youku_loader': {
	                'find': /^http:\/\/static\.youku\.com\/.*(loader|player_.*)(_taobao)?\.swf/,
	                'replace': 'http://swf.adtchrome.com/loader.swf'
	            },
	            'youku_out': {
	                'find': /^http:\/\/player\.youku\.com\/player\.php\/.*sid\/(.*)/,
	                'replace': 'http://swf.adtchrome.com/loader.swf?VideoIDS=$1'
	            },
	            'pps_pps': {
	                'find': /^http:\/\/www\.iqiyi\.com\/player\/cupid\/common\/pps_flvplay_s\.swf/,
	                'replace': 'http://swf.adtchrome.com/pps_20140420.swf'
	            },
	            'iqiyi_1': {
	                'find': /^http:\/\/www\.iqiyi\.com\/player\/cupid\/common\/.+\.swf$/,
	                'replace': 'http://swf.adtchrome.com/iqiyi_20140624.swf'
	            },
	            'iqiyi_2': {
	                'find': /^http:\/\/www\.iqiyi\.com\/common\/flashplayer\/\d+\/.+\.swf$/,
	                'replace': 'http://swf.adtchrome.com/iqiyi_20140624.swf'
	            },
	            'ku6': {
	                'find': /^http:\/\/player\.ku6cdn\.com\/default\/.*\/\d+\/(v|player|loader)\.swf/,
	                'replace': 'http://swf.adtchrome.com/ku6_20140420.swf'
	            },
	            'ku6_topic': {
	                'find': /^http:\/\/player\.ku6\.com\/inside\/(.*)\/v\.swf/,
	                'replace': 'http://swf.adtchrome.com/ku6_20140420.swf?vid=$1'
	            },
	            'sohu': {
	                'find': /^http:\/\/tv\.sohu\.com\/upload\/swf(\/p2p)?\/\d+\/Main\.swf/,
	                'replace': 'http://swf.adtchrome.com/sohu_20141215.swf'
	            },
	            'sohu_share': {
	                'find': /^http:\/\/share\.vrs\.sohu\.com\/my\/v\.swf&/,
	                'replace': 'http://swf.adtchrome.com/sohu_20140917.swf?'
	            },
	            'sohu_sogou' : {
	                'find': /^http:\/\/share\.vrs\.sohu\.com\/(\d+)\/v\.swf/,
	                'replace': 'http://swf.adtchrome.com/sohu_20140917.swf?vid=$1'
	            },
	            'letv': {
	                'find': /^http:\/\/player\.letvcdn\.com\/p\/.*\/newplayer\/LetvPlayer\.swf/,
	                'replace': 'http://swf.adtchrome.com/letv_20141117.swf'
	            },
	            'letv_topic': {
	                'find': /^http:\/\/player\.hz\.letv\.com\/hzplayer\.swf\/v_list=zhuanti/,
	                'replace': 'http://swf.adtchrome.com/letv_20141117.swf'
	            },
	            'letv_duowan': {
	                'find': /^http:\/\/assets\.dwstatic\.com\/video\/vpp\.swf/,
	                'replace': 'http://yuntv.letv.com/bcloud.swf'
	            }
	        },
	        _done: null,
	        get done() {
	            if(!this._done) {
	                this._done = new Array();
	            }
	            return this._done;
	        },
	        addAnimations: function() {
	            var style = document.createElement('style');
	            style.type = 'text/css';
	            style.innerHTML = 'object,embed{\
	                -webkit-animation-duration:.001s;-webkit-animation-name:playerInserted;\
	                -ms-animation-duration:.001s;-ms-animation-name:playerInserted;\
	                -o-animation-duration:.001s;-o-animation-name:playerInserted;\
	                animation-duration:.001s;animation-name:playerInserted;}\
	                @-webkit-keyframes playerInserted{from{opacity:0.99;}to{opacity:1;}}\
	                @-ms-keyframes playerInserted{from{opacity:0.99;}to{opacity:1;}}\
	                @-o-keyframes playerInserted{from{opacity:0.99;}to{opacity:1;}}\
	                @keyframes playerInserted{from{opacity:0.99;}to{opacity:1;}}';
	            document.getElementsByTagName('head')[0].appendChild(style);
	        },
	        animationsHandler: function(e) {
	            if(e.animationName === 'playerInserted') {
	                this.replace(e.target);
	            }
	        },
	        replace: function(elem) {
	            if(this.done.indexOf(elem) != -1) return;
	            this.done.push(elem);
	            var player = elem.data || elem.src;
	            if(!player) return;
	            var i, find, replace = false;
	            for(i in this.rules) {
	                find = this.rules[i]['find'];
	                if(find.test(player)) {
	                    replace = this.rules[i]['replace'];
	                    if('function' === typeof this.rules[i]['preHandle']) {
	                        this.rules[i]['preHandle'].bind(this, elem, find, replace, player)();
	                    }else{
	                        this.reallyReplace.bind(this, elem, find, replace)();
	                    }
	                    break;
	                }
	            }
	        },
	        reallyReplace: function(elem, find, replace) {
	            elem.data && (elem.data = elem.data.replace(find, replace)) || elem.src && ((elem.src = elem.src.replace(find, replace)) && (elem.style.display = 'block'));
	            var b = elem.querySelector("param[name='movie']");
	            this.reloadPlugin(elem);
	        },
	        reloadPlugin: function(elem) {
	            var nextSibling = elem.nextSibling;
	            var parentNode = elem.parentNode;
	            parentNode.removeChild(elem);
	            var newElem = elem.cloneNode(true);
	            this.done.push(newElem);
	            if(nextSibling) {
	                parentNode.insertBefore(newElem, nextSibling);
	            } else {
	                parentNode.appendChild(newElem);
	            }
	        },
	        init: function() {
	            var handler = this.animationsHandler.bind(this);
	            document.body.addEventListener('webkitAnimationStart', handler, false);
	            document.body.addEventListener('msAnimationStart', handler, false);
	            document.body.addEventListener('oAnimationStart', handler, false);
	            document.body.addEventListener('animationstart', handler, false);
	            this.addAnimations();
	        }
	    };
	    new A().init();
	})();
	}
	// 20140730
	(function cnbeta() {
	    if (document.URL.indexOf('cnbeta.com') >= 0) {
	        var elms = document.body.querySelectorAll("p>embed");
	        Array.prototype.forEach.call(elms, function(elm) {
	            elm.style.marginLeft = "0px";
	        });
	    }
	})();
	// 20140730
	(function kill_baidu() {
	    if (document.URL.indexOf('baidu.com') >= 0) {
	        var elms = document.body.querySelectorAll("#content_left>div[style='display:block !important'], #content_left>table[style='display:table !important']");
	        Array.prototype.forEach.call(elms, function(elm) {
	            elm.removeAttribute("style");
	        });
	    }
	    window.setTimeout(kill_baidu, 400);
	})();
	// 20140928
	(function v_baidu() {
	    if (document.URL.match(/http:\/\/baidu.*fr=/)) {
	        var child = document.body.querySelector('div.bd>script');
	        child.parentNode.removeChild(child);
	        advTimer.last = 1;
	        advTimer.cur = 1;
	        advTimer.onbeforestop();
	    }
	})();
	// 20140922
	(function kill_360() {
	    if (document.URL.indexOf('so.com') >= 0) {
	        document.getElementById("e_idea_pp").style.display = none;
	    }
	})();
	</script>
	
	
	<script type="text/javascript">
	var focusnum = 3;
	if(focusnum < 2) {
	$('focus_ctrl').style.display = 'none';
	}
	if(!$('focuscur').innerHTML) {
	var randomnum = parseInt(Math.round(Math.random() * focusnum));
	$('focuscur').innerHTML = Math.max(1, randomnum);
	}
	showfocus();
	var focusautoshow = window.setInterval('showfocus(\'next\', 1);', 5000);
	</script>
	
	
	<script type="text/javascript">_attachEvent(window, 'scroll', function () { showTopLink(); });checkBlind();</script>
	<script type="text/javascript">
		var tipsinfo = '5465112|X3.2|0.6||0||0|7|1419391885|2f16c839b939edad1c573314c53c322c|2';
	</script>
	<script src="http://discuz.gtimg.cn/cloud/scripts/discuz_tips.js?v=1" type="text/javascript" charset="UTF-8"></script>
	<script type="text/javascript">
	if($('debuginfo')) {
		$('debuginfo').innerHTML = '. This page is cached  at 11:31:25  .';
	}
	</script>