<?php

namespace App\Services;

use App\Services\Public\ParseService;

class ParseShortVideo extends ParseService
{
    public function __construct()
    {
        $this->apiUrl = "https://api.cooluc.com/";
    }

    public function format()
    {

        $json['err'] = $this->result['success'] ? 0 : 1;
        $json['msg'] = $this->result['msg'];

        $json['url'] = $this->apiParameter['url'];
        $json['data']['title'] = $this->result['nickname'] ?? false; // 视频题目
        $json['data']['desc'] = $this->result['desc'] ?? false; // 视频介绍
        $json['data']['cover'] = $this->result['cover'] ?? false; // 视频封面
        $json['data']['audio'] = $this->result['audio'] ?? false; // 视频封面


        if (isset($this->result['images']))
        {
            $json['data']['type'] = "img";
            $json['data']['url'] = $this->result['images'] ?? false; // 视频播放链接
        }else{
            $json['data']['type'] = "video";
            $json['data']['url'] = $this->result['video'] ?? false; // 视频播放链接
        }


        $this->result = $json;

        return $this;
    }

    public function get(): array
    {
        return $this->result;
    }
}
