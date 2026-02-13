let cropper = null;
let croppedBase64 = null;

const avatarInput = document.getElementById("avatarInput");
const cropModal = document.getElementById("cropModal");
const cropImage = document.getElementById("cropImage");
const cropBtn = document.getElementById("cropBtn");
const avatarPreview = document.getElementById("avatarPreview");
const editForm = document.getElementById("editProfileForm");
const errorText = document.getElementById("errorText");

// Helper to convert base64 to File object
function base64ToFile(base64, filename) {
    const arr = base64.split(",");
    const mime = arr[0].match(/:(.*?);/)[1];
    const bstr = atob(arr[1]);
    let n = bstr.length;
    const u8arr = new Uint8Array(n);
    while (n--) u8arr[n] = bstr.charCodeAt(n);
    return new File([u8arr], filename, { type: mime });
}

// 1. Show Crop Modal when image is picked
avatarInput.addEventListener("change", () => {
    const file = avatarInput.files[0];
    if (file && file.type.startsWith("image/")) {
        const reader = new FileReader();
        reader.onload = () => {
            cropImage.src = reader.result;
            cropModal.style.display = "flex"; // SHOW MODAL

            if (cropper) cropper.destroy();
            cropper = new Cropper(cropImage, {
                aspectRatio: 1,
                viewMode: 1,
                background: false
            });
        };
        reader.readAsDataURL(file);
    }
});

// 2. Perform Crop
cropBtn.addEventListener("click", () => {
    if (!cropper) return;
    const canvas = cropper.getCroppedCanvas({ width: 300, height: 300 });
    croppedBase64 = canvas.toDataURL("image/jpeg", 0.9);
    avatarPreview.src = croppedBase64; // Update preview on main page
    cropModal.style.display = "none";  // HIDE MODAL
    cropper.destroy();
    cropper = null;
});

// 3. Submit Form
editForm.addEventListener("submit", (e) => {
    e.preventDefault();
    const btn = document.getElementById("submitBtn");
    btn.classList.add("loading");
    errorText.textContent = "";

    const formData = new FormData();
    formData.append("fname", document.getElementById("firstName").value);
    formData.append("lname", document.getElementById("lastName").value);
    formData.append("email", document.getElementById("email").value);
    formData.append("password", document.getElementById("password").value);

    if (croppedBase64) {
        const imageFile = base64ToFile(croppedBase64, "avatar.jpg");
        formData.append("image", imageFile);
    }

    fetch("php/update-profile.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        btn.classList.remove("loading");
        if (data.status === "success") {
            window.location.href = "app.php";
        } else {
            errorText.textContent = data.message;
        }
    })
    .catch(() => {
        btn.classList.remove("loading");
        errorText.textContent = "Network error";
    });
});

// Toggle Password visibility
document.querySelectorAll(".toggle-password").forEach(toggle => {
    toggle.addEventListener("click", () => {
        const input = document.getElementById(toggle.dataset.target);
        input.type = input.type === "password" ? "text" : "password";
        toggle.textContent = input.type === "password" ? "Show" : "Hide";
    });
});