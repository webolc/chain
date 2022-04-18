<?php
use chain33\phpsdk\ChainClient;

class client{
    /**
     * 客户端
     * @var unknown
     */
    private $client;
    
    /**
     * 初始化
     */
    public function __construct(){
        $this->client = new ChainClient();
    }
    
    
    
    
    
    
    
    
    
    /**
     * 获取chain33SDK接口
     * @return unknown
     */
    public function chain33(){
        return $this->client;
    }
}



