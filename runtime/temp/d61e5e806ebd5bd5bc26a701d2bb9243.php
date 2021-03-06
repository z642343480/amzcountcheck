<?php /*a:1:{s:69:"C:\newwww\wamp64\www\amzcount\application\admin\view\index\index.html";i:1622973707;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>amzcount</title>
    <script src="/static/jq.js"></script>
    <link rel="stylesheet" href="/static/layui/css/layui.css">
    <script src="/static/layui/layui.js"></script>
</head>
<style>
</style>
<body>
<div style="width: 98%;height:200px;margin: 0 auto;">
    <div style="position:fixed;right:23px;top:10px;z-index:9999999"><a href="/index/index" style="color: #409EFF">返回前台</a></div>
    <form class="layui-form" action="">
        <div class="layui-form-item">
            <label style="width: auto;" class="layui-form-label">自动同步功能：</label>
            <input type="checkbox" name="zzz" lay-skin="switch" lay-text="开启|关闭" lay-filter="task_state">
        </div>
        <div class="layui-form-item" style="float:left">
            <input type="button" id="do_sync_btn" class="layui-btn layui-btn-primary layui-border-black"
                   style="width:100px;height:30px;line-height:30px;margin-left:5px;text-align:center;background-color:#F5F5F5;border-color: #D3D3D3 !important;"
                   value="手动同步">
        </div>
        <div class="layui-progress  layui-progress-big" style="float:left;width: 60%;margin-left: 30px;margin-top: 5px;"
             lay-filter="demo" lay-showPercent="true">
            <div class="layui-progress-bar" lay-percent="0%"></div>
        </div>
        <div class="syncing" style="float: left; line-height: 30px;margin-left: 30px;display: none;">
            数据同步中.............
        </div>
        <div class="done_sync" style="float: left; line-height: 30px;margin-left: 30px;display: none;">
            同步已完成
        </div>
        <div class="layui-form-item">
            <input type="button" id="stop_sync_btn" class="layui-btn layui-btn-primary layui-border-black"
                   style="width:100px;height:30px;line-height:30px;margin-left:5px;text-align:center;background-color:#F5F5F5;border-color: #D3D3D3 !important;"
                   value="终止同步">
        </div>
    </form>

</div>

<div style="width: 98%;margin: 0 auto;border:1px solid #d1dbe5">
    <div class="search_form" style="margin-top: 10px;">
        <form class="layui-form" action="">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">日期：</label>
                    <div class="layui-input-inline">
                        <input type="tel" name="error_time" lay-verify="required|phone" autocomplete="off"
                               class="layui-input" style="height: 30px;margin-top: 5px;" id="error_time">
                    </div>
                </div>
                <input type="button" class="layui-btn search" style="height: 29px;line-height: 29px;margin-top: -5px"
                       value="搜索">
                <!--                <input type="button" class="layui-btn" style="height: 29px;line-height: 29px;margin-top: 10px;margin-right:10px;float: right;" value="数据导出">-->
            </div>
        </form>

    </div>
    <table id="demo" lay-filter="test"></table>

</div>
<literal>
    <script>
        layui.use('table', function () {
            var table = layui.table;
            var laydate = layui.laydate;
            var form = layui.form;
            var layer = layui.layer;
            $.ajax({
                type: "POST",
                url: "/admin/log/getlong",
                data: {},
                dataType: "json",
                success: function (data) {
                    if (data[0]['is_auto'] == 1) {
                        $("input[type='checkbox'][name='zzz']").prop("checked", true);
                        form ? form.render("checkbox") : null;
                    } else {
                        $("input[type='checkbox'][name='zzz']").prop("checked", false);
                        form ? form.render("checkbox") : null;
                    }
                },
                error: function (jqXHR) {
                    layer.msg('获取同步状态失败');
                }
            });
            //第一个实例
            table.render({
                elem: '#demo'
                , height: 512
                , url: '/admin/Index/getListData' //数据接口
                , page: true //开启分页
                ,limits: [5, 10, 20, 40, 80, 200]
                , cols: [[ //表头
                    {type: 'checkbox', fixed: 'left'}
                    , {field: 'id', title: 'ID', sort: true, fixed: 'left'}
                    , {
                        field: 'area', title: '国家', sort: true, templet: function (row) {
                            if (row.area == "usa") {
                                return '美国'
                            } else if (row.area == "uk") {
                                return '英国'
                            } else if (row.area == "de") {
                                return '德国'
                            } else if (row.area == "jp") {
                                return '日本'
                            } else if (row.area == "esp") {
                                return '西班牙'
                            } else if (row.area == "it") {
                                return '意大利'
                            } else if (row.area == "fr") {
                                return '法国'
                            } else if (row.area == "mx") {
                                return '墨西哥'
                            } else if (row.area == "ca") {
                                return '加拿大'
                            }
                        }
                    }
                    , {field: 'error_code', title: '目标代码'}
                    , {field: 'error_time', title: '日期', sort: true}
                    , {field: 'data_count', title: '数据量', sort: true}
                    , {
                        field: 'o_type', title: '类型', templet: function (row) {
                            if (row.o_type == "0") {
                                return '<span style="color: #409EFF">手动</span>'
                            } else if (row.o_type == "1") {
                                return '<span style="color: #DAA520;">自动</span>'
                            }
                        }
                    }
                    , {field: 'remark', title: '备注',}
                    , {
                        field: 'is_success', title: '是否成功', templet: function (row) {
                            if (row.is_success == "0") {
                                return '<span style="color: red;">失败</span>'
                            } else if (row.is_success == "1") {
                                return '<span style="color: #3CB371;">成功</span>'
                            }
                        }
                    }
                ]]
            });
            form.on('switch(task_state)', function (data) {
                //开关是否开启，true或者false
                var checked = data.elem.checked;
                if (checked) {
                    htmlobj = $.ajax({
                        url: "/admin/Index/openTask",
                         async: false
                     });
                    if (htmlobj == 1) {
                        layer.msg('定时任务开启成功')
                    }
                } else {
                    htmlobj = $.ajax({
                        url: "/admin/Index/closeTask",
                         async: false
                     });
                    if (htmlobj == 1) {
                        layer.msg('定时任务已关闭')
                    }
                }

                form.render();
            });
            laydate.render({
                elem: '#error_time' //指定元素
                , type: 'datetime'
            });
        });
    </script>

    <script>
        var t2 = '';
        var loading = '';
        $("#do_sync_btn").click(function () {
            $(".syncing").hide();
            $(".done_sync").hide();
            $("#do_sync_btn").attr("disabled", 'disabled');
            // var loading = layer.msg('同步中，请稍等', {icon: 16, shade: 0.3, time:0});
            loading = layer.load(0, {
                shade: false,
                time: 0
            });
            $(this).css("background-color", '#5FB878');
            layui.use('table', function () {
                var element = layui.element;
                var table = layui.table;
                $(".syncing").show();
                $.ajax({
                    type: "POST",
                    url: "/admin/log/hsync?type=1",
                    data: {},
                    dataType: "json",
                    success: function (data) {
                        if(data==1){
                            layer.msg('同步终止');
                            $("#do_sync_btn").removeAttr("disabled");
                            $(".syncing").hide();
                            $(".done_sync").hide();
                            layer.close(loading)
                            element.progress('demo','0%');
                        }
                        if(data==2){
                            layer.msg('另一个同步进程正在执行，请稍后再试');
                            $("#do_sync_btn").removeAttr("disabled");
                            $(".syncing").hide();
                            $(".done_sync").hide();
                            layer.close(loading)
                            window.clearInterval(t2)
                            element.progress('demo','0%');

                        }
                    },
                    error: function (jqXHR) {
                        layer.msg('同步失败');
                        $("#do_sync_btn").removeAttr("disabled");
                        $(".syncing").hide();
                        $(".done_sync").hide();
                        layer.close(loading)
                    }
                });
                t2 = window.setInterval(function () {
                    var n = 0;
                    $.ajax({
                        type: "POST",
                        url: "/admin/log/getlong",
                        data: {},
                        dataType: "json",
                        async:false,
                        success: function (data) {
                            if (data[0]['progress'] >= 100) {
                                element.progress('demo','100%');
                                window.clearInterval(t2)
                                layer.msg('同步完成');
                                $("#do_sync_btn").removeAttr("disabled");
                                $("#do_sync_btn").css("background-color", "#F5F5F5");
                                $(".syncing").hide();
                                if(data[0]['count']==0){
                                    $(".done_sync").html("已完成，更新数据量:"+data[0]['count']+'（库内数据无需更新）')
                                }else{
                                    $(".done_sync").html("已完成，更新数据量:"+data[0]['count'])
                                }

                                $(".done_sync").show();
                                layer.close(loading);
                                table.reload('demo');

                            }else{
                                element.progress('demo', data[0]['progress'] + '%');
                                $(".syncing").html("正在同步"+data[0]['tablename_sync']+"站,请勿刷新页面......");
                            }
                        },
                        error: function (jqXHR) {
                        }
                    });

                }, 1000)


            })
        });
        $("#stop_sync_btn").click(function () {
            $(this).css("background-color", 'red');
            $("#do_sync_btn").css("background-color", '#F5F5F5');

            layui.use('table', function () {
                var element = layui.element;
                var table = layui.table;
                $.ajax({
                    type: "POST",
                    url: "/admin/log/stopt",
                    data: {},
                    dataType: "json",
                    success: function (data) {
                        var t1 = window.setTimeout(function () {
                            element.progress('demo', '0%');
                            layer.msg('同步中断');
                            window.clearInterval(t2)
                            $("#do_sync_btn").removeAttr("disabled");
                            $("#stop_sync_btn").css("background-color", '#F5F5F5');
                            $(".syncing").hide();
                            $(".done_sync").hide();
                            layer.close(loading);
                        }, 1000)

                    },
                    error: function (jqXHR) {

                        // layer.close(loading)
                    }
                });

            })
            // $(this).css("background-color",'#F5F5F5');

        });

        $(".search").click(function () {
            layui.use('table', function () {
                var table = layui.table;
                var laydate = layui.laydate;
                var form = layui.form;
                var layer = layui.layer;
                var errortime = $("input[name='error_time']").val();
                table.render({
                    elem: '#demo'
                    , height: 512
                    , url: '/admin/Index/getListData?errortime=' + errortime //数据接口
                    , page: true //开启分页
                    ,limits: [5, 10, 20, 40, 80, 200]
                    , cols: [[ //表头
                        {type: 'checkbox', fixed: 'left'}
                        , {field: 'id', title: 'ID', sort: true, fixed: 'left'}
                        , {
                            field: 'area', title: '国家', sort: true, templet: function (row) {
                                if (row.area == "usa") {
                                    return '美国'
                                } else if (row.area == "uk") {
                                    return '英国'
                                } else if (row.area == "de") {
                                    return '德国'
                                } else if (row.area == "jp") {
                                    return '日本'
                                } else if (row.area == "esp") {
                                    return '西班牙'
                                } else if (row.area == "it") {
                                    return '意大利'
                                } else if (row.area == "fr") {
                                    return '法国'
                                } else if (row.area == "mx") {
                                    return '墨西哥'
                                } else if (row.area == "ca") {
                                    return '加拿大'
                                }
                            }
                        }
                        , {field: 'error_code', title: '目标代码'}
                        , {field: 'error_time', title: '日期', sort: true}
                        , {field: 'data_count', title: '数据量', sort: true}
                        , {
                            field: 'o_type', title: '类型', templet: function (row) {
                                if (row.o_type == "0") {
                                    return '<span style="color: #409EFF">手动</span>'
                                } else if (row.o_type == "1") {
                                    return '<span style="color: #DAA520;">自动</span>'
                                }
                            }
                        }
                        , {field: 'remark', title: '备注',}
                        , {
                            field: 'is_success', title: '是否成功', templet: function (row) {
                                if (row.is_success == "0") {
                                    return '<span style="color: red;">失败</span>'
                                } else if (row.is_success == "1") {
                                    return '<span style="color: #3CB371;">成功</span>'
                                }
                            }
                        }
                    ]]

                });
            });

        });
    </script>
</literal>
</body>
</html>
