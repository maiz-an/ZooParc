document.addEventListener('DOMContentLoaded', function () {
    const audio = document.getElementById('background-music');
    const playPauseBtn = document.getElementById('musicPlaying');

    // Retrieve the saved playback state and time
    const isPlaying = localStorage.getItem('isPlaying') === 'true';
    const currentTime = parseFloat(localStorage.getItem('currentTime')) || 0;

    // Set the initial state
    audio.currentTime = currentTime;
    if (isPlaying) {
        playPauseBtn.innerHTML = '&#9208;'; // Pause Icon
        audio.play().catch((error) => {
            console.log('Playback failed: ', error);
        });
    } else {
        playPauseBtn.innerHTML = '&#9654;'; // Play Icon
    }

    // Toggle play/pause when button is clicked
    playPauseBtn.addEventListener('click', function () {
        if (audio.paused) {
            audio.play().catch((error) => {
                console.log('Playback failed: ', error);
            });
            playPauseBtn.innerHTML = '&#9208;'; // Pause Icon
            localStorage.setItem('isPlaying', 'true');
        } else {
            audio.pause();
            playPauseBtn.innerHTML = '&#9654;'; // Play Icon
            localStorage.setItem('isPlaying', 'false');
        }
    });

    // Save the current playback time periodically
    setInterval(function () {
        localStorage.setItem('currentTime', audio.currentTime);
    }, 1000);

    // Automatically play audio when the page loads if previously playing
    document.addEventListener('click', function () {
        if (audio.paused && localStorage.getItem('isPlaying') === 'true') {
            audio.play().catch((error) => {
                console.log('Playback failed: ', error);
            });
        }
    });
});
