<!--
	Sebastian Cabrejos (scabrej1) & Hubert Leo (hleo1)
	Project Phase E due May 11, 11 p.m.
-->

<head>
    <title> Query 10</title>
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

    $datapoints = array();

    echo "<div class=text-center>";
    echo "<h2>Query 10</h2>";

    if (empty($year) || empty($country)) {
        echo "<h1>Incomplete search parameters: missing year or country</h1>";
    } else {
        if ($stmt = $conn->prepare("CALL TopImportersLessEconomicallyComplex(?, ?)")) {
            $stmt->bind_param("si", $country, $year);

            if ($stmt->execute()) {
                $result = $stmt->get_result();
                if ($result->num_rows == 0) {
                    echo "<h1>No Importers Lower than the given country in that year or 
                            Invalid Country or Year not in range (2005 - 2019 only)</h1>";
                } else {
                    echo "<table class=\"table table-condensed table-striped  table-hover\">";
                    echo "<tr><th>Importing Country</th><th>Total Imports</th></tr>";
                    foreach ($result as $row) {
                        array_push($datapoints, array("label" => $row["country_origin_name"], "y" => $row["Total Imports"]));
                        echo "<tr>";
                        echo "<td>" . $row["country_origin_name"] . "</td>";
                        echo "<td>" . $row["Total Imports"] . "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
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
                theme: "light1",
                axisX: {
                    title: "Importing Country"
                },
                axisY: {
                    title: "Import Total (US Dollars)"
                },
                data: [{
                    type: "bar",
                    color: "gold",
                    dataPoints: <?php echo json_encode($datapoints, JSON_NUMERIC_CHECK); ?>
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