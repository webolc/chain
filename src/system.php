<?php
namespace webolc\chain;

trait system{
      
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
     * 查询节点状态
     * @return array
     */
    public static function getNetInfo()
    {
        $method = 'Chain33.GetNetInfo';
        return $this->sendRequest($method);
    }
    
    /**
     * 查询时间状态
     * @return array
     */
    public static function getTimeStatus()
    {
        $method = 'Chain33.GetTimeStatus';
        return $this->sendRequest($method);
    }
    
    /**
     * 查询同步状态
     * @return array
     */
    public static function isSync()
    {
        $method = 'Chain33.IsSync';
        return $this->sendRequest($method);
    }
}