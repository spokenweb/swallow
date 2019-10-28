function createCollection(){
    $.ajax({
        // The URL for the request
        url: "Controller/collection-create.php",
        type: "GET", 
        
        success: function( data ) {
            $("#main").load("View/collection-edit.php?collectionid="+data)
            //$("#main").load("View/collections.php")	
        },
         
        // Code to run if the request fails; the raw request and
        // status codes are passed to the function
        error: function( xhr, status, errorThrown ) {
            console.log(errorThrown);
            alert( "Sorry, there was a problem!" );
        },
            
        complete: function( xhr, status){
            
        }
         
    }); //$.ajax({
}


// btn_edit_cataloguer
function editCollection(id){
    $("#main").load("View/collection-edit.php?collectionid="+id)	
}

function deleteCollection(id){
    if(confirm(' Are you sure you want to delete this collection ? \n This action cannot be undone and data will be lost')){
        $.ajax({
            // The URL for the request
            url: "Controller/collection-delete.php?id="+id,
            type: "GET", 
            
            success: function( data ) {
                console.log(data);
                $("#main").load("View/collections.php")	
            },
             
            // Code to run if the request fails; the raw request and
            // status codes are passed to the function
            error: function( xhr, status, errorThrown ) {
                console.log(errorThrown);
                alert( "Sorry, there was a problem!" );
            },
                
            complete: function( xhr, status){
                
            }
             
        }); //$.ajax({
    }
}


function duplicateCollection(id){
    if(confirm('Are you sure you want to duplicate this collection ? \nThis will create a duplicate of all items in the original collection and associate them with the new collection. \nThis may take a while depending on the size ofthe collection.')){
        $.ajax({
            // The URL for the request
            url: "Controller/collection-duplicate.php?id="+id,
            type: "GET", 
            
            success: function( data ) {
                console.log(data);
                $("#main").load("View/collections.php")	
            },
             
            // Code to run if the request fails; the raw request and
            // status codes are passed to the function
            error: function( xhr, status, errorThrown ) {
                console.log(errorThrown);
                alert( "Sorry, there was a problem!" );
            },
                
            complete: function( xhr, status){
                
            }
             
        }); //$.ajax({
    }
}
