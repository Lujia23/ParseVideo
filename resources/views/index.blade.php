@include('public.head')
@include('public.header')
<div class="content">
    <div class="notice">
        <p style="color: #3F85FF">本工具开发者：吴禄嘉 (番趣网络科技)</p>
        <br>
        <p>使用教程如下：</p>
        <p style="color: #A5AAA3">把你想要保存的无水印视频，在视频APP或网页里点击分享，复制分享链接，进入本工具的首页面，把复制的分享链接粘贴到文本框中点击解析按钮即可。</p>
    </div>
    <div class="analysis_box">
        <div class="layui-form" style="margin-bottom: 20px">
            <textarea name="url" placeholder="请在这里粘贴你的视频分享链接或口令" class="layui-textarea"></textarea>
        </div>
        <button type="button"
                class="layui-btn layui-btn-normal layui-btn-fluid"
                lay-on="test-confirm">解析视频</button>
    </div>
    <div class="btnList">
        <b class="title">支持的平台</b>
        <div class="btn">
            <div class="btn_icon">
                <img src="{{ asset('img/logo2.png') }}" alt="">
            </div>
            <div class="btn_content">
                <b>哔哩哔哩</b>
                <p>一键保存哔哩哔哩无水印视频</p>
            </div>
        </div>
        <div class="btn">
            <div class="btn_icon">
                <img src="{{ asset('img/logo1.png') }}" alt="">
            </div>
            <div class="btn_content">
                <b>抖音 / 快手</b>
                <p>一键保存抖音 / 快手无水印视频</p>
            </div>
        </div>
    </div>
</div>
<script>
    layui.use(function(){
        var layer = layui.layer;
        var util = layui.util;
        // 事件
        util.on('lay-on', {
            "test-confirm": function(){
                layer.confirm('确定开始解析吗', {icon: 3}, function(){
                    var loadMsg = layer.msg('已向服务器提交解析请求', {icon: 1});
                    var loadIndex = layer.load(2);
                    // 模拟关闭
                    setTimeout(function(){
                        layer.close(loadMsg)
                    }, 800);
                    $.post("/analysis", {
                        url: $('textarea[name=url]').val(),
                        _token : "{{csrf_token()}}"
                    }, function (data) {
                        layer.close(loadIndex)
                        if (data.success === true) {
                            window.location.href = "/analysis/result/" + data.msg;
                        } else if (data.success === false) {
                            layer.msg(data.msg, {icon: 2});
                        }
                    }, "json");
                }, function(){
                    layer.msg('你取消了此操作~');
                });
            }
        })
    });
</script>
