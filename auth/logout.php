
<button onclick="logout()">Logout</button>
function logout() {
  localStorage.removeItem("user");
  window.location.href = "login.html";
  

}
