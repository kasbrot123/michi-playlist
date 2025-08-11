const jsmediatags = window.jsmediatags;



var aud = {
  // (A) INITIALIZE PLAYER
  player : null, // HTML <AUDIO> ELEMENT
  playlist : null, // HTML PLAYLIST
  now : 0, // CURRENT SONG
  isrunning : 0,

  title_name : "",
  artist_name : "",
  album_name : "",
  year_name : "",
  album_cover : null,
  button_skip : null,
  button_back : null,
  button_play : null,
  current_song : null,
  max_songs : null,
  
  nav : null,

  init : () => {
    // (A1) GET HTML ELEMENTS
    aud.player = document.getElementById("demoAudio");
    aud.playlist = document.querySelectorAll("#demoList .song");

    aud.title_name = document.getElementById("title");
    aud.artist_name = document.getElementById("artist");
    aud.album_name = document.getElementById("album");
    aud.year_name = document.getElementById("year");
    aud.album_cover = document.getElementById("albumcover");

    aud.button_skip = document.getElementById("skip");
    aud.button_back = document.getElementById("back");
    aud.button_play = document.getElementById("play");

    aud.current_song = document.getElementById("current_song");
    aud.max_song = document.getElementById("maxsong");

    // selbst geschrieben
    aud.button_skip.onclick = () => { aud.change(1); };
    aud.button_back.onclick = () => { aud.change(-1); };
    aud.button_play.onclick = () => { aud.pause(); };

    aud.max_song.textContent = aud.playlist.length;

    // (A2) LOOP THROUGH ALL THE SONGS, CLICK TO PLAY
    for (let i=0; i<aud.playlist.length; i++) {
      aud.playlist[i].onclick = () => { aud.play(i); };
    }

    // (A3) AUTO PLAY WHEN SUFFICIENTLY LOADED
    aud.player.oncanplay = aud.player.play;

    // (A4) AUTOPLAY NEXT SONG IN PLAYLIST WHEN CURRENT SONG ENDS
    aud.player.onended = () => {
      aud.now++;
      if (aud.now>=aud.playlist.length) { aud.now = 0; }
      aud.play(aud.now);
    };



    // (A5) AUTOPLAY FIRST SONG (OPTIONAL)
    aud.play(0);



  },

  // (B) PLAY SELECTED SONG
  play : (id) => {
  
  aud.isrunning = 1;
    // (B1) UPDATE AUDIO SRC
    aud.now = id;
    aud.player.src = aud.playlist[id].dataset.src;

    aud.current_song.textContent = id + 1;

    // (B2) A LITTLE BIT OF COSMETIC
    for (let i=0; i<aud.playlist.length; i++) {
      aud.playlist[i].style.backgroundColor = i==id ? "yellow" : "";
    }




if ('mediaSession' in navigator) {
  console.log('mediasession in navigator');
  navigator.mediaSession.metadata = new MediaMetadata({
    title: "",
    artist: "",
    album: ""
/*    artwork: [
      { src: 'https://dummyimage.com/96x96',   sizes: '96x96',   type: 'image/png' },
      { src: 'https://dummyimage.com/128x128', sizes: '128x128', type: 'image/png' },
      { src: 'https://dummyimage.com/192x192', sizes: '192x192', type: 'image/png' },
      { src: 'https://dummyimage.com/256x256', sizes: '256x256', type: 'image/png' },
      { src: 'https://dummyimage.com/384x384', sizes: '384x384', type: 'image/png' },
      { src: 'https://dummyimage.com/512x512', sizes: '512x512', type: 'image/png' },
    ]*/
  });


  navigator.mediaSession.setActionHandler('play', function() { aud.pause(); });
  navigator.mediaSession.setActionHandler('pause', function() { aud.pause(); });
  //navigator.mediaSession.setActionHandler('stop', function() { /* Code excerpted. */ });
  //navigator.mediaSession.setActionHandler('seekbackward', function() { /* Code excerpted. */ });
  //navigator.mediaSession.setActionHandler('seekforward', function() { /* Code excerpted. */ });
  //navigator.mediaSession.setActionHandler('seekto', function() { /* Code excerpted. */ });
  navigator.mediaSession.setActionHandler('previoustrack', function() { aud.change(-1); });
  navigator.mediaSession.setActionHandler('nexttrack', function() { aud.change(1); });
  //navigator.mediaSession.setActionHandler('skipad', function() { /* Code excerpted. */ });


}




    
    // show meta tags 

    // hat nicht funktioniert weil GET Request file name verdreht
    // const file = document.location.href + aud.playlist[id].dataset.src;

    rel_path = document.location.pathname;
    console.log(rel_path);
    if (rel_path.endsWith("index.php")) {
        rel_path = rel_path.split("/").slice(0, -1).join("/")+"/"
    }
    const file = document.location.origin + rel_path + aud.playlist[id].dataset.src;
    console.log("michi");
    console.log(document.location);
    jsmediatags.read(file, {


        onSuccess: function(tag) {
            console.log(tag);
	
            const data = tag.tags.picture.data;
            const format = tag.tags.picture.format;
            let base64String = "";
            for (let i = 0; i < data.length; i++) 
                base64String += String.fromCharCode(data[i]);

	    if (tag.version == "2.4.0") {
		    aud.year_name.textContent = tag.tags.TDRC.data;
	    } else {
                    aud.year_name.textContent = tag.tags.year;
            }

            aud.album_cover.style.backgroundImage = `url(data:${format};base64,${window.btoa(base64String)})`;
            aud.title_name.textContent = tag.tags.title;
            aud.artist_name.textContent = tag.tags.artist;
            aud.album_name.textContent = tag.tags.album;



            navigator.mediaSession.metadata.title = tag.tags.title;
            navigator.mediaSession.metadata.artist = tag.tags.artist;
            navigator.mediaSession.metadata.album = tag.tags.album;
            //console.log(navigator);
        },
        onError: function(error) {
           console.log(error);
            console.log("it does not work");
        }
    })
    


  },

  change : (step) => {
    current = aud.now + step;
    if (current >= aud.playlist.length) { current = 0; }
    if (current < 0) { current = aud.playlist.length - 1; }
    aud.play(current);
  },

  pause : () => {
    if (aud.isrunning == 1) { 
      aud.player.pause(); 
      navigator.mediaSession.playbackState = 'paused';
    }
    if (aud.isrunning == 0) { 
      aud.player.play();
      navigator.mediaSession.playbackState = 'playing';
    }
    aud.isrunning = (aud.isrunning + 1) % 2;
    //aud.isrunning = 0;
  }
};
window.addEventListener("DOMContentLoaded", aud.init);

