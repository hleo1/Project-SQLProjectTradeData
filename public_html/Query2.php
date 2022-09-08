<!--
	Sebastian Cabrejos (scabrej1) & Hubert Leo (hleo1)
	Project Phase E due May 11, 11 p.m.
-->

<head>
	<title>Query_2</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<body>
	<?php

	include 'open.php';

	$dataPoints = array();
	$dataPoints2 = array();
	$dataPoints3 = array();

	// Error handling
	// ini_set('error_reporting', E_ALL);
	// ini_set('display_errors', true);

	$country_name = $_POST['country_name'];
	$industry = $_POST['industry'];
	echo "<div class=text-center>";
	echo "<h2>Query 2</h2>";

	if (empty($country_name) || empty($industry)) {
		echo "<h1>No country or industry value(s) provided</h1><br><br>";
	} else {
		echo "<h3> Country Name: " . $country_name . "</h3>";
		echo "<h3> Industry: " . $industry . "</h3><br>";

		if ($stmt = $conn->prepare("CALL Query2(?,?)")) {
			$stmt->bind_param("ss", $country_name, $industry);

			if ($stmt->execute()) {
				$result = $stmt->get_result();
				if ($result->num_rows == 0) {
					echo "<h1>No values found for the specified country name and/or year</h1>";
				} else {
					foreach ($result as $row) {
						array_push($dataPoints, array(
							"label" => $row["year"],
							"y" => $row["export_value"]
						));
						array_push($dataPoints2, array(
							"label" => $row["year"],
							"y" => $row["gdp"]
						));
						array_push($dataPoints3, array(
							"label" => $row["year"],
							"y" => $row["co2_emissions"]
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
	<script type="text/javascript">
		window.onload = function() {
			var chart = new CanvasJS.Chart("chartContainer", {
				animationEnabled: true,
				theme: "light1",
				title: {
					text: "GDP & Export Value"
				},
				axisY: {
					title: "$USD",
					scaleBreaks: {
						autoCalculate: true
					}
				},
				data: [{
						type: "line",
						showInLegend: true,
						legendMarkerColor: "grey",
						legendText: "Export Value ($USD)",
						dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
					},
					{
						type: "line",
						showInLegend: true,
						legendMarkerColor: "blue",
						legendText: "GDP ($USD)",
						dataPoints: <?php echo json_encode($dataPoints2, JSON_NUMERIC_CHECK); ?>
					}
				],
			});
			chart.render();

			var chartTwo = new CanvasJS.Chart("chartContainerTwo", {
				animationEnabled: true,
				theme: "light1",
				title: {
					text: "CO2 Emissions"
				},
				axisY: {
					title: "CO2 Emissions (billions tons)",
					scaleBreaks: {
						autoCalculate: true
					}
				},
				data: [{
						type: "line",
						dataPoints: <?php echo json_encode($dataPoints3, JSON_NUMERIC_CHECK); ?>
					}
				],
			});
			chartTwo.render();
		}
	</script>
	<script type="text/javascript" src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</head>

<body>
	<div id="chartContainer" style="height: 370px; width: 100%;"></div> </br></br>
	<div id="chartContainerTwo" style="height: 370px; width: 100%;"></div>
</body>

</html>