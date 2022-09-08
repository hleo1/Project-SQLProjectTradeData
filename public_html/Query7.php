<!--
	Sebastian Cabrejos (scabrej1) & Hubert Leo (hleo1)
	Project Phase E due May 11, 11 p.m.
-->

<head>
    <title>Query 7</title>
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
    $start_year = $_POST['start_year'];
    $end_year = $_POST['end_year'];
    $dataPoints = array();
    $importDataPoints = array();
    $exportDataPoints = array();
    echo "<div class=text-center>";
    echo "<h2>Query 7</h2>";

    if (empty($country) || empty($start_year) || empty($end_year)) {
        echo "<h1>No country name, start year, and/or end year provided</h1><br><br>";
    } else {
        if ($start_year >= 2005 and $end_year <= 2019 and $start_year < $end_year) {
            echo "<h2>Show GDP for $country between $start_year and $end_year</h2>";

            if ($stmt = $conn->prepare("CALL ShowCountryGDP(?,?,?)")) {
                $stmt->bind_param("sii", $country, $start_year, $end_year);

                if ($stmt->execute()) {
                    $result = $stmt->get_result();
                    if ($result->num_rows == 0) {
                        echo "<h1>Unable to locate country's GDP values for that year.</h1>";
                    } else {
                        foreach ($result as $row) {
                            array_push($dataPoints, array(
                                "label" => $row["year"],
                                "y" => $row["gdp"]
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

            if ($stmt = $conn->prepare("CALL ShowCountryImports(?,?,?)")) {
                $stmt->bind_param("sii", $country, $start_year, $end_year);

                if ($stmt->execute()) {
                    $result = $stmt->get_result();
                    if ($result->num_rows == 0) {
                        echo "<h1>Unable to locate country's import values for that year.</h1>";
                    } else {
                        foreach ($result as $row) {
                            array_push($importDataPoints, array(
                                "label" => $row["year"],
                                "y" => $row["Total Imports"]
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

            if ($stmt = $conn->prepare("CALL ShowCountryExports(?,?,?)")) {
                $stmt->bind_param("sii", $country, $start_year, $end_year);

                if ($stmt->execute()) {
                    $result = $stmt->get_result();
                    if ($result->num_rows == 0) {
                        echo "<h1>Unable to locate country's exports for that given year</h1>";
                    } else {
                        foreach ($result as $row) {
                            array_push($exportDataPoints, array(
                                "label" => $row["year"],
                                "y" => $row["Total Exports"]
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
        } else {
            echo "<h1>Time Period not between 2005 - 2019</h1>";
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
                    title: "Years"
                },
                axisY: {
                    title: "US Dollars"
                },
                data: [{
                    type: "line",
                    showInLegend: true,
                    name: "GDP",
                    dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
                }, {
                    type: "line",
                    showInLegend: true,
                    name: "Imports",
                    dataPoints: <?php echo json_encode($importDataPoints, JSON_NUMERIC_CHECK); ?>
                }, {
                    type: "line",
                    showInLegend: true,
                    name: "Exports",
                    dataPoints: <?php echo json_encode($exportDataPoints, JSON_NUMERIC_CHECK); ?>
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

</html>