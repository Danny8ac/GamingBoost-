<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Resultado de pago</title>
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
      width:min(560px, 100%);
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
    .status{
      margin-top:14px;
      padding:14px;
      border-radius:16px;
      border:1px solid rgba(255,255,255,.10);
      background: rgba(255,255,255,.04);
      text-align:center;
    }
    .statusTitle{
      font-weight:950;
      font-size:1.15rem;
      margin:8px 0 0;
    }
    .ok{color:#22c55e;}
    .bad{color:#ef4444;}
    .btn{
      width:100%;
      padding:12px 14px;
      border-radius:14px;
      border:0;
      font-weight:950;
      cursor:pointer;
      margin-top:12px;
      color:#07101f;
      background: linear-gradient(90deg, rgba(96,165,250,.95), rgba(34,197,94,.88));
    }
    .countdown{margin-top:10px; text-align:center; color: rgba(234,240,255,.72);}
  </style>
</head>
<body>

@php
  $isWeb = isset($from) && $from === 'web';
  $intentLink = "intent://payment-result?order_id={$orderId}&status={$status}#Intent;scheme=gamingboost;package=com.gamingboost.app;end";
  $webReturn = url("/site/orders.html") . "?refresh=1&order_id={$orderId}&status={$status}";
@endphp

<div class="card">
  <div class="row">
    <div>
      <div style="font-weight:950; font-size:1.1rem;">GamingBoost</div>
      <div class="muted">Resultado del pago</div>
    </div>
    <div class="badge">Pedido #{{ $orderId }}</div>
  </div>

  <div style="margin-top:14px; font-weight:900; font-size:1.05rem;">
    Listo
  </div>

  <div class="muted">
    @if($isWeb)
      Te regresaremos a la <b>web</b> automáticamente.
    @else
      Te regresaremos a la <b>app</b> automáticamente.
    @endif
  </div>

  <div class="status">
    <div style="font-size:44px; line-height:1;">@if($status === 'paid') ✅ @else ❌ @endif</div>
    <div class="statusTitle @if($status === 'paid') ok @else bad @endif">
      @if($status === 'paid') Pago confirmado @else Pago cancelado @endif
    </div>
    <div class="muted" style="margin-top:6px;">
      @if($status === 'paid') Tu compra se reflejará. @else Puedes intentarlo de nuevo. @endif
    </div>
  </div>

  <div class="countdown">
    Redirigiendo en <span id="timer">2</span> segundos...
  </div>

  <button class="btn" id="goBtn">Volver ahora</button>
</div>

<script>
  const isWeb = @json($isWeb);
  const target = isWeb ? @json($webReturn) : @json($intentLink);

  document.getElementById("goBtn").onclick = () => {
    window.location.href = target;
  };

  let seconds = 2;
  const timer = document.getElementById("timer");

  const interval = setInterval(() => {
    seconds--;
    timer.textContent = seconds;

    if (seconds <= 0) {
      clearInterval(interval);
      window.location.href = target;
    }
  }, 1000);
</script>

</body>
</html>