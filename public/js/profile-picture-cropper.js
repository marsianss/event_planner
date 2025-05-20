// filepath: resources/js/profile-picture-cropper.js
document.addEventListener('DOMContentLoaded', function () {
    const changePictureBtn = document.getElementById('change-picture-btn');
    const cropperModal = document.getElementById('cropper-modal');
    const cropperImage = document.getElementById('cropper-image');
    const profilePicturePreview = document.getElementById('profile-picture-preview');
    const cancelCropBtn = document.getElementById('cancel-crop-btn');
    const saveCropBtn = document.getElementById('save-crop-btn');

    let cropper = null;

    // Open the cropper modal
    changePictureBtn.addEventListener('click', () => {
        const input = document.createElement('input');
        input.type = 'file';
        input.accept = 'image/*';
        input.onchange = (event) => {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    cropperImage.src = e.target.result;
                    cropperModal.classList.remove('hidden');

                    // Wait for image to load before initializing cropper
                    cropperImage.onload = () => {
                        if (cropper) {
                            cropper.destroy();
                        }
                        cropper = new Cropper(cropperImage, {
                            aspectRatio: 1,
                            viewMode: 1,
                        });
                    };
                };
                reader.readAsDataURL(file);
            }
        };
        input.click();
    });

    // Cancel cropping
    cancelCropBtn.addEventListener('click', () => {
        cropperModal.classList.add('hidden');
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        cropperImage.src = '';
    });

    // Save the cropped image
    saveCropBtn.addEventListener('click', () => {
        if (cropper) {
            const canvas = cropper.getCroppedCanvas({
                width: 300,
                height: 300,
            });

            canvas.toBlob((blob) => {
                const formData = new FormData();
                formData.append('profile_picture', blob);

                fetch(window.Laravel.profileUpdateRoute, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': window.Laravel.csrfToken,
                    },
                    body: formData,
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            profilePicturePreview.src = data.profile_picture_url + '?t=' + new Date().getTime();
                            cropperModal.classList.add('hidden');
                        } else {
                            alert('Failed to update profile picture.');
                        }
                    })
                    .catch((error) => {
                        alert('An error occurred.');
                        console.error('Error:', error);
                    });
            }, 'image/jpeg', 0.95);
        }
    });
});