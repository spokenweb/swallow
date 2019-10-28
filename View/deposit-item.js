function renderStep(name,type,itemid,menu_element){
    $("[id^=mnu_li_]").removeClass('active');    
    $("#"+menu_element).addClass('active');

    $("#stepContainer").load("View/step-renderer.php?name="+name+"&type="+type+"&itemid="+itemid,function(){
        var newheight = $("#stepContainer").height(); 
        if(newheight > 790){
            $("#main-menu").height(newheight+390);
            $("#sub-menu").height(newheight+200);
        }else{
            $("#main-menu").height(972);
            $("#sub-menu").height(790);
        }
    }); 

}


function toggleLock(itemId){
    $.ajax({
        // The URL for the request
        url: "Controller/item-lock.php?itemid="+itemId,
        type: "GET", 
         
        // Code to run if the request succeeds;
        // the response is passed to the function
        success: function( data ) {
            $("#main").load("View/deposit-item.php?itemid="+data)	
        },
         
        // Code to run if the request fails; the raw request and
        // status codes are passed to the function
        error: function( xhr, status, errorThrown ) {
            alert( "Sorry, there was a problem!" );
        },
            
         
    }); //$.ajax({

}