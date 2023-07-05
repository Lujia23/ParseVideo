@include('public.head')
@include('public.header')
<div class="content">
    <div class="result_box">
        <p>请在浏览器打开本页面，不然点击下载会出问题，请不要在QQ内点击下载</p>
        @if($data['data']['type'] == "video")
        <div class="video">
            <video width="100%" controls="controls">
                <source src="{{ $data['data']['url'] }}" type="video/mp4">
            </video>
        </div>
            <a href="/analysis/download/{{$id}}">
                <div class="layui-btn layui-btn-normal layui-btn-fluid" style="margin-bottom: 20px">下载视频</div>
            </a>
            <button type="button" class="layui-btn layui-btn-warm"
                    style="margin-left: 0"
                    lay-on="test-confirm">复制视频链接</button>
            <script>
                layui.use(function(){
                    var layer = layui.layer;
                    var util = layui.util;
                    // 事件
                    util.on('lay-on', {
                        "test-confirm": function(){
                            const loadMsg = layer.msg('已复制视频链接', {icon: 1});
                            // 模拟关闭
                            setTimeout(function(){
                                layer.close(loadMsg)
                            }, 800);
                            copyToClipboard('{{ $data['data']['url'] }}');
                        }
                    })
                });
            </script>
        @elseif($data['data']['type'] == "img")
            @php($i=0)
            @if(is_array($data['data']['url']))
                <div class="imagesBox">
                    @foreach($data['data']['url'] as $value)
                        <div class="imageList">
                            <div class="img">
                                <img src="{{$value}}" alt="">
                            </div>
                            <div class="btn">
                                <a href="/analysis/download/{{$id}}?type=img&no={{$i}}">
                                    <div class="layui-btn layui-btn-normal layui-btn-fluid" style="margin-bottom: 20px">下载图片(已去水印)</div>
                                </a>
                            </div>
                        </div>
                        @php($i++)
                    @endforeach
                </div>
            @endif
        @endif
    </div>
</div>
<script>
    function copyToClipboard(textToCopy) {
        // navigator clipboard 需要https等安全上下文
        if (navigator.clipboard && window.isSecureContext) {
            // navigator clipboard 向剪贴板写文本
            return navigator.clipboard.writeText(textToCopy);
        } else {
            // 创建text area
            let textArea = document.createElement("textarea");
            textArea.value = textToCopy;
            // 使text area不在viewport，同时设置不可见
            textArea.style.position = "absolute";
            textArea.style.opacity = 0;
            textArea.style.left = "-999999px";
            textArea.style.top = "-999999px";
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            return new Promise((res, rej) => {
                // 执行复制命令并移除文本框
                document.execCommand('copy') ? res() : rej();
                textArea.remove();
            });
        }
    }
</script>
