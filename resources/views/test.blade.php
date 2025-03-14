<script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>

<video id="hls-video" controls width="640" height="360"></video>

<script>
    var video = document.getElementById('hls-video');
    var videoSrc = "https://healling-reanaissance-service.test/storage/hls/4a419eaf-70d5-4129-ae33-21468c6f1499/index.m3u8";

    if (Hls.isSupported()) {
        var hls = new Hls();
        hls.loadSource(videoSrc);
        hls.attachMedia(video);
        hls.on(Hls.Events.MANIFEST_PARSED, function () {
            video.play();
        });
    } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
        video.src = videoSrc;
        video.addEventListener('loadedmetadata', function () {
            video.play();
        });
    }
</script>
