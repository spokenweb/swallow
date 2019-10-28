function createCataloguer(){
    $.ajax({
        // The URL for the request
        url: "Controller/cataloguer-create.php",
        type: "POST", 
        
        success: function( data ) {
            $("#main").load("View/cataloguer-edit.php?cataloguerid="+data)
            //$("#main").load("View/cataloguers.php")	
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
function editCataloguer(id){
    $("#main").load("View/cataloguer-edit.php?cataloguerid="+id)	
}

function deleteCataloguer(id){
    if(confirm(' Are you sure you want to delete this cataloguer ? \n This action cannot be undone and data will be lost')){
        $.ajax({
            // The URL for the request
            url: "Controller/cataloguer-delete.php?id="+id,
            type: "GET", 
            
            success: function( data ) {
                console.log(data);
                $("#main").load("View/cataloguers.php")	
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


function mailCataloguer(id){
    if(confirm('This action will reset the cataloguers password and email the new credentials. Do you want to proceed ?')){
        $.ajax({
            // The URL for the request
            url: "Controller/cataloguer-reset.php?id="+id,
            type: "GET", 
            
            success: function( data ) {
                console.log(data);
                if(data == "true"){
                    alert("The new credentials have been sent to the cataloguer");
                }else{
                    alert("Something went wrong");
                }
                $("#main").load("View/cataloguers.php")	
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
