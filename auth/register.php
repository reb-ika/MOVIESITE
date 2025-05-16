<!DOCTYPE html>
<html>
<head>
  <title>Register</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
  <h2>Register</h2>
  <form id="registerForm">
    <input type="text" id="name" class="form-control mb-2" placeholder="Name" required>
    <input type="email" id="email" class="form-control mb-2" placeholder="Email" required>
    <input type="password" id="password" class="form-control mb-2" placeholder="Password" required>
    <select id="role" class="form-control mb-2">
      <option value="user">User</option>
      <option value="admin">Admin</option>
    </select>
    <button type="submit" class="btn btn-primary">Register</button>
  </form>

  <script>
    document.getElementById("registerForm").addEventListener("submit", async function(e) {
      e.preventDefault();

      const name = document.getElementById("name").value;
      const email = document.getElementById("email").value;
      const password = document.getElementById("password").value;
      const role = document.getElementById("role").value;

      try {
        const res = await fetch("../api/register.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ username: name, email, password, role })
        });

        const data = await res.json();
        alert(data.message || data.error);
      } catch (err) {
        alert("An error occurred. Please try again.");
        console.error(err);
      }
    });
  </script>
</body>
</html>
