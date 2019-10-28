function clearMenu(){
    $("#li_mmnu_home").removeClass('active');
    $("#li_mmnu_myProfile").removeClass('active');
    $("#li_mmnu_manage_users").removeClass('active');
    $("#li_mmnu_manage_collections").removeClass('active');
    $("#li_mmnu_manage_items").removeClass('active');
    $("#li_mmnu_deposit").removeClass('active');
    $("#li_mmnu_import").removeClass('active');
    $("#li_mmnu_export").removeClass('active');
    
}

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

$(document).ready(function(){
	
	//$("#main").load("dashboard.php")
	// MENU ACTIONS
    
    $("#mmnu_home" ).click(function() {
        $("#main").load("View/dashboard.php");
        clearMenu();
        $("#li_mmnu_home").addClass('active');
	});
    
    
	$("#mmnu_myProfile" ).click(function() {
        $("#main").load("View/my-profile.php");
        clearMenu();
        $("#li_mmnu_myProfile").addClass('active');
	});
	
	$("#mmnu_manage_users" ).click(function() {
        $("#main").load("View/cataloguers.php");
        clearMenu();
        $("#li_mmnu_manage_users").addClass('active');
    });
    
    $("#mmnu_manage_collections" ).click(function() {
        $("#main").load("View/collections.php");
        clearMenu();
        $("#li_mmnu_manage_collections").addClass('active');
    });
	
	$("#mmnu_manage_items" ).click(function() {
        $("#main").load("View/items.php");
        clearMenu();
        $("#li_mmnu_manage_items").addClass('active');
    });



    $("#mmnu_deposit" ).click(function() {
        createItem();
        clearMenu();
        $("#li_mmnu_deposit").addClass('active');
    });

    
    $("#mmnu_import" ).click(function() {
        $("#main").load("View/import.php");
        clearMenu();
        $("#li_mmnu_import").addClass('active');
    });
    
    $("#mmnu_export" ).click(function() {
        $("#main").load("View/export.php");
        clearMenu();
        $("#li_mmnu_export").addClass('active');
	});  
    
    $("#main").load("View/dashboard.php");

});

