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
    $leader = $_POST['leader'];
    $start_year = $_POST['start_year'];
    $end_year = $_POST['end_year'];
    echo "<div class=text-center>";

    if (empty($leader) || empty($start_year) || empty($end_year)) {
        echo "<h2>Incomplete insert parameters</h2>";
    } else if (is_numeric($leader) || !is_numeric($start_year) || !is_numeric($end_year)) {
        echo "<h2>leader must be a string, and start/end years must be numbers</h2>";
    } else {
        if ($stmt = $conn->prepare("INSERT INTO US_Leaders VALUES(?, ?, ?)")) {
            $stmt->bind_param("sii", $leader, $start_year, $end_year);
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