<!--
	Sebastian Cabrejos (scabrej1) & Hubert Leo (hleo1)
	Project Phase E due May 11, 11 p.m.
-->

<head>
    <title>Query 9</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<body>
    <?php

    include 'open.php';

    //find the exports that grew the most and shrunk the most under US presidential terms
    $pres_term = $_POST['pres_term'];
    $currentYear = array();
    $nextYear = array();
    $bottomPast = array();
    $bottomFuture = array();
    echo "<div class=text-center>";
    echo "<h2>Query 9</h2>";

    if ($pres_term == "SELECT") {
        echo "<h1>Input a Presidential Term!</h1>";
    } else {
        $start_year = 0;
        $end_year = 0;
        if (empty($pres_term)) {
            echo "<h1>select a presidential term!</h1>";
        } else {
            if ($pres_term == "george_second") {
                $start_year = 2005;
                $end_year = 2008;
            } else if ($pres_term == "obama_first") {
                $start_year = 2008;
                $end_year = 2012;
            } else if ($pres_term == "obama_second") {
                $start_year = 2012;
                $end_year = 2016;
            } else if ($pres_term == "donald_first") {
                $start_year = 2016;
                $end_year = 2019;
            }

            echo "<h2>Export Markets that Grew the Most</h2>";
            if ($stmt = $conn->prepare("CALL ShowUSTopExportGrowths(?,?)")) {
                $stmt->bind_param("ii", $start_year, $end_year);

                if ($stmt->execute()) {
                    $result = $stmt->get_result();
                    if ($result->num_rows == 0) {
                        echo "<h1>No export values found for the specified country name</h1>";
                    } else {
                        echo "<table class=\"table table-condensed table-striped  table-hover\">";
                        echo "<tr><th>Sector</th><th>US $start_year Exports</th><th>$end_year Exports</th><th>Nominal Growth</th></tr>";
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

            echo "<h2>Export Markets that Grew the Least</h2>";
            if ($stmt = $conn->prepare("CALL ShowUSBottomExportGrowths(?,?)")) {
                $stmt->bind_param("ii", $start_year, $end_year);

                if ($stmt->execute()) {
                    $result = $stmt->get_result();
                    if ($result->num_rows == 0) {
                        echo "<h1>No export values found for the specified country name</h1>";
                    } else {
                        echo "<table class=\"table table-condensed table-striped  table-hover\">";
                        echo "<tr><th>Sector</th><th>US $start_year Exports</th><th>$end_year Exports</th><th>Nominal Growth</th></tr>";
                        foreach ($result as $row) {
                            array_push($bottomPast, array("label" => $row["sector"], "y" => $row["CurrentYearExports"]));
                            array_push($bottomFuture, array("label" => $row["sector"], "y" => $row["NextYearExports"]));
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
                title: {
                    text: "Export Markets that Grew the Most"
                },
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

            var chartTwo = new CanvasJS.Chart("chartTwoContainer", {
                animationEnabled: true,
                theme: "light2",
                title: {
                    text: "Export Markets that Grew the Least"
                },
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
                        dataPoints: <?php echo json_encode($bottomPast, JSON_NUMERIC_CHECK); ?>
                    },
                    {
                        type: "bar",
                        showInLegend: true,
                        name: "Next Year",
                        color: "silver",
                        dataPoints: <?php echo json_encode($bottomFuture, JSON_NUMERIC_CHECK); ?>
                    }
                ]
            });
            chartTwo.render();

        }
    </script>
</head>

<body>
    <div id="chartContainer" style="height: 370px; width: 100%;"></div>
    <div id="chartTwoContainer" style="height: 370px; width: 100%;"></div>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</body>

</html>