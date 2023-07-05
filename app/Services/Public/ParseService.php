<?php

namespace App\Services\Public;

use Illuminate\Support\Facades\Http;

abstract class ParseService
{
    // api接口
    public string $apiUrl;
    // api请求的参数
    public array $apiParameter = [];
    // 解析结果
    public array $result;

    /**
     * 由于不同接口的json格式不一样
     * 子类可以通过实现format方法对json格式进行统一化
     *
     * @return void
     */
    abstract public function format();

    /**
     * 获取返回的解析结果
     *
     * @return array
     */
    abstract public function get();

    /**
     * 设置要解析的URL链接，以及要请求的参数
     *
     * @param array $parameter
     * @return bool|$this
     */
    public function set(array $parameter): bool|static
    {
        $this->apiParameter = $parameter;
        return $this;
    }

    /**
     * 遍历参数数组，生成要请求的参数
     *
     * @param array $parameter
     * @return bool|string
     */
    public function generateRequestParameters(array $parameter): bool|string
    {
        $urlRequest = '';
        if (!str_ends_with($this->apiUrl, "&"))
            $urlRequest = "?";

        if (count($parameter) > 0)
        {
            foreach ($parameter as $name => $value)
            {
                $urlRequest = $urlRequest . $name . "=" . $value . "&";
            }
            return $urlRequest;
        }
        return false;
    }

    /**
     * 拼接api接口和参数
     *
     * @return string
     */
    public function generateRequestUrl(): string
    {
        if ($urlRequset = $this->generateRequestParameters($this->apiParameter))
        {
            return $this->apiUrl = $this->apiUrl . $urlRequset;
        }

        return $this->apiUrl;
    }

    /**
     * 向接口发送请求
     * 如果请求返回的是json数据，将它转换为数组否则按错误处理
     *
     * @return bool|$this
     */
    public function send(): bool|static
    {
        if ($this->generateRequestUrl())
        {
            // 向接口发送请求并转换json数据
            $analysis_results = json_decode(Http::get($this->apiUrl)->body(), 1);
            // 判断请求返回的结果是否非json数据
            if (json_last_error() == JSON_ERROR_NONE)
            {
                $this->result = $analysis_results;
            }

            return $this;
        }

        return false;
    }


}
