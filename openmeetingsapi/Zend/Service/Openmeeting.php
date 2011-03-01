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
 * Openmeeting SOAP service implementation
 *
 * @category   Zend
 * @package    Zend_Service
 * @subpackage Openmeeting
 * @copyright  Copyright (c) 2011-2012 Mohammed Jebrini <me@jebrini.net> . (http://www.jebrini.net)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @author     Mohammed Jebrini <me@jebrini.net>
 */

class Zend_Service_Openmeeting
{

	const HTTPADAPTER 	= 'http';
	const HTTPSADAPTER 	= 'https';

	static $USER_SERVICE_PATH = '/services/UserService?wsdl';
	static $ROOM_SERVICE_PATH = '/services/RoomService?wsdl';
	static $FILE_SERVICE_PATH = '/services/FileService?wsdl';

	/**
	 *
	 * Transfer Protocol HTTP/HTTPS
	 * @var string
	 */
	protected $_protocol = self::HTTPADAPTER;

	/**
	 *
	 * Open meeting server address
	 * @var string
	 */
	protected $_server = 'localhost';

	/**
	 *
	 * Openmeeting server port number
	 * @var string
	 */
	protected $_port = '5080';

	/**
	 *
	 * Openmeeting server Uri segment
	 * @var string
	 */
	protected $_uri = 'openmeetings';

	/**
	 *
	 * internal character encoding
	 * @var string
	 */
	protected $_encoding = 'UTF-8';
	/**
	 *
	 * Openmeeting server admin login
	 * @var string
	 */
	protected $_login = 'admin';

	/**
	 *
	 * Openmeeting server admin password
	 * @var string
	 */
	protected $_password = '';

	/**
	 *
	 * Classname for Room
	 * @var string
	 */
	protected $_RoomClass = 'Zend_Service_Openmeeting_Room';

	/**
	 *
	 * Classname for User
	 * @var string
	 */
	protected $_UserClass = 'Zend_Service_Openmeeting_User';

	/**
	 *
	 * Classname for File
	 * @var string
	 */
	protected $_FileClass = 'Zend_Service_Openmeeting_File';

	/**
	 *
	 * User class instance
	 * @var Zend_Service_Openmeeting_User
	 */
	private $userClass = null ;

	/**
	 *
	 * Room class instance
	 * @var Zend_Service_Openmeeting_Room
	 */
	private $roomClass = null;

	/**
	 *
	 * File class instance
	 * @var Zend_Service_Openmeeting_File
	 */
	private $fileClass = null;

	/**
	 *
	 * Contructor
	 *
	 * @param array $options
	 * @throws Zend_Service_Openmeeting_Exception
	 * @return void
	 */
	public function __construct(array $options = array())
	{
		while(list($name,$value) = each($options)){
			$this->setOption($name,$value);
		}
	}

	/**
	 *
	 * Set an option ...
	 * @param string $name
	 * @param mixed $value
	 * @throws Zend_Service_Openmeeting_Exception
	 * @return void
	 */
	public function setOption($name , $value)
	{
		if(!is_string($name)){
			Zend_Service_Openmeeting::throwException("Incorrect option name : $name");
		}

		$name = strtolower($name);
		if(array_key_exists('_'.$name, get_object_vars($this)))
		{
			$this->{'set'.ucfirst($name)}($value);
		}
	}

	/**
	 *
	 * get Used Protocol
	 * @return string
	 */
	public function getProtocol()
	{
		return $this->_protocol;
	}

	/**
	 *
	 * set protocole HTTP/HTTPS
	 * @param string $protocol
	 * @return Zend_Service_Openmeeting
	 */
	public function setProtocol($protocol = Zend_Service_Openmeeting::HTTPADAPTER)
	{
		if(is_string($protocol))
		$this->_protocol = $protocol;
			
		return $this ;
	}


	/**
	 *
	 * get openmeeting server
	 * @return string
	 */
	public function getServer()
	{
		return $this->_server;
	}

	/**
	 *
	 * set open meeting server address
	 * @param string $server
	 * @return Zend_Service_Openmeeting
	 */
	public function setServer($server = 'localhost')
	{
		if(is_string($server))
		$this->_server = $server;
			
		return $this ;
	}

	/**
	 *
	 * get openmeeting server Uri
	 * @return string
	 */
	public function getUri()
	{
		return $this->_uri;
	}

	/**
	 *
	 * set uri segment for openmeeting application
	 * @param string $uri
	 * @return Zend_Service_Openmeeting
	 */
	public function setUri($uri = 'openmeetings')
	{
		if(is_string($uri))
		$this->_uri = $uri;
			
		return $this ;
	}

	/**
	 *
	 * get openmeeting server port number
	 * @return string
	 */
	public function getPort()
	{
		return $this->_port;
	}

	/**
	 *
	 * set openmeeting server port number
	 * @param string $port
	 * @return Zend_Service_Openmeeting
	 */
	public function setPort($port = '5080')
	{
		if(is_string($port))
		$this->_port = $port;
			
		return $this ;
	}

	/**
	 *
	 * get openmeeting server admin login
	 * @return string
	 */
	public function getUsername()
	{
		return $this->_login;
	}

	/**
	 *
	 * set openmeeting server admin login
	 * @param string $port
	 * @return Zend_Service_Openmeeting
	 */
	public function setUsername($username = 'admin')
	{
		if(is_string($username))
		$this->_login = $username;
			
		return $this ;
	}


	/**
	 *
	 * get openmeeting server admin password
	 * @return string
	 */
	public function getPassword()
	{
		return $this->_password;
	}

	/**
	 *
	 * set openmeeting server admin password
	 * @param string $port
	 * @return Zend_Service_Openmeeting
	 */
	public function setPassword($password)
	{
		if(is_string($password))
		$this->_password = $password;
			
		return $this ;
	}


	/**
	 *
	 * Throw as Exception
	 *
	 * @param string $msg Message for the exception
	 * @param Exception $e
	 * @throws Zend_Service_Exception
	 */
	public static function throwException($msg, Exception $e = null)
	{
		require_once "Zend/Service/Exception.php";
		throw new Zend_Service_Exception($msg,0, $e);
	}

	/**
	 * 
	 * get user service 
	 * 
	 * @return Zend_Service_Openmeeting_User
	 */
	public function getUserService()
	{
		if(null === $this->userClass){
			$wsdl = $this->getProtocol().'://'.$this->getServer().":".$this->getPort()."/".$this->getUri().self::$USER_SERVICE_PATH;
			$options = array('encoding'=> $this->_encoding);
			$this->userClass =  new $this->_UserClass(new Zend_Soap_Client($wsdl,$options));
		}
		return $this->userClass;
	}

	/**
	 * 
	 * get Room service 
	 * 
	 * @return Zend_Service_Openmeeting_Room
	 */
	public function getRoomService()
	{
		if(null === $this->roomClass){
			$wsdl = $this->getProtocol().'://'.$this->getServer().":".$this->getPort()."/".$this->getUri().self::$ROOM_SERVICE_PATH;
			$options = array('encoding'=> $this->_encoding );
			$this->roomClass =  new $this->_RoomClass(new Zend_Soap_Client($wsdl,$options));
		}
		return $this->roomClass;
	}

	/**
	 * 
	 * get File service 
	 * 
	 * @return Zend_Service_Openmeeting_File
	 */
	public function getFileService()
	{
		if(null === $this->fileClass){
			$wsdl = $this->getProtocol().'://'.$this->getServer().":".$this->getPort()."/".$this->getUri().self::$FILE_SERVICE_PATH;
			$options = array('encoding'=> $this->_encoding);
			$this->fileClass =  new $this->_FileClass(new Zend_Soap_Client($wsdl,$options));
		}
		return $this->fileClass;
	}


}