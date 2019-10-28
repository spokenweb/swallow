<?php
// Loads teh csv 
// Makes a simple instr query with the input text
// Returns a json list with all the matches

class csvlookup{
    public $dataset;
    
    public function load($path,$has_header){
        $dataset = [];
        if (($handle = fopen($path, "r")) !== FALSE) {
            $numRow = 0;
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if($has_header == false){
                    $dataset[] = $data;
                }else{
                    if($numRow > 0){
                        $dataset[] = $data; 
                    }
                }
                $numRow++;
            }
            fclose($handle);
        }
        
        $this->dataset = $dataset;
    }


    public function search($query_string, $main_index, $secondary_index = -1){
        $results_main = [];
        $results_secondary = [];
        foreach($this->dataset as $row){
            if(strripos($row[$main_index],$query_string) !== false ){
                $results_main[] = trim(preg_replace('/\s\s+/', ' ', $row[$main_index]));
            }
            if($secondary_index > -1){
                if(strripos($row[$secondary_index],$query_string) !== false ){
                    $results_main[] = trim(preg_replace('/\s\s+/', ' ', $row[$secondary_index]));
                }
            }
        } //foreach($this->dataset as $row){
        
        sort($results_main, SORT_STRING);
        sort($results_secondary, SORT_STRING);

        return array_merge($results_main,$results_secondary);

    } // public function search($query_string, $main_index, $secondary_index = -1){
}


//-------------------------------------------------------------------------------------------------------------------------

if(isset($_GET['query'])){
    $query_string = $_GET['query'];
    if(strlen($query_string) >= 3){
        $nation_data = new csvlookup();
        $nation_data->load('nation-data.csv',true);
        $results = $nation_data->search($query_string,1,3);
        echo(json_encode($results));
    }
}

?>