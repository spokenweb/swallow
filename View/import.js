$(document).ready(function(){
	
	$("#applicationForm").submit(function(e){
	
		$.ajax({
			// The URL for the request
			url: "Controller/import.php",
			type: "POST", 
			// The data to send (will be converted to a query string)
			data: new FormData( this ),
			processData: false,
      		contentType: false,
			 
			// Code to run if the request succeeds;
			// the response is passed to the function
			success: function( data ) {
                console.log(data);
                $('#report').append("<hr><h2> Report </h2>");
                var report = $.parseJSON(data);
                $.each(report, function(i, obj) {
                    //use obj.id and obj.name here, for example:
                    $('#report').append("<div class='report-item'>"+obj.message+"</div>")
                });

             //   $('#report').append("</table>");

				
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

