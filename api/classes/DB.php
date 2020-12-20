<?php
namespace DB;

require_once "config.php";
class DB {
	public $host;
	public $username;
	public $password;
	public $dbname;

	public function connect(){
		try {
            $this->conn = new PDO('mysql:host='.HOST.';dbname='.DBNAME, USERNAME, PASSWORD);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Exception $e) {
            echo json_encode([
                'connection' => 'Error establishing connection to DB'.$e->getMessage()
            ]);
            exit;
        } 
	}

	public function insert_contact($data) {
		$query = $this->conn->prepare('INSERT INTO contacts(first_name, last_name, email, message) 
																	VALUES (:first_name, :last_name, :email, :message)');
		$query->bindParam(':first_name', $data['first_name'], PDO::PARAM_STR);
		$query->bindParam(':last_name', $data['last_name'], PDO::PARAM_STR);
		$query->bindParam(':email', $data['email'], PDO::PARAM_STR);
		$query->bindParam(':message', $data['message'], PDO::PARAM_STR);

    if($query->execute()){
    	return json_encode([
            'message' => 'contact inserted successfully'
        ]);
    }

	}

}