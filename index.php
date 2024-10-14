<!DOCTYPE html>
<html>

<head>
    <title>API Product Interface</title>
    <script src="./js/index.js" defer></script>
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.colors.min.css">
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.colors.min.css">
    <link
        rel="stylesheet"
        href="./css/index.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="https://img.icons8.com/?size=512w&id=13910&format=png">
</head>

<body>
    <h1>API Product Interface</h1>
    <div class="container-fluid">
        <article class="grid">
            <section data-endpoint="create">
                <h2>Create a new product</h2>
                <form>
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" placeholder="Name" required>
                    <label for="price">Price:</label>
                    <input type="text" id="price" name="price" placeholder="Price" required>
                    <label for="description">Description:</label>
                    <input type="text" id="description" name="description" placeholder="Description" required>
                    <button type="button">Create this one</button>
                </form>
            </section>
            <section>
                <h2>Result</h2>
            </section>
        </article>
        <article class="grid grid-read-all">
            <section data-endpoint="read">
                <h2>List all products</h2>
                <button type="button">List all products</button>
            </section>
            <section class="overflow-auto">
                <h2>Result</h2>
            </section>
        </article>
        <article class="grid">
            <section data-endpoint="read-one">
                <h2>Find by id</h2>
                <form>
                    <label for="id">Enter a valid id</label>
                    <input type="text" id="id" name="id" placeholder="Enter an id..." required>
                    <button disabled type="button">Find this one</button>
                </form>
            </section>
            <section>
                <h2>Result</h2>
            </section>
        </article>
        <article class="grid">
            <section data-endpoint="update">
                <h2>Update a product</h2>
                <form>
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" placeholder="Name" required>
                    <label for="price">Price:</label>
                    <input type="text" id="price" name="price" placeholder="Price" required>
                    <label for="stock">Description:</label>
                    <input type="text" id="description" name="description" placeholder="Description" required>
                </form>
            </section>
            <section>
                <h2>Result</h2>
            </section>
        </article>
        <article class="grid">
            <section data-endpoint="delete">
                <h2>Delete a product</h2>
                <form>
                    <label for="id">Enter an id :</label>
                    <input type="text" id="id" name="id" placeholder="Enter an id ..." required>
                    <button disabled type="button">Delete this one</button>
                </form>
            </section>
            <section>
                <h2>Result</h2>
            </section>
        </article>
    </div>
</body>

</html>