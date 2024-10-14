<!DOCTYPE html>
<html>

<head>
    <title>PHP Counter with Button</title>
</head>

<body>
    <h1>PHP Counter</h1>

    <?php
    // Initialize the counter variable
    if (!isset($_SESSION['counter'])) {
        $_SESSION['counter'] = 0;
    }

    // Increment the counter when the button is clicked
    if (isset($_POST['increment'])) {
        $_SESSION['counter']++;
    }
    ?>

    <p>Current Count: <?php echo $_SESSION['counter']; ?></p>

    <form method="post" action="">
        <input type="submit" name="increment" value="Increment">
    </form>
</body>

</html>