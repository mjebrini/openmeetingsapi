<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Service
 * @subpackage Openmeeting
 * @copyright  Copyright (c) 2011-2012 Mohammed Jebrini <me@jebrini.net> . (http://www.jebrini.net)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Openmeeting.php 1.0.0.0 2011-03-01 08:05:09 PM Mohammed Jebrini $
 */


/**
 * @category   Zend
 * @package    Zend_Service
 * @subpackage Openmeeting
 * @copyright  Copyright (c) 2011-2012 Mohammed Jebrini <me@jebrini.net> . (http://www.jebrini.net)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @author     Mohammed Jebrini <me@jebrini.net> Feb 28 2011 8:10 pm
 */

class Zend_Service_Openmeeting_Data_Error
{
	/**
	 * @var string
	 */
	private $errmessage = '' ;

	/**
	 * @var string
	 */
	private $errorId = null ;

	/**
	 * @var string
	 */
	private $errortype = '' ;

	/**
	 *
	 * Constructor
	 * @param array $options
	 */
	public function __construct(array $options = array())
	{
		if(is_array($options)){
			while(list($name,$value) = each($options)){
				$this->setOption($name, $value);
			}
		}
	}

	/**
	 *
	 * Set Error options
	 * @param string $name
	 * @param mixed $value
	 * @throws Zend_Service_Openmeeting_Exception
	 */
	public function setOption($name,$value)
	{
		if(array_key_exists($name,get_object_vars($this))){
			$this->{'set'.ucfirst($name)}($value);
		}
		else{
			throw new Zend_Service_Openmeeting_Exception("Incorrect option name : $name ", 0);
		}
	}

	/**
	 *
	 * get Error message
	 * @return string
	 */
	public function getErrorMessage(){
		return $this->errmessage;
	}

	/**
	 *
	 * set Error message
	 * @return Zend_Service_Openmeeting_Data_Error
	 */
	private function setErrmessage($message)
	{
		$this->errmessage = $message;
		return $this;
	}

	/**
	 *
	 * get Error Id
	 * @return integer
	 */
	public function getErrorId()
	{
		return $this->errorId;
	}

	/**
	 *
	 * set Error Id
	 * @return Zend_Service_Openmeeting_Data_Error
	 */
	public function setErrorId($error)
	{
		$this->errorId = $error;
		return $this;
	}

	/**
	 *
	 * get Error Type
	 * @return string
	 */
	public function getErrortype()
	{
		return $this->errortype;
	}
	
	/**
	 *
	 * set Error Type
	 * @return Zend_Service_Openmeeting_Data_Error
	 */
	public function setErrortype($type)
	{
		$this->errortype = $type;
		return $this;
	}

}
