<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

    <title>Explain ______ to me</title>
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
    $submitted_username = $_POST['username']; //get username from form
    $submitted_password = $_POST['password']; //get password from form
    $hash = password_hash($submitted_password, PASSWORD_DEFAULT);

    // prepared statement to prevent SQL injection
    $stmt = $conn->prepare('INSERT INTO `users` (`id`, `username`, `password-hash`) VALUES (NULL, ?, ?);');
    $stmt->bind_param('ss', $submitted_username, $hash); // 's' specifies the variable type => 'string'

    $stmt->execute();

    $message = "Success! You created your account under the username ".$submitted_username . "<br><br>";
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
            <h1 class="h3">Please explain _______ to me >> Login</h1>
        </div>
    </div>

    <div class="row mt-3 mb-3 py-3 bg-light" id="create-topic-row">
        <div class="col">
            <?php echo $message; ?>
            Ok, let's get you signed up<br>
            <br><br>
            <form action="" method="post">
                <div class="mb-3">
                    <label for="exampleInputUsername" class="form-label">Username</label>
                    <input type="text" class="form-control" id="exampleInputUsername" aria-describedby="usernameHelp" name="username">
                    <div id="usernameHelp" class="form-text">We'll never share your user details with anyone else.</div>
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Password</label>
                    <input type="password" class="form-control" id="exampleInputPassword1" aria-describedby="passwordHelp" name="password">
                    <div id="passwordHelp" class="form-text">Your password is stored encrypted in a way we can't even know what it is.</div>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1">
                    <label class="form-check-label" for="exampleCheck1"><?php echo (bool)random_int(0, 1) ? "" : "don't"; ?> check this box</label>
                </div>
                <input type="submit" name="SubmitButton" class="btn btn-primary" value="Sign up now" />
            </form>
        </div>
    </div>





    <div class="row mt-5 pt-5 mb-5">
        <div class="col">
            <hr>
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
