

<?php
$title = "Movies";
include '../includes/header.php';
?>
<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
  <h2>Login</h2>
  
  <form id="loginForm">
    <input type="email" id="email" class="form-control mb-2" placeholder="Email" required>
    <input type="password" id="password" class="form-control mb-2" placeholder="Password" required>
    <button type="submit" class="btn btn-primary">Login</button>
  </form>

  <script>
    document.getElementById("loginForm").addEventListener("submit", async function(e) {
      e.preventDefault();

      const email = document.getElementById("email").value;
      const password = document.getElementById("password").value;

      try {
        const res = await fetch("../api/login.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ email, password })
        });

        const data = await res.json();

        if (data.user) {
          localStorage.setItem("user", JSON.stringify(data.user));
          alert("Login successful!");

          // Redirect based on role
          if (data.user.role === 'admin') {
            window.location.href = '/moviesite/admin/dashboard.php';
          } else {
            window.location.href = '/moviesite/index.php';
          }

        } else {
          alert(data.message || "Invalid credentials");
        }

      } catch (error) {
        console.error("Login error:", error);
        alert("An error occurred. Please try again.");
      }
    });
  </script>
</body>
</html>
