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

class Zend_Service_Openmeeting_User
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
	 * get Session Information from openmeeting 
	 * @return Zend_Service_Openmeeting_Data_Sessio | False 
	 * @throws Zend_Soap_Client_Exception 
	 */
	public function getSession()
	{
		$client  = $this->getSoapClient();
		$session = $client->getSession();
		$options = get_object_vars($session->return);
		if(!$options){
			return false ;
		}
		return new Zend_Service_Openmeeting_Data_Session($options);
	}

	/**
	 * 
	 * auth function, use the SID you get by getSession, return true means logged-in, if other its an Error
	 * @param integer $SID
	 * @param string $username
	 * @param string $userpass
	 * @return Zend_Service_Openmeeting_Data_Error on errror or True on success 
	 * @throws Zend_Soap_Client_Exception 
	 */
	public function loginUser($SID,$username,$userpass)
	{
		$client = $this->getSoapClient();
		$data = $client->loginUser(array('SID'=>$SID,'username'=>$username, 'userpass'=>$userpass));
		if($data->return > 0 ){
			return true ;
		}
		else{
			$errors =  $client->getErrorByCode(array('SID'=> $SID,'errorid'=>$data->return ,'language_id'=>1));
			return new Zend_Service_Openmeeting_Data_Error(get_object_vars($errors->return));
		}
	}

	/**
	 *
	 * Adds a new user like through the Frontend, but also does active the Account
	 * @param $SID
	 * @param $username
	 * @param $userpass
	 * @param $lastname
	 * @param $firstname
	 * @param $email
	 * @param $additionalname
	 * @param $street
	 * @param $zip
	 * @param $fax
	 * @param $states_id
	 * @param $town
	 * @param $language_id
	 * @param $baseURL
	 * @return integer 
	 * @return string 
	 * @throws Zend_Soap_Client_Exception
	 */
	public function addNewUser($SID,$username,$userpass,$lastname,
							   $firstname,$email,$additionalname,$street,
	     					   $zip,$fax,$states_id,$town,$language_id = 1,$baseURL =''){

		$data = array('SID'=>$SID,
					  'username'=>$username,
					  'userpass'=>$userpass,
					  'lastname'=>$lastname,
					  'firstname'=>$firstname,
					  'email'=>$email,
					  'additionalname'=>$additionalname,
					  'street'=>$street,
					  'zip'=>$zip,
					  'fax'=>$fax,
					  'states_id'=>$states_id,
					  'town'=>$town,
					  'language_id'=>$language_id,
					  'baseURL'=>$baseURL
		);
		
		$client = $this->getSoapClient();
		$res = $client->addNewUser($data);
		
		return $res->return ;
	}

	/**
	 *
	 * Sets the SessionObject for a certain SID, after setting this Session-Object
	 * you can use the SID + a RoomId to enter any Room.
	 * Session-Hashs are deleted 15 minutes after the creation if not used.
	 * @param String  $session Session id 
	 * @param String  $username  User name 
	 * @param String  $firstname First name  
	 * @param String  $lastname  Last Name 
	 * @param String  $profilePictureUrl  Profile picture url
	 * @param String  $email   user email
	 * @param String  $externalUserId
	 * @param String  $externalUserType
	 * @param String  $room_id  Room ID
	 * @param integer $becomeModeratorAsInt 0 means no Moderator, 1 means Moderator
	 * @param integer $showAudioVideoTestAsInt 0 means don't show Audio/Video Test, 1 means show Audio/Video
	 * 										   Test Application before the user is logged into the room
	 * @return string RoomHash 
	 */
	public function setUserObjectAndGenerateRoomHash($session,$username,$firstname,
													 $lastname,$profilePictureUrl,$email,
													 $externalUserId,$externalUserType,$room_id,
													 $becomeModeratorAsInt=0,$showAudioVideoTestAsInt=0){
	
		$data = array('SID'=>$session,
					  'username'=>$username,
					  'lastname'=>$lastname,
					  'firstname'=>$firstname,
					  'email'=>$email,
					  'profilePictureUrl'=>$profilePictureUrl,
					  'externalUserId'=>$externalUserId,
					  'externalUserType'=>$externalUserType,
					  'room_id'=>$room_id,
					  'becomeModeratorAsInt'=>$becomeModeratorAsInt,
					  'showAudioVideoTestAsInt'=>$showAudioVideoTestAsInt
		);
		
		$client = $this->getSoapClient();
		$res = $client->setUserObjectAndGenerateRoomHash($data);
		
		return $res->return ;											 	
		
	}

	/**
	 *
	 * Sets the SessionObject for a certain SID, after setting this Session-Object
	 * you can use the SID + a RoomId to enter any Room.
	 * Session-Hashs are deleted 15 minutes after the creation if not used.
	 * @param String  $session Session id 
	 * @param String  $username  User name 
	 * @param String  $firstname First name  
	 * @param String  $lastname  Last Name 
	 * @param String  $profilePictureUrl  Profile picture url
	 * @param String  $email   user email
	 * @param String  $externalUserId
	 * @param String  $externalUserType
	 * @param String  $room_id  Room ID
	 * @param integer $becomeModeratorAsInt 0 means no Moderator, 1 means Moderator
	 * @param integer $showAudioVideoTestAsInt 0 means don't show Audio/Video Test, 1 means show Audio/Video
	 * 										   Test Application before the user is logged into the room
	 * @return string RoomHash 
	 */
	public function setUserObjectAndGenerateRoomHashByURL($session,$username,$firstname,
													 $lastname,$profilePictureUrl,$email,
													 $externalUserId,$externalUserType,$room_id,
													 $becomeModeratorAsInt=0,$showAudioVideoTestAsInt=0){
	
		$data = array('SID'=>$session,
					  'username'=>$username,
					  'lastname'=>$lastname,
					  'firstname'=>$firstname,
					  'email'=>$email,
					  'profilePictureUrl'=>$profilePictureUrl,
					  'externalUserId'=>$externalUserId,
					  'externalUserType'=>$externalUserType,
					  'room_id'=>$room_id,
					  'becomeModeratorAsInt'=>$becomeModeratorAsInt,
					  'showAudioVideoTestAsInt'=>$showAudioVideoTestAsInt
		);
			
		$client = $this->getSoapClient();
		$res = $client->setUserObjectAndGenerateRoomHashByURL($data);
		
		return $res->return ;											 	
		
	}
	
	/**
	 * 
	 * sets the SessionObject for a certain SID, after setting this Session-Object you can use the SID and directly login into the dashboard
	 * @param string $session
	 * @param string $username  username 
	 * @param string $firstname  first name 
	 * @param string $lastname  last name 
	 * @param string $profilePictureUrl profile url addres
	 * @param string $email  user email
	 * @param string $externalUserId  
	 * @param string $externalUserType  like 'Moodle' 
	 * @return string Hash
	 * @throws Zend_Soap_Client_Exception
	 */
	public function setUserObjectMainLandingZone($session,$username,$firstname,
												 $lastname,$profilePictureUrl,$email,
												 $externalUserId,$externalUserType){
		$data = array('SID'=>$session,
					  'username'=>$username,
					  'lastname'=>$lastname,
					  'firstname'=>$firstname,
					  'email'=>$email,
					  'profilePictureUrl'=>$profilePictureUrl,
					  'externalUserId'=>$externalUserId,
					  'externalUserType'=>$externalUserType,
					  'room_id'=>$room_id,
					  'becomeModeratorAsInt'=>$becomeModeratorAsInt,
					  'showAudioVideoTestAsInt'=>$showAudioVideoTestAsInt
		);
			
		$client = $this->getSoapClient();
		$res = $client->setUserObjectMainLandingZone($data);
		
		return $res->return ;										 	
	}
	
	
	/**
	 *  set user and nick name of user for room 
	 *
	 * @param string $session
	 * @param string $username  username 
	 * @param string $firstname  first name 
	 * @param string $lastname  last name 
	 * @param string $profilePictureUrl profile url addres
	 * @param string $email  user email
	 * @param string $externalUserId  
	 * @param string $externalUserType  like 'Moodle' 
	 * @return string 
	 * @throws Zend_Soap_Client_Exception
	 */
	public function setUserAndNickName($session,$username,$firstname,
									   $lastname,$profilePictureUrl,$email,
									   $externalUserId,$externalUserType,$room_id,
									   $becomeModeratorAsInt=0,$showAudioVideoTestAsInt=0,$showNickNameDialogAsInt=0){
		$data = array('SID'=>$session,
					  'username'=>$username,
					  'lastname'=>$lastname,
					  'firstname'=>$firstname,
					  'email'=>$email,
					  'profilePictureUrl'=>$profilePictureUrl,
					  'externalUserId'=>$externalUserId,
					  'externalUserType'=>$externalUserType,
					  'room_id'=>$room_id,
					  'becomeModeratorAsInt'=>$becomeModeratorAsInt,
					  'showAudioVideoTestAsInt'=>$showAudioVideoTestAsInt,
					  'showNickNameDialogAsInt'=>$showNickNameDialogAsInt
		);
			
		$client = $this->getSoapClient();
		$res = $client->setUserAndNickName($data);
		
		return $res->return ;										 	
	}
	
	/**
	 *  
	 *  Use this method to access a Recording instead of Room
	 *  
	 * @param string $session
	 * @param string $username  username 
	 * @param string $firstname  first name 
	 * @param string $lastname  last name 
	 * @param string $profilePictureUrl profile url addres
	 * @param string $email  user email
	 * @param string $externalUserId  
	 * @param string $externalUserType  like 'Moodle' 
	 * @return string RecordingHash
	 * @throws Zend_Soap_Client_Exception
	 */
	public function setUserObjectAndGenerateRecordingHashByURL($session,$username,$firstname,
									   $lastname,$externalUserId,$externalUserType,$recording_id){
		$data = array('SID'=>$session,
					  'username'=>$username,
					  'lastname'=>$lastname,
					  'firstname'=>$firstname,
					  'externalUserId'=>$externalUserId,
					  'externalUserType'=>$externalUserType,
					  'recording_id'=>$recording_id
		);
			
		$client = $this->getSoapClient();
		$res = $client->setUserObjectAndGenerateRecordingHashByURL($data);
		
		return $res->return ;										 	
	}
}