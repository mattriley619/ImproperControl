<?php

$config = include('db_config.php');

$conn = new mysqli(
    $config['host'],
    $config['user'],
    $config['password'],
    $config['database']
);

$equation = '';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["equation"])) {
    $equation = $conn->real_escape_string($_POST["equation"]);

    $sql = "INSERT INTO Equation (equation) VALUES ('$equation')";
    if ($conn->query($sql) === TRUE) {
        echo "Equation saved successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<body>
<h1>Enter an Equation</h1>
<form method="post" action="">
    <input type="text" name="equation" required>
    <input type="submit" value="Save">
</form>
<a href="solution.php?equation=<?= urlencode($equation) ?>">View Solution</a>
</body>
</html>
