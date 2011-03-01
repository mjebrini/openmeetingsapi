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

class Zend_Service_Openmeeting_Room
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
	 * Returns a array set of Rooms.
	 * @param string $session The SID of the User. This SID must be marked as Loggedin
	 * @param string $roomTypesId  1 : conference , 2 : Audience
	 * @return array of Zend_Service_Openmeeting_Data_Room
	 * @throws Zend_Soap_Client_Exception
	 */
	public function getRoomsPublic($session,$roomTypesId = 1 ){

		if($roomTypesId == null){
			$roomTypesId = Zend_Service_Openmeeting_Data_Room::$CONFERENCE_ROOM;
		}

		$data = array('SID'=>$session,
					  'roomtypes_id'=> $roomTypesId);

		$client = $this->getSoapClient();

		$res = $client->getRoomsPublic($data);
			
		$rooms = array();
		if(is_array($res->return)){
			foreach ($res->return as $room){
				$rooms[] = new Zend_Service_Openmeeting_Data_Room(get_object_vars($room));
			}
		}else if(is_object($res->return)){
			$rooms[] = new Zend_Service_Openmeeting_Data_Room(get_object_vars($res->return));
		}

		return $rooms ;
	}


	/**
	 *
	 * Returns Room Types array.
	 * @param string $session  The SID of the User. This SID must be marked as Loggedin
	 * @return array
	 * @throws Zend_Soap_Client_Exception
	 */
	public function getRoomTypes($session){

		$data = array('SID'=>$session);
		$client = $this->getSoapClient();
		$res = $client->getRoomTypes($data);
			
		$roomTypes = array();
		if(is_array($res->return)){
			return $res->return ;
		}
		else if(is_object($res->return)){
			$roomTypes[] =  $res->return;
		}

		return $roomTypes ;
	}

	/**
	 * 
	 * get room informations 
	 * @param string $session The SID of the User. This SID must be marked as Loggedin
	 * @param integer $id room id 
	 * @return Zend_Service_Openmeeting_Data_Room
	 */
	public function getRoomById($session,$id)
	{
		$data = array('SID'=>$session,'rooms_id'=>$id);
		$client = $this->getSoapClient();
		$res = $client->getRoomById($data);
		return new Zend_Service_Openmeeting_Data_Room(get_object_vars($res->return));
	}
	
	/**
	 * 
	 * get room informations 
	 * @param string $session
	 * @param integer $id
	 * @return Zend_Service_Openmeeting_Data_Room
	 */
	public function getRoomWithCurrentUsersById($session,$id)
	{
		$data = array('SID'=>$session,'rooms_id'=>$id);
		$client = $this->getSoapClient();
		$res = $client->getRoomWithCurrentUsersById($data);
		return new Zend_Service_Openmeeting_Data_Room(get_object_vars($res->return));
	}	
	
	/**
	 *
	 * Returns a array set of Rooms.
	 * @param string $session The SID of the User. This SID must be marked as Loggedin
	 * @param string $start The id you want to start
	 * @param int $max  The maximum you want to get
	 * @param string $orderby The column it will be ordered, You can use "name" as value for orderby or rooms_id
	 * @param boolean $asc Asc[true] or Desc[false] sort ordering
	 * @return array of Zend_Service_Openmeeting_Data_Room
	 * @throws Zend_Soap_Client_Exception
	 */
	public function getRooms($session,$start,$max ,$orderby='name',$asc = true){
 
		$data = array('SID'=>$session,
					  'start'=> $start,
					  'max'=> $max,
					  'orderby'=> $orderby,
					  'asc'=> $asc );

		$client = $this->getSoapClient();

		$res = $client->getRooms($data);
 
		$rooms = array();
		if(is_array($res->return->result)){
			foreach ($res->return->result as $room){
				$rooms[] = new Zend_Service_Openmeeting_Data_Room(get_object_vars($room));
			}
		}else if(is_object($res->return->result)){
			$rooms[] = new Zend_Service_Openmeeting_Data_Room(get_object_vars($res->return->result));
		}

		return $rooms ;
	}

	/**
	 *
	 * Returns a array set of Rooms with current users in the room.
	 * @param string $session The SID of the User. This SID must be marked as Loggedin
	 * @param string $start The id you want to start
	 * @param int $max  The maximum you want to get
	 * @param string $orderby The column it will be ordered, You can use "name" as value for orderby or rooms_id
	 * @param boolean $asc Asc[true] or Desc[false] sort ordering
	 * @return array of Zend_Service_Openmeeting_Data_Room
	 * @throws Zend_Soap_Client_Exception
	 */
	public function getRoomsWithCurrentUsers($session,$start,$max ,$orderby='name',$asc = true){
 
		$data = array('SID'=>$session,
					  'start'=> $start,
					  'max'=> $max,
					  'orderby'=> $orderby,
					  'asc'=> $asc );

		$client = $this->getSoapClient();

		$res = $client->getRoomsWithCurrentUsers($data);
			
		$rooms = array();
		if(is_array($res->return->result)){
			foreach ($res->return->result as $room){
				$rooms[] = new Zend_Service_Openmeeting_Data_Room(get_object_vars($room));
			}
		}else if(is_object($res->return->result)){
			$rooms[] = new Zend_Service_Openmeeting_Data_Room(get_object_vars($res->return->result));
		}

		return $rooms ;
	}

	/**
	 * 
	 * Create new room
	 * @param  string $session
	 * @param  Zend_Service_Openmeeting_Data_Room $room
	 * @return string RoomId
	 * @throws Zend_Soap_Client_Exception
	 */
	public function addRoomWithModeration($session,Zend_Service_Openmeeting_Data_Room $room)
	{
		$data = array('SID'=>$session,
					  'name'=> $room->name,
					  'roomtypes_id'=> $room->roomtype->roomtypes_id,
					  'comment'=> $room->comment,
					  'numberOfPartizipants'=> $room->numberOfPartizipants,
					  'ispublic'=> $room->ispublic,
					  'appointment'=> $room->appointment,
					  'isDemoRoom'=> $room->isDemoRoom,
					  'demoTime'=> $room->demoTime,
					  'isModeratedRoom'=> $room->isModeratedRoom );

		$client = $this->getSoapClient();
		$res = $client->addRoomWithModeration($data);
		return ($res->return > 0) ? $res->return : false ;
	}
	
	/**
	 * 
	 * Update Room Data 
	 * @param string $session
	 * @param Zend_Service_Openmeeting_Data_Room $room
	 * @return string 
	 * @throws Zend_Soap_Client_Exception 
	 */
	public function updateRoomWithModeration($session,Zend_Service_Openmeeting_Data_Room $room)
	{
		$data = array('SID'=>$session,
					  'name'=> $room->name,
					  'roomtypes_id'=> $room->roomtype->roomtypes_id,
					  'comment'=> $room->comment,
					  'numberOfPartizipants'=> $room->numberOfPartizipants,
					  'ispublic'=> $room->ispublic,
					  'appointment'=> $room->appointment,
					  'isDemoRoom'=> $room->isDemoRoom,
					  'demoTime'=> $room->demoTime,
					  'isModeratedRoom'=> $room->isModeratedRoom );
		
		$client = $this->getSoapClient();
		$res = $client->updateRoomWithModeration($data);
		return $res->result ;
	}

	
	/**
	 * 
	 * get room informations 
	 * @param string $session The SID of the User. This SID must be marked as Loggedin
	 * @param integer $id room id 
	 * @return Zend_Service_Openmeeting_Data_Room
	 */
	public function deleteRoom($session,$id)
	{
		$data = array('SID'=>$session,'rooms_id'=>$id);
		$client = $this->getSoapClient();
		$res = $client->deleteRoom($data);
		return $res->return ;
	}

	/**
	 * 
	 * Removes all Users from a Room
	 * @param string $sessiona admin user's SessionId
	 * @param integer $id room id 
	 * @return Boolean
	 */
	public function kickUser($session,$id)
	{
		$data = array('SID_Admin'=>$session,'rooms_id'=>$id);
		$client = $this->getSoapClient();
		$res = $client->kickUser($data);
		return (boolean)$res->return ;
	}
	
	/**
	 * 
	 * Update Room Data 
	 * @param string $session
	 * @param Zend_Service_Openmeeting_Data_Room $room
	 * @return string 
	 * @throws Zend_Soap_Client_Exception 
	 */
	public function getRoomIdByExternalId($session,Zend_Service_Openmeeting_Data_Room $room)
	{
		$data = array('SID'=>$session,
					  'name'=> $room->name,
					  'roomtypes_id'=> $room->roomtype->roomtypes_id,
					  'comment'=> $room->comment,
					  'numberOfPartizipants'=> $room->numberOfPartizipants,
					  'ispublic'=> $room->ispublic,
					  'appointment'=> $room->appointment,
					  'isDemoRoom'=> $room->isDemoRoom,
					  'demoTime'=> $room->demoTime,
					  'isModeratedRoom'=> $room->isModeratedRoom,
					  'externalRoomId'=> $room->externalRoomId,
					  'externalRoomType'=> $room->externalRoomType );
		
		$client = $this->getSoapClient();
		$res = $client->getRoomIdByExternalId($data);
		return $res->return ;
	}
	
	/**
	 * 
	 * enable or disable the buttons to apply for moderation this does 
	 * only work in combination with the room-type restricted
	 * 
	 * @param string $session
	 * @param Zend_Service_Openmeeting_Data_Room $room
	 * @return string RoomHash
	 * @throws Zend_Soap_Client_Exception
	 */
	public function addRoomWithModerationAndQuestions($session, Zend_Service_Openmeeting_Data_Room $room)
	{
		$data = array('SID'=>$session,
					  'name'=> $room->name,
					  'roomtypes_id'=> Zend_Service_Openmeeting_Data_Room::$RESTRICTED ,
					  'comment'=> $room->comment,
					  'numberOfPartizipants'=> $room->numberOfPartizipants,
					  'ispublic'=> $room->ispublic,
					  'appointment'=> $room->appointment,
					  'isDemoRoom'=> $room->isDemoRoom,
					  'demoTime'=> $room->demoTime,
					  'isModeratedRoom'=> $room->isModeratedRoom,
					  'allowUserQuestions'=> $room->allowUserQuestions );

		$client = $this->getSoapClient();
		$res = $client->addRoomWithModeration($data);
		return ($res->return > 0 ) ? $res->return : false ;
	}
	
	/**
	 * 
	 * get Flv Recording By External Room 
	 * @param string $session The SID of the User. This SID must be marked as Loggedin
	 * @param string $externalRoomType  moodle,sugarCrm,joyvita etc
	 */
	public function getFlvRecordingByExternalRoomType($session,$externalRoomType)
	{
		$data = array('SID'=>$session,'externalRoomType'=>$externalRoomType);
		$client = $this->getSoapClient();
		$res = $client->getFlvRecordingByExternalRoomType($data);
		return $res->return ;
	}
	
	/**
	 * 
	 * Create a Invitation hash the From to Date is as String
	 * @param String  $session The SID of the User. This SID must be marked as Loggedin
	 * @param String  $username the username of the User that he will get
	 * @param integer $roomId
	 * @param Boolean $isPasswordProtected if the invitation is password protected
	 * @param String  $invitationpass the password for accessing the conference room via the invitation hash
	 * @param Integer $valid the type of validation for the hash 1: endless, 2: from-to period, 3: one-time
	 * @param String  $validFromDate Date in Format of dd.mm.yyyy only of interest if valid is type 2
	 * @param String  $validFromTime time in Format of hh:mm only of interest if valid is type 2
	 * @param String  $validToDate Date in Format of dd.mm.yyyy only of interest if valid is type 2
	 * @param String  $validToTime time in Format of hh:mm only of interest if valid is type 2
	 * @return String HASH value that can be made into a URL 
	 */
	public function getInvitationHash($session,$username,$roomId,$isPasswordProtected = false,
									 $invitationpass, $valid,$validFromDate,$validFromTime,
									 $validToDate,$validToTime ){
								
	    $data = array('SID'=>$session,
					  'username'=> $username,
					  'room_id'=> $roomId ,
					  'isPasswordProtected'=> $isPasswordProtected,
					  'invitationpass'=> $invitationpass,
					  'valid'=> $valid,
					  'validFromDate'=> $validFromDate,
					  'validFromTime'=> $validFromTime,
					  'validToDate'=> $validToDate,
					  'validToTime'=> $validToTime );

		$client = $this->getSoapClient();
		$res = $client->getInvitationHash($data);
		return $res->return;
			
	}
	
	/**
	 * 
	 *  send a Invitation hash the From to Date is as String
	 * @param String  $session The SID of the User. This SID must be marked as Loggedin
	 * @param String  $username the username of the User that he will get
	 * @param integer $roomId
	 * @param Boolean $isPasswordProtected if the invitation is password protected
	 * @param String  $invitationpass the password for accessing the conference room via the invitation hash
	 * @param Integer $valid the type of validation for the hash 1: endless, 2: from-to period, 3: one-time
	 * @param String  $validFromDate Date in Format of dd.mm.yyyy only of interest if valid is type 2
	 * @param String  $validFromTime time in Format of hh:mm only of interest if valid is type 2
	 * @param String  $validToDate Date in Format of dd.mm.yyyy only of interest if valid is type 2
	 * @param String  $validToTime time in Format of hh:mm only of interest if valid is type 2
     * @param String $message  the Message in the Email Body send with the invitation if sendMail is true
	 * @param String $baseurl  the baseURL for the Infivations link in the Mail Body if sendMail is true
	 * @param String $email the language id of the EMail that is send with the invitation if sendMail is true
	 * @param String $subject the subject of the Email send with the invitation if sendMail is true
	 * @param Boolean $sendMail if sendMail is true then the RPC-Call will send the invitation to the email
	 * @param String $conferencedomain the domain of the room (keep empty not in use at the moment)
	 * @param integer $language_id
	 * @return String HASH value that can be made into a URL 
	 */

	public function sendInvitationHash($session,$username,$roomId,$isPasswordProtected = false,
									 $invitationpass, $valid,$validFromDate,$validFromTime,
									 $validToDate,$validToTime,$message,$baseurl,
									 $email,$subject,$sendMail, $conferencedomain='',$language_id =1){
								
	    $data = array('SID'=>$session,
					  'username'=> $username,
					  'room_id'=> $roomId ,
					  'isPasswordProtected'=> $isPasswordProtected,
					  'invitationpass'=> $invitationpass,
					  'valid'=> $valid,
					  'validFromDate'=> $validFromDate,
					  'validFromTime'=> $validFromTime,
					  'validToDate'=> $validToDate,
					  'validToTime'=> $validToTime );

		$client = $this->getSoapClient();
		$res = $client->sendInvitationHash($data);
		return $res->return;
	}
	
	
	/**
	 * 
	 *  Create a Invitation hash and optionally send it by mail
	 * @param String  $session The SID of the User. This SID must be marked as Loggedin
	 * @param String  $username the username of the User that he will get
	 * @param integer $roomId
	 * @param Boolean $isPasswordProtected if the invitation is password protected
	 * @param String  $invitationpass the password for accessing the conference room via the invitation hash
	 * @param Integer $valid the type of validation for the hash 1: endless, 2: from-to period, 3: one-time
	 * @param String  $fromDate Date in Format of dd.mm.yyyy hh:mm only of interest if valid is type 2 
	 * @param String  $toDate Date in Format of dd.mm.yyyy hh:mm only of interest if valid is type 2 
     * @param String $message  the Message in the Email Body send with the invitation if sendMail is true
	 * @param String $baseurl  the baseURL for the Infivations link in the Mail Body if sendMail is true
	 * @param String $email the language id of the EMail that is send with the invitation if sendMail is true
	 * @param String $subject the subject of the Email send with the invitation if sendMail is true
	 * @param Boolean $sendMail if sendMail is true then the RPC-Call will send the invitation to the email
	 * @param String $conferencedomain the domain of the room (keep empty not in use at the moment)
	 * @param integer $language_id the language id of the EMail that is send with the invitation if sendMail is true
	 * @return String HASH value that can be made into a URL 
	 */
	public function sendInvitationHashWithDateObject($session,$username,$roomId,$isPasswordProtected = false,
													 $invitationpass, $valid,$fromDate,
													 $toDate,$message,$baseurl,$email,
													 $subject,$sendMail, $conferencedomain='',$language_id =1){
												
	    $data = array('SID'=>$session,
					  'username'=> $username,
					  'room_id'=> $roomId ,
					  'isPasswordProtected'=> $isPasswordProtected,
					  'invitationpass'=> $invitationpass,
					  'valid'=> $valid,
					  'validFromDate'=> $validFromDate,
					  'validFromTime'=> $validFromTime,
					  'validToDate'=> $validToDate,
					  'validToTime'=> $validToTime );

		$client = $this->getSoapClient();
		$res = $client->sendInvitationHashWithDateObject($data);
		return $res->return;
	}
	
	/**
	 *
	 * Returns a List of RoomReturn Objects that contains all current users with their Session attributes.
	 * @param string $session The SID of the User. This SID must be marked as Loggedin
	 * @param string $start The id you want to start
	 * @param int $max  The maximum you want to get
	 * @param string $orderby The column it will be ordered, You can use "name" as value for orderby or rooms_id
	 * @param boolean $asc Asc[true] or Desc[false] sort ordering
	 * @return array of Zend_Service_Openmeeting_Data_Room
	 * @throws Zend_Soap_Client_Exception
	 */
	public function getRoomsWithCurrentUsersByList($session,$start,$max ,$orderby='name',$asc = true){
 
		$data = array('SID'=>$session,
					  'start'=> $start,
					  'max'=> $max,
					  'orderby'=> $orderby,
					  'asc'=> $asc );

		$client = $this->getSoapClient();
		$res = $client->getRoomsWithCurrentUsersByList($data);
		
		return $res->return ;
	}

	/**
	 *
	 * Returns a List of RoomReturn Objects that contains all current users with their Session attributes.
	 * But only the ones of a certain externalRoomType
	 * @param string $session The SID of the User. This SID must be marked as Loggedin
	 * @param string $externalRoomType  the name of the external room type to return
	 * @param string $start The id you want to start
	 * @param int $max  The maximum you want to get
	 * @param string $orderby The column it will be ordered, You can use "name" as value for orderby or rooms_id
	 * @param boolean $asc Asc[true] or Desc[false] sort ordering
	 * @return array of Zend_Service_Openmeeting_Data_Room
	 * @throws Zend_Soap_Client_Exception
	 */
	public function getRoomsWithCurrentUsersByListAndType($session,$externalRoomType,$start,$max ,$orderby='name',$asc = true){
 
		$data = array('SID'=>$session,
					  'start'=> $start,
					  'max'=> $max,
					  'orderby'=> $orderby,
					  'asc'=> $asc ,
					  'externalRoomType'=> $externalRoomType );

		$client = $this->getSoapClient();
		$res = $client->getRoomsWithCurrentUsersByListAndType($data);
		
		return $res->return ;
	}
	 
	/**
	 * 
	 * enable or disable the buttons to apply for moderation this does 
	 * only work in combination with the room-type restricted
	 * 
	 * @param string $session
	 * @param integer $reminderTypeId  1=none, 2=simple mail, 3=ICAL
	 * @param Zend_Service_Openmeeting_Data_Room $room
	 * @return string RoomHash
	 * @throws Zend_Soap_Client_Exception
	 */
	public function addRoomWithModerationAndExternalTypeAndStartEnd($session,$reminderTypeId,Zend_Service_Openmeeting_Data_Room $room)
	{
		$data = array('SID'=>$session,
					  'name'=> $room->name,
					  'roomtypes_id'=> Zend_Service_Openmeeting_Data_Room::$RESTRICTED ,
					  'comment'=> $room->comment,
					  'numberOfPartizipants'=> $room->numberOfPartizipants,
					  'ispublic'=> $room->ispublic,
					  'appointment'=> $room->appointment,
					  'isDemoRoom'=> $room->isDemoRoom,
					  'demoTime'=> $room->demoTime,
					  'isModeratedRoom'=> $room->isModeratedRoom,
					  'externalRoomType'=> $room->externalRoomType,
					  'validFromDate'=> $room->validFromDate,
					  'validFromTime'=> $room->validFromTime,
					  'validToDate'=> $room->validToDate,
					  'isPasswordProtected'=> $room->isPasswordProtected,
					  'password'=> $room->password,
					  'reminderTypeId'=> $reminderTypeId ,
					  'redirectURL'=> $room->redirectURL );

		$client = $this->getSoapClient();
		$res = $client->addRoomWithModerationAndExternalTypeAndStartEnd($data);
		return ($res->return > 0 ) ? $res->return : false ;
	}
	
	/**
	 * 
	 * Add a meeting member to a certain room. This is the same 
	 * as adding an external user to a event in the calendar.
	 * 
	 * @param String $session
	 * @param integer $roomId
	 * @param String $firstname
	 * @param String $lastname
	 * @param String $email
	 * @param String $baseUrl
	 * @param integer $language_id
	 * @return integer | false 
	 * @throws Zend_Soap_Client_Exception
	 */
 
	public function addMeetingMemberRemindToRoom($session,$roomId,$firstname,$lastname,$email,$baseUrl,$language_id = 1)
	{
		$data = array('SID'=>$session,
					  'room_id'=> $roomId,
					  'firstname'=> $firstname ,
					  'lastname'=> $lastname,
					  'email'=> $email,
					  'baseUrl'=>$baseUrl,
					  'language_id'=> $language_id);

		$client = $this->getSoapClient();
		$res = $client->addMeetingMemberRemindToRoom($data);
		return ($res->return > 0 ) ? $res->return : false ;
	}
	
	/**
	 * 
	 * Method to remotely close or open rooms.
	 * @param String $session
	 * @param integer $roomId
	 * @param boolean $status
	 * @return Returns positiv value if authentification was successful.
	 * @throws Zend_Service_Openmeeting_Exception
	 */
	public function closeRoom($session,$roomId,$status = false)
	{
		$data = array('SID'=>$session,
					  'room_id'=> $roomId,
					  'status'=> $status );

		$client = $this->getSoapClient();
		$res = $client->closeRoom($data);
		return ($res->return > 0 ) ? true : false ;
	}
}