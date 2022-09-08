<!-- 
	Sebastian Cabrejos (scabrej1) & Hubert Leo (hleo1)
	Project Phase E due May 11, 11 p.m.
-->

<head>
    <title>Delete TradeFlows</title>
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
    $sector = $_POST['sector'];
    $year = $_POST['year'];
    echo "<div class=text-center>";

    if (empty($country_origin) || empty($country_dest) || empty($sector) || empty($year)) {
        echo "<h2>Incomplete delete parameters</h2>";
    } else if (is_numeric($country_origin) || is_numeric($country_dest) || is_numeric($sector) || !is_numeric($year)) {
        echo "<h2>country origin and country destination must be a string,
                sector must be a string, year must be a number</h2>";
    } else {
        if ($stmt = $conn->prepare("DELETE FROM TradeFlows WHERE country_origin_name LIKE ? 
                                    AND country_dest_name LIKE ? AND sector LIKE ? AND year = ?")) {
            $stmt->bind_param("sssi", $country_origin, $country_dest, $sector, $year);
            if ($stmt->execute()) {
                echo "<h1>Deletion succeeded!</h1>";
            } else {
                echo "<h1>Deletion failed!</h1>";
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