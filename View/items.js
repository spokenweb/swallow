function createItem(){
    $.ajax({
        // The URL for the request
        url: "Controller/item-create.php",
        type: "GET", 
        
        success: function( data ) {
            console.log(data);
            $("#main").load("View/deposit-item.php?itemid="+data)
            //$("#main").load("View/items.php")	
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

function editItem(id){
    $("#main").load("View/deposit-item.php?itemid="+id)	
}

function filter(page){
    var institution = $("#f_institution").val();
    var cataloguer = $("#f_cataloguer").val();
    var collection = $("#f_collection").val();
    var orderby =  $("#f_orderby").val();
    var query = $("#metadaquery").val();

    $("#main").load("View/items.php?institution="+encodeURI(institution)+"&cataloguer="+cataloguer+"&collection="+collection+"&metadataquery="+btoa(unescape(encodeURIComponent(query)))+"&page="+page+"&orderby="+orderby);
}

function query(page){
    var institution = $("#f_institution").val();
    var cataloguer = $("#f_cataloguer").val();
    var collection = $("#f_collection").val();
    var query = $("#metadaquery").val();

   // console.log(query);

     $("#main").load("View/items.php?institution="+encodeURI(institution)+"&cataloguer="+cataloguer+"&collection="+collection+"&metadataquery="+btoa(unescape(encodeURIComponent(query)))+"&page="+page);
}

function deletedataset(){
    var institution = $("#f_institution").val();
    var cataloguer = $("#f_cataloguer").val();
    var collection = $("#f_collection").val();
    var query = $("#metadaquery").val();

    if( confirm('All items that matching the search / filter criteria will be deleted. This operation cannot be undone and will result in dataloss.') ){
        $.ajax({
            // The URL for the request
            url: "Controller/batch-delete.php?institution="+institution+"&cataloguer="+cataloguer+"&collection="+collection+"&query="+btoa(query),
            type: "GET", 
            
            success: function( data ) {
                console.log(data);
                $("#main").load("View/items.php")	
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

function preview(id){
    $.ajax({
        // The URL for the request
        url: "View/preview-record.php?id="+id,
        type: "GET", 
        
        success: function( data ) {
           $("#modal-main").empty();
           $("#modal-main").append(data);
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


function deleteItem(id){
    if(confirm(' Are you sure you want to delete this item ? \n This action cannot be undone and data will be lost')){
        $.ajax({
            // The URL for the request
            url: "Controller/item-delete.php?itemid="+id,
            type: "GET", 
            
            success: function( data ) {
                console.log(data);
                $("#main").load("View/items.php")	
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


function duplicateItem(id){
    if(confirm(' Are you sure you want to duplicatethis item ? \n This action will create an exact duplicate if the record with a new unique identifier. \n It should appear right after the original')){
        $.ajax({
            // The URL for the request
            url: "Controller/item-duplicate.php?itemid="+id,
            type: "GET", 
            
            success: function( data ) {
                console.log(data);
                $("#main").load("View/items.php")	
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
