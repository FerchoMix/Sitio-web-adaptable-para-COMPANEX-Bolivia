/* COMPANEX Bolivia – lógica del catálogo, pedido y menú */
var CFG=window.COMPANEX||{};
var WHATSAPP=CFG.whatsapp,PDF_URL=CFG.pdf,PRODUCTS=CFG.productos||[],COLORS=CFG.colores||{};
var cat="Todos",chosen=[];
function $(i){return document.getElementById(i)}
function esc(s){return String(s).replace(/[&<>"]/g,function(c){return{"&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;"}[c]})}
function card(p){
  var on=chosen.indexOf(p.n)>-1, l=esc(p.n.charAt(0));
  var visual=p.i
    ? '<img src="'+esc(p.i)+'" alt="" loading="lazy" data-l="'+l+'" onerror="this.replaceWith(this.dataset.l)" style="position:absolute;inset:0;width:100%;height:100%;object-fit:contain;background:#fff">'
    : l;
  return '<article class="card"><div class="thumb" style="background:'+COLORS[p.c]+'" aria-hidden="true">'+visual+(p.nuevo?'<span class="badge">Nuevo</span>':'')+
  '</div><div class="card__body"><span class="card__cat">'+esc(p.c)+'</span><h3>'+esc(p.n)+'</h3><span class="card__price">'+esc(p.p)+
  '</span><button type="button" class="add'+(on?' on':'')+'" data-n="'+esc(p.n)+'" aria-pressed="'+on+'">'+(on?'Agregado ✓':'Agregar')+'</button></div></article>';
}
function render(){
  var q=$("q").value.trim().toLowerCase();
  var l=PRODUCTS.filter(function(p){return(cat==="Todos"||p.c===cat)&&p.n.toLowerCase().indexOf(q)>-1});
  $("grid").innerHTML=l.length?l.map(card).join(""):'<div class="empty">No encontramos productos con esa búsqueda. Prueba otra palabra o elige “Todos”.</div>';
  $("result").textContent=l.length+(l.length===1?" producto":" productos");
  $("newGrid").innerHTML=PRODUCTS.filter(function(p){return p.nuevo}).map(card).join("");
}
function sync(){
  $("count").textContent=chosen.length;
  var manual=$("lista").value.split("\n").map(function(s){return s.trim()}).filter(function(l){
    return l&&!PRODUCTS.some(function(p){return"• "+p.n===l})});
  $("lista").value=chosen.map(function(n){return"• "+n}).concat(manual).join("\n");
}
document.addEventListener("error",function(e){
  var t=e.target;
  if(t.tagName==="IMG"&&t.parentNode.classList.contains("has-img")){t.parentNode.classList.remove("has-img");t.remove()}
},true);
function chips(){
  $("chips").innerHTML=["Todos"].concat(Object.keys(COLORS)).map(function(c){
    return'<button type="button" class="chip" aria-pressed="'+(c===cat)+'" data-c="'+esc(c)+'">'+esc(c)+'</button>'}).join("");
}
document.addEventListener("click",function(e){
  var b=e.target.closest("button[data-n]");
  if(b){var n=b.getAttribute("data-n"),i=chosen.indexOf(n);if(i>-1)chosen.splice(i,1);else chosen.push(n);render();sync();return}
  var c=e.target.closest("button[data-c]");
  if(c){cat=c.getAttribute("data-c");chips();render()}
});
$("q").addEventListener("input",render);

/* Menú móvil */
function menu(o){$("menu").classList.toggle("show",o);$("open").setAttribute("aria-expanded",o)}
$("open").addEventListener("click",function(){menu(true)});
$("close").addEventListener("click",function(){menu(false)});
$("menu").addEventListener("click",function(e){if(e.target.closest("a"))menu(false)});
document.addEventListener("keydown",function(e){if(e.key==="Escape")menu(false)});

/* Slider de opiniones */
var slides=[].slice.call(document.querySelectorAll(".testi__slide")),cur=0;
function go(d){slides[cur].classList.remove("show");cur=(cur+d+slides.length)%slides.length;slides[cur].classList.add("show")}
$("prev").addEventListener("click",function(){go(-1)});
$("next").addEventListener("click",function(){go(1)});

/* PDF */
$("pdfBtn").addEventListener("click",function(e){
  if(!PDF_URL){e.preventDefault();alert("El catálogo en PDF estará disponible pronto. Mientras tanto, usa la lista de arriba.")}else this.href=PDF_URL;
});

/* Pedido → WhatsApp */
$("form").addEventListener("submit",function(e){
  e.preventDefault();
  var nombre=$("nombre").value.trim(),lista=$("lista").value.trim();
  $("e1").textContent=nombre?"":"Escribe tu nombre para saber quién eres.";
  $("e2").textContent=lista?"":"Agrega al menos un producto a tu pedido.";
  if(!nombre||!lista)return;
  var neg=$("negocio").value.trim();
  var msg="Hola COMPANEX, soy "+nombre+(neg?" de "+neg:"")+". Quiero pedir:\n"+lista;
  window.open("https://wa.me/"+WHATSAPP+"?text="+encodeURIComponent(msg),"_blank","noopener");
  try{fetch("php/pedido.php",{method:"POST",body:new FormData($("form")),keepalive:true,headers:{"X-Requested-With":"fetch"}})}catch(x){}
});
chips();render();
