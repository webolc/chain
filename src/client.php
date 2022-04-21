<?php
namespace webolc\chain;

class client{
    
    /**
     * 配置数据
     * @var array
     */
    protected $config = [
        'host' => '127.0.0.1',
        'port' => '8801',
        'timeout' => 60
    ];
    
    /**
     * 初始化
     */
    public function __construct($config=[]){
        if ($config) $this->config = array_merge($this->config,$config);
    }
    
    /**
     * 获取远程节点列表
     * @return array
     */
    public function getPeerInfo()
    {
        $method = 'Chain33.GetPeerInfo';
        return $this->sendRequest($method);
    }
    
    
    
    
    
    
    
    
    
    
    
    /**
     * @param string $method
     * @param array $params
     * @return array
     */
    private function sendRequest(string $method,$params = [])
    {
        $id = time();
        try {
            $curl = new Curl($this->config);
            $data = [
                'id' => $id,
                "jsonrpc" => "2.0",
                'method' => $method,
                'params' => empty($params) ? [] : [$params]
            ];
            
            $curl->setData(json_encode($data));
            $curl->setHeader(['Content-Type:application/json']);
            $res = $curl->post();
            $res = $res ? $res : ['id' => $id,'result' => [],'error' => 'request fail'];
        } catch (\Exception $e) {
            $res = ['id' => $id,'result' => [],'error' => $e->getMessage()];
        }
        unset($res['id']);
        return $res;
    }
}



