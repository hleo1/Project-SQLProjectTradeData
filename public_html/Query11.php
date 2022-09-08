<!--
	Sebastian Cabrejos (scabrej1) & Hubert Leo (hleo1)
	Project Phase E due May 11, 11 p.m.
-->

<head>
    <title>Query 11</title>
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
    $dataPoints = array();
    echo "<div class=text-center>";
    echo "<h2>Query 11</h2>";

    if (empty($country) || empty($year)) {
        echo "<h1>No country and/or year provided<h1><br><br>";
    } else {
        echo "<h2>Show Top Bilateral Sector/Partners for $country in $year</h2>";

        if ($stmt = $conn->prepare("CALL TopBilateralPartnerSector(?, ?)")) {
            $stmt->bind_param("si", $country, $year);

            if ($stmt->execute()) {
                $result = $stmt->get_result();
                if ($result->num_rows == 0) {
                    echo "<h1>Invalid Country or Time Period!</h1>";
                } else {
                    foreach ($result as $row) {
                        array_push($dataPoints, array(
                            "label" => $row["country_dest_name"]  . " | " . $row["sector"],
                            "y" => $row["TotalBilateralTrade"]
                        ));
                    }
                }

                $result->free_result();
            } else {
                echo "<h1>Execute failed.</h1><br>";
            }

            $stmt->close();
        } else {
            echo "<h1>Prepare failed.</h1><br>";
            $error = $conn->errno . ' ' . $conn->error;
            echo $error;
        }
    }
    echo "</div>";
    $conn->close();
    ?>
</body>

<html>

<head>
    <script>
        window.onload = function() {

            var chart = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,
                theme: "light2",
                axisX: {
                    title: "Bilateral Partner/Sector"
                },
                axisY: {
                    title: "US Dollars"
                },
                data: [{
                    type: "bar",
                    dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
                }]
            });
            chart.render();

        }
    </script>
</head>

<body>
    <div id="chartContainer" style="height: 370px; width: 100%;"></div>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</body>

</html>