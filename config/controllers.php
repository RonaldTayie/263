<?php

// Database Connection and hasher function
class DBController {
	public function Conn(){
		include('Settings.php');
		return $this->conn = new mysqli($HOST,$USERNAME,$PASSWORD,$DATABASE);		
	}

	//Hashing handler function
	protected function Hasher($use,$text){
		if(isset($use)){
			switch ($use) {
				case 'token':
					return md5($text);
					break;
				case 'pwd':
					//reverse the hash
					$hash = strrev(md5($text));
					return $hash;
					break;
				case 'sess':
					//Hash the reverse of the string
					$hash = md5(strrev($text));
					return $hash;
					break;
				case "upload":
					$hash = uniqid(md5(date("HMsDnyyy")),true);
					break;
				case "issue":
					$hash = uniqid(md5(date("HMsDnyyyy"))."_issue",false);
					break;
				default:
					# code...
					break;
			}
			return $hash;
		}
	}
}

// Athentication 
class AuthController extends DBController{

	private function createToken($uid,$email){
		$token = self::Hasher('token',$email);
		// 2021-03-13 00:13:18
		$date = date('Y-m-d H:i:s');

		$SQL = "INSERT INTO `auth_token` (`user`, `token`, `date_created`) VALUES (?,?,?);";

		$conn = $this->Conn();
		$stmt = mysqli_stmt_init($conn);
		if(!mysqli_stmt_prepare($stmt,$SQL)){
			return "Preparation failed";
		}else{
			mysqli_stmt_bind_param($stmt,"sss",$uid,$token,$date);
			mysqli_execute($stmt);
			$query = mysqli_stmt_get_result($stmt);
			return $token;
		}
	}

	// Get Token
	private function getToken($uid){
		$SQL = "SELECT * FROM `auth_token` WHERE `user`=? ;";
		$conn = self::Conn();

		$stmt = mysqli_stmt_init($conn);
		if(!mysqli_stmt_prepare($stmt,$SQL)){
			return "FAILED";
		}else{
			mysqli_stmt_bind_param($stmt,'s',$uid);
			mysqli_execute($stmt);
			$query = mysqli_stmt_get_result($stmt);

			$token = [];

			while($row = $query->fetch_assoc()){
				array_push($token, $row);
			}
			return $token;
		}
	}

	public function Login($username,$password){
		$pwd = self::Hasher('pwd',$password);
		for ($i=0; $i < strlen($username) ; $i++) {
			if($username[$i]=='@'){
				$type = "email";
				break;
			}else{
				$type = "username";
			}
		}
		$SQL = "SELECT * FROM `users` WHERE `$type` = ? AND `password`= ? ;";
		$conn = $this->Conn();
		$stmt = mysqli_stmt_init($conn);
		if(!mysqli_stmt_prepare($stmt,$SQL)){
			return "Preparation failed";
		}else{
			mysqli_stmt_bind_param($stmt,"ss",$username,$pwd);
			mysqli_execute($stmt);
			$query = mysqli_stmt_get_result($stmt);

			// Data Container
			$data = [];
			while($row = $query->fetch_assoc()){
				$data['user'] = $row['uid'];
				$data['isManager'] = ($row['isManager']==1)?True:False;
			}

			if(!empty($data)){
				$data['token'] = self::getToken($data['user']);
			}
			// array_push($data, $token);
			return json_encode($data);
		}
	}

	public function Register($data){
		$email = $data['email'];
		$password = $data['password'];
		$username = $data['username'];
		$first_name = $data['first_name'];
		$last_name = $data['last_name'];
		$uid = self::Hasher('pwd',$email);
		$pwd = self::Hasher('pwd',$password);

		$isManager = 0;
		$SQL = "INSERT INTO `users` (`uid`,`first_name`,`last_name`,`username`,`email`,`password`,`isManager`) VALUES (?,?,?,?,?,?,?)";

		$conn = self::Conn();
		$stmt = mysqli_stmt_init($conn);
		if(!mysqli_stmt_prepare($stmt,$SQL)){
			return "Preparation failed";
		}else{
			mysqli_stmt_bind_param($stmt,"ssssssb",$uid,$first_name,$last_name,$username,$email,$pwd,$isManager);
			mysqli_execute($stmt);
			$query = mysqli_stmt_get_result($stmt);
			// Create a token for the new User
			$token = self::createToken($uid,$email);
			return $token;
		}
	}

	// Validate Token
	public function validate_token($token){
		// connection
		$conn = self::Conn();

		$SQL = "SELECT * FROM `auth_token` WHERE `token`=? ;";

		$stmt = mysqli_stmt_init($conn);
		if(!mysqli_stmt_prepare($stmt,$SQL)){
			return false;
		}
		mysqli_stmt_bind_param($stmt,'s',$token);
		mysqli_execute($stmt);

		$query = mysqli_stmt_get_result($stmt);
		$result = $query->num_rows;
		// return an OK / a NOT FOUND
		return ($result==1)?200:404;

	}

}

// blacklist
class Blacklist extends DBController{

	// Get specific blacklist Accounts
	public function getBlacklistAccount($account_num){
		$SQL = "SELECT * FROM `blacklist` WHERE `account_num`=? ;";
		// DB Connection
		$conn = self::Conn();

		$stmt = mysqli_stmt_init($conn);
		if(!mysqli_stmt_prepare($stmt,$SQL)){
			return die;
		}
		mysqli_stmt_bind_param($stmt,'s',$account_num);
		mysqli_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		// Data contaner
		while($row = $result->fetch_assoc()){
			$data = $row;
		}
		if(empty($data)){
			$client = new ClientAccount();
			$data = $client->getClient($account_num);
		}else{
			$data['transactions'] = self::getAccountTransactions($account_num);
		}
		return json_encode($data);
	}

	public function getAccountTransactions($account_num){
		$SQL = "SELECT * FROM `blacklist_transactions` where `account_num`=? ;";

		// DB Connection
		$conn = self::Conn();
		$stmt = mysqli_stmt_init($conn);
		if(!mysqli_stmt_prepare($stmt,$SQL)){
			return die;
		}
		mysqli_stmt_bind_param($stmt,'s',$account_num);
		mysqli_execute($stmt);
		$query = mysqli_stmt_get_result($stmt);
		$data = [];
		while($row = $query->fetch_assoc()){
			array_push($data, $row);
		}
		return $data;
	}

	// set the blacklist id on a client's account
	private function addAccountBlackListId($account_num,$blacklist_id){

		$conn = self::Conn();
		$date_blacklisted = date("Y-m-d H:i:s");

		$SQL = "UPDATE `client_account` SET `blacklist_id`=?,`date_blacklisted`=? WHERE `account_num`=? ;";

		$stmt = mysqli_stmt_init($conn);
		if(!mysqli_stmt_prepare($stmt,$SQL)){
			return die;
		}
		mysqli_stmt_bind_param($stmt,'sss',$blacklist_id,$date_blacklisted,$account_num);
		mysqli_execute($stmt);
		$query = mysqli_stmt_get_result($stmt);

		return true;
	}

	// Add an account to the blacklist table
	public function AddToBlacklist($account_num){
		$amount_owed = floatval(rand(5000,7000000));
		$date_blacklisted = date("Y-m-d H:i:s");
		$isCleared = false;

		$SQL = "INSERT INTO `blacklist`(`account_num`, `amount_owed`, `date_blacklisted`, `isCleared`) VALUES (?,?,?,?);";

		$conn = self::Conn();
		$stmt = mysqli_stmt_init($conn);

		if(!mysqli_stmt_prepare($stmt,$SQL)){
			return "Failed";
		}else{
			mysqli_stmt_bind_param($stmt,'sdsi',$account_num,$amount_owed,$date_blacklisted,$isCleared);
			mysqli_execute($stmt);

			$blacklist = json_decode(self::getBlacklistAccount($account_num),1);	
			// add the blacklist id of the created blacklist record
			self::addAccountBlackListId($account_num,$blacklist['id']);
			return json_encode($blacklist);
		}
	}

	private function UpdateOwedAmount($account_num,$depositAmount,$Balance){
		
		$newBalance = floatval($Balance)-floatval($depositAmount);

		$SQL = "UPDATE `blacklist` SET `amount_owed`='$newBalance' WHERE `account_num`='$account_num' ;";
		$conn = self::Conn();
		$query = $conn->query($SQL);
		if(!$query){
			return false;
		}
		return true;

	}

	// Transactions
	public function makeTransaction($id,$account,$amount,$balance){

		$conn = self::Conn();

		$date = date("Y-m-d H:i:s");
		$SQL = "INSERT INTO `blacklist_transactions`(`blacklist_id`, `account_num`, `paid_amount`, `date_paid`) VALUES (?,?,?,?);";

		$stmt = mysqli_stmt_init($conn);
		if(!mysqli_stmt_prepare($stmt,$SQL)){
			return 500;
		}
		mysqli_stmt_bind_param($stmt,'isss',$id,$account,$amount,$date);
		mysqli_execute($stmt);

		// Update the owed amount

		$result = self::UpdateOwedAmount($account,$amount,$balance);
		return json_encode($result);

	}

	public function PayDebt($data){

		// get the blacklist record from the blaclist table
		$account = json_decode(self::getBlacklistAccount($data['account_num']),1);

		$transaction = self::makeTransaction($account['id'],$data['account_num'],$data['amount'],$account['amount_owed']);
		return $transaction;

	}



}

// Client Controller Class
class ClientAccount extends DBController{

	public function getAllClients(){
		// Connection
		$conn = self::Conn();
		$query = $conn->query("SELECT * FROM `client_account`;");
		$data = [];
		while($row = $query->fetch_assoc()){
			array_push($data,$row);
		}
		return json_encode($data);
	}

	public function getClient($acc_num){
		// Connection
		$conn = self::Conn();
		$query = $conn->query("SELECT * FROM `client_account` WHERE `account_num`='$acc_num';");
		while($row = $query->fetch_assoc()){
			$data = $row;
		}
		return $data;
	}

	// Add or create a new client
	public function AddClient($data){

		print_r($data);

		$account_name = $data['account_name'];
		$isBusiness = ($data['isBusiness']==true)?1:0;
		$institution = rand(1,3);
		
		// the blacklist values will be determined by whether or not the client is initially blacklisted
		$isBlacklisted = ($data['isBlacklisted']==true)?1:0;
		$date_blacklisted = '';

		// manager by default will me 263Microfinance
		$manager = '263microfinance';

		// auto generate account number
		$account_num = rand(1000000000000,1999999999999);

		// Connection
		$conn = self::Conn();

		// SQL QUERY
		$SQL = "INSERT INTO `client_account` ( `account_num`, `account_name`, `isBusiness`, `institution`, `manager`, `date_blacklisted`,`isBlacklisted`) VALUES (?,?,?,?,?,?,?);";

		// Prepared Statements

		$stmt = mysqli_stmt_init($conn);
		if(!mysqli_stmt_prepare($stmt,$SQL)){
			return 500;
		}

		mysqli_stmt_bind_param($stmt,'ssiisii',$account_num,$account_name,$isBusiness,$institution,$manager,$date_blacklisted,$isBlacklisted);
		mysqli_execute($stmt);
		$query = mysqli_stmt_get_result($stmt);

		if($isBlacklisted==1){
			$blacklister = new Blacklist();
			$blacklister->AddToBlacklist($account_num);
		}

		return json_encode(array('status'=>201));

	}
	// blacklist a client and create a new blacklist record
	public function BlacklistClient($account_num){

		$date_blacklisted = date("Y-m-d H:i:s");

		$conn = self::Conn();
		$SQL = "UPDATE `client_account` SET `isBlacklisted` = '1' , `date_blacklisted`='$date_blacklisted' WHERE `account_num`='$account_num' ;";

		$query = $conn->query($SQL);
		if($query){
			return true;
		}else{
			return false;
		}

	}


	// DELETE Client

	public function deleteClient($account_num){
		$conn = self::Conn();
		//SQL
		$SQL = "DELETE  FROM `client_account` WHERE `account_num`='$account_num';";
		// QUERY
		$query = $conn->query($SQL);

		if($query){
			return true;
		}else{
			return false;
		}

	}

}


// $auth = new AuthController();
// // print_r($auth->Login("Koketso@gmail.com",'KKetso'));
// // Registration
// // print_r(
// // 	$auth->Register(
// // 		array(
// // 			'email' =>'Koketso@gmail.com',
// // 			'username' => 'Ketso',
// // 			'password' => 'KKetso',
// // 			'first_name' => 'Koketso',
// // 			'last_name' => 'Mandoza'
// // 		)
// // 	)
// // );

// // Client Test
// $client = new ClientAccount();
// // print_r($client->getAllClients());

// // $clientData = array(
// // 	'account_name'=>'hazel marksman',
// // 	'isBusiness' => false,
// // 	'institution'=> 1,
// // );


// // Add New Client
// // print_r(
// // 	$client->AddClient($clientData)
// // )


// // Blacklist Existing Client
// $client_data = array(
// 	'account_num' => '1575021111340'
// );
// print_r(json_decode($client->BlacklistClient($client_data)));

?>