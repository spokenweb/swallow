<?php
require_once "db.php";
require_once "log.php";

class Item extends db{

    public $id;
    public $title;
    public $cataloguer_id;
    public $collection_id;
    public $schema_version;
    public $metadata = [];
    public $conn;
    public $query_total;
    public $locked;
	
	public function __contructor($in_conn){
		$this->conn = $in_conn;
    }
    

    function updateValue($path,$key,$value){

        if($value != -1){
            $this->metadata[$path][$key] = $value; 
        }else{
            if(key_exists($path,$this->metadata) and key_exists($key,$this->metadata[$path])){
               unset($this->metadata[$path][$key]);
            }
        }
    }

    function addElement($path,$keyValueArray){      
        $this->metadata[$path]=$keyValueArray;
    }    

    function addElementMultiple($path,$keyValueArray,$path2='',$parentid=''){
        
        if(!key_exists('id',$keyValueArray)){
            $keyValueArray['id']=uniqid('',TRUE);
        }

        if($path2 == ''){
            $this->metadata[$path][]=$keyValueArray;
        }else{
            if($parentid == 0){
                $this->metadata[$path][$path2][]=$keyValueArray;
            }else{
                //find the parent element
                $cont = 0;

                foreach($this->metadata[$path] as $element){
                    if($element['id'] == $parentid){
                        $element[$path2][]=$keyValueArray;
                        //make the element is properly formated to avoid json incompatibility issues (special charcarters)
                        $this->metadata[$path][$cont] = $element;
                    }
                    $cont++;
                }
            }
            
        }
        
    }


    function deleteElement($path,$id,$path2 = '',$parentid = 0){
        $newArray = [];
        $elementList = [];

        if($parentid == 0){ 
            if($path2 == ''){
                $elementList = $this->metadata[$path];
            }else{
                $elementList = $this->metadata[$path][$path2];
            } 

            foreach ($elementList  as $element){
                if($element['id'] != $id){
                    $newArray[] = $element;
                }
            }
    
            if($path2 == ''){
                $this->metadata[$path] = $newArray;
            }else{
                $this->metadata[$path][$path2] = $newArray;
            }

        }else{ // is multiple field in a multiple step
            $stepElements = $this->metadata[$path];
            foreach($stepElements as $stepElement){
                if($stepElement['id'] != $parentid){
                    $newArray[] = $stepElement;
                }else{
                    $fieldElements = $stepElement[$path2];
                    $newFieldElements = [];
                    foreach($fieldElements as $fieldElement){
                        if($fieldElement['id'] != $id){
                            $newFieldElements[] = $fieldElement;
                        }
                    }
                    $stepElement[$path2] = $newFieldElements;
                    $newArray[] = $stepElement;
                }
            }

            $this->metadata[$path] = $newArray;
 
        }
        
    }

    

    function getElement($path){
        if(is_array($this->metadata) and key_exists($path,$this->metadata)){
            return  $this->metadata[$path];
        }else{
            return  [];
        }
    }
    
    function getValue($path,$key){
        $encoded_key = str_replace(' ','_',$key);
       
        if(is_array($this->metadata) and key_exists($path,$this->metadata)){
            if(key_exists($encoded_key,$this->metadata[$path])){
                return $this->metadata[$path][$encoded_key];
            }else{
                return NULL;
            }
        }else{
            return NULL;
        }       
    }


    function select($id){
        $sql = "SELECT id,title,cataloguer_id,collection_id,schema_version, CAST(metadata as CHAR) as metadata, locked  FROM item WHERE id = ".$id;
		
        $result =  $this->conn->query($sql);
        if($result != false){
            while ($row= $result->fetch_assoc() ){
                $this->list[] = $row;
            }
            $this->index = 0;
            $this->total = $result->num_rows;
            $this->update();

        }		
    } 


    function exists($in_title,$in_collection_id){
        $sql = "SELECT id FROM item WHERE title = '".trim($in_title)."' and collection_id = ".$in_collection_id;
        $result =  $this->conn->query($sql);
        if($result == false){
            return false;
        }else{
            if($result->num_rows > 0){
                $row= $result->fetch_assoc();
                return $row['id'];
            }else{
                return false;
            }
            
        }
    }
    
    function prepareJson($json){

        $cleaned = str_ireplace( "\\r", "", $json  ); // TN
        $cleaned = str_ireplace( "\\n", "\\\\n", $cleaned ); // TN
   
        //remove single quotes
        $cleaned = str_ireplace("\'", "'",$cleaned );
        $cleaned = str_ireplace("'", "\\'",$cleaned );

        //make sure the backslashes are properly escapeds        
        $cleaned = str_ireplace('\\\"','\"',$cleaned);
        $cleaned = str_ireplace('\"','\\\"',$cleaned);

        return $cleaned;
    }

    function save(){
        $date = new DateTime();

        $jsonStr = json_encode($this->metadata,JSON_UNESCAPED_UNICODE);

        $title = str_ireplace("\'", "'",trim($this->title)); 
        $title = str_ireplace("'", "\'",$title); 


        $sql = "UPDATE item  SET  title = '".$title."', cataloguer_id = ".$this->cataloguer_id.", collection_id = ".$this->collection_id.", schema_version = '".$this->schema_version."',  metadata = '".$this->prepareJson($jsonStr)."' , last_modified = '".$date->format('Y-m-d H:i:s')."', locked = ".$this->locked." WHERE id = ".$this->id;
    
        $result =  $this->conn->query($sql);

        return $result;

    }

    function update(){

        if($this->total > 0){
            $this->id = $this->list[$this->index]["id"];
            $this->title = $this->list[$this->index]["title"];
            $this->cataloguer_id = $this->list[$this->index]["cataloguer_id"];
            $this->collection_id = $this->list[$this->index]["collection_id"];
            $this->schema_version = $this->list[$this->index]["schema_version"];
            $this->locked = $this->list[$this->index]["locked"];
            $this->metadata = json_decode($this->list[$this->index]["metadata"],true);
        }
    }

    function selectAll($sortby = "date",$limit = 100){

        $sortbystr = "";
        switch ($sortby){
            case "date":
                break;
            case "title":
                break;

        }

        $sql = "SELECT id,title,cataloguer_id,collection_id,locked,schema_version, CAST(metadata as CHAR) as metadata  FROM item LIMIT $limit" ;
		
        $result =  $this->conn->query($sql);

        if($result != false)  {     
            while ($row= $result->fetch_assoc() ){
                $this->list[] = $row;
            }   
        }

		$this->total = $result->num_rows;
		$this->index = 0;
		
		$this->update();
    }

    function selectLatests($limit = 10){

        $sql = "SELECT id,title,cataloguer_id,collection_id,locked,schema_version, CAST(metadata as CHAR) as metadata  FROM item ORDER BY create_date DESC LIMIT $limit " ;
		
        $result =  $this->conn->query($sql);

        if($result != false)  {     
            while ($row= $result->fetch_assoc() ){
                $this->list[] = $row;
            }   
        }

		$this->total = $result->num_rows;
		$this->index = 0;
		
		$this->update();
    }



    function metadataQuery($in_query,$institution = -1,$cataloguer = -1,$collection = -1,$page=-1,$orderby=''){
        //parse the input query string
        if($page != -1){
            $offset = ($page -1) * 15;
            $limit = " LIMIT $offset,15";
        }else{
           $limit = " LIMIT 1000";
        }

        $conditions = '';
        $whereClause = "";
        if($in_query != ''){
            $whereClause = "WHERE ";
            $tokens = explode('+',$in_query);
      
            $operators = array('AND','OR');
            foreach ($tokens as $token){
                
                if($token != ''){
                    if(in_array(trim($token),$operators) ){
                        $whereClause .= " ".$token." ";
                    } else{
                        $token = str_ireplace("'","\'",$token);
                        $whereClause .= "JSON_SEARCH(metadata,'all','%".trim($token)."%') IS NOT NULL ";
                    }
                }
                
            }
        }
        
        //Institution is selected and no cataloguer
        if($institution != -1 and $cataloguer == -1){
            if(strpos($whereClause,"WHERE ") !== false ){
                $conditions .= " and collection.partner_institution = '".$institution."' and item.collection_id = collection.id"; 
            }else{
                $conditions .= " WHERE collection.partner_institution = '".$institution."' and item.collection_id = collection.id"; 
            }
        }

        //Cataloguer is selected 
        if($cataloguer != -1){
            if(strpos($whereClause,"WHERE ") !== false or (strpos($conditions,"WHERE "))){
                $conditions .= " and item.cataloguer_id = $cataloguer";
            }else{
                $conditions .= " WHERE item.cataloguer_id = $cataloguer";
            }
        }

        //Collection is selected 
        if($collection != -1){
            if(strpos($whereClause,"WHERE ") !== false or (strpos($conditions,"WHERE ")) ) {
                $conditions .= " and item.collection_id = $collection";
            }else{
                $conditions .= " WHERE and item.collection_id = $collection";
            }
            
        }

        //orderby
        if($orderby != ''){
            if($orderby == 'create_date' or $orderby == 'last_modified'){
                $orderbystr = 'ORDER BY item.'.$orderby." DESC";
            }else{
                $orderbystr = 'ORDER BY item.'.$orderby;
            }
            
        }else{
            $orderbystr = '';
        }

        $sql = "SELECT DISTINCT item.id AS id,item.title AS title,item.cataloguer_id as cataloguer_id,item.collection_id as collection_id,item.schema_version as schema_version, CAST(item.metadata as CHAR) as metadata, item.locked as locked, item.create_date as create_date, item.last_modified as last_modified FROM item,collection $whereClause $conditions  $orderbystr  $limit";

       // echo($sql);

        $result =  $this->conn->query($sql);
    

        if($result != false)  {     
            while ($row= $result->fetch_assoc() ){
                $this->list[] = $row;
            } 
            
            $this->total = $result->num_rows;    
        }else{
            $this->total = 0;
        }

        $this->index = 0;		
		$this->update();

        
        $sql = "SELECT COUNT(DISTINCT item.id) AS total FROM item,collection $whereClause $conditions ORDER BY item.title ";
        $total = $this->conn->query($sql);
        if($total !== false){
            $totalAssoc = $total->fetch_assoc() ;
            $this->query_total = $totalAssoc['total'];
        }else{
            $this->query_total = 0;
        }
        
       // $this->query_total = 0;
    }

	
	function delete($in_id){
        $sql = "DELETE FROM item WHERE id=".$in_id;
        $result =  $this->conn->query($sql);
        return $result;
    }

    function deletelist(){
        for($i=0;$i < $this->total; $i++){
            $this->go($i);
            $sql = "DELETE FROM item WHERE id=".$this->id;
            $this->conn->query($sql);
        }
    }

    function selectCataloguerID($cataloguer_id){
        $sql = "SELECT id,title,cataloguer_id,collection_id,schema_version, CAST(metadata as CHAR) as metadata. locked  FROM item WHERE cataloguer_id = ".$cataloguer_id;
		
		$result =  $this->conn->query($sql);
		while ($row= $result->fetch_assoc() ){
			$this->list[] = $row;
		}
		
		$this->index = 0;
		$this->total = $result->num_rows;
		$this->update();
    }  
    
    function create($in_cataloguer_id,$in_schema_version){
        $sql = "INSERT INTO item (cataloguer_id,schema_version,title) VALUES (".$in_cataloguer_id.",'".$in_schema_version."','new item')";
        $result =  $this->conn->query($sql);
		return $this->conn->insert_id;
    }

    public function getTotal(){
		$sql = "SELECT COUNT(id) as total FROM item";
		$result =  $this->conn->query($sql);
		$total = $result->fetch_assoc();
		return $total['total'];
	}

}


?>
