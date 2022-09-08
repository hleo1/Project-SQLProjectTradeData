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
    $country_origin = $_POST['country_origin'];
    $country_dest = $_POST['country_dest_name'];
    $export_values = $_POST['export_values'];
    $sector = $_POST['sector'];
    $year = $_POST['year'];
    echo "<div class=text-center>";

    if (empty($country_origin) || empty($country_dest) || empty($export_values) || empty($sector) || empty($year)) {
        echo "<h2>Incomplete insert parameters</h2>";
    } else if (is_numeric($country_origin) || is_numeric($country_dest) || !is_numeric($export_values) ||
                is_numeric($sector) || !is_numeric($year)) {
        echo "<h2>country origin and country destination must be a string, export_values must be a number, 
                sector must be a string, year must be a number</h2>";
    } else {
        if ($stmt = $conn->prepare("INSERT INTO TradeFlows VALUES(?, ?, ?, ?, ?)")) {
            $stmt->bind_param("ssisi", $country_origin, $country_dest, $export_values, $sector, $year);
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