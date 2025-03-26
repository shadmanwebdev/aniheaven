$(document).ready(function(){
    // Load more data
    $('.load-more').click(function(){
        console.log('clicked!');
        var row = Number($('#row').val());
        var allcount = Number($('#all').val());
        row = row + 1;

        if(row < allcount){
            $("#row").val(row);

            $.ajax({
                url: 'load_post.php',
                type: 'post',
                data: {row:row},
                beforeSend:function(){
                    $(".load-more").text("Loading...");
                },
                success: function(response){

                    // Setting little delay while displaying new content
                    setTimeout(function() {
                        // appending posts after last post with class="post"
                        $(".post:last").after(response).show().fadeIn("slow");

                        var rowno = row + 1;

                        // checking row value is greater than allcount or not
                        if(rowno >= allcount){
                            // Change the text and background
                            $('.load-more').text("Hide");
                        }else{
                            $(".load-more").text("Load more");
                        }
                    }, 2000);


                }
            });
        } else {
            $('.load-more').text("Loading...");
            console.log('removing...');

            // Setting little delay while removing contents
            setTimeout(function() {

                // When row is greater than allcount then remove all class='post' element after 3 element
                $('.post:nth-child(1)').nextAll('.post').remove().fadeIn("slow");

                // Reset the value of row
                $("#row").val(0);

                // Change the text and background
                $('.load-more').text("Load more");

            }, 2000);
        }
    });
});