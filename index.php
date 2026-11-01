<?php
declare(strict_types=1);
$cfg = require __DIR__ . '/config.php';
$productos = require __DIR__ . '/data/productos.php';
function e(string $s): string { return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }

/* Datos */
$datos = [
    'whatsapp'  => $cfg['whatsapp'],
    'pdf'       => $cfg['pdf'],
    'colores'   => $cfg['colores'],
    'productos' => array_map(fn($p) => [
        'n'     => $p['nombre'],
        'c'     => $p['categoria'],
        'p'     => $p['precio'],
        'i'     => $p['imagen'] ?? '',
        'nuevo' => !empty($p['nuevo']),
    ], $productos),
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title><?= e($cfg['empresa']) ?> – Productos nuevos y catálogo</title>
<meta name="description" content="Conoce los productos nuevos y el catálogo de COMPANEX Bolivia. Arma tu pedido y envíalo por WhatsApp.">
<meta name="theme-color" content="#001a49">
<link rel="icon" type="image/png" href="images/favicon.png">
<link rel="stylesheet" href="css/estilos.css">
</head>
<body>
<a class="skip" href="#main">Saltar al contenido</a>

<header class="hero" id="inicio">
  <nav class="nav" aria-label="Principal">
    <a class="logo" href="#inicio" aria-label="COMPANEX Bolivia, inicio"><img id="logo" alt="COMPANEX" width="409" height="116" src="images/logo.png"></a>
    <button class="nav__btn" id="open" aria-expanded="false" aria-controls="menu">Menú</button>
    <ul id="menu">
      <li><a href="#nuevos">Nuevos</a></li>
      <li><a href="#catalogo">Catálogo</a></li>
      <li><a href="#como">Cómo comprar</a></li>
      <li><a href="#preguntas">Preguntas</a></li>
      <li><a href="#pedido">Mi pedido<span class="count" id="count">0</span></a></li>
      <li><button class="nav__close" id="close" type="button">Cerrar</button></li>
    </ul>
  </nav>
  <div class="hero__body">
    <h1 class="hero__title">Los productos que tu negocio necesita, siempre a la mano</h1>
    <p>Mira los productos nuevos de COMPANEX Bolivia, arma tu pedido en minutos y envíalo por WhatsApp a tu preventista.</p>
    <div class="hero__actions">
      <a class="cta cta--orange" href="#catalogo">Ver catálogo</a>
      <a class="cta cta--ghost" href="#nuevos">Productos nuevos</a>
    </div>
  </div>
</header>

<main id="main">
  <noscript><p class="lead" style="padding:20px">Activa JavaScript para ver el catálogo y armar tu pedido.</p></noscript>
  <section class="container about">
    <h2 class="subtitle">¿Por qué comprar con COMPANEX?</h2>
    <p class="lead" style="margin:0 auto">Distribución directa en Cochabamba, con atención de un preventista que conoce tu negocio.</p>
    <div class="about__main">
      <article class="about__item">
        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 7l9-4 9 4-9 4-9-4zM3 7v10l9 4 9-4V7M12 11v10"/></svg>
        <h3>Variedad de marcas</h3><p>Abarrotes, bebidas, limpieza y más en un solo lugar.</p>
      </article>
      <article class="about__item">
        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 7h11v9H3zM14 10h4l3 3v3h-7zM7 19a2 2 0 100-4 2 2 0 000 4zM17 19a2 2 0 100-4 2 2 0 000 4z"/></svg>
        <h3>Entrega a tu negocio</h3><p>Rutas programadas para que no te quedes sin stock.</p>
      </article>
      <article class="about__item">
        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21 12a8 8 0 01-11.6 7.1L4 20l1-4.6A8 8 0 1121 12z"/></svg>
        <h3>Pedido por WhatsApp</h3><p>Sin registro ni filas: eliges, envías y confirmamos.</p>
      </article>
    </div>
  </section>

  <section class="dots" id="nuevos">
    <div class="container">
      <h2 class="subtitle">Productos nuevos</h2>
      <p class="lead" style="margin-bottom:34px">Lo último que llegó a nuestro almacén.</p>
      <div class="grid" id="newGrid"></div>
    </div>
  </section>

  <section class="container" id="catalogo">
    <h2 class="subtitle">Catálogo completo</h2>
    <p class="lead">Busca por nombre o filtra por categoría. Toca “Agregar” para sumar el producto a tu pedido.</p>
    <div class="tools">
      <input class="search" id="q" type="search" placeholder="Buscar producto…" aria-label="Buscar producto">
      <div class="chips" id="chips" role="group" aria-label="Filtrar por categoría"></div>
    </div>
    <p class="hint" id="result" aria-live="polite"></p>
    <div class="grid" id="grid"></div>
    <p style="margin-top:34px"><a class="cta" id="pdfBtn" href="#">Descargar catálogo en PDF</a></p>
  </section>

  <section class="container steps" id="como">
    <h2 class="subtitle">Comprar es muy simple</h2>
    <p class="lead" style="margin:0 auto">Tres pasos y sin registrarte.</p>
    <div class="steps__row">
      <div class="step"><p class="step__n">Paso 1</p><h3>Elige</h3><p>Agrega a tu lista los productos que necesitas.</p><a href="#catalogo">Ir al catálogo</a></div>
      <div class="step step--best"><p class="step__n">Paso 2</p><h3>Envía</h3><p>Completa tu nombre y negocio y envía el pedido por WhatsApp.</p><a href="#pedido">Armar mi pedido</a></div>
      <div class="step"><p class="step__n">Paso 3</p><h3>Recibe</h3><p>Tu preventista confirma precios, stock y fecha de entrega.</p><a href="#preguntas">Ver preguntas</a></div>
    </div>
  </section>

  <section class="testi" aria-label="Opiniones de clientes">
    <div class="container testi__box">
      <button class="arrow" id="prev" aria-label="Opinión anterior">‹</button>
      <div class="testi__slide show"><div><h2 class="subtitle">Doña Rosa, <span class="testi__who">Tienda de barrio</span></h2><p>Ahora veo los productos nuevos antes de que llegue el preventista y hago mi pedido más rápido.</p></div><div class="avatar" aria-hidden="true">R</div></div>
      <div class="testi__slide"><div><h2 class="subtitle">Carlos, <span class="testi__who">Minimarket</span></h2><p>Me gusta poder buscar por nombre y mandar la lista completa por WhatsApp sin llamar.</p></div><div class="avatar" aria-hidden="true" style="background:#c4521f">C</div></div>
      <div class="testi__slide"><div><h2 class="subtitle">Marisol, <span class="testi__who">Kiosco</span></h2><p>Es fácil de usar desde el celular y siempre sé qué hay de nuevo.</p></div><div class="avatar" aria-hidden="true" style="background:#3a7d6b">M</div></div>
      <button class="arrow" id="next" aria-label="Opinión siguiente">›</button>
    </div>
  </section>

  <section class="container faq" id="preguntas">
    <h2 class="subtitle">Preguntas frecuentes</h2>
    <div class="faq__list">
      <details><summary>¿Los precios son finales?</summary><p>Son referenciales. Tu preventista confirma el precio y el stock antes de entregar.</p></details>
      <details><summary>¿Tengo que crear una cuenta?</summary><p>No. Solo escribes tu nombre y el de tu negocio y envías el pedido por WhatsApp.</p></details>
      <details><summary>¿Cuándo recibo mi pedido?</summary><p>Se coordina según la ruta de tu zona; tu preventista te confirma la fecha.</p></details>
    </div>
    <div class="offer" id="pedido">
      <h2 class="subtitle">Mi pedido</h2>
      <p>Tu lista se llena sola con los productos que agregues. Puedes editarla antes de enviar.</p>
      <div class="contact" style="text-align:left">
        <div class="lead">Horario de atención: lunes a sábado.<br>Ciudad: <?= e($cfg['ciudad']) ?>.<br><span class="small">Revisa tu lista y envíala; no se cobra nada en esta página.</span></div>
        <form id="form" method="post" action="php/pedido.php" novalidate>
          <div class="hp" aria-hidden="true"><label>No llenar este campo<input name="web" tabindex="-1" autocomplete="off"></label></div>
          <label for="nombre">Nombre<input id="nombre" name="nombre" autocomplete="name" maxlength="80" required><span class="err" id="e1" role="alert"></span></label>
          <label for="negocio">Nombre de tu negocio<input id="negocio" name="negocio" autocomplete="organization" maxlength="80"></label>
          <label for="lista">Productos <span class="small">(uno por línea)</span><textarea id="lista" name="lista" maxlength="1500" required></textarea><span class="err" id="e2" role="alert"></span></label>
          <button class="cta cta--orange" type="submit">Enviar pedido por WhatsApp</button>
        </form>
      </div>
    </div>
  </section>
</main>

<footer>
  <div class="container" style="padding-bottom:0">
    <div class="footer__top">
      <div><a class="logo" href="#inicio" aria-label="COMPANEX Bolivia, inicio" style="margin-bottom:14px"><img alt="COMPANEX" width="409" height="116" src="images/logo.png"></a><p>Distribuidora de productos de consumo masivo.<br>Cochabamba, Bolivia.</p></div>
      <nav aria-label="Pie de página"><ul><li><a href="#nuevos">Nuevos</a></li><li><a href="#catalogo">Catálogo</a></li><li><a href="#como">Cómo comprar</a></li><li><a href="#pedido">Mi pedido</a></li></ul></nav>
    </div>
    <p class="footer__copy">© <?= date('Y') ?> <?= e($cfg['empresa']) ?>. Precios referenciales sujetos a confirmación.</p>
  </div>
</footer>

<script>window.COMPANEX = <?= json_encode($datos, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;</script>
<script src="js/main.js" defer></script>
</body>
</html>
