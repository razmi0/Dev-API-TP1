<!DOCTYPE html>
<html data-theme="dark">

<head>
    <title>API Product Interface</title>
    <script src="./js/dist/index.js" type="module"></script>
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.colors.min.css">
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.colors.min.css">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="https://img.icons8.com/?size=512w&id=13910&format=png">
</head>

<body>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }



        input:not([name='description']) {
            max-width: 50vw;
        }

        label:has(~input[required]) {
            position: relative;
            width: fit-content;

            &:after {
                content: "*";
                position: absolute;
                top: 0;
                right: -1ch;
                color: red;

            }
        }

        button {
            max-width: fit-content;
        }

        section[data-endpoint]~section {
            max-height: 40vh;
        }

        [data-output='id'] {
            position: fixed;
            bottom: 0;
            right: 0;
            padding-inline: 0.8rem;
            padding-block: 0.4rem;
            margin-bottom: 20px;
            margin-right: 20px;
            background: var(--pico-color-grey-100);
            border-radius: 1rem;
            box-shadow: 0 0 1rem rgba(0, 0, 0, 0.1);
            font-size: 0.8rem;
            color: var(--pico-color-grey-900);

            & small {
                font-variant-numeric: tabular-nums;
            }
        }
    </style>
    <header>
        <nav class="container">
            <ul>
                <h1>API Product Interface</h1>
            </ul>
            <ul>
                <li>
                    <button data-theme class="outline" data-tooltip="Toggle theme" data-placement="left">
                        <?php include("images/theme-icon.svg"); ?>
                    </button>
                </li>
            </ul>
        </nav>
    </header>
    <main class="container-fluid">

        <!--        -->
        <!-- CREATE -->
        <!--        -->

        <article>
            <section data-endpoint="create">
                <header style="margin-bottom : 1rem;">
                    <h2 style="margin-bottom: 0;">Create a new product</h2>
                </header>
                <form>
                    <fieldset class="grid">
                        <div>
                            <label for="name">Name:</label>
                            <input type="text" id="name" name="name" placeholder="Enter name..." required>
                        </div>
                        <div>
                            <label for="prix">Price:</label>
                            <input type="text" id="prix" name="prix" placeholder="Enter price..." required>
                        </div>
                    </fieldset>
                    <fieldset>
                        <label for="description">Description:</label>
                        <input type="text" id="description" name="description" placeholder="Enter description..." required>
                    </fieldset>
                    <button type="button" disabled>Create this one</button>
                </form>
            </section>
            <section>
            </section>
        </article>

        <!--        -->
        <!-- READ   -->
        <!--        -->

        <article>
            <section data-endpoint="read">
                <h2>List all products</h2>
                <button type="button">List all products</button>
            </section>
            <section class="overflow-auto">

            </section>
        </article>

        <!--        -->
        <!-- READ ONE -->
        <!--        -->

        <article>
            <section data-endpoint="read-one">
                <h2>Find by id</h2>
                <form>
                    <fieldset role="group">
                        <input type="text" id="id" name="id" placeholder="Enter an id..." required>
                        <label style="display: none;" for="id">Identifier :</label>
                        <button disabled type="button">Find this one</button>
                    </fieldset>
                </form>
            </section>
            <section>

            </section>
        </article>

        <!--        -->
        <!-- UPDATE -->
        <!--        -->

        <article>
            <section data-endpoint="update">
                <h2>Update a product</h2>
                <form>
                    <fieldset class="grid">
                        <div>
                            <label for="name">Name:</label>
                            <input type="text" id="name" name="name" placeholder="Enter name..." required>
                        </div>
                        <div>
                            <label for="prix">Price:</label>
                            <input type="text" id="prix" name="prix" placeholder="Enter price..." required>
                        </div>
                    </fieldset>
                    <label for="stock">Description:</label>
                    <input type="text" id="description" name="description" placeholder="Enter description..." required>
                </form>
            </section>
            <section>

            </section>
        </article>

        <!--        -->
        <!-- DELETE -->
        <!--        -->

        <article>
            <section data-endpoint="delete">
                <h2>Delete a product</h2>
                <form>
                    <fieldset role="group">
                        <input type="text" id="id" name="id" placeholder="Enter an id ..." required>
                        <label style="display: none;" for="id">Identifier :</label>
                        <button disabled type="button">Delete this one</button>
                    </fieldset>
                </form>
            </section>
            <section>

            </section>
        </article>
        <section data-output='id'>
            <small>Selected id : </small>
            <output></output>
        </section>
    </main>
</body>

</html>