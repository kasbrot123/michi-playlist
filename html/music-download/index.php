<html>
    <head>
        <title> Michi-Playlist Download </title>

        <link rel="stylesheet" href="/style.css">
        <link rel="icon" href="/mango_icon.png">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            .input-group {
                display: flex; /* Macht den Container zum Flex-Container */
                align-items: center; /* Zentriert die Elemente vertikal */
                max-width: 600px;
            }

            .input-group input {
                flex-grow: 1; /* Das Textfeld nimmt den verfügbaren Platz ein */
                margin-right: 10px; /* Fügt einen Abstand zwischen Textfeld und Button hinzu */
            }
            .submit_button {
                max-width: 100px;
            }
            label {
                margin-right: 5px;
            }
        </style>

    </head>

    <body>
        <a href="/index.html"> Home </a>
        <h1> Download the Music you like.. </h1>
        <h3> Enter Youtube Link: </h3>

        <form action="download.php" method="post">
            <label>Advanced Text Search: </label><br>
            <input class="field" name="advanced_search" id="advanced_search"/> <br><br>
            <div class="input-group">
                <label>Url: </label><input class="field" name="song_url" id="song_url">
                <input class="submit_button" type="submit" value="Download"/> 
                </p>
            </div>
        </form>

        <?php

            /**
             * Löscht den Inhalt eines Verzeichnis.
             *
             */
            function rrmdir(string $dir): bool
            {
                // Überprüfen, ob das Verzeichnis existiert
                if (!is_dir($dir)) {
                    return false;
                }

                // Löschen des ".." und "." Eintrags
                $files = array_diff(scandir($dir), ['.', '..']);

                foreach ($files as $file) {
                    $path = "$dir/$file";

                    if (!unlink($path)) {
                        return false; // Fehler beim Löschen der Datei
                    }
                }

                return True;
            }


            if (isset($_POST["delete"])) {
                // delete song
                rrmdir('cache_image');
                rrmdir('cache_mp3');
                echo "Song deleted.";
            }
            if (isset($_POST["add"])) {
                rrmdir('cache_image');
                if (isset($_POST["file_name"])) {

                    $file_name = $_POST["file_name"];
                    rename("cache_mp3/" . $file_name, "../michi-playlist/audio/" . $file_name);

                    $command = escapeshellcmd('python3 /app/metatags_playlist.py'); 
                    $output = shell_exec($command);
                    echo $output;

                    echo "added song to playlist";
                }
            }

        ?>

    </body>
</html>
