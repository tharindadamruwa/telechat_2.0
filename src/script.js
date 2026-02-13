const loginForm = document.getElementById("loginForm");
const errorText = document.getElementById("errorText");

// Toggle password
function togglePassword() {
  const passwordInput = document.getElementById("password");
  const toggle = document.querySelector(".toggle-password");

  if (passwordInput.type === "password") {
    passwordInput.type = "text";
    toggle.textContent = "Hide";
  } else {
    passwordInput.type = "password";
    toggle.textContent = "Show";
  }
}

// Submit login form
loginForm.addEventListener("submit", function (e) {
  e.preventDefault();
  
  errorText.textContent = "";

  const email = document.getElementById("email").value;
  const password = document.getElementById("password").value;

  const formData = new FormData();
  formData.append("email", email);
  formData.append("password", password);

  const button = loginForm.querySelector("button");
  const btnText = button.querySelector(".btn-text");
  const loader = button.querySelector(".loader");

  // Show loading
  button.classList.add("loading");

  fetch("php/login.php", {
    method: "POST",
    body: formData
  })
    .then(res => res.text())
    .then(data => {
      // Hide loading
      button.classList.remove("loading");

      if (data.trim() === "success") {
        window.location.href = "app.php";
      } else {
        errorText.textContent = data;
      }
    })
    .catch(() => {
      btnText.style.display = "inline";
      loader.style.display = "none";
      errorText.textContent = "Network error, try again";
    });
});