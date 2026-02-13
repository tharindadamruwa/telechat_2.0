const userList = document.getElementById("userList");
const chatUser = document.getElementById("chatUser");
const chatMessages = document.getElementById("chatMessages");
const input = document.querySelector(".chat-input input");
const button = document.querySelector(".chat-input button");
const sidebar = document.getElementById("sidebar");
const menuDots = document.getElementById("menuDots");
const dropdownMenu = document.getElementById("dropdownMenu");
const menuBtn = document.getElementById("menuBtn");
const scrollBtn = document.getElementById("scrollBtn");
const logoutBtn = document.getElementById("logoutBtn"); // New reference

let currentChatId = null;

/* -------------------- Scroll Control -------------------- */
function isNearBottom() {
  return (chatMessages.scrollHeight - chatMessages.scrollTop - chatMessages.clientHeight < 50);
}

function scrollToBottom(force = false) {
  if (force || isNearBottom()) {
    chatMessages.scrollTop = chatMessages.scrollHeight;
  }
}

chatMessages.addEventListener("scroll", () => {
  scrollBtn.style.display = isNearBottom() ? "none" : "flex";
});

scrollBtn.addEventListener("click", () => {
  chatMessages.scrollTop = chatMessages.scrollHeight;
});

/* -------------------- Open Chat -------------------- */
function openChat(name, uniq_id) {
  currentChatId = uniq_id;

  document.querySelectorAll(".user").forEach(u => u.classList.remove("active"));
  const active = document.querySelector(`.user[data-id="${uniq_id}"]`);
  if (active) active.classList.add("active");

  chatUser.textContent = name;
  input.disabled = false;
  button.disabled = false;
  sidebar.classList.remove("show"); 

  loadMessages(true);
  loadUsers();
}

/* -------------------- Load Users -------------------- */
function loadUsers() {
  fetch("php/get-users-sorted.php")
    .then(res => res.json())
    .then(data => {
      if (data.status !== "success") return;
      userList.innerHTML = "";
      data.users.forEach(user => {
        //const dot = user.unread && currentChatId !== user.uniq_id
          if (user.unread && currentChatId !== user.uniq_id) {
            var dot = '<span class="green-dot"></span>';
          } else {
            var dot = '';
          }
            //? '<span class="green-dot"></span>'
            //: '';
        const div = document.createElement("div");
        div.className = "user";
        div.dataset.id = user.uniq_id;
        if (currentChatId === user.uniq_id) div.classList.add("active");
        div.innerHTML = `
          <img src="img/${user.img}" alt="${user.name}">
          <div class="user-name">${user.name} </div>
          ${dot}
        `;
        div.onclick = () => openChat(user.name, user.uniq_id);
        userList.appendChild(div);
      });
    });
}

/* -------------------- Load Messages -------------------- */
function loadMessages(forceScroll = false) {
  if (!currentChatId) return;
  const shouldScroll = isNearBottom();
  fetch("php/get-messages.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: "incoming_id=" + currentChatId
  })
    .then(res => res.json())
    .then(data => {
      if (data.status !== "success") return;
      chatMessages.innerHTML = "";
      data.messages.forEach(msg => {
        const div = document.createElement("div");
        div.className = msg.outgoing_msg_id === MY_ID ? "msg sent" : "msg received";
        div.textContent = msg.msg;
        chatMessages.appendChild(div);
      });
      scrollToBottom(forceScroll || shouldScroll);
    });
}

/* -------------------- Send Message -------------------- */
function sendMessage() {
  const message = input.value.trim();
  if (!message || !currentChatId) return;
  fetch("php/send-message.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: `incoming_id=${currentChatId}&msg=${encodeURIComponent(message)}`
  })
    .then(res => res.json())
    .then(() => {
      input.value = "";
      loadMessages(true);
      loadUsers();
    });
}

button.onclick = sendMessage;
input.addEventListener("keydown", e => {
  if (e.key === "Enter") {
    e.preventDefault();
    sendMessage();
  }
});

/* -------------------- Realtime -------------------- */
setInterval(() => {
  loadUsers();
  loadMessages();
}, 1500);

/* -------------------- UI Controls -------------------- */
document.addEventListener("click", (e) => {
  if (!sidebar.contains(e.target) && !menuBtn.contains(e.target) && !menuDots.contains(e.target) && !dropdownMenu.contains(e.target) && !e.target.closest(".brand")) {
    sidebar.classList.remove("show");
  }
});

menuBtn.onclick = (e) => {
  e.stopPropagation();
  sidebar.classList.toggle("show");
};

menuDots.onclick = (e) => {
  e.stopPropagation();
  dropdownMenu.style.display = dropdownMenu.style.display === "flex" ? "none" : "flex";
};

document.body.addEventListener("click", () => {
  dropdownMenu.style.display = "none";
});

/* -------------------- NEW: Logout Logic -------------------- */
logoutBtn.onclick = (e) => {
    e.preventDefault();
    if (confirm("Are you sure you want to logout?")) {
        fetch("php/logout.php")
            .then(res => res.json())
            .then(data => {
                if (data.status === "success") {
                    // Redirect back to login page
                    window.location.href = "https://telechat.rf.gd/index.php";
                } else {
                    alert("Logout failed. Please try again.");
                }
            })
            .catch(err => console.error("Error logging out:", err));
    }
};

/* -------------------- Initial Load -------------------- */
loadUsers();

function setAppHeight() {
  const vh = window.visualViewport
    ? window.visualViewport.height
    : window.innerHeight;

  document.documentElement.style.setProperty('--app-height', `${vh}px`);
}

setAppHeight();

window.visualViewport?.addEventListener('resize', setAppHeight);
window.addEventListener('resize', setAppHeight);