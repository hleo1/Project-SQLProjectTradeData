<!--
	Sebastian Cabrejos (scabrej1) & Hubert Leo (hleo1)
	Project Phase E due May 11, 11 p.m.
-->

<head>
	<title>Query_4</title>
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

	// Error handling
	// ini_set('error_reporting', E_ALL);
	// ini_set('display_errors', true);

	$year = $_POST['year'];
	echo "<div class=text-center>";
	echo "<h2>Query 4</h2>";

	if (empty($year)) {
		echo "<h1>No value provided for year</h1><br><br>";
	} else {
		echo "<h3> Year: " . $year . "</h3><br>";

		if ($stmt = $conn->prepare("CALL Query4(?)")) {
			$stmt->bind_param("i", $year);

			if ($stmt->execute()) {
				$result = $stmt->get_result();
				if ($result->num_rows == 0) {
					echo "<h1>No export values found for the specified country name</h1>";
				} else {
					foreach ($result as $row) {
						array_push($dataPoints, array(
							"label" => $row["country_name"],
							"y" => $row["gdp"]
						));
						array_push($dataPoints2, array(
							"label" => $row["country_name"],
							"y" => $row["export_value"]
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
				theme: "light2",
				title: {
					text: "GDP"
				},
				axisX: {
					title: "Country Name",
				},
				axisY: {
					title: "GDP ($USD)",
				},
				data: [{
					type: "column",
					dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
				}]
			});
			chart.render();

			var chartTwo = new CanvasJS.Chart("chartContainerTwo", {
				animationEnabled: true,
				theme: "light2",
				title: {
					text: "Total Export Values"
				},
				axisX: {
					title: "Country Name",
				},
				axisY: {
					title: "Total Export Value ($USD)"
				},
				data: [{
						type: "column",
						dataPoints: <?php echo json_encode($dataPoints2, JSON_NUMERIC_CHECK); ?>
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