/*
 * Bullet Screen for NUPT MP
 * main application.js
 * @author Rijn
 */

!$(document).ready(function() {

    $.server = 'http://nupt.pixelnfinite.com/BulletScreen/';

    $.bulletList = {};
    $.displaySwitch = false;

    $.wall = [];

    $.tempList = [];

    $.normalColor = "#000";
    $.emphasisColor = "#ff0000";

    $.pushInterval = 500;
    $.getInterval = 1000;

    var ReplaceArguments = function(text) {
        var i = 1,
            args = arguments;
        return text.replace(/%s/g, function() {
            return (i < args.length) ? args[i++] : "";
        });
    };

    var len = function(s) {
        var l = 0;
        var a = s.split("");
        for (var i = 0; i < a.length; i++) {
            if (a[i].charCodeAt(0) < 299) {
                l++;
            } else {
                l += 2;
            }
        }
        return l;
    }

    var randomColor = function() {
        return '#' + ('00000' + (Math.random() * 0x1000000 << 0).toString(16)).slice(-6);
    }

    var item = function(id, speed, color, nickname, content) {

        this.id = id;
        this.speed = speed;
        this.color = color;
        this.nickname = nickname;
        this.content = content;

    }

    item.fn = item.prototype = {
        create: function() {
            this.dom = $(ReplaceArguments('<div class="item"><div class="nickname">%s</div><div class="content">%s</div></div>', this.nickname, this.content));
            this.dom.css({
                color: this.color,
                left: $("#playground").width() + "px",
                top: Math.random() * ($("#playground").height() - 50) + "px"
            });
            this.dom.animate({
                left: (-(len(this.content) + 5) * 30) + "px",
            }, 100000 / this.speed, 'linear');
            $("#playground").append(this.dom);
            (function(dom, speed) {
                setTimeout(
                    function() {
                        dom.remove();
                    },
                    100000 / speed
                )
            }(this.dom, this.speed));
        }
    }

    var getList = function() {
        var _ajax = $.ajax({
            url: $.server + 'get.php',
            data: null,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $.displaySwitch = data.display;
                var tempA = $.tempList;
                $.tempList = new Array();
                if ($.displaySwitch == true) {
                    for (var key in data.list) {
                        $.tempList.push(key);
                        if ($.bulletList[key] === undefined) {
                            $.bulletList[key] = {};
                            $.bulletList[key].status = data.list[key];
                        } else {
                            $.bulletList[key].status = data.list[key];
                        }
                    };

                    var tempB = $.tempList;

                    for (var key in tempA) {
                        delete tempB[key];
                    }
                    for (var key in tempB) {
                        pushItem(key);
                    }
                };
                getContent();
            },
            complete: function() {
                setTimeout(
                    function() {
                        getList();
                    },
                    $.getInterval
                );
            },
            error: function(e) {
                console.error(e);
            }
        });
    };

    var getContent = function() {
        for (var key in $.bulletList) {
            if ($.bulletList[key] !== undefined && $.bulletList[key].status > 0 && $.bulletList[key].content === undefined) {
                (function(id) {
                    var _ajax = $.ajax({
                        url: $.server + 'get.php',
                        data: {
                            "id": id,
                        },
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $.bulletList[data.id].status = data.status;
                            $.bulletList[data.id].content = data.content;
                            $.bulletList[data.id].time = data.time;
                            $.bulletList[data.id].nickname = data.nickname;
                        },
                        error: function(e) {
                            console.error(e);
                        }
                    });
                })(key);
            }
        };
    };

    var pushItem = function(id) {
        var key = $.tempList[Math.floor(Math.random() * $.tempList.length)];
        if (id !== undefined) {
            key = id;
        }
        if ($.bulletList[key] !== undefined && $.bulletList[key].content !== undefined) {
            window.temp = new item(key, Math.random() * 5 + 5, $.bulletList[key].status == 2 ? randomColor() : $.normalColor, $.bulletList[key].nickname, $.bulletList[key].content);
            window.temp.create();
            $.wall.push(window.temp);
        }
    }

    getList();

    setInterval(
        function() {
            pushItem();
        },
        $.pushInterval
    );

});
