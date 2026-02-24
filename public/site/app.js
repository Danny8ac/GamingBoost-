<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>GamingBoost • Login</title>
  <link rel="stylesheet" href="styles.css"/>
</head>
<body>
  <div class="container">
    <div class="nav">
      <div class="brand">
        <div class="logo"></div>
        <div>
          <div>GamingBoost</div>
          <div class="small">Login</div>
        </div>
      </div>
      <div class="navlinks" id="nav-auth"></div>
    </div>

    <div class="card" style="margin-top:16px;">
      <h2>Iniciar sesión</h2>

      <div style="margin-top:12px;">
        <input class="input" id="email" placeholder="Email"/>
      </div>

      <div style="margin-top:10px;">
        <input class="input" id="password" type="password" placeholder="Password"/>
      </div>

      <div style="margin-top:14px;">
        <button class="btn primary" id="loginBtn">Entrar</button>
      </div>

      <div id="msg" class="small" style="margin-top:10px;"></div>
    </div>
  </div>

  <script src="app.js"></script>
  <script>
    navbarRender();

    const msg = document.getElementById("msg");

    document.getElementById("loginBtn").onclick = async () => {
      msg.textContent = "Entrando...";

      const email = document.getElementById("email").value.trim();
      const password = document.getElementById("password").value;

      const res = await apiFetch("/login", {
        method: "POST",
        auth: false,
        body: { email, password }
      });

      if (!res.ok || !res.data) {
        msg.textContent = "❌ Login incorrecto";
        return;
      }

      setToken(res.data.token);
      setUser(res.data.user);

      msg.textContent = "✅ Login correcto";
      location.href = "catalog.html";
    };
  </script>
</body>
</html>