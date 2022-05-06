<?php
namespace webolc\chain;

use webolc\chain\rsa\PrivateKey;
use webolc\chain\rsa\AddressCodec;

class client{
    /**
     * 配置数据
     * @var array
     */
    private $config = [
        'host' => '127.0.0.1',
        'port' => '8801',
        'timeout' => 60
    ];
    /**
     * 私钥
     * @var unknown
     */
    private static $private_key;
    
    /**
     * 初始化
     */
    public function __construct($config=[]){
        if ($config) $this->config = array_merge($this->config,$config);
    }
    
    /**
     * @param string $method
     * @param array $params
     * @return array
     */
    public function sendRequest(string $method,$params = [])
    {
        $id = time();
        try {
            $curl = new curl($this->config);
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
    /**
     * 获取私钥
     * @return String
     */
    public function getPrivateKey(){
        $PrivateKey = new PrivateKey();
        self::$private_key = $PrivateKey->getPrivateKey();
        return self::$private_key;
    }
    /**
     * 获取公钥
     * @return String
     */
    public static function getPublicKey()
    {
        $PrivateKey = new PrivateKey(self::$private_key);
        $point = $PrivateKey->getPubKeyPoints();
        return AddressCodec::Compress($point);
    }
    /**
     * 从私钥中导出地址
     * @param $private_key
     * @return String
     */
    public static function getAddress($private_key)
    {
        $PrivateKey = new PrivateKey($private_key);
        $point = $PrivateKey->getPubKeyPoints();
        $compressedPublicKey = AddressCodec::Compress($point);
        $hash = AddressCodec::Hash($compressedPublicKey);
        return AddressCodec::Encode($hash);
    }
}