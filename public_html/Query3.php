<!--
	Sebastian Cabrejos (scabrej1) & Hubert Leo (hleo1)
	Project Phase E due May 11, 11 p.m.
-->

<head>
	<title>Query_3</title>
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

	$initial_year = $_POST['initial_year'];
	$end_year = $_POST['end_year'];
	echo "<div class=text-center>";
	echo "<h2>Query 3</h2>";

	if (empty($initial_year) || empty($end_year)) {
		echo "<h1>No initial and/or end - year(s) provided</h1><br><br>";
	} else {
		echo "<h3> Initial Year: " . $initial_year . "</h3>";
		echo "<h3> End Year: " . $end_year . "</h3><br>";

		if ($stmt = $conn->prepare("CALL Query3(?,?)")) {

			$stmt->bind_param("ii", $initial_year, $end_year);

			if ($stmt->execute()) {
				$result = $stmt->get_result();
				if ($result->num_rows == 0) {
					echo "<h1>No export values found for the specified country name</h1>";
				} else {
					echo "<table class=\"table table-condensed table-striped  table-hover\" border=\"1px solid black\">";
					echo "<tr><th> Leader Name </th><th> Start Term </th><th> End Term </th></tr>";
					while ($row = $result->fetch_row()) {
						echo "<tr>";
						echo "<td>" . $row[1] . "</td>";
						echo "<td>" . $row[5] . "</td>";
						echo "<td>" . $row[6] . "</td>";
						echo "</tr>";
					}
					echo "</table></br></br>";

					foreach ($result as $row) {
						array_push($dataPoints, array(
							"label" => $row["country_name"],
							"y" => $row["export_value"]
						));
						array_push($dataPoints2, array(
							"label" => $row["country_name"],
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
	}

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
						legendMarkerColor: "blue",
						legendText: "Export Value ($USD)",
						dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
					},
					{
						type: "line",
						showInLegend: true,
						legendMarkerColor: "red",
						legendText: "GDP ($USD)",
						dataPoints: <?php echo json_encode($dataPoints2, JSON_NUMERIC_CHECK); ?>
					}
				],
			});
			chart.render();
		}
	</script>
	<script type="text/javascript" src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</head>

<body>
	<div id="chartContainer" style="height: 370px; width: 100%;"></div> </br></br>
</body>

</html>