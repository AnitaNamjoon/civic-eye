// script.js - Frontend functionality for CivicEye

document.addEventListener('DOMContentLoaded', () => {

    // Form validation
    const forms = document.querySelectorAll('form');

    forms.forEach(form => {
        form.addEventListener('submit', (e) => {
            let isValid = true;
            const requiredFields = form.querySelectorAll('[required]');

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.style.borderColor = 'var(--status-urgent)';
                } else {
                    field.style.borderColor = 'var(--cloud)';
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('Please fill out all required fields.');
            }
        });
    });

    // Optional: Preview uploaded image
    const fileInput = document.getElementById('report_file');
    const previewContainer = document.getElementById('image_preview');

    if (fileInput && previewContainer) {
        fileInput.addEventListener('change', function () {
            previewContainer.innerHTML = ''; // Clear previous

            const file = this.files[0];
            if (file) {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();

                    reader.onload = function (e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.style.maxWidth = '100%';
                        img.style.maxHeight = '200px';
                        img.style.borderRadius = '8px';
                        img.style.marginTop = '10px';
                        previewContainer.appendChild(img);
                    }

                    reader.readAsDataURL(file);
                } else if (file.type.startsWith('video/')) {
                    previewContainer.innerHTML = '<p class="form-text" style="color: var(--civic-orange);">Video selected for upload.</p>';
                }
            }
        });
    }

    // Optional: Get user location (Latitude/Longitude)
    const getLocationBtn = document.getElementById('get_location_btn');
    const latInput = document.getElementById('latitude');
    const lngInput = document.getElementById('longitude');

    if (getLocationBtn && latInput && lngInput) {
        getLocationBtn.addEventListener('click', () => {
            if (navigator.geolocation) {
                getLocationBtn.textContent = 'Getting location...';
                getLocationBtn.disabled = true;

                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        latInput.value = position.coords.latitude;
                        lngInput.value = position.coords.longitude;
                        getLocationBtn.textContent = 'Location Added ✓';
                        getLocationBtn.classList.remove('btn-secondary');
                        getLocationBtn.style.backgroundColor = 'var(--status-resolved)';
                    },
                    (error) => {
                        alert('Error getting location: ' + error.message);
                        getLocationBtn.textContent = 'Get My Location';
                        getLocationBtn.disabled = false;
                    }
                );
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        });
    }

    // Password visibility toggle
    const eyeToggles = document.querySelectorAll('.eye-toggle');
    eyeToggles.forEach(toggle => {
        toggle.addEventListener('click', function () {
            const input = this.previousElementSibling;
            if (input.type === 'password') {
                input.type = 'text';
                this.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                    <path d="m10.79 12.912-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7.029 7.029 0 0 0 2.79-.588zM5.21 3.088A7.028 7.028 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474L5.21 3.089z"/>
                    <path d="M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829l-2.83-2.829zm4.95.708-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829zm3.171 6-12-12 .708-.708 12 12-.708.708z"/>
                </svg>`;
                this.setAttribute('aria-label', 'Hide password');
            } else {
                input.type = 'password';
                this.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                    <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                </svg>`;
                this.setAttribute('aria-label', 'Show password');
            }
        });
    });
});
