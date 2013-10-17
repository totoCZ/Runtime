<?php
/**
 * Stachl
 *
 * LICENSE
 *
 * This source file is subject to the CC-GNU GPL license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://creativecommons.org/licenses/GPL/2.0/
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@stachl.me so we can send you a copy immediately.
 *
 * @category   Stachl
 * @package    Stachl_Ping
 * @copyright  Copyright (c) 2010 Stachl.me (http://www.stachl.me)
 * @license    http://creativecommons.org/licenses/GPL/2.0/     CC-GNU GPL License
 */

/**
 * modded by Toto
 * @category   Stachl
 * @package    Stachl
 * @author     Thomas Stachl <thomas@stachl.me>
 * @copyright  Copyright (c) 2010 Stachl.me (http://www.stachl.me)
 * @license    http://creativecommons.org/licenses/GPL/2.0/     CC-GNU GPL License
 */

class Stachl_Ping
{
	
	/**
	 * $_host - Host value
	 * 
	 * @var string
	 */
	protected $_host;
	
	/**
	 * $_packets - Packets value
	 * 
	 * @var int
	 */
	protected $_packets;
	
	/**
	 * $_min - Min value
	 * 
	 * @var float
	 */
	protected $_min;
	
	/**
	 * $_avg - Avg value
	 * 
	 * @var float
	 */
	protected $_avg;
	
	/**
	 * $_max - Max value
	 * 
	 * @var float
	 */
	protected $_max;
	
	/**
	 * $_mdev - Mdev value
	 * 
	 * @var float
	 */
	protected $_mdev;
	
	/**
	 * $_result - holds the results of the ping
	 * 
	 * @var string
	 */
	protected $_result;
	
	/**
	 * __construct() - Sets the configuration options
	 * 
	 * @param  string  $host     hostname or IP address to ping
	 * @param  integer $packets  to be sent to the host, default is 5
	 * @return void
	 */
	public function __construct($host, $packets = 5)
	{
		$this->_host = $host;
		$this->_packets = $packets;
	}
	
	
	/**
	 * Returns the shortest roundtrip time
	 * 
	 * @return float
	 */
	public function getMin()
	{
		return $this->_min;
	}

	/**
	 * Returns the average roundtrip time
	 * 
	 * @return float
	 */
	public function getAvg()
	{
		return $this->_avg;
	}

	/**
	 * Returns the maximum roundtrip time
	 * 
	 * @return float
	 */
	public function getMax()
	{
		return $this->_max;
	}

	/**
	 * Returns the standard deviation of the round-trip time
	 * 
	 * @return float
	 */
	public function getMdev()
	{
		return $this->_mdev;
	}

	
	/**
	 * ping() - trys to ping the host
	 * 
	 * @return boolean
	 */
	public function ping()
	{
		if ($this->_ping()) {
			$this->_parseResult();
			return $this;
		}
		return false;
	}
	
	/**
	 * _ping() - does the actual pinging and saves the results, returns false if the network is unreachable or the result is empty
	 * 
	 * @return boolean
	 */
	protected function _ping()
	{
		$this->_result = exec('ping -c' . $this->_packets . ' ' . $this->_host);
		if (empty($this->_result) || ($this->_result == 'connect: Network is unreachable')) {
			return false;
		}
		return true;
	}
	
	/**
	 * _parseResult - parses the result and sets the properties
	 * 
	 * @return void
	 */
	protected function _parseResult()
	{
		preg_match('/=[[:space:]](.*)\/(.*)\/(.*)\/(.*)[[:space:]]/i', $this->_result, $matches);
		$this->_min  = (float)$matches[1];
		$this->_avg  = (float)$matches[2];
		$this->_max  = (float)$matches[3];
		$this->_mdev = (float)$matches[4];
	}
}