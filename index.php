<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="theme-color" content="#000000" />
        <meta
            name="description"
            content="product preview"
        />
        <link rel="stylesheet" href="style.css">
        <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
        <script src="fetch-token.js"></script>
        <title>iNSIGHT</title>
    </head>
    <body>
    <div class="form">
        <h2>3D Prototype Launch Config</h2>
        <form id="form-data" method="post">
            <input type="text" placeholder="Enter token" id="token" class="token" required/>
            <span id="error" class="error hidden">Invalid token</span>
            <button type="submit" id="submit">
                View
            </button>
        </form>
    </div>
    </body>
</html>
