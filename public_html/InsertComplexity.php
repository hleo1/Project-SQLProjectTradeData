<head>
    <title> Insert Countries</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<body>
    <?php

    include 'open.php';
    $year = $_POST['year'];
    $country = $_POST['country'];
    $complexity = $_POST['complexity'];
    echo "<div class=text-center>";
    
    if (empty($country) || empty($year) || empty($complexity)) {
        echo "<h2>Incomplete insert parameters</h2>";
    } else if (!is_numeric($year) || is_numeric($country) || !is_numeric($complexity)) {
        echo "<h2>Year must be a number, country must be a string, complexity must be a number</h2>";
    } else {
        if ($stmt = $conn->prepare("INSERT INTO Economic_Complexity VALUES(?, ?, ?)")) {
            $stmt->bind_param("sss", $year, $country, $complexity);
            if ($stmt->execute()) {
                echo "<h1>Insert succeeded!</h1>";
            } else {
                echo "<h1>Insert failed!</h1>";
            }
            $stmt->close();
        } else {
            echo "Prepare failed.<br>";
            $error = $conn->errno . ' ' . $conn->error;
            echo $error;
        }
    }
    echo "</div>";
    $conn->close();

    ?>
</body>