<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use function PHPUnit\Framework\isJson;

class Analysis
{
    public function index(Request $request, Http $http)
    {
        if ($request->method() == 'POST')
        {
            // 视频解析api接口
            $api = Config('api')['api'];
            // 要解析的分享视频口令
            $share = $request->post('url');
            // 解析视频分享链接
            if (preg_match('/https?:\/\/(\w+\.)*\w+(\/[\w-]+)*\/?/', $share , $url))
            {
                // 获取一级域名
                preg_match('/([\d\w]+\.\w+)\//', $url[0], $result);
                // 判断是否有支持的api
                if (isset($api[$result[1]]))
                {
                    // 快手电脑域名转手机域名
                    if ($result[1] == "kuaishou.com")
                        $url[0] = str_replace("www.kuaishou.com","v.kuaishou.com",$url[0]);

                    file_put_contents('1.txt', $url[0]);

                    // 判断数据库中有没有这个视频的解析记录
                    if (DB::table('analysis')->where('url', $url[0])->exists())
                    {
                        $data = DB::table('analysis')->where('url', $url[0])->get()->map(function ($value) {
                            return (array)$value;
                        })->toArray();

                        if ($data[0]['effective'] > time())
                        {
                            DB::table('analysis')->where('url', $url[0])->delete();
                        }else{
                            return json_encode([
                                "success" => true,
                                'msg' => $data[0]['id']
                            ]);
                        }
                    }

                    $parse = app($api[$result[1]]['provider']);

                    $parse->set([
                        'url' => $url[0]
                    ]);

                    // 发送解析请求
                    $analysis_results = $parse->send()->format()->get();

                    if (!$analysis_results['err'])
                    {
                        // 生成ID
                        $id = time() . rand(100,999);
                        // 缓存到数据库
                        DB::table('analysis')->insert([
                            'id' => $id,
                            'url' => $url[0],
                            'result' => serialize($analysis_results),
                            'time' => time(),
                            'effective' => time() + 360,
                            'ip' => $request->getClientIp()
                        ]);
                        return json_encode([
                            "success" => true,
                            'msg' => $id
                        ]);
                    }else{
                        return json_encode([
                            "success" => false,
                            'msg' => '解析失败：接口异常，建议尝试重试解析'
                        ]);
                    }
                }else{
                    return json_encode([
                        "success" => false,
                        'msg' => '不支持解析这个平台，请在首页选择对应的平台或检查链接是否正确'
                    ]);
                }
            }else{
                return json_encode([
                    "success" => false,
                    'msg' => '链接参数填写有误'
                ]);
            }
        }
        return "非法访问";
    }

    public function result(Request $request)
    {
        // 解析记录的ID号
        $id = $request->route('id');
        // 判断数据库中是否有该记录
        if (DB::table('analysis')->where('id', $id)->exists())
        {
            // 获取视频数据
            $data = DB::table('analysis')->where('id', $id)->value('result');
            $data = (array)unserialize($data);

            return view('result', [
                'data' => $data,
                'id' => $id,
                'title' => '视频/图片解析结果',
            ]);
        }else{
            return "数据不存在！！！";
        }

    }

    public function download(Request $request)
    {
        // 解析记录的ID号
        $id = $request->route('id');
        $type = $request->get('type');
        $no = $request->get('no');

        if (DB::table('analysis')->where('id', $id)->exists())
        {
            $url = $data = DB::table('analysis')->where('id', $id)->value('result');
            $url = (array)unserialize($url);

            if ($type == "img")
            {
                if (isset($url['data']['url'][$no]))
                    return response()->streamDownload(function () use($no, $url){
                            echo file_get_contents($url['data']['url'][$no]);
                    }, 'video-'. time() . '.png' ,[
                        'Content-Type: image/png'
                        ]);
                return "下载失败";
            }else{
                if (isset($url['data']['url']))
                    return response()->streamDownload(function () use($url){
                            echo file_get_contents($url['data']['url']);
                    }, 'video-'. time() . '.mp4', [
                        'Content-Type: video/mp4'
                    ]);
                return "下载失败";
            }
        }
    }
}
