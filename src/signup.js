let cropper = null;
let croppedBase64 = null;

const avatarInput = document.getElementById("avatarInput");
const cropModal = document.getElementById("cropModal");
const cropImage = document.getElementById("cropImage");
const cropBtn = document.getElementById("cropBtn");
const avatarPreview = document.getElementById("avatarPreview");
const avatarWrapper = document.getElementById("avatarWrapper");
const croppedInput = document.getElementById("croppedImage");
const signupForm = document.getElementById("signupForm");
const errorText = document.getElementById("errorText");

/* -------------------------
   Base64 → File helper
-------------------------- */
function base64ToFile(base64, filename) {
  const arr = base64.split(",");
  const mime = arr[0].match(/:(.*?);/)[1];
  const bstr = atob(arr[1]);
  let n = bstr.length;
  const u8arr = new Uint8Array(n);

  while (n--) {
    u8arr[n] = bstr.charCodeAt(n);
  }

  return new File([u8arr], filename, { type: mime });
}

/* -------------------------
   Image select
-------------------------- */
avatarInput.addEventListener("change", () => {
  const file = avatarInput.files[0];
  if (!file || !file.type.startsWith("image/")) return;

  const reader = new FileReader();
  reader.onload = () => {
    cropImage.src = reader.result;
    cropModal.style.display = "flex";

    if (cropper) cropper.destroy();

    cropper = new Cropper(cropImage, {
      aspectRatio: 1,
      viewMode: 1,
      zoomable: true,
      movable: true,
      cropBoxResizable: true
    });
  };

  reader.readAsDataURL(file);
});

/* -------------------------
   Crop image
-------------------------- */
cropBtn.addEventListener("click", () => {
  if (!cropper) return;

  const canvas = cropper.getCroppedCanvas({
    width: 300,
    height: 300
  });

  croppedBase64 = canvas.toDataURL("image/jpeg", 0.9);

  avatarPreview.src = croppedBase64;
  croppedInput.value = croppedBase64;

  cropModal.style.display = "none";

  cropper.destroy();
  cropper = null;
});

/* -------------------------
   Submit form
-------------------------- */
signupForm.addEventListener("submit", (e) => {
  e.preventDefault();
  errorText.textContent = "";

  const password = document.getElementById("password").value;
  const confirmPassword = document.getElementById("confirmPassword").value;

  if (password !== confirmPassword) {
    errorText.textContent = "Confirm password doesn't match";
    return;
  }

  const formData = new FormData();
  formData.append("fname", document.getElementById("firstName").value);
  formData.append("lname", document.getElementById("lastName").value);
  formData.append("email", document.getElementById("email").value);
  formData.append("password", password);

  // ✅ upload cropped image
  if (croppedBase64) {
    const imageFile = base64ToFile(croppedBase64, "avatar.jpg");
    formData.append("image", imageFile);
  }

  const button = signupForm.querySelector("button");
  button.classList.add("loading");

  fetch("php/signup.php", {
    method: "POST",
    body: formData
  })
    .then(res => res.text())
    .then(data => {
      //button.classList.remove("loading");

      if (data.trim() === "success") {
        window.location.href = "https://telechat.rf.gd/app.php";
      } else {
        errorText.textContent = data;
      }
    })
    .catch(() => {
      button.classList.remove("loading");
      errorText.textContent = "Network error";
    });
});

/* -------------------------
   Show / Hide password
-------------------------- */
document.querySelectorAll(".toggle-password").forEach(toggle => {
  toggle.addEventListener("click", () => {
    const input = document.getElementById(toggle.dataset.target);
    input.type = input.type === "password" ? "text" : "password";
    toggle.textContent = input.type === "password" ? "Show" : "Hide";
  });
});