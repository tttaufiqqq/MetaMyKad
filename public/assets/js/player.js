/**
 * MetaMyKad — Custom Media Player
 * Wires .custom-player elements for audio and video playback.
 */
(function () {
    'use strict';

    function fmt(seconds) {
        if (!isFinite(seconds) || seconds < 0) return '0:00';
        const m = Math.floor(seconds / 60);
        const s = Math.floor(seconds % 60);
        return m + ':' + String(s).padStart(2, '0');
    }

    function initPlayer(wrap) {
        const isVideo  = wrap.classList.contains('custom-player--video');
        const media    = wrap.querySelector(isVideo ? 'video' : 'audio');
        const playBtn  = wrap.querySelector('.cp-play');
        const seek     = wrap.querySelector('.cp-seek');
        const volume   = wrap.querySelector('.cp-volume');
        const current  = wrap.querySelector('.cp-current');
        const duration = wrap.querySelector('.cp-duration');
        const fsBtn    = wrap.querySelector('.cp-fullscreen');
        const src      = wrap.dataset.src;

        if (!media || !playBtn || !seek) return;

        // Lazy-load: set src on first play to avoid unnecessary network requests
        let loaded = false;
        function ensureLoaded() {
            if (!loaded) {
                media.src = src;
                loaded = true;
            }
        }

        // Play / pause
        playBtn.addEventListener('click', function () {
            ensureLoaded();
            if (media.paused) {
                media.play();
            } else {
                media.pause();
            }
        });

        media.addEventListener('play', function () {
            playBtn.classList.add('is-playing');
            playBtn.setAttribute('aria-label', 'Pause');
        });

        media.addEventListener('pause', function () {
            playBtn.classList.remove('is-playing');
            playBtn.setAttribute('aria-label', 'Play');
        });

        media.addEventListener('ended', function () {
            playBtn.classList.remove('is-playing');
            seek.value = 0;
            if (current) current.textContent = '0:00';
        });

        // Duration display once metadata is loaded
        media.addEventListener('loadedmetadata', function () {
            seek.max = media.duration;
            if (duration) duration.textContent = fmt(media.duration);
        });

        // Time update → seek bar + current time display
        media.addEventListener('timeupdate', function () {
            if (!isFinite(media.duration)) return;
            seek.value = media.currentTime;
            if (current) current.textContent = fmt(media.currentTime);
        });

        // Seek bar → scrub
        seek.addEventListener('input', function () {
            ensureLoaded();
            media.currentTime = parseFloat(seek.value);
        });

        // Volume
        if (volume) {
            media.volume = parseFloat(volume.value);
            volume.addEventListener('input', function () {
                media.volume = parseFloat(volume.value);
                media.muted  = media.volume === 0;
            });
        }

        // Fullscreen (video only)
        if (fsBtn && isVideo) {
            fsBtn.addEventListener('click', function () {
                if (document.fullscreenElement) {
                    document.exitFullscreen();
                } else {
                    wrap.requestFullscreen();
                }
            });
            document.addEventListener('fullscreenchange', function () {
                if (document.fullscreenElement === wrap) {
                    fsBtn.classList.add('is-fullscreen');
                    fsBtn.setAttribute('aria-label', 'Exit fullscreen');
                } else {
                    fsBtn.classList.remove('is-fullscreen');
                    fsBtn.setAttribute('aria-label', 'Fullscreen');
                }
            });
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.custom-player').forEach(initPlayer);
    });
}());
