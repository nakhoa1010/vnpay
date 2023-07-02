<!DOCTYPE html>
<html>

<head>
    <title>Sample Table</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <h2>Sample Table</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Number Plate</th>
                    <th>Payment Status</th>
                    <th>Order Number</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Replace 'YourTableName' with the actual table name in your database
                $servername = "localhost";
                $username = "root";
                $password = "";
                $database = "thanhtoan";

                // Create a connection
                $conn = new mysqli($servername, $username, $password, $database);

                // Check the connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Function to generate a random 4-digit order number
                function generateOrderNumber()
                {
                    return rand(1000, 9999);
                }

                if (isset($_POST['submit'])) {
                    // Get the number plate and generate an order number
                    $number_plate = $_POST['number_plate'];
                    $order_number = generateOrderNumber();
                    $vnp_TxnRef = $order_number;
                    // Update the database with the new order number
                    $sql = "UPDATE YourTableName SET order_number = $order_number WHERE number_plate = '$number_plate'";
                    $result = $conn->query($sql);

                    // Redirect to another page
                    if ($result) {
                        header('Location: vnpay/vnpay_create_payment.php?order_number=' . $order_number);
                        die();
                        exit();
                    }
                }

                // Fetch data from the table
                $sql = "SELECT * FROM YourTableName";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['number_plate'] . "</td>";
                        echo "<td>" . ($row['payment_status'] ? 'Yes' : 'No') . "</td>";
                        echo "<td>" . $row['order_number'] . "</td>";

                        // Show "Thanh toán" button only for rows where payment status is "no"
                        if (!$row['payment_status']) {
                            echo "<td>";
                            echo "<form method='post'>";
                            echo "<input type='hidden' name='number_plate' value='" . $row['number_plate'] . "'>";
                            echo "<button type='submit' name='submit' class='btn btn-primary' >Thanh toán</button>";
                            echo "</form>";
                            echo "</td>";
                        } else {
                            echo "<td></td>"; // Empty cell for rows with payment status "yes"
                        }

                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No records found</td></tr>";
                }

                // Close the connection
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>