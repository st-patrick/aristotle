<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

    <title>Aristotle Home</title>
</head>
<body>

<?php

// let's connect to our remote database
$servername = "localhost";
$username = "USER353865_admin";
$password = "iabFDQvaczRfo8T6Aa2I";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";

?>


<div class="container">


    <div class="row mt-4">
        <div class="col">
            <h1 class="h3">HashTable</h1>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <a href="#">post answer</a>
        </div>
    </div>


    <div id="content-goes-here">
        <br>
    </div>


    <div class="row mt-5 pt-5 mb-5">
        <div class="col">
            <hr>
            This is the footer<br>
        </div>
    </div>

</div>


<!-- Optional JavaScript; choose one of the two! -->

<!-- Option 1: Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>

<!-- Option 2: Separate Popper and Bootstrap JS -->
<!--
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.6.0/dist/umd/popper.min.js" integrity="sha384-KsvD1yqQ1/1+IA7gi3P0tyJcT3vR+NdBTt13hSJ2lnve8agRGXTTyNaBYmCR/Nwi" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.min.js" integrity="sha384-nsg8ua9HAw1y0W1btsyWgBklPnCUAFLuTMS2G72MMONqmOymq585AcH49TLBQObG" crossorigin="anonymous"></script>
-->

<script type='text/javascript'>
    var topic = {};

    <?php


    // TODO get this programmatically from the backend database
    $topic = [
        "content" => [
            [
                "name" => "How HashMap Works : A missing piece of hood.",
                "link" => "https://medium.com/zero-equals-false/how-hashmap-works-a-missing-piece-of-hood-29dd28c4c01e",
                "id" => 1,
                "upvotes" => 234,
                "user_voted_for_this" => 0
            ],
            [
                "name" => "Data Structures: Hash Tables - YouTube",
                "link" => "https://www.youtube.com/watch?v=shs0KM3wKv8",
                "id" => 2,
                "upvotes" => 167,
                "user_voted_for_this" => 0
            ],
        ],
    ];


    $js_array = json_encode($topic);
    echo "topic = ". $js_array . ";\n";
    ?>

    let topic_container = document.getElementById('content-goes-here');
    rerender();



    // TODO enable both up and downvote and then store information
    function vote(id, up) {
        if(topic['content'][id]['user_voted_for_this'] == 0) {
            if(up) {
                topic['content'][id]['user_voted_for_this'] = 1;
            } else {
                topic['content'][id]['user_voted_for_this'] = -1;
            }

            topic['content'][id]['upvotes'] += topic['content'][id]['user_voted_for_this'];

            rerender();
        }
    }

    function rerender() {
        topic_container.innerHTML = "";

        for(let i = 0; i < topic['content'].length; i++) {
            let current_content = topic['content'][i];

            let new_topic_html = `
            <div class="row">
                <div class="col">
                <hr>
                <i class="bi bi-caret-up" onclick="vote(` + i + `, true)"></i>
                <i class="bi bi-caret-down" onclick="vote(` + i + `, false)"></i>&nbsp;&nbsp;
                ` + current_content['upvotes'] + `&nbsp;&nbsp;
                <a class="h5" target="_blank" href="` + current_content['link'] + `">` + current_content['name'] + `</a>
                &nbsp;&nbsp; <small>report</small>
                </div>
            </div>`;

            topic_container.innerHTML += new_topic_html;
        };
    }
</script>


</body>
</html>
