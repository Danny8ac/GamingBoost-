<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Checkout Simulado</title>
  <style>
    body{
      margin:0;
      font-family: Arial, sans-serif;
      min-height:100vh;
      background: radial-gradient(900px 500px at 15% 0%, rgba(96,165,250,.18), transparent 60%),
                  radial-gradient(900px 500px at 90% 10%, rgba(34,197,94,.14), transparent 60%),
                  linear-gradient(160deg, #070A12, #0B1020);
      color:#eaf0ff;
      display:flex;
      align-items:center;
      justify-content:center;
      padding:24px;
    }

    .card{
      width:min(760px, 100%);
      border-radius:18px;
      padding:18px;
      border:1px solid rgba(255,255,255,.12);
      background: rgba(255,255,255,.06);
      box-shadow: 0 18px 60px rgba(0,0,0,.55);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
    }

    .row{display:flex; justify-content:space-between; gap:10px; flex-wrap:wrap; align-items:center;}
    .muted{color: rgba(234,240,255,.72);}
    .badge{
      padding:7px 12px;
      border-radius:999px;
      border:1px solid rgba(255,255,255,.12);
      background: rgba(255,255,255,.05);
      font-weight:800;
    }

    .hr{ height:1px; background: rgba(255,255,255,.10); margin:14px 0; }

    .tabs{ display:flex; gap:10px; flex-wrap:wrap; margin-top:12px; }
    .tab{
      padding:10px 14px;
      border-radius:14px;
      border:1px solid rgba(255,255,255,.12);
      background: rgba(255,255,255,.06);
      color:#eaf0ff;
      font-weight:900;
      cursor:pointer;
      user-select:none;
      transition: transform .12s ease, box-shadow .12s ease, border-color .12s ease;
    }
    .tab:hover{ transform: translateY(-1px); box-shadow: 0 14px 28px rgba(0,0,0,.35); }
    .tab.active{
      border:0;
      color:#07101f;
      background: linear-gradient(90deg, rgba(96,165,250,.95), rgba(34,197,94,.88));
    }

    .panel{
      margin-top:14px;
      border-radius:16px;
      border:1px solid rgba(255,255,255,.12);
      background: rgba(255,255,255,.04);
      padding:14px;
    }

    .grid{
      display:grid;
      grid-template-columns: 1fr 1fr;
      gap:12px;
      margin-top:12px;
    }
    @media(max-width: 760px){ .grid{ grid-template-columns: 1fr; } }

    label{ display:block; font-size:.9rem; color: rgba(234,240,255,.72); margin-bottom:6px; }
    .input{
      width:100%;
      padding:12px 12px;
      border-radius:14px;
      border:1px solid rgba(255,255,255,.12);
      background: rgba(10,14,25,.35);
      color:#eaf0ff;
      outline:none;
    }
    .input:focus{
      border-color: rgba(96,165,250,.55);
      box-shadow: 0 0 0 4px rgba(96,165,250,.12);
    }

    .chips{ display:flex; gap:10px; flex-wrap:wrap; margin-top:8px; }
    .chip{
      padding:9px 12px;
      border-radius:999px;
      border:1px solid rgba(255,255,255,.12);
      background: rgba(255,255,255,.05);
      cursor:pointer;
      font-weight:900;
      color:#eaf0ff;
    }
    .chip.active{
      border:0;
      color:#07101f;
      background: linear-gradient(90deg, rgba(96,165,250,.95), rgba(34,197,94,.88));
    }

    .actions{ display:grid; gap:10px; margin-top:14px; }
    .btn{
      width:100%;
      padding:12px 14px;
      border-radius:14px;
      border:1px solid rgba(255,255,255,.12);
      background: rgba(255,255,255,.06);
      color:#eaf0ff;
      font-weight:950;
      cursor:pointer;
    }
    .btn.primary{
      border:0;
      color:#07101f;
      background: linear-gradient(90deg, rgba(96,165,250,.95), rgba(34,197,94,.88));
    }
    .btn.danger{
      background: rgba(239,68,68,.16);
      border: 1px solid rgba(239,68,68,.35);
    }

    .notice{
      margin-top:10px;
      color: rgba(234,240,255,.72);
      font-size:.92rem;
    }
  </style>
</head>
<body>

@php
  // provider llega del backend en /checkout/{id}?provider=...
  $provider = request('provider', $order->provider);
  $fromQuery = isset($from) && $from ? ('?from=' . urlencode($from)) : '';
  $total = number_format($order->total_amount / 100, 2);
@endphp

<div class="card">
  <div class="row">
    <div>
      <div style="font-weight:950; font-size:1.1rem;">GamingBoost</div>
      <div class="muted">Checkout simulado</div>
      <div class="muted" style="margin-top:10px;">
        Proveedor: <b style="color:#eaf0ff;" id="providerLabel">{{ $provider }}</b><br/>
        Total: <b style="color:#eaf0ff;">${{ $total }} MXN</b>
      </div>
    </div>
    <div class="badge">Pedido #{{ $order->id }}</div>
  </div>

  <div class="hr"></div>

  <!-- TABS proveedor -->
  <div class="muted" style="font-weight:900;">Elige proveedor</div>
  <div class="tabs" id="providerTabs">
    <div class="tab" data-provider="stripe">Stripe</div>
    <div class="tab" data-provider="mercadopago">MercadoPago</div>
    <div class="tab" data-provider="paypal">PayPal</div>
  </div>

  <!-- PANEL -->
  <div class="panel">

    <!-- Stripe -->
    <div id="panel-stripe">
      <div style="font-weight:950;">Pago con tarjeta (Stripe)</div>
      <div class="grid">
        <div>
          <label>Nombre</label>
          <input class="input" placeholder="Nombre en la tarjeta" />
        </div>
        <div>
          <label>Número de tarjeta</label>
          <input class="input" placeholder="4242 4242 4242 4242" />
        </div>
        <div>
          <label>Exp</label>
          <input class="input" placeholder="12/34" />
        </div>
        <div>
          <label>CVV</label>
          <input class="input" placeholder="123" />
        </div>
      </div>
      <div class="notice">* Datos simulados (solo UI).</div>
    </div>

    <!-- MercadoPago -->
    <div id="panel-mercadopago" style="display:none;">
      <div style="font-weight:950;">MercadoPago</div>
      <div class="muted" style="margin-top:6px;">Elige el método:</div>

      <div class="chips" id="mpChips">
        <div class="chip active" data-method="card">Tarjeta</div>
        <div class="chip" data-method="oxxo">OXXO</div>
        <div class="chip" data-method="spei">SPEI</div>
      </div>

      <div id="mp-card" style="margin-top:12px;">
        <div class="grid">
          <div>
            <label>Nombre</label>
            <input class="input" placeholder="Nombre" />
          </div>
          <div>
            <label>Número de tarjeta</label>
            <input class="input" placeholder="4509 9535 6623 3704" />
          </div>
          <div>
            <label>Exp</label>
            <input class="input" placeholder="11/33" />
          </div>
          <div>
            <label>CVV</label>
            <input class="input" placeholder="999" />
          </div>
        </div>
      </div>

      <div id="mp-oxxo" style="display:none; margin-top:12px;">
        <div class="muted">Se generará una referencia OXXO (simulado).</div>
        <div class="grid">
          <div>
            <label>Nombre</label>
            <input class="input" placeholder="Nombre" />
          </div>
          <div>
            <label>Email</label>
            <input class="input" placeholder="correo@ejemplo.com" />
          </div>
        </div>
      </div>

      <div id="mp-spei" style="display:none; margin-top:12px;">
        <div class="muted">Se mostrará CLABE SPEI (simulado).</div>
        <div class="grid">
          <div>
            <label>Nombre</label>
            <input class="input" placeholder="Nombre" />
          </div>
          <div>
            <label>Banco</label>
            <input class="input" placeholder="BBVA / Banorte / etc" />
          </div>
        </div>
      </div>

      <div class="notice">* Métodos simulados (solo UI).</div>
    </div>

    <!-- PayPal -->
    <div id="panel-paypal" style="display:none;">
      <div style="font-weight:950;">PayPal</div>
      <div class="muted" style="margin-top:6px;">
        Normalmente aquí iría el login de PayPal. En esta demo es simulado.
      </div>

      <div class="grid" style="margin-top:12px;">
        <div>
          <label>Email PayPal</label>
          <input class="input" placeholder="paypal@correo.com" />
        </div>
        <div>
          <label>Confirmación</label>
          <input class="input" placeholder="Código 123456" />
        </div>
      </div>

      <div class="notice">* Simulado (solo UI).</div>
    </div>

  </div>

  <!-- ACTIONS -->
  <div class="actions">
    <form method="POST" action="/checkout/{{ $order->id }}/confirm{{ $fromQuery }}">
      @csrf
      <button class="btn primary" type="submit">Pagar</button>
    </form>

    <form method="POST" action="/checkout/{{ $order->id }}/cancel{{ $fromQuery }}">
      @csrf
      <button class="btn danger" type="submit">Cancelar</button>
    </form>
  </div>
</div>

<script>
  // Tabs proveedor
  const initialProvider = @json($provider || "stripe");
  const tabs = Array.from(document.querySelectorAll("#providerTabs .tab"));
  const providerLabel = document.getElementById("providerLabel");

  const panels = {
    stripe: document.getElementById("panel-stripe"),
    mercadopago: document.getElementById("panel-mercadopago"),
    paypal: document.getElementById("panel-paypal")
  };

  function setProvider(p){
    providerLabel.textContent = p;
    tabs.forEach(t => t.classList.toggle("active", t.dataset.provider === p));
    Object.keys(panels).forEach(k => panels[k].style.display = (k === p ? "block" : "none"));
  }

  tabs.forEach(t => t.addEventListener("click", () => setProvider(t.dataset.provider)));
  setProvider(initialProvider);

  // Chips MP
  const mpChips = Array.from(document.querySelectorAll("#mpChips .chip"));
  const mpCard = document.getElementById("mp-card");
  const mpOxxo = document.getElementById("mp-oxxo");
  const mpSpei = document.getElementById("mp-spei");

  function setMp(method){
    mpChips.forEach(c => c.classList.toggle("active", c.dataset.method === method));
    mpCard.style.display = method === "card" ? "block" : "none";
    mpOxxo.style.display = method === "oxxo" ? "block" : "none";
    mpSpei.style.display = method === "spei" ? "block" : "none";
  }

  mpChips.forEach(c => c.addEventListener("click", () => setMp(c.dataset.method)));
  setMp("card");
</script>

</body>
</html>