<?php
/**
 * Created by zkeys
 * Author Kaneki <zhangkaneki@gmail.com>
 * Date: 2020/10/15
 * Time: 4:21 下午
 */
namespace core;

class Response
{
    protected $headers = [];
    protected $content = '';
    protected $code = 200;

    public function sendContent()
    {
        echo $this->content;
    }

    public function sendHeaders()
    {
        foreach ($this->headers as $key => $header)
            header($key . ': ' . $header);

    }

    protected function initBaseHeaders()
    {
        $this->headers['Date'] = date();
        $this->headers['Cache-Control'] = 'no-cache, private';
    }

    public function send()
    {
        $this->sendHeaders();
        $this->sendContent();
        return $this;
    }

    public function setContent($content)
    {

        if (is_array($content))
            $content = json_encode($content);

        $this->content = $content;
        return $this;
    }


    public function getContent()
    {
        return $this->content;
    }

    // 获取状态码
    public function getStatusCode()
    {
        return $this->code;
    }

    public function setCode(int $code)
    {
        $this->code = $code;
        return $this;
    }
}