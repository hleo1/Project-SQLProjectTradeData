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
    $country = $_POST['country'];
    $year = $_POST['year'];
    $co2 = $_POST['co2'];
    echo "<div class=text-center>";

    if (empty($country) || empty($year) || empty($co2)) {
        echo "<h2>Incomplete insert parameters</h2>";
    } else if (!is_numeric($year) || !is_numeric($co2) || is_numeric($country)) {
        echo "<h2>country must be a string, year must be a number, co2 must be a number</h2>";
    } else {
        if ($stmt = $conn->prepare("INSERT INTO Climate VALUES(?, ?, ?)")) {
            $stmt->bind_param("sii", $country, $year, $co2);
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