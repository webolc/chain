<?php
namespace webolc\chain;

use webolc\chain\rsa\AddressCodec;
use webolc\chain\rsa\PrivateKey;

class GenerateKey
{
    static $_private_key;

    /**
     * 生成私钥
     * @return String
     * @author zhaeng <zhangf@disanbo.com>
     */
    public static function privateKey()
    {
        $PrivateKey = new PrivateKey();
        self::$_private_key = $PrivateKey->getPrivateKey();
        return self::$_private_key;
    }

    /**
     * 获取公钥
     * @return String
     * @author zhaeng <zhangf@disanbo.com>
     */
    public static function publicKey()
    {
        return self::_publicKey(self::$_private_key);
    }

    /**
     * 从私钥中导出地址
     * @param $private_key
     * @return String
     * @author zhaeng <zhangf@disanbo.com>
     */
    public static function getAddress($private_key)
    {
        return self::_publicKey($private_key);
    }

    /**
     * 通过私钥获取公钥
     * @param $private_key
     * @return String
     * @author zhaeng <zhangf@disanbo.com>
     */
    private static function _publicKey($private_key)
    {
        $PrivateKey = new PrivateKey($private_key);
        $point = $PrivateKey->getPubKeyPoints();
        $compressedPublicKey = AddressCodec::Compress($point);
        $hash = AddressCodec::Hash($compressedPublicKey);
        return AddressCodec::Encode($hash);
    }

}