<?php require "templates/header.php"; ?>

<?php

if (isset($_POST['submit'])) {

    require "database/config.php";
    //Establish the connection
    $conn = mysqli_init();
    mysqli_ssl_set($conn,NULL,NULL,$sslcert,NULL,NULL);
    if(!mysqli_real_connect($conn, $host, $username, $password, $db_name, 3306, MYSQLI_CLIENT_SSL)){
		die('Failed to connect to MySQL: '.mysqli_connect_error());
	}

    //Test if table exists
    $res = mysqli_query($conn, "SHOW TABLES LIKE 'Products'");
    if (mysqli_num_rows($res) <= 0) {
        echo "<h2>Catalog is empty</h2>";
    } else {
        //Update data
        $ProductName = $_POST['ProductName'];
        $Price = $_POST['Price'];

        if ($stmt = mysqli_prepare($conn, "UPDATE Products SET Price = ? WHERE ProductName = ?")) {
            mysqli_stmt_bind_param($stmt, 'ds', $Price, $ProductName);
            mysqli_stmt_execute($stmt);
            if (mysqli_stmt_affected_rows($stmt) == 0) {
                echo "<h2>Product \"$ProductName\" was not found in the catalog.</h2>";
            }
            else {
                echo "<h2>Price of the product \"$ProductName\" has been successfully updated to $Price</h2>";
            }
            mysqli_stmt_close($stmt);
            
        } 
    }
    //Close the connection
    mysqli_close($conn);

} else {

?>

<h2>Update a Product</h2>

<form method="post" action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="ProductName">Product Name</label>
    <input type="text" name="ProductName" id="ProductName">
    <label for="Price">Updated Price</label>
    <input type="text" name="Price" id="Price">
    <input type="submit" name="submit" value="Submit">
</form>

<?php
      }
?>


<br> <br> <br>
<table>
    <tr>
        <td> <a href="update.php">Update another Product</a> </td>
        <td> <a href="read.php">View Catalog</a> </td>
        <td> <a href="index.php">Back to Home Page</a> </td>
    </tr>
</table>

<?php require "templates/footer.php"; ?>

