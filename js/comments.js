/**
 * Created by Vito on 29/05/2020.
 */
function sendComment(){
    var text = $("#comment").val().trim();
    if(text.length > 0){
        $.ajax({
            url: "php/put_comment.php",
            type: 'POST',
            data: {"text":text},
            success: function(data) {
                if(data=="ok")
                    alert("Thanks for the comment!");
                $("#comment").val("")
            },
            error: function(error) {
                console.log(error);
            },
        });
    }
}