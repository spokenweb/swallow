
function query(page){
    var query = $("#metadaquery").val();
    var institution = $("#f_institution").val();
    var cataloguer = $("#f_cataloguer").val();
    var collection = $("#f_collection").val();

    $("#main").load("View/export.php?metadataquery="+btoa(unescape(encodeURIComponent(query)))+"&institution="+encodeURI(institution)+"&cataloguer="+cataloguer+"&collection="+collection)+"&page="+page;
}


function filter(page){
    var institution = $("#f_institution").val();
    var cataloguer = $("#f_cataloguer").val();
    var collection = $("#f_collection").val();
    var query = $("#metadaquery").val();

    $("#main").load("View/export.php?metadataquery="+btoa(unescape(encodeURIComponent(query)))+"&institution="+encodeURI(institution)+"&cataloguer="+cataloguer+"&collection="+collection+"&page="+page);

    //$("#main").load("View/export.php?institution="+encodeURI(institution)+"&cataloguer="+cataloguer+"&collection="+collection+"&page="+page);
}

function exportdataset(){
    var format = $("#export_format").val();
    var institution = $("#f_institution").val();
    var cataloguer = $("#f_cataloguer").val();
    var collection = $("#f_collection").val(); 
    var query = $("#metadaquery").val();
    if (query != ''){
        query = btoa(query);
    }

    url = "Controller/export.php?institution="+encodeURI(institution)+"&cataloguer="+cataloguer+"&collection="+collection+"&query="+query+"&format="+format;

    window.open(url);
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
