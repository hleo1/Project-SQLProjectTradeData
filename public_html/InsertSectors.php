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
    $sector = $_POST['sector'];
    echo "<div class=text-center>";

    if (empty($sector)) {
        echo "<h2>Incomplete insert parameters</h2>";
    } else if (is_numeric($sector)) {
        echo "<h2>Sector must be a string</h2>";
    } else {
        if ($stmt = $conn->prepare("INSERT INTO Sectors VALUES(?)")) {
            $stmt->bind_param("s", $sector);
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