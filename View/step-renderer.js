$( "#collection_id" ).change(function() {
    
    var collectionId = $( this ).val();
    var itemid = $("#itemid").value;
    $("#step-collection-info").load("View/step-renderer-collection.php?collectionid="+collectionId+"&itemid="+itemid);
    
  });

 
  function deleteElement(id,stepname,itemid){
      if(confirm('Are you sure you want to delete this? \n This operation cannot be undone and will result in dataloss')){
        $.ajax({
          // The URL for the request
          url: "Controller/item-delete-step.php?id="+id+"&stepname="+stepname+"&itemid="+itemid,
          type: "GET", 
          
          processData: false,
              contentType: false,
          
          // Code to run if the request succeeds;
          // the response is passed to the function
          success: function( data ) {
            console.log(data);
            var response = jQuery.parseJSON(data);
            $("#stepContainer").load("View/step-renderer.php?name="+encodeURI(response.step)+"&type="+response.stepType+"&itemid="+response.itemid);
            
          },
          
          // Code to run if the request fails; the raw request and
          // status codes are passed to the function
          error: function( xhr, status, errorThrown ) {
            alert( "Sorry, there was a problem!" );
          },
            
          complete: function( xhr, status){
            
          }
          
        }); //$.ajax({
    }
  }

  function removeMultipleFieldValue(itemid,stepname,fieldname,elementid,parentid,steptype){

    $.ajax({
      // The URL for the request
      url: "Controller/item-delete-multiple-field.php?elementid="+elementid+"&stepname="+stepname+"&itemid="+itemid+"&fieldname="+fieldname+"&parentid="+parentid+"&steptype="+steptype,
      type: "GET", 
      
      processData: false,
          contentType: false,
      
      success: function( data ) {
        console.log(data);
        var response = jQuery.parseJSON(data);
        $("#stepContainer").load("View/step-renderer.php?name="+encodeURI(response.step)+"&type="+response.stepType+"&itemid="+response.itemid);
        
      },
      
      // Code to run if the request fails; the raw request and
      // status codes are passed to the function
      error: function( xhr, status, errorThrown ) {
        alert( "Sorry, there was a problem!" );
      }
        
    }); //$.ajax({
  } 


  function addMultipleFieldValue(itemid,stepname,fieldname,steptype,parentid){

    var fieldvalue = $("#"+fieldname+"-ignore").val();

  
    if(fieldvalue == null){ // maybe is a lookupfield field
      var fieldid = parentid+fieldname+"-ignore";
      fieldvalue = window.document.getElementById(fieldid).value;
    }

    $.ajax({
      // The URL for the request
      url: "Controller/item-add-multiple-field.php?stepname="+stepname+"&itemid="+itemid+"&fieldname="+fieldname+"&fieldvalue="+fieldvalue+"&steptype="+steptype+"&parentid="+parentid,
      type: "GET", 
      
      processData: false,
          contentType: false,
      
      success: function( data ) {
        console.log(data);
        var response = jQuery.parseJSON(data);
        $("#stepContainer").load("View/step-renderer.php?name="+encodeURI(response.step)+"&type="+response.stepType+"&itemid="+response.itemid);
        
      },
      
      // Code to run if the request fails; the raw request and
      // status codes are passed to the function
      error: function( xhr, status, errorThrown ) {
        alert( "Sorry, there was a problem!" );
      }
        
    }); //$.ajax({
  } 

  function saveChanges(id){
    
    $.ajax({
      // The URL for the request
      url: "Controller/item-save-step.php",
      type: "POST", 
      // The data to send (will be converted to a query string)
      data: new FormData(document.getElementById(id)) ,
      processData: false,
          contentType: false,
      
      // Code to run if the request succeeds;
      // the response is passed to the function
      success: function( data ) {

        console.log(data);
        
        var response = jQuery.parseJSON(data);
        if(response.errorMgs == ""){
            alert('Changes have been saved');
        }else{
           alert(response.errorMgs);
        }

        $("#stepContainer").load("View/step-renderer.php?name="+encodeURI(response.step)+"&type="+response.stepType+"&itemid="+response.itemid);
       
        
      },
      
      // Code to run if the request fails; the raw request and
      // status codes are passed to the function
      error: function( xhr, status, errorThrown ) {
        alert( "Sorry, there was a problem!" );
      },
        
      complete: function( xhr, status){
        
      }
      
    }); //$.ajax({
  }





  $(document).ready(function(){
	
    $("#stepForm").submit(function(e){
    
      $.ajax({
        // The URL for the request
        url: "Controller/item-save-step.php",
        type: "POST", 
        // The data to send (will be converted to a query string)
        data: new FormData( this ),
        processData: false,
            contentType: false,
         
        // Code to run if the request succeeds;
        // the response is passed to the function
        success: function( data ) {
          console.log(data);
          var response = jQuery.parseJSON(data);
          if(response.errorMgs == ""){
              alert('Changes have been saved');
          }else{
             alert(response.errorMgs);
          }

          $("#stepContainer").load("View/step-renderer.php?name="+encodeURI(response.step)+"&type="+response.stepType+"&itemid="+response.itemid); 
          
        },
         
        // Code to run if the request fails; the raw request and
        // status codes are passed to the function
        error: function( xhr, status, errorThrown ) {
                alert( "Sorry, there was a problem!" );
        },
          
        complete: function( xhr, status){
          
        }
         
      }); //$.ajax({
      
      e.preventDefault();
      })



  });
