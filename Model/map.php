<?php 

class Map{

    public $fieldmap = array();
    public $source_schema = "";
    public $target_schema = "";
    public $source_file_type = "";


    function load($path){
        $contents = file_get_contents($path); 
        $contents = utf8_encode($contents); 
        $decoded = json_decode($contents,TRUE);

        $this->source_file_type = $decoded['source file type'];
        $this->source_schema = $decoded['source schema'];
        $this->target_schema = $decoded['target schema'];
        $this->fieldmap = $decoded['fieldmap'];
        
    }
      
    function apply($in_source_key){
        $result = false;
        $result['target_field'] = "";
        foreach($this->fieldmap as $pair ){
            if($pair['source_field'] == $in_source_key){
                $result['target_field'] = $pair['target_field'];
                if(array_key_exists("type",$pair)){
                    $result["type"] = $pair['type'];
                    if(array_key_exists("id",$pair)){
                        $result["id"] = $pair['id'];
                    }
                }else{
                    $result["type"] = "single";
                }
                if(array_key_exists("source",$pair)){
                    $result["source"] = $pair['source'];
                }
            
            }
        }
        return $result;
    }

    function validateControlledVocabulary($path,$value){
        $contents = file_get_contents($path); 
        $contents = utf8_encode($contents); 
        $vocabulary = json_decode($contents,TRUE);

        $found = false;
        foreach($vocabulary["values"] as $term){
            if($term['label'] == $value){
                $found = true;
            }
        }
        return $found;
    }

    function maprecord($source_record){
        
        $cataloguer = [];
        $collection = [];
        $metadata = [];
        $title = "Undefined Title";
        $error = "";

        foreach($source_record as $key => $value){

            $mappedField = $this->apply($key);
            

            if($mappedField != false){
                if($value != ""){
                    if(strpos($mappedField['target_field'],'Cataloguer.') !== false){
                        $tokens = explode('.',$mappedField['target_field']);
                        $cataloguer[$tokens[1]] = $value;
                    }elseif(strpos($mappedField['target_field'],'Collection.') !== false){
                        $tokens = explode('.',$mappedField['target_field']);
                        $collection[$tokens[1]] = $value;
                    }else{
                        if($mappedField['target_field'] != ""){                     
                            $tokens = explode('/',$mappedField['target_field']);
                            $encodedField = str_ireplace(" ","_",$tokens[1]);
                            if($mappedField["type"] == "single"){
                                if($encodedField == 'Title' or $encodedField == 'title'){
                                    $title = $value;
                                }
                                $addResult = $this->addToSingle($metadata,$mappedField,$value); 
                               
                            }else{
                              
                                $addResult = $this->addToMultiple($metadata,$tokens[0],$encodedField,$mappedField,$value);
                                
                            }   

                            if($addResult !== false){
                                $metadata = $addResult;
                            }else{
                                $error .= "Value $value not valid for field ".$encodedField.". ";
                            }
                        }// if($mappedField['target_field'] != ""){        
                    }
                }// if($value != ""){  
            } // if($mappedField != false){
        }

        $result = array($cataloguer,$collection,$title,$metadata,$error);
        return $result;
      
    }


    function checkCataloguer($mappedField,$value){
        $cataloguer = [];   
        if(strpos($mappedField['target_field'],'Cataloguer.') !== false){
            $tokens = explode('.',$mappedField['target_field']);
            $cataloguer[$tokens[1]] = $value;
        }
        return $cataloguer;
    }


    function checkCollection($mappedField,$value){
        $collection = [];
        if(strpos($mappedField['target_field'],'Collection.') !== false){
            $tokens = explode('.',$mappedField['target_field']);
            $collection[$tokens[1]] = $value;
        }
        return $collection;
    }

    function checkTitle($mappedField,$value){

        $title = "";
        $tokens = explode('/',$mappedField['target_field']);
    
        if(count($tokens) > 1){
    
            if($tokens[1] == 'Title' or $tokens[1] == 'title'){
                $title = $value;
            }
        }
        return $title;
    }

    function addToSingle($metadata,$mappedField,$value){

        if($mappedField['target_field'] != ""){                     
            $tokens = explode('/',$mappedField['target_field']);
            $encodedField = str_ireplace(" ","_",$tokens[1]);
            if($mappedField["type"] == "single"){
                if($encodedField == 'Title' or $encodedField == 'title'){
                    $title = $value;
                }
               // var_dump($mappedField);
                if(key_exists('source',$mappedField)){
                    if(!is_array($value)){
                        if($this->validateControlledVocabulary("../Workflow/".$this->target_schema."/".$mappedField['source'],$value)){
                            $metadata[$tokens[0]][$encodedField] = $value;
                        }else{
                            return false;
                        }
                    }else{
                        $metadata[$tokens[0]][$encodedField] = $value;
                    }
                    
                }else{
                    $metadata[$tokens[0]][$encodedField] = $value;
                }
                
            }
        }

        return $metadata;
    }

    //$addResult = $this->addToMultiple( $metadata, $mappedTokens[0], $mappedTokens[1], $id, $multiple_value );
    function addToMultiple($metadata,$step,$field,$mappedField,$value){
   
        $id = $mappedField['id'];
        //check if is valid value
        $valid = true;

        if(key_exists('source',$mappedField)){
            $valid = $this->validateControlledVocabulary("../Workflow/".$this->target_schema."/".$mappedField['source'],$value);
        }

        if($valid){
            if(array_key_exists($step,$metadata)){
                
                $cont = 0;
                $found = false;
                foreach ( $metadata[$step] as $elem){
                    if($elem['id'] == $id){ // if the element already exists add the new value
                        $elem[$field] = $value;
                        $found = true;
                    }
                    $metadata[$step][$cont] = $elem;
                    $cont++;
                }

                if(!$found){
                    $metadata[$step][$cont] = array();
                    $metadata[$step][$cont]['id'] = $id;
                    $metadata[$step][$cont][$field] = $value;
                }

            }else{
                $metadata[$step] = array();
                $metadata[$step][0]['id'] = $id;
                $metadata[$step][0][$field] = $value;
            }

            return $metadata;

        }else{
            return false;
        }
    }



    //$addResult = $this->addToMultiple( $metadata, $mappedTokens[0], $mappedTokens[1], $id, $multiple_value );
    function addToMultiple2($metadata,$step,$mappedfield,$id,$value){
   
        //$id = $mappedField['id'];
        //check if is valid value
        $valid = true;

      /*  if(key_exists('source',$mappedField)){
            $valid = $this->validateControlledVocabulary("../Workflow/".$this->target_schema."/".$mappedField['source'],$value);
        }
    */
        if($valid){
            if(array_key_exists($step,$metadata)){
                
                $cont = 0;
                $found = false;
                foreach ( $metadata[$step] as $elem){
                    if($elem['id'] == $id){ // if the element already exists add the new value
                        $elem[$mappedfield] = $value;
                        $found = true;
                    }
                    $metadata[$step][$cont] = $elem;
                    $cont++;
                }

                if(!$found){
                    $metadata[$step][$cont] = array();
                    $metadata[$step][$cont]['id'] = $id;
                    $metadata[$step][$cont][$mappedfield] = $value;
                }

            }else{
                $metadata[$step] = array();
                $metadata[$step][0]['id'] = $id;
                $metadata[$step][0][$mappedfield] = $value;
            }

            return $metadata;

        }else{
            return false;
        }
    }
    
   function isSingleStep($value){
       if(!is_array($value)){ // for sure is single value
           return true;
       }else{
            if(key_exists("id",$value)  ){ //is a multiple field
                return false;
           }else{  
                return true;
           }
       }
   }

    function mapJSONrecord($source_record){
        
        $cataloguer = [];
        $collection = [];
        $metadata = [];
        $title = "";
        $error = "";
        foreach($source_record as $step => $value){

            $metadataValue = true;

            if(!is_array($value)){
                // is a string                
                $fullkey = $step;  
                $mappedField = $this->apply($fullkey );
                
              
                if($cataloguer == []){
                    $cataloguer = $this->checkCataloguer($mappedField,$value);
                    $metadataValue = false;
                }

                if($collection == []){
                    $collection = $this->checkCollection($mappedField,$value);
                    $metadataValue = false;
                }

                if($title == ""){
                    $title = $this->checkTitle($mappedField,$value);
                }
                
                if($metadataValue){
                    
                    $addResult = $this->addToSingle($metadata,$mappedField,$value);

                    if($addResult !== false){
                        $metadata = $addResult;
                    }else{
                        $error .= "Value $value not valid for field ".$encodedField.". ";
                    }
                }

            }else{    

                foreach($value as $step_key => $step_value){

                    $metadataValue = true;
                    
                    if($this->isSingleStep($step_value)){ // need to check if is a multiple value
                        //is a single step
                        $fullkey = $step."/".$step_key;
                        $mappedField = $this->apply($fullkey);

                        if($mappedField['target_field'] != ""){
                            if($cataloguer == []){
                                $cataloguer = $this->checkCataloguer($mappedField,$step_value);
                                $metadataValue = false;
                            }

                            if($collection == []){
                                $collection = $this->checkCollection($mappedField,$step_value);
                                $metadataValue = false;
                            }

                            if($title == ""){
                                $title = $this->checkTitle($mappedField,$step_value);
                            }

                            if($metadataValue){
                               
                                $addResult = $this->addToSingle($metadata,$mappedField,$step_value);

                                if($addResult !== false){
                                    $metadata = $addResult;
                                }else{
                                    $error .= "Value $step_value not valid for field ".$mappedField['target_field'].". ";
                                }
                            }
                        }
                                
                    }else{
                        //is a multiple step
                        // create an id
                        $id = uniqid('',TRUE);
                        foreach($step_value as $multiple_key=>$multiple_value){

                            $fullkey = $step."/".$multiple_key;
                            $mappedField = $this->apply($fullkey);
                            
                            if(strpos($fullkey,"id") === false and $mappedField['target_field'] != "" ){
                                $mappedTokens = explode("/",$mappedField['target_field']);                                
                                $addResult = $this->addToMultiple2( $metadata, $mappedTokens[0], $mappedTokens[1], $id, $multiple_value );

                                if($addResult !== false){
                                    $metadata = $addResult;
                                }else{
                                    $error .= "Value $value not valid for field ".$encodedField.". ";
                                }
                            }
                        } // foreach($step_value as $multiple_key=>$multiple_value){
                    } // else{              
                } // foreach($value as $step_key => $step_value){
            }    
        }

        $result = array($cataloguer,$collection,$title,$metadata,$error);
         
        return $result;
       
    }




}

?>