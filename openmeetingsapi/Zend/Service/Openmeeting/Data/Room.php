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

class Zend_Service_Openmeeting_Data_Room
{

	static $CONFERENCE_ROOM = 1;
	static $AUDIENCE_ROOM = 2 ;
	static $RESTRICTED = 3 ;
	static $INTERVIEW = 4 ;
	/**
	 * @example Boolean $room['isModeratedRoom'] Users have to wait untill a Moderator arrives.  
	 * @var array
	 */
	private $room = array(   'rooms_id'=> null ,
							 'allowUserQuestions'=>null, 
							 'conferencePin' =>null, 
	 						 'externalRoomId' =>null,
							 'externalRoomType' =>null,
							 'name'=> null,
							 'comment'=> null ,
							 'roomtype'=> null ,
							 'starttime'=> null ,
							 'updatetime'=> null ,
							 'deleted'=> null ,
							 'ispublic'=> null ,
							 'numberOfPartizipants'=> null ,
							 'appointment'=> null,
							 'isModeratedRoom'=> null , 
							 'currentusers'=> null ,
							 'isDemoRoom'=> null ,
							 'demoTime'=> null,
							 'redirectURL'=>null,
							 'sipNumber' => null,
							 'isAudioOnly'=> null,
							 'isClosed'=> null);
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
		if(array_key_exists($name,$this->room)){
			$this->room[$name] = $value ;
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
		if(array_key_exists($name, $this->room)){
			return $this->room[$name];
		}else {
			throw new Zend_Service_Openmeeting_Exception(" Incorrect options name : $name ", 0);
		}
	}

	/**
	 *
	 * set private properties
	 * @param string $name
	 * @param string $value
	 * @throws Zend_Service_Openmeeting_Exception
	 */
	public function __set($name,$value)
	{
		if(array_key_exists($name, $this->room)){
			return $this->room[$name] = $value ;
		}else {
			throw new Zend_Service_Openmeeting_Exception(" Incorrect options name : $name ", 0);
		}
	}

	/**
	 *
	 * get Room's Id
	 * @return string if it exist | null if not exist
	 */
	public function getRoomId()
	{
		return $this->rooms_id;
	}

	/**
	 *
	 * get Room Name
	 * @return integer
	 */
	public function getRoomName()
	{
		return $this->name;
	}

	/**
	 *
	 * get Room Type
	 * @return string
	 */
	public function getRoomType()
	{
		return $this->roomtype;
	}

	/**
	 *  toString convert
	 * @return string session id
	 */
	public function __toString()
	{
		return $this->rooms_id;
	}

}