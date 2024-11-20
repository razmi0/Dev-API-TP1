<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.classless.fuchsia.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="https://img.icons8.com/?size=512w&id=13910&format=png">
</head>

<body style="padding: 2rem">
    <h1>Login</h1>
    <article>
        <form action="/controllers/Login.php" method="post">
            <!-- username -->
            <input type="text" name="username" placeholder="Username">
            <!-- email -->
            <input type="email" name="email" placeholder="Email">
            <!-- password -->
            <input type="password" name="password" placeholder="Password">
            <button type="submit">Login</button>
        </form>
    </article>
    <a href="/signup">Register</a>

</body>

</html>