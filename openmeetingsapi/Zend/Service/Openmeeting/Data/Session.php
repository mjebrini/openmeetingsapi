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
 * @author     Mohammed Jebrini <me@jebrini.net>
 */

class Zend_Service_Openmeeting_Data_Session
{

	/**
	 * 
	 * @var array
	 */
	private $session = array('id'=> null ,
							 'language_id'=> '' ,
							 'organization_id'=> '' ,
							 'refresh_time'=> '' ,
							 'sessionXml'=> '' ,
							 'session_id'=> null ,
							 'starttermin_time'=> '' ,
							 'storePermanent'=> '' ,
							 'user_id'=> '' );
	/**
	 * 
	 * Constructor 
	 * @param array $options
	 * @throws Zend_Service_Openmeeting_Exception
	 */
	public  function __construct(array $options = array())
	{
		if(is_array($options)){
			while(list($name,$value) = each($options)){
				$this->setOption($name,$value);
			}
		}
	}

	/**
	 * 
	 * set Option by name and value 
	 * @param string $name
	 * @param mixed $value
	 * @throws Zend_Service_Openmeeting_Exception
	 * @return void 
	 */
	public function setOption($name,$value)
	{
		if(array_key_exists($name,$this->session)){
			$this->session[$name] = $value ;
		}
		else{
			throw new Zend_Service_Openmeeting_Exception("Incorrect option name : $name ", 0);
		}
	}

	/**
	 * 
	 * get private properties 
	 * @param string $name
	 * @throws Zend_Service_Openmeeting_Exception
	 */
	public function __get($name)
	{
		if(array_key_exists($name, $this->session)){
			return $this->session[$name];
		}else {
			throw new Zend_Service_Openmeeting_Exception(" Incorrect options name : $name ", 0);
		}
	}

	/**
	 * 
	 * get Session Id 
	 * @return string if it exist | null if not exist 
	 */
	public function getSessionId()
	{
		return $this->session_id;
	}
	
	/**
	 * 
	 * get Language Id 
	 * @return integer 
	 */
	public function getLanguageId()
	{
		return $this->language_id;
	}
	
	/**
	 * 
	 * get Session XML 
	 * @return string 
	 */
	public function getSessionXml()
	{
		return $this->sessionXml;
	}
	
	/**
	 *  toString convert 
	 * @return string session id 
	 */
	public function __toString()
	{
		return $this->session_id;
	}
}