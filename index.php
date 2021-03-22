<?php

// Initialize the session
session_start();


// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){

    echo "not logged in";

    //header("location: login.php");
    //exit;
} else {
        echo "logged in as ". $_SESSION["username"];
}

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

    <title>Aristotle Home</title>
</head>
<body>

<?php


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

// handle posting new topic ///////////
$message = "";
if(isset($_POST['SubmitButton'])){ //check if form was submitted
    $input = $_POST['inputText']; //get input text

    // prepared statement to prevent SQL injection
    $stmt = $conn->prepare('INSERT INTO `topics` (`id`, `title`) VALUES (NULL, ?);');
    $stmt->bind_param('s', $input); // 's' specifies the variable type => 'string'

    $stmt->execute();

    $message = "Success! You created the topic ".$input . "<br><br>";
}


// get topics ////////////////////

$result = $conn->query("SELECT * FROM `topics`;");

if ($result->num_rows > 0) {
    $result_array = $result->fetch_all(MYSQLI_ASSOC);
} else {
    echo "0 results";
}
$conn->close();

?>


<div class="container">


    <div class="row mt-4">
        <div class="col">
            <h1 class="h3">Please explain _______ to me</h1>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <a href="#" onclick="document.getElementById('create-topic-row').classList.remove('d-none');">create topic</a>
        </div>
    </div>

    <div class="row mt-3 mb-3 py-3 bg-light d-none" id="create-topic-row">
        <div class="col">
            <?php echo $message; ?>
            Ok, let's create a new topic. Enter the title you want your topic to appear under:<br>
            <form action="" method="post">
                <input type="text" name="inputText"/>
                <input type="submit" name="SubmitButton" value="create topic now"/>
            </form>
            <br>
            <a href="#" onclick="document.getElementById('create-topic-row').classList.add('d-none');">hide form</a>
        </div>
    </div>



    <?php

    /*
    $course = [
        "topics" => [
            [
                "name" => "Hash Tables",
                "id" => 1,
                "upvotes" => 3000,
                "suggestions" => 5
            ],
            [
                "name" => "LinkedLists",
                "id" => 2,
                "upvotes" => 3000,
                "suggestions" => 5
            ],
        ],
    ];*/

    $topics = $result_array;


    for($i = 0; $i < count($topics); $i++) {
        $current_topic = $topics[$i];

        echo '<div class="row">'.
                '<div class="col">'.
                    '<hr>'.
                    '<a class="h5" href="topic.php?id='. $current_topic['id'] . '&title='. $current_topic['title'] .'">' .$current_topic['title']. '</a>';
                '</div>'.
            '</div>';
    }


    ?>


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


</body>
</html>
