<!DOCTYPE html>
<html>
  <head>
    <title> Michi-Playlist </title>
    <link rel="stylesheet" href="./style.css">
    <link rel="icon" href="./mango_icon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="jsmediatags.js"></script>
    <script src="audio.js"></script>

    <style>
    div.todos {
        max-width: 900px;
        /* max-width: 100%; */
        margin: auto;
        }
    div.main {
	/* max-width: 400px; */
	max-width: 100%;
        float: left;
        margin: auto;
        max-height: 100%;
	}
    div.rolling {
	overflow-y: scroll;
	height: 750px;
    width: 400px;
    max-width: 90%;
        /* max-width: 400px; */

	float: left;
        margin: auto;
        }
    #demoList {
      max-width: 400px;
      border: 1px solid #eee;
    }
    #demoList .song {
      padding: 10px;
      cursor: pointer;
    }
    #demoList .song:nth-child(odd) {
      background-color: #eee;
    }
    #albumcover {
      width: 350px;
      height: 350px;
      border: 1px solid black;
      background-position: center;
      background-size: cover;
    }



    button.controls {
  	background: #7DE5CC;
  	border-radius: 10rem;
  	height: 3rem;
  	margin: 0rem 0rem 0rem;
 	position: relative;
	transition: box-shadow 0.05s ease-in;
	width: 5rem;

	}

    img.controls {
	height: 20px;
	}

    div.playlist_buttons {
    padding-top: 20px;
    padding-bottom: 10px;
}

    div.player {
	background-color: #EEFEFF;
	border-radius: 15px;
	max-width: 400px;
	height: auto;
	padding-left: 30px;
	padding-right: 30px;
	padding-top: 15px;
	padding-bottom: 40px;
	font-size: 14pt;
	margin: 20px;
	}
    div.info_left {
	float: left;
	text-align: left;
	}
    div.info_right {
	float: right;
	text-align: right;
	}
    div.fromto {
	text-align: left;
	font-size: 12pt;
	}

    </style>
  </head>
  <body>

<div class="todos">

<div class="main">
<center>
<a href="/index.html"> Home </a>
<h1> Michi Playlist </h1>



<div id="albumcover"></div>

<div class="player">

<div class="fromto">
Playing <span id="current_song">1</span> of <span id="maxsong">600</span>
<span style="float:right;"> <a href="info.html" target="_blank"> <img src="info.png" width="25px"> </a> </span>
<br>
<br>
</div>

<div class="controls">

	<span id="title">Title</span>
	<br>
	<br>

    <!-- (A) AUDIO TAG -->
    <audio id="demoAudio" controls></audio>


	<br>
   	<button id="back" class="controls"> <img class="controls" src="back.svg"> </button>
	<button id="play" class="controls"> <img class="controls" src="play2.svg" id="play_icon"> </button>
	<button id="skip" class="controls"> <img class="controls" src="skip.svg"> </button>

	<br>
	<br>
	<div class="info">
	
	<span id="artist">Artist</span>
	<br>
	<br>
	<span id="album">Album</span>
	<br>
	<span id="year">Year</span>

	</div>

</div>

</div>



</center>
</div>

<div class="rightside">
<div class="playlist_buttons">

<form action="index.php" method="get">

    <select name="playlists_michi" id="playlists_michi" style="width:300px">

    <?php
          echo '<option value="nothing">Playlists</option>';
        if (!empty($_GET["playlists_michi"])) {
            $current = $_GET["playlists_michi"];
              // echo '<option value="'. $current . '">Current: '.$current.'</option>';
            $file = $_GET['playlists_michi'];
        } else {
            $file = "playlist.txt";

          // echo '<option value="playlist.txt">Current: All</option>';
        }
          echo '<option value="nothing">------------------------</option>';
          echo '<option value="playlist.txt">Alle Songs</option>';
          echo '<option value="nothing">------------------------</option>';
        

      $playlist_files = scandir("playlists/");
        // str_ends_with only works in PHP 8
        function endsWith(string $haystack, string $needle): bool
        {
            $length = strlen($needle);
            if ($length === 0) {
                return true;
            }
            return substr($haystack, -$length) === $needle;
        }
      foreach ($playlist_files as $f) {
          if (endsWith($f,'.txt')) {
              if ($f == "playlist.txt"){
                  continue;
              }
              echo '<option value="'.$f.'">'.$f.'</option>';
          }
        }
    ?>

    </select>
    <input type="submit" value="Play"/>
</form>

</div>

<div class="rolling">


    <!-- (B) PLAYLIST -->
    <div id="demoList"><?php
      // (B1) GET ALL SONGS
        if ($file == "nothing") {
            $file = "playlist.txt";
        }
      // $lines = file("playlist.txt");
      $lines = file("playlists/$file");
      shuffle($lines);
      foreach ($lines as $line) 
	      echo $line;
      /*
      $songs = glob("audio/*.{mp3,webm,ogg,wav}", GLOB_BRACE);
      shuffle($songs);

      // (B2) OUTPUT SONGS IN <DIV>
      if (is_array($songs)) { foreach ($songs as $k=>$s) {
        printf('<div data-src="%s" class="song">%s</div>', $s, basename($s));
      }} else { echo "No songs found!"; }
       */
    ?></div>
</div>
</div>

</div>

</body>
</html>
