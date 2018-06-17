/**
 * Created by Sebbans on 2018-06-17.
 */

$(document).ready(function() {
    $("#feedform").validate({
        submitHandler: FeedDisplay
    });
});

function FeedDisplay(){
    var data = $("#feedform").serialize();
    $.ajax({
        type: "POST",
        url: "includes/doSomething.php",
        data: data,
        success: function(response){
            console.log(response);
            response = JSON.parse(response);
            var errors = response['errors'];
            var successful = response['successful'];
            var statusmessage = response['statusmessage'];

            if(successful){
                //console.log(response['variables']['output']);
                console.log($("#githubfeed"));
                $("#githubfeed").html(response['variables']['output']);
            }else{
                alert(statusmessage);
                //alert("No events found.");
            }
        }
    });
}