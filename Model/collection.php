<?php
require_once "db.php";
require_once "log.php";

class Collection extends db {

    public $id;
    public $partner_institution;
    public $contributing_unit;
    public $source_collection;
    public $source_collection_description;
    public $source_collection_ID;
    
    
    public function update(){
        if($this->total > 0){
            $this->id = $this->list[$this->index]["id"];
            $this->partner_institution = $this->list[$this->index]["partner_institution"];
            $this->contributing_unit = $this->list[$this->index]["contributing_unit"];
            $this->source_collection = $this->list[$this->index]["source_collection"];
            $this->source_collection_description = $this->list[$this->index]["source_collection_description"];
            $this->source_collection_ID = $this->list[$this->index]["source_collection_ID"];
        }
    }
       
    public function select($in_id){
        $sql = "SELECT * FROM collection WHERE id=".$in_id;
		
        $result =  $this->conn->query($sql);
        if($result != false){
    		$this->list[] = $result->fetch_assoc();
	    	$this->index = 0;
		    $this->total = $result->num_rows;
            $this->update();
        }
    }


    public function selectFromPartner($in_partner){
        $sql = "SELECT * FROM collection WHERE partner_institution ='".$in_partner."' ORDER BY partner_institution,contributing_unit,source_collection";	
		$result =  $this->conn->query($sql);
		
		while ($row= $result->fetch_assoc() ){
			$this->list[] = $row;
        }
		$this->index = 0;
		$this->total = $result->num_rows;
		$this->update();
    }

    public function selectFromSourceCollection($in_source_collection){
        $sql = "SELECT * FROM collection WHERE source_collection='".$in_source_collection."'";
		$result =  $this->conn->query($sql);
	
		while ($row= $result->fetch_assoc() ){
			$this->list[] = $row;
        }
		$this->index = 0;
		$this->total = $result->num_rows;
		$this->update();
    }
   
    public function selectAll(){
        $sql = "SELECT * FROM collection ORDER BY partner_institution,contributing_unit,source_collection";
		
		$result =  $this->conn->query($sql);
		while ($row= $result->fetch_assoc() ){
			$this->list[] = $row;
        }
        	
		$this->index = 0;
		$this->total = $result->num_rows;
		$this->update();
    }
    
    public function getItemsPerCollection($limit = 10){
        $sql = "SELECT collection.source_collection, COUNT(DISTINCT item.id) AS Total FROM item, collection WHERE (collection.id = item.collection_id) GROUP BY item.collection_id ORDER BY Total DESC LIMIT $limit";
        $result =  $this->conn->query($sql);


        if($result !== false){
            return $result->fetch_all(MYSQLI_ASSOC);
        }else{
            return false;
        }

    }

    public function gotoID($in_id){
        $result = false;
        for($i = 0; $i < $this->total; $i++){
            
            if($this->list[$i]["id"] == $in_id){
                $this->index = $i;
                $this->update();
                $result = true;
            }
        }

        return $result;
    }

    public function save(){
        $sql = "UPDATE collection SET partner_institution='".trim($this->partner_institution)."', contributing_unit='".trim($this->contributing_unit)."',source_collection='".trim($this->source_collection)."', source_collection_description ='".trim($this->source_collection_description)."', source_collection_ID = '".trim($this->source_collection_ID)."'  WHERE id=$this->id";
		
		$result =  $this->conn->query($sql);
		return $result;
    }
	
	public function delete($in_id){
        // Delete all items associated with this collection
        $sql = "DELETE FROM item where collection_id = ".$in_id;
        $result =  $this->conn->query($sql);		
        
        $sql = "DELETE FROM collection where id=".$in_id;
		
		$result =  $this->conn->query($sql);		
		return $result;
    }

    public function create($partner_institution = ''){
        $sql = "INSERT INTO collection 
            (partner_institution,contributing_unit,source_collection,source_collection_description,source_collection_ID) 
            VALUES ('".$partner_institution."','new','new','','')";
        
        $result =  $this->conn->query($sql);
		return $this->conn->insert_id ;
    }

    public function getTotal(){
		$sql = "SELECT COUNT(id) as total FROM collection";
		$result =  $this->conn->query($sql);
		$total = $result->fetch_assoc();
		return $total['total'];
	}

}

?>