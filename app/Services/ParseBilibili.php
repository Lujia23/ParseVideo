<?php

namespace App\Services;

use App\Services\Public\ParseService;

class ParseBilibili extends ParseService
{
    public function __construct()
    {
        $this->apiUrl = "https://www.mxnzp.com/api/bilibili/video?app_id="
            . Config('key')['app_id'] .'&app_secret='
            . Config('key')['app_secret'] . "&";
    }

    public function set(array $parameter): bool|static
    {
        $this->apiParameter = $parameter;
        $this->apiParameter['url'] = base64_encode($this->apiParameter['url']);

        return $this;
    }

    public function format()
    {

        $json['err'] = $this->result['code'] ? 0 : 1;
        $json['msg'] = $this->result['msg'];
        $json['url'] = $this->apiParameter['url'];
        $json['data']['type'] = "video"; // 视频类型
        $json['data']['title'] = $this->result['data']['title'] ?? false; // 视频题目
        $json['data']['desc'] = $this->result['data'][0]['desc'] ?? false; // 视频介绍
        $json['data']['cover'] = $this->result['data']['cover'] ?? false; // 视频封面
        $json['data']['url'] = $this->result['data']['list'][0]['url'] ?? false; // 视频播放链接
        $json['data']['audio'] = false; // 视频音频链接

        $this->result = $json;

        return $this;
    }

    public function get(): array
    {
        return $this->result;
    }

}
