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
    $continent = $_POST['continent'];
    $country = $_POST['country'];
    echo "<div class=text-center>";

    if (empty($country) || empty($continent)) {
        echo "<h2>Incomplete insert parameters</h2>";
    } else if (is_numeric($country) || is_numeric($continent) ){
        echo "<h2>Country or Continent is not a string!</h2>";
    } else {
        if ($stmt = $conn->prepare("INSERT INTO Countries VALUES(?, ?)")) {
            $stmt->bind_param("ss", $country, $continent);
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
    $conn->close();

    ?>
</body>