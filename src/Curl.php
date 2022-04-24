<?php
namespace webolc\chain;

/**
 * curl助手模型
 */
class curl
{
	public $data = null;
	public $timeout = null;
	public $referer = null;
	public $agent = null;
	public $header = null;
	public $proxy = null;

	public $httpType = 'http';
	public $httpInfo = null;
	public $url;
	public $dataType = 'json';

	public function __construct($config)
    {
        if (!isset($config['host']) || !isset($config['port'])) {
            throw new \Exception('must config host and port');
        }
        $this->url = $config['host'] . ':' . $config['port'];
        $this->timeout = $config['timeout'] ?? 60;
    }

    /**
	 * get请求
	 * @param string $httpType 访问类型 http/https
	 * @return string $result 返回内容
	 */
	public function get($httpType = 'http')
	{
		$this->httpType = $httpType;
		return $this->_httpRequest('GET');
	}

	/**
	 * post请求
	 * @param string $httpType 访问类型 http/https
	 * @return string | array $result 返回内容
	 */
	public function post($httpType = 'http')
	{
		$this->httpType = $httpType;
		return $this->_httpRequest('POST');
	}

	/**
	 * curl 请求实例
	 * @param string $method 请求方式
	 * @return string $result 返回内容
	 */
	private function _httpRequest($method)
	{
		$timeout = isset($this->timeout) ? $this->timeout : 15;
		$referer = isset($this->referer) ? $this->referer : '';
		$header = isset($this->header) ? $this->header : ['Connection: Keep-Alive'];
		$agent = isset($this->agent) ? $this->agent : 'Curl_Service_Requester';
		if ($method == 'GET' && count($this->data)) {
            $this->url = $this->url . '?' . http_build_query($this->data);
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->url);
		switch (strtolower($method)) {
			case 'get':
				curl_setopt($ch, CURLOPT_HTTPGET, TRUE);
				break;
			case 'post':
				$this->data = is_string($this->data) ? $this->data : http_build_query($this->data);
				curl_setopt($ch, CURLOPT_POST, TRUE);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $this->data);
				break;
			default:
				break;
		}		
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_USERAGENT, $agent);
		curl_setopt($ch, CURLOPT_REFERER, $referer);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		if (strtolower($this->httpType) == 'https') {
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		}
		if (isset($this->proxy)) {
			curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, TRUE);
			curl_setopt($ch, CURLOPT_PROXY, $this->proxy);
		}
		$result = curl_exec($ch);
		$this->httpInfo = curl_getinfo($ch);
		curl_close($ch);
		if (strtolower($this->dataType) == 'json') {
			$result = ($arr = json_decode($result, true)) ? $arr : $result;
		}
		return $result;
	}

    /**
     * 获取请求头信息
     * @return null
     */
	public function getInfo()
	{
		return $this->httpInfo;
	}

    /**
     * 设置发送数据
     * @param array $data
     */
	public function setData($data = [])
	{
		$this->data = $data;
	}

    /**
     * 设置超时时间
     * @param string $timeout
     */
	public function setTimeout($timeout = '10')
	{
		$this->timeout = $timeout;
	}

    /**
     * 设置来源信息referer
     * @param string $referer
     */
	public function setReferer($referer = '')
	{
		$this->referer = $referer;
	}

    /**
     * 设置agent
     * @param string $agent
     */
	public function setAgent($agent = 'Service_Requester')
	{
		$this->agent = $agent;
	}

    /**
     * 设置header
     * @param array $header
     */
	public function setHeader($header = ['Connection: Keep-Alive'])
	{
		$this->header = $header;
	}

    /**
     * 设置代理
     * @param null $ip
     */
	public function setProxy($ip = null)
	{
		$this->proxy = $ip;
	}
}
