<?php
require_once "db.php";
require_once "log.php";

class Cataloguer extends db{
		
	public $id;
	public $name;
	public $lastname;
	public $email;
	public $pwd;
	public $institution;
	public $role;

	public $conn;
	
	public function __contructor($in_conn){
		$this->conn = $in_conn;
	}

	
	public function update(){
		if($this->total > 0){
			$this->id = $this->list[$this->index]["id"];
			$this->name = $this->list[$this->index]["name"];
			$this->lastname = $this->list[$this->index]["lastname"];
			$this->email = $this->list[$this->index]["email"];
			$this->pwd = $this->list[$this->index]["pwd"];
			$this->institution = $this->list[$this->index]["institution"];
			$this->role = $this->list[$this->index]["role"];
		}
	}
	
	public function create($in_name, $in_lastname, $in_email, $in_pwd, $in_institution,$in_role = 0){
		$sql = "SELECT * FROM cataloguer WHERE email='".$in_email."'";
		$result = $this->conn->query($sql);
		if ($result->num_rows == 0) {
			$sql = "INSERT INTO cataloguer (name,lastname,email,pwd,institution,role) VALUES ('". $in_name ."','". $in_lastname ."','". $in_email . "','" . $in_pwd . "','" . $in_institution ."',".$in_role.")";

			$this->conn->query($sql);
			return $this->conn->insert_id ;
    	}else{
			return 0;
		}
	
		
	}

	public function authenticate($in_email,$in_pwd){

		$sql = "SELECT * FROM cataloguer WHERE email='".$in_email."'";

		$result = $this->conn->query($sql);
		
		
		if($row = $result->fetch_assoc()){ 
		
			$hash = trim($row['pwd']);
		
			$passwordMatch = password_verify($in_pwd,$hash);
		
			if($passwordMatch){
				return $row['id'];
			}else{
				return false;
			}
		}else{
			return false;
		}

	}

	public function select($in_id){
		$sql = "SELECT * FROM cataloguer WHERE id=".$in_id;
		
		$result =  $this->conn->query($sql);
		$this->list[] = $result->fetch_assoc();
		$this->index = 0;
		$this->total = $result->num_rows;
		$this->update();
	}

	public function selectFromEmail($in_email){
		$sql = "SELECT * FROM cataloguer WHERE email='".$in_email."'";	
		$result =  $this->conn->query($sql);
		$this->list[] = $result->fetch_assoc();
		$this->index = 0;
		$this->total = $result->num_rows;
		$this->update();
	}


	public function selectFromName($in_name,$in_lastname){
		$sql = "SELECT * FROM cataloguer WHERE name='".$in_name."' and lastname='".$in_lastname."'";	
		$result =  $this->conn->query($sql);
		$this->list[] = $result->fetch_assoc();
		$this->index = 0;
		$this->total = $result->num_rows;
		$this->update();
	}
		
	public function selectAll(){
		$sql = "SELECT * FROM cataloguer ORDER BY lastname, name ";
		
		$result =  $this->conn->query($sql);
		while ($row= $result->fetch_assoc() ){
			$this->list[] = $row;
		}
		
		$this->index = 0;
		$this->total = $result->num_rows;
		$this->update();
	}

	public function selectTop($limit = 10){
		$sql = "SELECT cataloguer.name, cataloguer.lastname, COUNT(DISTINCT item.id) AS Total FROM item, cataloguer WHERE (cataloguer.id = item.cataloguer_id) GROUP BY item.cataloguer_id ORDER BY Total DESC LIMIT $limit";

		$result =  $this->conn->query($sql);

		if($result !== false){
            return $result->fetch_all(MYSQLI_ASSOC);
        }else{
            return false;
		}
		
	}
	

    public function gotoID($in_id){

        for($i = 0; $i < $this->total; $i++){
            
            if($this->list[$i]["id"] == $in_id){
                $this->index = $i;
                $this->update();
            }
        }
    }

	public function save(){

		$sql = "UPDATE cataloguer SET name='".trim($this->name)."', lastname='".trim($this->lastname)."',email='".trim($this->email)."', institution ='".trim($this->institution)."', pwd = '".trim($this->pwd)."' , role = ".$this->role." WHERE id=$this->id";
		
		$result =  $this->conn->query($sql);
		return $result;

	}
	
	public function delete($in_id){
		// Delete all items associated with this cataloguer
        $sql = "DELETE FROM item where cataloguer_id = ".$in_id;
        $result =  $this->conn->query($sql);		
		
		
		$sql = "DELETE FROM cataloguer where id=".$in_id;
		
		$result =  $this->conn->query($sql);		
		return $result;

	}

	public function getTotal(){
		$sql = "SELECT COUNT(id) as total FROM cataloguer";
		$result =  $this->conn->query($sql);
		$total = $result->fetch_assoc();
		return $total['total'];
	}

}


?>
