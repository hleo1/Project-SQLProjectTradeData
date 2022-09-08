<!-- 
	Sebastian Cabrejos (scabrej1) & Hubert Leo (hleo1)
	Project Phase E due May 11, 11 p.m.
-->
<head>
    <title>Delete GDP</title>
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
    echo "<div class=text-center>";

    if (empty($country) || empty($year)) {
        echo "<h2>Incomplete delete parameters</h2>";
    } else if (is_numeric($country) || !is_numeric($year)) {
        echo "<h2>Country must be a string, Year must be a number</h2>";
    } else {
        if ($stmt = $conn->prepare("DELETE FROM GDP WHERE country_name LIKE ? AND year = ?")) {
            $stmt->bind_param("si", $country, $year);
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