<?php

// Initialize the session
session_start();
echo $_SESSION["test"];

?>

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

    <title>Aristotle: find the best educational content</title>
</head>
<body>

<?php

// TODO use URL rewrite to be able to see topic in actual URL, not GET vars

// let's connect to our remote database
$servername = "localhost";
$username = "USER353865_admin";
$password = "iabFDQvaczRfo8T6Aa2I";
$database = "db_353865_6";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// which topic are we on?
if(isset($_GET['id'])) { //check if topic id is set
    // TODO validate id

    $topic_id = intval($_GET['id']);

    // TODO is it possible that we will some day have id 0?
    if ($topic_id == 0) {
        die("invalid topic ID");
    }
}


// handle posting new explanation ///////////
$message = "";
if(isset($_POST['SubmitButton'])){ //check if form was submitted

    //get input texts
    $url = $_POST['url'];
    $title = $_POST['title'];

    echo "new explanation successfully posted: ".$title;

    // prepared statement to prevent SQL injection
    $stmt = $conn->prepare('INSERT INTO `explanations` (`id`, `topic_id`, `url`, `upvotes`, `title`) VALUES (NULL, ?, ?, 0, ?);');
    $stmt->bind_param('iss', $topic_id, $url, $title); // 's' specifies the variable type => 'string'

    $stmt->execute();

    $message = "Success! You created the topic ".$title . "<br><br>";
} elseif (isset($_POST['upvote'])) {

    // make sure only logged in users can upvote
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] == false) {
        echo "you must be logged in to upvote";
    } else {

        // DONE check if user has already upvoted this question
        $stmt = $conn->prepare("SELECT * FROM `junction_explanations_users-who-voted` WHERE (`explanation_id`,`user_id`) = (?,?) ;");
        $stmt->bind_param('ii', $_POST['upvote'], $_SESSION['id']); // 'i' specifies the variable type => 'integer'
        $stmt->execute();

        $result = $stmt->get_result();

        // DONE if result is not empty, cancel upvote
        if ($result->num_rows > 0) {
            echo "you already upvoted this explanation";
        } else {

            $stmt = $conn->prepare('UPDATE explanations SET upvotes = upvotes + 1 WHERE id = ?');
            $stmt->bind_param('i', $_POST['upvote']); // 's' specifies the variable type => 'string'
            $stmt->execute();

            // update conjunction table to only allow 1 upvote per user
            $stmt = $conn->prepare('INSERT INTO `junction_explanations_users-who-voted` (`explanation_id`,`user_id`) VALUES (?, ?);');
            $stmt->bind_param('ii', $_POST['upvote'], $_SESSION['id']); // 's' specifies the variable type => 'string'
            $stmt->execute();

            echo "you upvoted explanation with id ".$_POST['upvote'];
        }

    }

}



// get topics ////////////////////

// prepared statement to prevent SQL injection
$stmt = $conn->prepare("SELECT `id`, `title`, `upvotes`, `url` FROM `explanations` WHERE `topic_id` = ? ;");
$stmt->bind_param('i', $topic_id); // 'i' specifies the variable type => 'integer'
$stmt->execute();

// Extract result set and loop rows
$result = $stmt->get_result();
$data = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();
$conn->close();


//TODO use google safe browsing API to check against malicious URLs
//TODO if an actual URL was posted (and not javascript, a string or other stuff we don't want)
//TODO possibility to report URLs



?>


<div class="container">


    <div class="row mt-4">
        <div class="col">
            <h1 class="h3"><a href="/index.php">Aristotle</a></h1><h1 class="h3"> >> <?php echo $_GET['title']; ?></h1>
        </div>
    </div>


    <div class="row">
        <div class="col">
            <a href="#" onclick="document.getElementById('post-explanation-row').classList.remove('d-none');">post an explanation</a>
        </div>
    </div>

    <div class="row mt-3 mb-3 py-3 bg-light d-none" id="post-explanation-row">
        <div class="col">
            <?php echo $message; ?>
            Found a good explanation? Paste the URL here:<br>
            <form action="" method="post">
                <input type="url" name="url"/><br>
                <br>
                And then the title you want to appear here:<br>
                <input type="text" name="title"/><br>
                <input type="submit" name="SubmitButton" value="post explanation now"/>
            </form>
            <br>
            <a href="#" onclick="document.getElementById('post-explanation-row').classList.add('d-none');">hide form</a>
        </div>
    </div>



    <div id="content-goes-here">
        <br>
    </div>


    <div class="row mt-5 pt-5 mb-5">
        <div class="col">
            <hr>
            <a href="signup.php">sign up</a><br>
            <a href="login.php">login</a><br>
            This is the footer<br>
            Copyright Aristotle<br>
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
    var explanations = {};

    <?php


    // TODO get this programmatically from the backend database
    $explanations = $data;


    $js_array = json_encode($explanations);
    echo "explanations = ". $js_array . ";\n";
    ?>

    let topic_container = document.getElementById('content-goes-here');
    rerender();



    // TODO enable both up and downvote and then store information
    function vote(id, up) {
            if(up) {
                explanations[id]['user_voted_for_this'] = 1;
            } else {
                explanations[id]['user_voted_for_this'] = -1;
            }

            explanations[id]['upvotes'] += explanations[id]['user_voted_for_this'];

            rerender();
    }

    function rerender() {
        topic_container.innerHTML = "";

        for(let i = 0; i < explanations.length; i++) {
            let current_content = explanations[i];

            // TODO click on upvote needs to be an API call or sth AJAXy that calls a php file to store upvote in conjunction table
            let new_topic_html = `
            <div class="row">
                <div class="col">
                <hr>
                <form action=""  method="post" class="d-none" id="upvote`+ current_content['id'] +`">
                    <input type="number" value="`+ current_content['id'] +`" name="upvote"/>
                </form>
                <i class="bi bi-caret-up" onclick="vote(` + i + `, true); document.getElementById('upvote`+ current_content['id'] +`').submit();"></i>
                ` + current_content['upvotes'] + `&nbsp;&nbsp;
                <a class="h5" target="_blank" href="` + current_content['url'] + `">` + current_content['title'] + `</a>
                </div>
            </div>`;

            topic_container.innerHTML += new_topic_html;
        };
    }
</script>


</body>
</html>
