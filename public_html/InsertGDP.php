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
    //open a connection to dbase server
    include 'open.php';
    $country = $_POST['country'];
    $year = $_POST['year'];
    $gdp = $_POST['gdp'];
    echo "<div class=text-center>";

    if (empty($country) || empty($year) || empty($gdp)) {
        echo "<h2>Incomplete insert parameters</h2>";
    } else if (is_numeric($country) || !is_numeric($year) || !is_numeric($gdp)) {
        echo "<h2>Country must be a string, Year must be a number, GDP must be a number</h2>";
    } else {
        if ($stmt = $conn->prepare("INSERT INTO GDP VALUES(?, ?, ?)")) {
            $stmt->bind_param("sii", $country, $year, $gdp);
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