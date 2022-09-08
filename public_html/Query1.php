<!--
	Sebastian Cabrejos (scabrej1) & Hubert Leo (hleo1)
	Project Phase E due May 11, 11 p.m.
-->

<head>
	<title>Query_1</title>
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

	// Error handling
	// ini_set('error_reporting', E_ALL);
	// ini_set('display_errors', true);

	$year = $_POST['year'];
	echo "<div class=text-center>";
	echo "<h2>Query 1</h2>";

	if (empty($year)) {
		echo "<h1>No value for year provided.</h1> <br><br>";
	} else {
		echo "<h3> Year: " . $year . "</h3><br>";

		if ($stmt = $conn->prepare("CALL Query1(?)")) {
			$stmt->bind_param("i", $year);
			if ($stmt->execute()) {
				$result = $stmt->get_result();
				if ($result->num_rows == 0) {
					echo "<h1>No values found specified year</h1>";
				} else {
					echo "<table class=\"table table-condensed table-striped  table-hover\" border=\"1px solid black\">";
					echo "<tr><th> Country Name </th><th> CO2 Emissions </th>
							<th> Economic Complexity </th><th> Sector </th><th> Export Value </th></tr>";
					while ($row = $result->fetch_row()) {
						echo "<tr>";
						echo "<td>" . $row[0] . "</td>";
						echo "<td>" . $row[1] . "</td>";
						echo "<td>" . $row[2] . "</td>";
						echo "<td>" . $row[3] . "</td>";
						echo "<td>" . $row[4] . "</td>";
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