<html>
    <head>
        <title> Michi-Playlist Download </title>

        <link rel="stylesheet" href="/style.css">
        <link rel="icon" href="/mango_icon.png">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            .container-flex {
                max-width: 800px;
                display: flex;
                align-items: center; /* Zentriert die Elemente vertikal */
                gap: 20px; /* Optional: Fügt einen Abstand zwischen den Elementen hinzu */
                padding: 10px;
                margin-bottom: 20px;
            }
            img {
                width: 250px;
            }

            .text-content {
                /* width: 700px; */
                max-width: 600px;
                /* Optional: Passt die Textbreite an, wenn nötig */
                flex: 1;
            }

            /* Optional: Für kleine Bildschirme die Anzeige ändern */
            @media (max-width: 600px) {
                .container-flex {
                    flex-direction: column; /* Stapelt die Elemente untereinander */
                    text-align: center;
                }
            }
        </style>

    </head>

    <body>
        <h1> Downloaded Video: </h1>
        <form action="index.php" method="post">
            <input type="submit" name="add" id="add" value="Add Song"/>
            <input type="submit" name="delete" id="delete" value="Delete"/>

        <?php
            if (empty($_POST["song_url"])) {
                echo "No valid url. Return to download page.";
                header( "refresh:5;url=index.php" );
                die();
            } 
            $advanced_search = '';
            if (!empty($_POST["advanced_search"])) {
                $advanced_search = ' "' . $_POST["advanced_search"] . '"';
            } 

            $url = $_POST["song_url"];
            echo "<br>";
            echo "<h3>URL: " . $url . "</h3>";

            $command = escapeshellcmd('python3 /app/tagging_mp3_files.py ' . $url . $advanced_search);
            /* $command = escapeshellcmd('python3 test.py' . $url); */
            $output = shell_exec($command);

            echo $output;

        ?>
        </form>

    </body>
</html>

