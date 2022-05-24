<?php require "templates/header.php"; ?>

<div class="center-align">

    <?php

    if (isset($_POST['submit'])) {
        require "database/config.php";

        //Establish the connection
        $conn = mysqli_init();
        mysqli_ssl_set($conn,NULL,NULL,$sslcert,NULL,NULL);
        if(!mysqli_real_connect($conn, $host, $username, $password, $db_name, 3306, MYSQLI_CLIENT_SSL)){
            die('Failed to connect to MySQL: '.mysqli_connect_error());
        }

        $res = mysqli_query($conn, "SHOW TABLES LIKE 'Products'");
    
        if (mysqli_num_rows($res) <= 0) {
            //Create table if it does not exist
            $sql = file_get_contents("database/schema.sql");
            if(!mysqli_query($conn, $sql)){
                die('Table Creation Failed');
            }
        }

        // Insert data from form
        $ProductName = $_POST['ProductName'];
        $Price = $_POST['Price'];

        if ($stmt = mysqli_prepare($conn, "INSERT INTO Products (ProductName, Price) VALUES (?, ?)")) {
            mysqli_stmt_bind_param($stmt, 'sd', $ProductName, $Price);
            mysqli_stmt_execute($stmt);
            if (mysqli_stmt_affected_rows($stmt) == 0) {
                echo "<h2>Catalog update failed.</h2>";
            }
            else {
                echo "<h2>Product \"$ProductName\" has been successfully added.</h2>";
            }
            mysqli_stmt_close($stmt);
            
        }

        //Close the connection
        mysqli_close($conn);

    } else {

    ?>

    <h2>Add a Product</h2>
    <br>

    <form method="post" action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <table>
            <tr>
                <td class="no-border"> <label for="ProductName">Product Name</label> </td>
                <td class="no-border"> <input type="text" name="ProductName" id="ProductName"> </td>
            </tr>
            <tr>
                <td class="no-border"> <label for="Price">Price (USD)</label> </td>
                <td class="no-border"> <input type="text" name="Price" id="Price"> </td>
            </tr>
        </table>      
        <br><br>
        <input type="submit" name="submit" value="Submit">
    </form>

    <?php
        }
    ?>

    <br> <br> <br>
    <table>
        <tr>
            <td> <a href="insert.php">Add Another Product</a> </td>
            <td> <a href="read.php">View Catalog</a> </td>
            <td> <a href="index.php">Back to Home Page</a> </td>
        </tr>
    </table>

</div>

<?php require "templates/footer.php"; ?>

