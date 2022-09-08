<!--
	Sebastian Cabrejos (scabrej1) & Hubert Leo (hleo1)
	Project Phase E due May 11, 11 p.m.
-->

<head>
    <title>Query 8</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<body>
    <?php
    //Given a year, what were the top 5 main sectors that contributed to the top polluter for that year’s 
    //export growth during that time?

    include 'open.php';
    $year = $_POST['year'];
    $next_year = $year + 1;
    $currentYear = array();
    $nextYear = array();
    echo "<div class=text-center>";
    echo "<h2>Query 8</h2>";

    if (empty($year)) {
        echo "<h1>No year provided<h1><br><br>";
    } else {
        if ($stmt = $conn->prepare("CALL TopPolluter(?)")) {
            $stmt->bind_param("i", $year);

            if ($stmt->execute()) {
                $result = $stmt->get_result();
                if ($result->num_rows == 0) {
                    echo "<h1>Year not available in table</h1>";
                } else {
                    while ($row = $result->fetch_row()) {
                        echo "<h2>Top Polluter in $year is ".$row[0]."</h2>
                        <h3>It's top 5 main exports that grew nominally in $year were: </h3>";
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

    if (empty($year) || !($year >= 2005 && $year <= 2019)) {
        echo "<h1>No year provided or year not within range</h1><br><br>";
    } else {
        if ($stmt = $conn->prepare("CALL Top5ExportGrowthSectorsForLargestPolluter(?)")) {
            $stmt->bind_param("i", $year);

            if ($stmt->execute()) {
                $result = $stmt->get_result();
                if ($result->num_rows == 0) {
                    echo "<h1>Input a Valid Year between 2005 to 2019</h1>";
                } else {
                    echo "<table class=\"table table-condensed table-striped  table-hover\">";
                    echo "<tr><th>Sector</th><th>$year Exports</th><th>$next_year Exports</th><th>Nominal Growth</th></tr>";
                    foreach ($result as $row) {
                        array_push($currentYear, array("label" => $row["sector"], "y" => $row["CurrentYearExports"]));
                        array_push($nextYear, array("label" => $row["sector"], "y" => $row["NextYearExports"]));
                        echo "<tr>";
                        echo "<td>" . $row["sector"] . "</td>";
                        echo "<td>" . $row["CurrentYearExports"] . "</td>";
                        echo "<td>" . $row["NextYearExports"] . "</td>";
                        echo "<td>" . $row["NominalGrowth"] . "</td>";
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
                theme: "light2", 
                axisX: {
                    title: "Sector"
                },
                axisY: {
                    title: "Export Values (US Dollars)"
                },
                data: [{
                        type: "bar",
                        showInLegend: true,
                        name: "Present Year",
                        color: "gold",
                        dataPoints: <?php echo json_encode($currentYear, JSON_NUMERIC_CHECK); ?>
                    },
                    {
                        type: "bar",
                        showInLegend: true,
                        name: "Next Year",
                        color: "silver",
                        dataPoints: <?php echo json_encode($nextYear, JSON_NUMERIC_CHECK); ?>
                    }
                ]
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