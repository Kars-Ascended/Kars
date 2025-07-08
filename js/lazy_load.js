// Lazy load audio players in songs.php table
document.addEventListener('DOMContentLoaded', function() {
    const options = {
        root: null,
        rootMargin: '50px',
        threshold: 0.1
    };

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const playerDiv = entry.target;
                loadAudioPlayer(playerDiv);
                observer.unobserve(playerDiv); // Stop observing once loaded
            }
        });
    }, options);

    // Observe all audio player divs
    document.querySelectorAll('.audio-player').forEach(playerDiv => {
        observer.observe(playerDiv);
    });

    // Load audio player when in view
    function loadAudioPlayer(playerDiv) {
        // Read raw values
        const songRaw = playerDiv.dataset.song || '';
        const releaseRaw = playerDiv.dataset.release || '';
        // Interpret main_release as a boolean: TRUE means main release, FALSE means demo
        const isDemo = (playerDiv.dataset.demo === '1' || playerDiv.dataset.demo === 'TRUE' || playerDiv.dataset.demo === 'true');

        let mp3_url = '';
        if (isDemo) {
            if (!songRaw) {
                playerDiv.innerHTML = '<span style="color:red;">No song title</span>';
                return;
            }
            const safeSong = sanitizeForWindows(songRaw);
            mp3_url = `https://bolt-gpl-secondary-knight.trycloudflare.com/kars/discogs/demos/${encodeURIComponent(safeSong)}.mp3`;
        } else {
            if (!releaseRaw || !songRaw) {
                playerDiv.innerHTML = '<span style="color:red;">Missing release or song</span>';
                return;
            }
            const safeRelease = sanitizeForWindows(releaseRaw);
            const safeSong = sanitizeForWindows(songRaw);
            mp3_url = `https://bolt-gpl-secondary-knight.trycloudflare.com/kars/discogs/releases/${encodeURIComponent(safeRelease)}/${encodeURIComponent(safeSong)}.mp3`;
        }

        const audio = document.createElement('audio');
        audio.controls = true;
        audio.innerHTML = `<source src="${mp3_url}" type="audio/mpeg">`;

        // Get current volume from cookie, fallback to 1
        let savedVolume = 1;
        try {
            const match = document.cookie.match(/(?:^|;\s*)audioVolume=([^;]+)/);
            if (match) savedVolume = parseFloat(match[1]);
        } catch {}
        audio.volume = isNaN(savedVolume) ? 1 : savedVolume;

        // Remove placeholder and add audio element
        const placeholder = playerDiv.querySelector('.audio-placeholder');
        if (placeholder) placeholder.remove();
        playerDiv.appendChild(audio);
    }

    function sanitizeForWindows(name) {
        // Remove forbidden Windows filename characters: < > : " / \ | ? *
        return name.replace(/[<>:"/\\|?*]/g, '');
    }
});

// Utility: Set all audio elements' volume to 5%
function setAllAudioVolume() {
    const audioElements = document.getElementsByTagName('audio');
    const volumeLevel = 0.05;
    Array.from(audioElements).forEach(audio => {
        audio.volume = volumeLevel;
    });
}