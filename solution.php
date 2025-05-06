<?php

$config = include('db_config.php');

$conn = new mysqli(
    $config['host'],
    $config['user'],
    $config['password'],
    $config['database']
);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$equation = $_GET['equation'] ?? '';

$solution = '';
if ($equation !== '') {
    $stmt = $conn->prepare("SELECT solution FROM Equation WHERE equation = ? LIMIT 1");
    $stmt->bind_param("s", $equation);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $solution = $row['solution'];
    } else {
        $solution = "No solution found for that equation.";
    }

    $stmt->close();
} else {
    $solution = "No equation provided.";
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<body>
<p><strong>Equation:</strong> <?= $equation ?></p>
<p><strong>Solution:</strong> <?= $solution ?></p>
</body>
</html>
