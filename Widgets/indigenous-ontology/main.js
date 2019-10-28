$( "#inputquery" ).keyup(function() {
    var querystring = $(this).val();
    $("#results").empty();
    $.ajax({
        url: "lookup.php?query="+querystring,
        type: "GET", 
        success: function( data ) {
            if(data != ''){
                var response = jQuery.parseJSON(data);
                $.each(response,function(i,term){
                    var id = term.replace(/ /g,'');
                    id = id.replace(/[^\w\s]/gi, '');
                    $("#results").append("<p id=\""+id+"\"><button type='button' class='btn btn-primary' style='padding: 0px 4px;margin-right:10px;' onclick='select(\""+id+"\")'>+</button><span id=\""+id+"-text\">"+term+"</span></p>");
                });
            }      
        },
         
        error: function( xhr, status, errorThrown ) {
            alert( "Sorry, there was a problem!" );
        }
    }); //$.ajax({

  });

  function select(i_term){     
      
      //textField.value = i_term;
      
      $("p").removeClass("selected");
      var id = i_term.replace(/ /g,'');
      id = id.replace(/[^\w\s]/gi, '');
      $("#"+id).addClass("selected");

      textField = window.parent.document.getElementById(textfield_id);
      textField.value =  $("#"+id+"-text").text();
  }

const urlParams = new URLSearchParams(window.location.search);
var textfield_id = urlParams.get('id');
    
