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

class Zend_Service_Openmeeting_File
{

	/**
	 * @var Zend_Soap_Client
	 */
	private $_soapCient = null ;

	/**
	 *
	 * Constructor
	 * @param Zend_Soap_Client $connection
	 * @throws Zend_Service_Openmeeting_Exception
	 */
	public function __construct(Zend_Soap_Client $connection){

		if($connection instanceof  Zend_Soap_Client){
			$this->_soapCient = $connection;
		}
		else{
			throw new Zend_Service_Openmeeting_Exception("Invalid SOAP Client ", 0);
		}
	}

	/**
	 *
	 * Get SOAP client object
	 * @return Zend_Soap_Client
	 */
	public function getSoapClient()
	{
		return $this->_soapCient;
	}

	/**
	 *
	 * Set SOAP client
	 * @param object $client
	 */
	public function setSoapClient(Zend_Soap_Client $client)
	{
		if($client instanceof Zend_Soap_Client){
			$this->_soapCient = $client;
		}
		return $this;
	}

	/**
	 *
	 * Returns a Library Object.
	 * @param string $session
	 * @param string $room_id
	 * @param string $moduleName
	 * @param string $parentFolder
	 * @return object
	 * @throws Zend_Soap_Client_Exception
	 */
	public function getListOfFiles($session,$room_id,$moduleName='videoconf1',$parentFolder=''){

		$data = array('SID'=>$session,
						  'moduleName'=>$moduleName,
						  'parentFolder'=>$parentFolder,   
						  'room_id'=>$room_id 
		);

		$client = $this->getSoapClient();
		$res = $client->getListOfFiles($data);

		return $res->return ;
	}

	/**
	 *
	 * Returns a std class object.
	 * @param string $session
	 * @param string $room_id
	 * @param string $fileName
	 * @param string $moduleName
	 * @param string $parentFolder
	 * @return boolean True|False 
	 * @throws Zend_Soap_Client_Exception
	 */
	public function deleteFile($session,$fileName,$room_id,$moduleName='videoconf1',$parentFolder=''){

		$data = array('SID'=>$session,
					  'fileName'=> $fileName,
						  'moduleName'=>$moduleName,
						  'parentFolder'=>$parentFolder,   
						  'room_id'=>$room_id 
		);

		$client = $this->getSoapClient();
		$res = $client->deleteFile($data);

		return (bool)$res->return ;
	}


}