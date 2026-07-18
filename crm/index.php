<?php
declare(strict_types=1);

session_start([
    'cookie_httponly' => true,
    'cookie_secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
    'cookie_samesite' => 'Strict',
    'use_strict_mode' => true,
]);

const CRM_USERNAME = 'diego';
const CRM_PASSWORD_HASH = '$2y$12$QYfvOkWXrTgq9GVzyFm2jeEeLkLTdWid/56ngLMLbftDKnlNs9JYK';

$loginError = '';

if (isset($_GET['logout'])) {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }
    session_destroy();
    header('Location: ./');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'], $_POST['password'])) {
    $submittedUsername = trim((string) $_POST['username']);
    $submittedPassword = (string) $_POST['password'];

    if (
        hash_equals(CRM_USERNAME, $submittedUsername) &&
        password_verify($submittedPassword, CRM_PASSWORD_HASH)
    ) {
        session_regenerate_id(true);
        $_SESSION['crm_authenticated'] = true;
        $_SESSION['crm_login_time'] = time();
        header('Location: ./');
        exit;
    }

    usleep(350000);
    $loginError = 'Usuário ou senha incorretos.';
}

if (empty($_SESSION['crm_authenticated'])):
?>
<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="robots" content="noindex,nofollow">
  <title>Acesso ao CRM | Level Up</title>
  <style>
    *{box-sizing:border-box}
    body{margin:0;min-height:100vh;display:grid;place-items:center;padding:20px;background:radial-gradient(circle at top,#132849 0,#07101d 52%,#040a12 100%);font-family:Inter,Segoe UI,Arial,sans-serif;color:#f5f7fb}
    .login{width:min(420px,100%);background:rgba(13,24,40,.96);border:1px solid #22344c;border-radius:24px;padding:30px;box-shadow:0 25px 90px rgba(0,0,0,.48)}
    .mark{width:58px;height:58px;border-radius:18px;display:grid;place-items:center;margin-bottom:22px;background:linear-gradient(135deg,#367fff,#69a2ff);font-weight:900;font-size:18px;box-shadow:0 12px 35px rgba(54,127,255,.35)}
    h1{font-size:25px;margin:0 0 8px}p{color:#8fa2bb;line-height:1.5;margin:0 0 24px}
    label{display:block;color:#b9c8da;font-size:12px;font-weight:800;margin:14px 0 7px}
    input{width:100%;border:1px solid #22344c;background:#091421;color:#fff;border-radius:12px;padding:13px;outline:none;font:inherit}
    input:focus{border-color:#367fff;box-shadow:0 0 0 3px rgba(54,127,255,.14)}
    button{width:100%;margin-top:20px;border:0;border-radius:12px;background:#367fff;color:#fff;padding:14px;font:800 14px Inter,Segoe UI,Arial,sans-serif;cursor:pointer}
    .error{background:rgba(255,100,112,.1);border:1px solid rgba(255,100,112,.32);color:#ffadb4;padding:11px 12px;border-radius:11px;font-size:13px;margin-bottom:12px}
    small{display:block;text-align:center;color:#62768f;margin-top:18px}
  </style>
</head>
<body>
  <main class="login">
    <div class="mark">LU</div>
    <h1>CRM Comercial</h1>
    <p>Área interna da Level Up. Entre com suas credenciais para continuar.</p>
    <?php if ($loginError !== ''): ?>
      <div class="error"><?= htmlspecialchars($loginError, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>
    <form method="post" action="./" autocomplete="on">
      <label for="username">Usuário</label>
      <input id="username" name="username" type="text" autocomplete="username" required autofocus>
      <label for="password">Senha</label>
      <input id="password" name="password" type="password" autocomplete="current-password" required>
      <button type="submit">Entrar no CRM</button>
    </form>
    <small>Acesso restrito • Level Up Marketing</small>
  </main>
</body>
</html>
<?php
exit;
endif;
?>
<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="robots" content="noindex,nofollow">
  <title>CRM Comercial | Level Up</title>
  <style>
    :root{
      --bg:#07101d;--panel:#0d1828;--panel2:#111f32;--line:#22344c;
      --text:#f5f7fb;--muted:#8fa2bb;--blue:#367fff;--green:#29c987;
      --yellow:#ffbd4a;--red:#ff6470;--purple:#a985ff;--shadow:0 18px 60px rgba(0,0,0,.28)
    }
    *{box-sizing:border-box}
    body{margin:0;background:linear-gradient(145deg,#07101d 0%,#0a1422 48%,#07101d 100%);color:var(--text);font-family:Inter,Segoe UI,Arial,sans-serif;min-height:100vh}
    button,input,select,textarea{font:inherit}
    button{cursor:pointer}
    .app{max-width:1500px;margin:auto;padding:24px}
    .topbar{display:flex;justify-content:space-between;gap:20px;align-items:center;margin-bottom:24px}
    .brand{display:flex;align-items:center;gap:14px}
    .brand-mark{width:46px;height:46px;border-radius:15px;background:linear-gradient(135deg,var(--blue),#69a2ff);display:grid;place-items:center;font-weight:900;box-shadow:0 10px 30px rgba(54,127,255,.35)}
    .brand h1{font-size:20px;margin:0}.brand p{font-size:12px;color:var(--muted);margin:4px 0 0}
    .top-actions{display:flex;flex-wrap:wrap;gap:10px}
    .btn{border:1px solid var(--line);background:var(--panel2);color:var(--text);padding:11px 15px;border-radius:12px;font-weight:700;transition:.2s}
    .btn:hover{transform:translateY(-1px);border-color:#3b5372}
    .btn.primary{background:var(--blue);border-color:var(--blue)}
    .btn.danger{color:#ff9aa2}.btn.small{padding:8px 10px;font-size:12px}
    .stats{display:grid;grid-template-columns:repeat(6,1fr);gap:14px;margin-bottom:20px}
    .stat{background:linear-gradient(180deg,rgba(17,31,50,.94),rgba(13,24,40,.94));border:1px solid var(--line);border-radius:18px;padding:18px;box-shadow:var(--shadow)}
    .stat span{color:var(--muted);font-size:12px;text-transform:uppercase;letter-spacing:.08em}
    .stat strong{font-size:28px;display:block;margin-top:9px}.stat small{color:var(--muted)}
    .toolbar{display:grid;grid-template-columns:2fr 1fr 1fr 1fr auto;gap:12px;background:rgba(13,24,40,.94);border:1px solid var(--line);padding:14px;border-radius:18px;margin-bottom:20px}
    input,select,textarea{width:100%;border:1px solid var(--line);background:#091421;color:var(--text);border-radius:11px;padding:11px 12px;outline:none}
    input:focus,select:focus,textarea:focus{border-color:var(--blue);box-shadow:0 0 0 3px rgba(54,127,255,.12)}
    .pipeline{display:grid;grid-template-columns:repeat(7,minmax(245px,1fr));gap:14px;overflow-x:auto;padding-bottom:12px}
    .column{background:rgba(13,24,40,.85);border:1px solid var(--line);border-radius:18px;min-height:430px;padding:12px}
    .column-head{display:flex;justify-content:space-between;align-items:center;padding:6px 4px 13px}
    .column-head h2{font-size:13px;margin:0;text-transform:uppercase;letter-spacing:.06em}.count{background:#172a43;color:#bcd0e8;border-radius:999px;padding:3px 8px;font-size:11px}
    .cards{display:flex;flex-direction:column;gap:10px;min-height:340px}
    .card{background:var(--panel2);border:1px solid #263b57;border-radius:15px;padding:14px;transition:.2s}
    .card:hover{transform:translateY(-2px);border-color:#3d5b80}
    .card-top{display:flex;justify-content:space-between;gap:10px}.card h3{font-size:15px;margin:0}.priority{width:10px;height:10px;border-radius:50%;margin-top:4px;flex:0 0 auto}
    .priority.alta{background:var(--red)}.priority.media{background:var(--yellow)}.priority.baixa{background:var(--green)}
    .segment{display:inline-block;color:#b7c8dc;background:#172a43;border-radius:7px;padding:4px 7px;font-size:10px;margin-top:9px}
    .card p{font-size:12px;color:var(--muted);margin:9px 0;line-height:1.45}
    .money{color:#dcecff;font-size:13px;font-weight:800}.late{color:var(--red)!important}.today{color:var(--yellow)!important}
    .card-actions{display:flex;gap:7px;margin-top:12px}.card-actions button{flex:1;border:1px solid var(--line);background:#0a1625;color:#cbd8e8;border-radius:9px;padding:8px;font-size:11px}
    .empty{text-align:center;color:#61758e;font-size:12px;padding:30px 8px}
    dialog{width:min(760px,94vw);border:1px solid var(--line);border-radius:20px;background:var(--panel);color:var(--text);padding:0;box-shadow:0 30px 100px rgba(0,0,0,.6)}
    dialog::backdrop{background:rgba(1,7,14,.78);backdrop-filter:blur(5px)}
    .modal-head{display:flex;justify-content:space-between;align-items:center;padding:20px 22px;border-bottom:1px solid var(--line)}
    .modal-head h2{margin:0;font-size:19px}.close{border:0;background:transparent;color:var(--muted);font-size:24px}
    form{padding:20px 22px}.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px}
    label{display:block;color:#b9c8da;font-size:12px;font-weight:700;margin-bottom:7px}.full{grid-column:1/-1}
    textarea{min-height:95px;resize:vertical}.modal-actions{display:flex;justify-content:flex-end;gap:10px;margin-top:20px}
    .notice{background:rgba(255,189,74,.1);border:1px solid rgba(255,189,74,.28);color:#f4d792;padding:12px 14px;border-radius:12px;font-size:12px;margin-bottom:18px}
    .toast{position:fixed;right:22px;bottom:22px;background:#12243a;border:1px solid #315074;padding:13px 17px;border-radius:12px;box-shadow:var(--shadow);opacity:0;transform:translateY(15px);pointer-events:none;transition:.25s;z-index:10}
    .toast.show{opacity:1;transform:none}
    @media(max-width:1100px){.stats{grid-template-columns:repeat(3,1fr)}.toolbar{grid-template-columns:1fr 1fr}.toolbar input{grid-column:1/-1}}
    @media(max-width:650px){.app{padding:14px}.topbar{align-items:flex-start;flex-direction:column}.stats{grid-template-columns:1fr 1fr}.stat{padding:14px}.stat strong{font-size:23px}.toolbar{grid-template-columns:1fr}.toolbar input{grid-column:auto}.form-grid{grid-template-columns:1fr}.full{grid-column:auto}.pipeline{grid-template-columns:repeat(7,85vw)}}
  </style>
</head>
<body>
<div class="app">
  <header class="topbar">
    <div class="brand"><div class="brand-mark">LU</div><div><h1>CRM Comercial Level Up</h1><p>Operação R$ 50 mil • Dados salvos neste navegador</p></div></div>
    <div class="top-actions">
      <a class="btn danger" href="?logout=1" style="text-decoration:none">Sair</a>
      <button class="btn" id="exportBtn">Exportar backup</button>
      <button class="btn" id="importBtn">Importar</button>
      <input type="file" id="importFile" accept=".json" hidden>
      <button class="btn primary" id="newLeadBtn">+ Novo lead</button>
    </div>
  </header>

  <section class="stats">
    <article class="stat"><span>Total de leads</span><strong id="sTotal">0</strong><small>na base comercial</small></article>
    <article class="stat"><span>Contatos em atraso</span><strong id="sLate">0</strong><small>precisam de ação</small></article>
    <article class="stat"><span>Reuniões</span><strong id="sMeetings">0</strong><small>no funil</small></article>
    <article class="stat"><span>Propostas</span><strong id="sProposals">0</strong><small>em negociação</small></article>
    <article class="stat"><span>Potencial</span><strong id="sPotential">R$ 0</strong><small>pipeline aberto</small></article>
    <article class="stat"><span>Fechado</span><strong id="sWon">R$ 0</strong><small>receita conquistada</small></article>
  </section>

  <section class="toolbar">
    <input id="search" placeholder="Pesquisar empresa, decisor, segmento...">
    <select id="segmentFilter"><option value="">Todos os segmentos</option></select>
    <select id="priorityFilter"><option value="">Todas prioridades</option><option>Alta</option><option>Média</option><option>Baixa</option></select>
    <select id="followFilter"><option value="">Todos os contatos</option><option value="late">Em atraso</option><option value="today">Para hoje</option><option value="none">Sem data</option></select>
    <button class="btn" id="clearFilters">Limpar</button>
  </section>

  <main class="pipeline" id="pipeline"></main>
</div>

<dialog id="leadDialog">
  <div class="modal-head"><h2 id="modalTitle">Novo lead</h2><button class="close" type="button" id="closeDialog">×</button></div>
  <form id="leadForm">
    <div class="notice">Use esta versão apenas para dados comerciais básicos. O acesso ainda não possui autenticação segura e os registros ficam neste navegador.</div>
    <input type="hidden" id="leadId">
    <div class="form-grid">
      <div><label>Empresa *</label><input id="company" required></div>
      <div><label>Segmento</label><input id="segment" placeholder="Clínica, hotel, construtora..."></div>
      <div><label>Nome do decisor</label><input id="decisionMaker"></div>
      <div><label>Cargo</label><input id="role" placeholder="Sócio, diretora, gerente..."></div>
      <div><label>WhatsApp</label><input id="phone" placeholder="5582999999999"></div>
      <div><label>Instagram</label><input id="instagram" placeholder="@empresa"></div>
      <div><label>Site</label><input id="website" placeholder="https://..."></div>
      <div><label>Valor estimado mensal</label><input id="value" type="number" min="0" step="100" placeholder="2500"></div>
      <div><label>Etapa do funil</label><select id="stage"></select></div>
      <div><label>Prioridade</label><select id="priority"><option>Alta</option><option selected>Média</option><option>Baixa</option></select></div>
      <div><label>Último contato</label><input id="lastContact" type="date"></div>
      <div><label>Próximo contato</label><input id="nextContact" type="date"></div>
      <div class="full"><label>Observações e próximo passo</label><textarea id="notes" placeholder="Ex.: enviar diagnóstico na terça; Instagram parado; indicação de..."></textarea></div>
    </div>
    <div class="modal-actions">
      <button class="btn danger" type="button" id="deleteBtn" hidden>Excluir</button>
      <button class="btn" type="button" id="cancelBtn">Cancelar</button>
      <button class="btn primary" type="submit">Salvar lead</button>
    </div>
  </form>
</dialog>
<div class="toast" id="toast"></div>

<script>
const STAGES = ["Novo","Pesquisado","Primeiro contato","Conversando","Reunião marcada","Proposta enviada","Fechado"];
const KEY = "levelup_crm_secure_v1";
let leads = JSON.parse(localStorage.getItem(KEY) || "[]");

const $ = s => document.querySelector(s);
const money = n => Number(n||0).toLocaleString("pt-BR",{style:"currency",currency:"BRL",maximumFractionDigits:0});
const isoToday = () => new Date().toISOString().slice(0,10);
const norm = s => (s||"").normalize("NFD").replace(/[\u0300-\u036f]/g,"").toLowerCase();
const save = () => { localStorage.setItem(KEY,JSON.stringify(leads)); render(); };
const toast = msg => { $("#toast").textContent=msg;$("#toast").classList.add("show");setTimeout(()=>$("#toast").classList.remove("show"),2200); };

function seedStages(){
  $("#stage").innerHTML = STAGES.map(s=>`<option>${s}</option>`).join("");
  $("#pipeline").innerHTML = STAGES.map(s=>`<section class="column"><div class="column-head"><h2>${s}</h2><span class="count" data-count="${s}">0</span></div><div class="cards" data-stage="${s}"></div></section>`).join("");
}

function followClass(date){
  if(!date) return "";
  if(date < isoToday()) return "late";
  if(date === isoToday()) return "today";
  return "";
}

function filtered(){
  const q=norm($("#search").value), seg=$("#segmentFilter").value, pri=$("#priorityFilter").value, fol=$("#followFilter").value;
  return leads.filter(l=>{
    const text=norm([l.company,l.segment,l.decisionMaker,l.role,l.notes].join(" "));
    const follow = !l.nextContact ? "none" : l.nextContact < isoToday() ? "late" : l.nextContact === isoToday() ? "today" : "future";
    return (!q||text.includes(q)) && (!seg||l.segment===seg) && (!pri||l.priority===pri) && (!fol||follow===fol);
  });
}

function card(l){
  const fc=followClass(l.nextContact);
  return `<article class="card">
    <div class="card-top"><div><h3>${escapeHtml(l.company)}</h3>${l.segment?`<span class="segment">${escapeHtml(l.segment)}</span>`:""}</div><i class="priority ${norm(l.priority)}" title="${l.priority}"></i></div>
    <p>${l.decisionMaker?`<b>${escapeHtml(l.decisionMaker)}</b>${l.role?" • "+escapeHtml(l.role):""}`:"Decisor ainda não identificado"}</p>
    <div class="money">${money(l.value)}</div>
    <p class="${fc}">${l.nextContact ? `Próximo contato: ${new Date(l.nextContact+"T12:00:00").toLocaleDateString("pt-BR")}` : "Próximo contato não definido"}</p>
    <div class="card-actions">
      ${l.phone?`<button onclick="openWhats('${String(l.phone).replace(/\D/g,"")}')">WhatsApp</button>`:""}
      <button onclick="editLead('${l.id}')">Abrir</button>
    </div>
  </article>`;
}

function render(){
  document.querySelectorAll(".cards").forEach(x=>x.innerHTML="");
  const view=filtered();
  STAGES.forEach(s=>{
    const items=view.filter(l=>l.stage===s);
    document.querySelector(`[data-count="${s}"]`).textContent=items.length;
    document.querySelector(`[data-stage="${s}"]`).innerHTML=items.length?items.map(card).join(""):`<div class="empty">Nenhum lead</div>`;
  });
  const open=leads.filter(l=>l.stage!=="Fechado");
  $("#sTotal").textContent=leads.length;
  $("#sLate").textContent=leads.filter(l=>l.nextContact && l.nextContact<isoToday() && l.stage!=="Fechado").length;
  $("#sMeetings").textContent=leads.filter(l=>l.stage==="Reunião marcada").length;
  $("#sProposals").textContent=leads.filter(l=>l.stage==="Proposta enviada").length;
  $("#sPotential").textContent=money(open.reduce((a,l)=>a+Number(l.value||0),0));
  $("#sWon").textContent=money(leads.filter(l=>l.stage==="Fechado").reduce((a,l)=>a+Number(l.value||0),0));
  const segments=[...new Set(leads.map(l=>l.segment).filter(Boolean))].sort();
  const current=$("#segmentFilter").value;
  $("#segmentFilter").innerHTML='<option value="">Todos os segmentos</option>'+segments.map(s=>`<option>${escapeHtml(s)}</option>`).join("");
  $("#segmentFilter").value=current;
}

function escapeHtml(v){return String(v??"").replace(/[&<>"']/g,m=>({"&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#039;"}[m]));}
function openWhats(phone){window.open(`https://wa.me/${phone}`,"_blank");}

function openNew(){
  $("#leadForm").reset();$("#leadId").value="";$("#stage").value="Novo";$("#priority").value="Média";
  $("#deleteBtn").hidden=true;$("#modalTitle").textContent="Novo lead";$("#leadDialog").showModal();
}
window.editLead=id=>{
  const l=leads.find(x=>x.id===id); if(!l)return;
  Object.keys(l).forEach(k=>{const el=document.getElementById(k);if(el)el.value=l[k]??""});
  $("#leadId").value=l.id;$("#deleteBtn").hidden=false;$("#modalTitle").textContent=l.company;$("#leadDialog").showModal();
};

$("#leadForm").addEventListener("submit",e=>{
  e.preventDefault();
  const id=$("#leadId").value||crypto.randomUUID();
  const data={id,company:$("#company").value.trim(),segment:$("#segment").value.trim(),decisionMaker:$("#decisionMaker").value.trim(),role:$("#role").value.trim(),phone:$("#phone").value.trim(),instagram:$("#instagram").value.trim(),website:$("#website").value.trim(),value:Number($("#value").value||0),stage:$("#stage").value,priority:$("#priority").value,lastContact:$("#lastContact").value,nextContact:$("#nextContact").value,notes:$("#notes").value.trim(),updatedAt:new Date().toISOString()};
  const i=leads.findIndex(x=>x.id===id);if(i>=0)leads[i]=data;else leads.unshift(data);
  save();$("#leadDialog").close();toast("Lead salvo com sucesso");
});
$("#deleteBtn").onclick=()=>{const id=$("#leadId").value;if(confirm("Excluir este lead definitivamente?")){leads=leads.filter(x=>x.id!==id);save();$("#leadDialog").close();toast("Lead excluído");}};
$("#newLeadBtn").onclick=openNew;
$("#closeDialog").onclick=$("#cancelBtn").onclick=()=>$("#leadDialog").close();
["search","segmentFilter","priorityFilter","followFilter"].forEach(id=>document.getElementById(id).addEventListener(id==="search"?"input":"change",render));
$("#clearFilters").onclick=()=>{$("#search").value="";$("#segmentFilter").value="";$("#priorityFilter").value="";$("#followFilter").value="";render();};

$("#exportBtn").onclick=()=>{
  const blob=new Blob([JSON.stringify({exportedAt:new Date().toISOString(),leads},null,2)],{type:"application/json"});
  const a=document.createElement("a");a.href=URL.createObjectURL(blob);a.download=`levelup-crm-backup-${isoToday()}.json`;a.click();URL.revokeObjectURL(a.href);
};
$("#importBtn").onclick=()=>$("#importFile").click();
$("#importFile").onchange=async e=>{
  try{
    const parsed=JSON.parse(await e.target.files[0].text());
    const incoming=Array.isArray(parsed)?parsed:parsed.leads;
    if(!Array.isArray(incoming))throw new Error();
    if(confirm(`Importar ${incoming.length} leads? Isso substituirá a base atual.`)){leads=incoming;save();toast("Backup importado");}
  }catch{alert("Arquivo de backup inválido.");}
  e.target.value="";
};

seedStages();
render();
</script>
</body>
</html>