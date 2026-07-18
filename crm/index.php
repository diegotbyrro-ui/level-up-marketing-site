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
    setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
  }
  session_destroy();
  header('Location: ./');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'], $_POST['password'])) {
  $submittedUsername = trim((string) $_POST['username']);
  $submittedPassword = (string) $_POST['password'];

  if (hash_equals(CRM_USERNAME, $submittedUsername) && password_verify($submittedPassword, CRM_PASSWORD_HASH)) {
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
  --yellow:#ffbd4a;--red:#ff6470;--orange:#ff8a3d;--purple:#a985ff;
  --shadow:0 18px 60px rgba(0,0,0,.28)
}
*{box-sizing:border-box}
body{margin:0;background:linear-gradient(145deg,#07101d 0%,#0a1422 48%,#07101d 100%);color:var(--text);font-family:Inter,Segoe UI,Arial,sans-serif;min-height:100vh}
button,input,select,textarea{font:inherit}
button{cursor:pointer}
a{color:inherit}
.app{max-width:1600px;margin:auto;padding:24px}
.topbar{display:flex;justify-content:space-between;gap:20px;align-items:center;margin-bottom:24px}
.brand{display:flex;align-items:center;gap:14px}
.brand-mark{width:46px;height:46px;border-radius:15px;background:linear-gradient(135deg,var(--blue),#69a2ff);display:grid;place-items:center;font-weight:900;box-shadow:0 10px 30px rgba(54,127,255,.35)}
.brand h1{font-size:20px;margin:0}.brand p{font-size:12px;color:var(--muted);margin:4px 0 0}
.top-actions{display:flex;flex-wrap:wrap;gap:10px}
.btn{border:1px solid var(--line);background:var(--panel2);color:var(--text);padding:11px 15px;border-radius:12px;font-weight:700;transition:.2s;text-decoration:none}
.btn:hover{transform:translateY(-1px);border-color:#3b5372}
.btn.primary{background:var(--blue);border-color:var(--blue)}
.btn.danger{color:#ff9aa2}.btn.small{padding:8px 10px;font-size:12px}
.stats{display:grid;grid-template-columns:repeat(6,1fr);gap:14px;margin-bottom:20px}
.stat{background:linear-gradient(180deg,rgba(17,31,50,.94),rgba(13,24,40,.94));border:1px solid var(--line);border-radius:18px;padding:18px;box-shadow:var(--shadow)}
.stat span{color:var(--muted);font-size:12px;text-transform:uppercase;letter-spacing:.08em}
.stat strong{font-size:28px;display:block;margin-top:9px}.stat small{color:var(--muted)}
.toolbar{display:grid;grid-template-columns:2fr 1fr 1fr 1fr 1fr auto;gap:12px;background:rgba(13,24,40,.94);border:1px solid var(--line);padding:14px;border-radius:18px;margin-bottom:20px}
input,select,textarea{width:100%;border:1px solid var(--line);background:#091421;color:var(--text);border-radius:11px;padding:11px 12px;outline:none}
input:focus,select:focus,textarea:focus{border-color:var(--blue);box-shadow:0 0 0 3px rgba(54,127,255,.12)}
.pipeline{display:grid;grid-template-columns:repeat(7,minmax(260px,1fr));gap:14px;overflow:auto;padding:0 0 12px;max-height:calc(100vh - 350px);min-height:430px;scrollbar-gutter:stable;overscroll-behavior:contain;cursor:grab;user-select:none;position:relative}.pipeline.panning{cursor:grabbing;scroll-behavior:auto}.pipeline::-webkit-scrollbar{height:14px;width:12px}.pipeline::-webkit-scrollbar-track{background:#07101d;border-radius:999px}.pipeline::-webkit-scrollbar-thumb{background:#748399;border-radius:999px;border:3px solid #07101d}.pipeline::-webkit-scrollbar-thumb:hover{background:#9aa8ba}
.column{background:rgba(13,24,40,.85);border:1px solid var(--line);border-radius:18px;min-height:470px;padding:12px;transition:.2s;align-self:start}.column-head{position:sticky;top:0;z-index:3;background:rgba(13,24,40,.97);border-radius:12px}
.column.drag-over{border-color:var(--blue);box-shadow:0 0 0 3px rgba(54,127,255,.14) inset}
.column-head{display:flex;justify-content:space-between;align-items:center;padding:6px 4px 13px}
.column-head h2{font-size:13px;margin:0;text-transform:uppercase;letter-spacing:.06em}.count{background:#172a43;color:#bcd0e8;border-radius:999px;padding:3px 8px;font-size:11px}
.cards{display:flex;flex-direction:column;gap:10px;min-height:380px}
.card{background:var(--panel2);border:1px solid #263b57;border-radius:15px;padding:14px;transition:.2s;cursor:grab}
.card:active{cursor:grabbing}.card.dragging{opacity:.45;transform:rotate(1deg)}
.card:hover{transform:translateY(-2px);border-color:#3d5b80}
.card-top{display:flex;justify-content:space-between;gap:10px}.card h3{font-size:15px;margin:0}.priority{width:10px;height:10px;border-radius:50%;margin-top:4px;flex:0 0 auto}
.priority.alta{background:var(--red)}.priority.media{background:var(--yellow)}.priority.baixa{background:var(--green)}
.segment{display:inline-block;color:#b7c8dc;background:#172a43;border-radius:7px;padding:4px 7px;font-size:10px;margin-top:9px}
.temperature{font-size:10px;font-weight:800;border-radius:999px;padding:4px 7px;display:inline-block;margin-left:5px}
.temperature.frio{background:#16345a;color:#9ecbff}.temperature.morno{background:#4a3b13;color:#ffd878}.temperature.quente{background:#512810;color:#ffb182}.temperature.muito-quente{background:#4f1820;color:#ff9da6}
.card p{font-size:12px;color:var(--muted);margin:9px 0;line-height:1.45}
.money{color:#dcecff;font-size:13px;font-weight:800}.late{color:var(--red)!important}.today{color:var(--yellow)!important}
.next-action{background:#0a1625;border:1px solid #1d3149;padding:8px 9px;border-radius:9px;color:#d7e5f6!important}
.quick-links{display:flex;gap:6px;flex-wrap:wrap;margin-top:10px}.quick-links a{font-size:10px;text-decoration:none;border:1px solid var(--line);padding:6px 8px;border-radius:8px;color:#cbd8e8;background:#0a1625}
.card-actions{display:flex;gap:7px;margin-top:12px}.card-actions button{flex:1;border:1px solid var(--line);background:#0a1625;color:#cbd8e8;border-radius:9px;padding:8px;font-size:11px}
.empty{text-align:center;color:#61758e;font-size:12px;padding:30px 8px}
dialog{width:min(1060px,96vw);max-height:92vh;border:1px solid var(--line);border-radius:20px;background:var(--panel);color:var(--text);padding:0;box-shadow:0 30px 100px rgba(0,0,0,.6)}
dialog::backdrop{background:rgba(1,7,14,.78);backdrop-filter:blur(5px)}
.modal-head{position:sticky;top:0;z-index:2;background:var(--panel);display:flex;justify-content:space-between;align-items:center;padding:20px 22px;border-bottom:1px solid var(--line)}
.modal-head h2{margin:0;font-size:19px}.close{border:0;background:transparent;color:var(--muted);font-size:24px}
form{padding:22px}.form-section{border:1px solid var(--line);border-radius:16px;padding:16px;margin-bottom:16px;background:rgba(9,20,33,.45)}
.form-section h3{margin:0 0 14px;font-size:14px;color:#dcecff}.form-section p{margin:-7px 0 14px;font-size:11px;color:var(--muted)}
.form-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px}
.form-grid.two{grid-template-columns:repeat(2,1fr)}
.full{grid-column:1/-1}
label{display:block;color:#b9c8da;font-size:11px;font-weight:800;margin-bottom:7px}
textarea{min-height:105px;resize:vertical}
.notice{background:rgba(54,127,255,.09);border:1px solid rgba(54,127,255,.3);color:#bcd5ff;padding:11px 13px;border-radius:11px;margin-bottom:16px;font-size:12px}
.modal-actions{display:flex;justify-content:flex-end;gap:10px;padding-top:4px;position:sticky;bottom:0;background:linear-gradient(180deg,rgba(13,24,40,.5),var(--panel) 35%);padding-bottom:4px}
.history{display:flex;flex-direction:column;gap:8px;max-height:220px;overflow:auto}
.history-item{border-left:3px solid #315074;background:#0a1625;border-radius:8px;padding:9px 10px;font-size:12px}
.history-item small{color:var(--muted);display:block;margin-top:4px}
.toast{position:fixed;right:22px;bottom:22px;background:#12243a;border:1px solid #315074;padding:13px 17px;border-radius:12px;box-shadow:var(--shadow);opacity:0;transform:translateY(15px);pointer-events:none;transition:.25s;z-index:10}
.toast.show{opacity:1;transform:none}
@media(max-width:1200px){.stats{grid-template-columns:repeat(3,1fr)}.toolbar{grid-template-columns:1fr 1fr 1fr}.toolbar input{grid-column:1/-1}.form-grid{grid-template-columns:1fr 1fr}}
@media(max-width:680px){.app{padding:14px}.topbar{align-items:flex-start;flex-direction:column}.stats{grid-template-columns:1fr 1fr}.stat{padding:14px}.stat strong{font-size:23px}.toolbar{grid-template-columns:1fr}.toolbar input{grid-column:auto}.form-grid,.form-grid.two{grid-template-columns:1fr}.full{grid-column:auto}.pipeline{grid-template-columns:repeat(7,86vw)}dialog{width:96vw}form{padding:14px}.modal-head{padding:16px}}
</style>
</head>
<body>
<div class="app">
<header class="topbar">
  <div class="brand"><div class="brand-mark">LU</div><div><h1>CRM Comercial Level Up</h1><p>Operação R$ 50 mil • Dados salvos neste navegador</p></div></div>
  <div class="top-actions">
    <a class="btn danger" href="?logout=1">Sair</a>
    <button class="btn" id="exportBtn">Exportar backup</button>
    <button class="btn" id="importBtn">Importar</button>
    <button class="btn" id="assistantBtn">Assistente IA</button>
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
  <input id="search" placeholder="Pesquisar empresa, decisor, segmento, oportunidade...">
  <select id="segmentFilter"><option value="">Todos os segmentos</option></select>
  <select id="priorityFilter"><option value="">Todas prioridades</option><option>Alta</option><option>Média</option><option>Baixa</option></select>
  <select id="temperatureFilter"><option value="">Todas temperaturas</option><option>Frio</option><option>Morno</option><option>Quente</option><option>Muito quente</option></select>
  <select id="followFilter"><option value="">Todos os contatos</option><option value="late">Em atraso</option><option value="today">Para hoje</option><option value="none">Sem data</option></select>
  <button class="btn" id="clearFilters">Limpar</button>
</section>

<main class="pipeline" id="pipeline"></main>
</div>

<dialog id="leadDialog">
  <div class="modal-head"><h2 id="modalTitle">Novo lead</h2><button class="close" type="button" id="closeDialog">×</button></div>
  <form id="leadForm">
    <div class="notice">Os leads já cadastrados são preservados. Os novos campos podem ser preenchidos aos poucos.</div>
    <input type="hidden" id="leadId">

    <section class="form-section">
      <h3>Informações da empresa</h3>
      <div class="form-grid">
        <div><label>Empresa *</label><input id="company" required></div>
        <div><label>Segmento</label><input id="segment" placeholder="Clínica, hotel, construtora..."></div>
        <div><label>Cidade</label><input id="city" placeholder="Maceió - AL"></div>
        <div><label>WhatsApp da empresa</label><input id="phone" placeholder="5582999999999"></div>
        <div><label>Telefone alternativo</label><input id="altPhone"></div>
        <div><label>E-mail</label><input id="email" type="email"></div>
        <div><label>Instagram</label><input id="instagram" placeholder="@empresa ou URL"></div>
        <div><label>LinkedIn</label><input id="linkedin" placeholder="https://linkedin.com/..."></div>
        <div><label>Site</label><input id="website" placeholder="https://..."></div>
        <div class="full"><label>Google Maps</label><input id="maps" placeholder="Link do Google Maps"></div>
      </div>
    </section>

    <section class="form-section">
      <h3>Decisor</h3>
      <div class="form-grid">
        <div><label>Nome do decisor</label><input id="decisionMaker"></div>
        <div><label>Cargo</label><input id="role" placeholder="Sócio, diretora, gerente..."></div>
        <div><label>WhatsApp direto</label><input id="decisionPhone"></div>
        <div><label>E-mail do decisor</label><input id="decisionEmail" type="email"></div>
        <div><label>Instagram do decisor</label><input id="decisionInstagram"></div>
        <div><label>LinkedIn do decisor</label><input id="decisionLinkedin"></div>
      </div>
    </section>

    <section class="form-section">
      <h3>Marketing atual</h3>
      <div class="form-grid">
        <div><label>Estrutura atual</label><select id="marketingStructure"><option value="">Não identificado</option><option>Marketing interno</option><option>Agência</option><option>Freelancer</option><option>Sem marketing estruturado</option></select></div>
        <div><label>Agência ou responsável atual</label><input id="currentAgency"></div>
        <div><label>Investe em tráfego?</label><select id="paidMedia"><option value="">Não identificado</option><option>Sim</option><option>Não</option><option>Possivelmente</option></select></div>
        <div><label>Frequência de conteúdo</label><select id="contentFrequency"><option value="">Não identificado</option><option>Alta</option><option>Média</option><option>Baixa</option><option>Parado</option></select></div>
        <div><label>Qualidade percebida</label><select id="marketingQuality"><option value="">Não avaliado</option><option>Forte</option><option>Regular</option><option>Fraca</option></select></div>
        <div><label>Concorrente de referência</label><input id="benchmark"></div>
        <div class="full"><label>Pontos fortes do marketing</label><textarea id="strengths"></textarea></div>
        <div class="full"><label>Problemas encontrados</label><textarea id="weaknesses"></textarea></div>
        <div class="full"><label>Oportunidades para a Level Up</label><textarea id="opportunities"></textarea></div>
      </div>
    </section>

    <section class="form-section">
      <h3>Comercial</h3>
      <div class="form-grid">
        <div><label>Etapa do funil</label><select id="stage"></select></div>
        <div><label>Prioridade</label><select id="priority"><option>Alta</option><option selected>Média</option><option>Baixa</option></select></div>
        <div><label>Temperatura</label><select id="temperature"><option>Frio</option><option selected>Morno</option><option>Quente</option><option>Muito quente</option></select></div>
        <div><label>Origem</label><select id="source"><option value="">Não informado</option><option>Prospecção ativa</option><option>Indicação</option><option>Instagram</option><option>Site</option><option>Networking</option><option>Ex-cliente</option><option>Inbound</option></select></div>
        <div><label>Valor estimado mensal</label><input id="value" type="number" min="0" step="100" placeholder="2500"></div>
        <div><label>Responsável</label><input id="owner" value="Diego"></div>
        <div><label>Último contato</label><input id="lastContact" type="date"></div>
        <div><label>Próximo contato</label><input id="nextContact" type="date"></div>
        <div><label>Próxima ação</label><input id="nextAction" placeholder="Ligar, enviar diagnóstico, marcar reunião..."></div>
        <div class="full"><label>Observações gerais</label><textarea id="notes"></textarea></div>
      </div>
    </section>

    <section class="form-section">
      <h3>Histórico automático</h3>
      <div id="historyList" class="history"><div class="empty">O histórico aparecerá aqui.</div></div>
    </section>

    <div class="modal-actions">
      <button class="btn danger" type="button" id="deleteBtn" hidden>Excluir</button>
      <button class="btn" type="button" id="cancelBtn">Cancelar</button>
      <button class="btn primary" type="submit">Salvar lead</button>
    </div>
  </form>
</dialog>


<dialog id="importDialog">
  <div class="modal-head"><h2>Importar leads</h2><button class="close" type="button" id="closeImportDialog">×</button></div>
  <div style="padding:22px">
    <div class="notice">Escolha como o arquivo deve ser importado. A opção recomendada é adicionar e mesclar, pois mantém os leads que já estão no CRM.</div>
    <div class="form-section"><h3>Adicionar e mesclar</h3><p>Adiciona empresas novas, atualiza duplicadas quando houver mais informações e preserva toda a base atual.</p><button class="btn primary" id="mergeImportBtn" type="button">Adicionar sem apagar</button></div>
    <div class="form-section"><h3>Restaurar backup completo</h3><p>Substitui toda a base atual. Use apenas quando quiser restaurar um backup.</p><button class="btn danger" id="replaceImportBtn" type="button">Substituir toda a base</button></div>
  </div>
</dialog>

<dialog id="assistantDialog">
  <div class="modal-head"><h2>Assistente comercial da Level Up</h2><button class="close" type="button" id="closeAssistantDialog">×</button></div>
  <div style="padding:22px">
    <div class="notice">Esta área será conectada à API da OpenAI pelo servidor. Seus leads continuarão protegidos e a chave da API não ficará exposta no navegador.</div>
    <section class="form-section"><h3>Comando</h3><textarea id="assistantPrompt" placeholder="Ex.: Pesquise 10 clínicas odontológicas de Maceió com potencial para contratar a Level Up."></textarea><div style="display:flex;gap:10px;justify-content:flex-end;margin-top:12px"><button class="btn primary" id="runAssistantBtn" type="button">Pesquisar e preparar leads</button></div></section>
    <section class="form-section"><h3>Resultado</h3><div id="assistantResult" class="empty">O módulo está preparado. Para ativá-lo, configure a API da OpenAI no servidor.</div></section>
  </div>
</dialog>

<div class="toast" id="toast"></div>

<script>
const STAGES = ["Novo","Pesquisado","Primeiro contato","Conversando","Reunião marcada","Proposta enviada","Fechado"];
const KEY = "levelup_crm_secure_v1";

let leads = safeLoad();
let draggedId = null;

const $ = s => document.querySelector(s);
const money = n => Number(n||0).toLocaleString("pt-BR",{style:"currency",currency:"BRL",maximumFractionDigits:0});
const isoToday = () => new Date().toISOString().slice(0,10);
const norm = s => (s||"").normalize("NFD").replace(/[\u0300-\u036f]/g,"").toLowerCase();

function safeLoad(){
  try{
    const parsed = JSON.parse(localStorage.getItem(KEY) || "[]");
    return Array.isArray(parsed) ? parsed.map(migrateLead) : [];
  }catch(e){
    return [];
  }
}

function migrateLead(l){
  return {
    id: l.id || crypto.randomUUID(),
    company: l.company || "",
    segment: l.segment || "",
    city: l.city || "",
    decisionMaker: l.decisionMaker || "",
    role: l.role || "",
    phone: l.phone || "",
    altPhone: l.altPhone || "",
    email: l.email || "",
    instagram: l.instagram || "",
    linkedin: l.linkedin || "",
    website: l.website || "",
    maps: l.maps || "",
    decisionPhone: l.decisionPhone || "",
    decisionEmail: l.decisionEmail || "",
    decisionInstagram: l.decisionInstagram || "",
    decisionLinkedin: l.decisionLinkedin || "",
    marketingStructure: l.marketingStructure || "",
    currentAgency: l.currentAgency || "",
    paidMedia: l.paidMedia || "",
    contentFrequency: l.contentFrequency || "",
    marketingQuality: l.marketingQuality || "",
    benchmark: l.benchmark || "",
    strengths: l.strengths || "",
    weaknesses: l.weaknesses || "",
    opportunities: l.opportunities || "",
    value: Number(l.value || 0),
    stage: STAGES.includes(l.stage) ? l.stage : "Novo",
    priority: l.priority || "Média",
    temperature: l.temperature || (l.stage==="Conversando"||l.stage==="Reunião marcada"||l.stage==="Proposta enviada" ? "Quente" : "Morno"),
    source: l.source || "",
    owner: l.owner || "Diego",
    lastContact: l.lastContact || "",
    nextContact: l.nextContact || "",
    nextAction: l.nextAction || "",
    notes: l.notes || "",
    history: Array.isArray(l.history) ? l.history : [],
    createdAt: l.createdAt || l.updatedAt || new Date().toISOString(),
    updatedAt: l.updatedAt || new Date().toISOString()
  };
}

const save = () => {
  localStorage.setItem(KEY, JSON.stringify(leads));
  render();
};

const toast = msg => {
  $("#toast").textContent = msg;
  $("#toast").classList.add("show");
  setTimeout(()=>$("#toast").classList.remove("show"),2200);
};

function seedStages(){
  $("#stage").innerHTML = STAGES.map(s=>`<option>${s}</option>`).join("");
  $("#pipeline").innerHTML = STAGES.map(s=>`
    <section class="column" data-column="${escapeHtml(s)}">
      <div class="column-head"><h2>${escapeHtml(s)}</h2><span class="count" data-count="${escapeHtml(s)}">0</span></div>
      <div class="cards" data-stage="${escapeHtml(s)}"></div>
    </section>`).join("");

  document.querySelectorAll(".cards").forEach(zone=>{
    zone.addEventListener("dragover", e=>{e.preventDefault();zone.closest(".column").classList.add("drag-over")});
    zone.addEventListener("dragleave", ()=>zone.closest(".column").classList.remove("drag-over"));
    zone.addEventListener("drop", e=>{
      e.preventDefault();
      zone.closest(".column").classList.remove("drag-over");
      const newStage = zone.dataset.stage;
      moveLead(draggedId,newStage);
    });
  });
}

function moveLead(id,newStage){
  const lead = leads.find(x=>x.id===id);
  if(!lead || lead.stage===newStage) return;
  const oldStage = lead.stage;
  lead.stage = newStage;
  lead.updatedAt = new Date().toISOString();
  addHistory(lead,`Etapa alterada de “${oldStage}” para “${newStage}”`);
  save();
  toast(`Lead movido para ${newStage}`);
}

function addHistory(lead,text){
  lead.history = Array.isArray(lead.history) ? lead.history : [];
  lead.history.unshift({date:new Date().toISOString(),text});
}

function followClass(date){
  if(!date) return "";
  if(date < isoToday()) return "late";
  if(date === isoToday()) return "today";
  return "";
}

function filtered(){
  const q=norm($("#search").value), seg=$("#segmentFilter").value, pri=$("#priorityFilter").value, temp=$("#temperatureFilter").value, fol=$("#followFilter").value;
  return leads.filter(l=>{
    const text=norm([l.company,l.segment,l.city,l.decisionMaker,l.role,l.notes,l.nextAction,l.opportunities,l.currentAgency].join(" "));
    const follow = !l.nextContact ? "none" : l.nextContact < isoToday() ? "late" : l.nextContact === isoToday() ? "today" : "future";
    return (!q||text.includes(q)) && (!seg||l.segment===seg) && (!pri||l.priority===pri) && (!temp||l.temperature===temp) && (!fol||follow===fol);
  });
}

function safeUrl(v,type="site"){
  if(!v) return "";
  let url = String(v).trim();
  if(type==="instagram" && url.startsWith("@")) url = "https://instagram.com/"+url.slice(1);
  if(!/^https?:\/\//i.test(url) && type==="site") url = "https://"+url;
  return url;
}

function card(l){
  const fc=followClass(l.nextContact);
  const tempClass=norm(l.temperature).replace(/\s+/g,"-");
  const phone=String(l.decisionPhone||l.phone||"").replace(/\D/g,"");
  return `<article class="card" draggable="true" data-id="${escapeHtml(l.id)}">
    <div class="card-top">
      <div>
        <h3>${escapeHtml(l.company)}</h3>
        ${l.segment?`<span class="segment">${escapeHtml(l.segment)}</span>`:""}
        ${l.temperature?`<span class="temperature ${tempClass}">${escapeHtml(l.temperature)}</span>`:""}
      </div>
      <i class="priority ${norm(l.priority)}" title="${escapeHtml(l.priority)}"></i>
    </div>
    <p>${l.decisionMaker?`<b>${escapeHtml(l.decisionMaker)}</b>${l.role?" • "+escapeHtml(l.role):""}`:"Decisor ainda não identificado"}</p>
    <div class="money">${money(l.value)}</div>
    ${l.nextAction?`<p class="next-action"><b>Próxima ação:</b> ${escapeHtml(l.nextAction)}</p>`:""}
    <p class="${fc}">${l.nextContact ? `Próximo contato: ${new Date(l.nextContact+"T12:00:00").toLocaleDateString("pt-BR")}` : "Próximo contato não definido"}</p>
    <div class="quick-links">
      ${phone?`<a href="https://wa.me/${phone}" target="_blank" rel="noopener">WhatsApp</a>`:""}
      ${l.instagram?`<a href="${escapeHtml(safeUrl(l.instagram,"instagram"))}" target="_blank" rel="noopener">Instagram</a>`:""}
      ${l.website?`<a href="${escapeHtml(safeUrl(l.website))}" target="_blank" rel="noopener">Site</a>`:""}
      ${l.maps?`<a href="${escapeHtml(safeUrl(l.maps))}" target="_blank" rel="noopener">Maps</a>`:""}
    </div>
    <div class="card-actions"><button onclick="editLead('${escapeHtml(l.id)}')">Abrir ficha</button></div>
  </article>`;
}

function bindCards(){
  document.querySelectorAll(".card").forEach(el=>{
    el.addEventListener("dragstart",e=>{
      draggedId=el.dataset.id;
      el.classList.add("dragging");
      if(e.dataTransfer){
        e.dataTransfer.effectAllowed="move";
        e.dataTransfer.setData("text/plain",draggedId);
      }
    });
    el.addEventListener("dragend",()=>{
      draggedId=null;
      el.classList.remove("dragging");
      document.querySelectorAll(".column").forEach(c=>c.classList.remove("drag-over"));
    });
  });
}

function enablePipelineNavigation(){
  const pipeline=$("#pipeline");
  let panning=false,startX=0,startY=0,startLeft=0,startTop=0;

  pipeline.addEventListener("mousedown",e=>{
    if(e.button!==0) return;
    if(e.target.closest(".card,button,a,input,select,textarea")) return;
    panning=true;
    startX=e.clientX; startY=e.clientY;
    startLeft=pipeline.scrollLeft; startTop=pipeline.scrollTop;
    pipeline.classList.add("panning");
    e.preventDefault();
  });

  window.addEventListener("mousemove",e=>{
    if(!panning) return;
    pipeline.scrollLeft=startLeft-(e.clientX-startX);
    pipeline.scrollTop=startTop-(e.clientY-startY);
  });

  window.addEventListener("mouseup",()=>{
    panning=false;
    pipeline.classList.remove("panning");
  });

  pipeline.addEventListener("wheel",e=>{
    if(Math.abs(e.deltaY)>Math.abs(e.deltaX) && !e.shiftKey){
      if(pipeline.scrollWidth>pipeline.clientWidth){
        pipeline.scrollLeft+=e.deltaY;
        e.preventDefault();
      }
    }
  },{passive:false});

  pipeline.addEventListener("dragover",e=>{
    if(!draggedId) return;
    const rect=pipeline.getBoundingClientRect();
    const edge=85;
    const speed=18;
    if(e.clientX<rect.left+edge) pipeline.scrollLeft-=speed;
    else if(e.clientX>rect.right-edge) pipeline.scrollLeft+=speed;
    if(e.clientY<rect.top+edge) pipeline.scrollTop-=speed;
    else if(e.clientY>rect.bottom-edge) pipeline.scrollTop+=speed;
  });
}

function render(){
  document.querySelectorAll(".cards").forEach(x=>x.innerHTML="");
  const view=filtered();

  STAGES.forEach(s=>{
    const items=view.filter(l=>l.stage===s);
    document.querySelector(`[data-count="${CSS.escape(s)}"]`).textContent=items.length;
    document.querySelector(`[data-stage="${CSS.escape(s)}"]`).innerHTML=items.length?items.map(card).join(""):`<div class="empty">Nenhum lead</div>`;
  });

  bindCards();

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

function escapeHtml(v){
  return String(v??"").replace(/[&<>"']/g,m=>({"&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#039;"}[m]));
}

function fieldIds(){
  return ["company","segment","city","decisionMaker","role","phone","altPhone","email","instagram","linkedin","website","maps","decisionPhone","decisionEmail","decisionInstagram","decisionLinkedin","marketingStructure","currentAgency","paidMedia","contentFrequency","marketingQuality","benchmark","strengths","weaknesses","opportunities","value","stage","priority","temperature","source","owner","lastContact","nextContact","nextAction","notes"];
}

function openNew(){
  $("#leadForm").reset();
  $("#leadId").value="";
  $("#stage").value="Novo";
  $("#priority").value="Média";
  $("#temperature").value="Morno";
  $("#owner").value="Diego";
  $("#deleteBtn").hidden=true;
  $("#modalTitle").textContent="Novo lead";
  $("#historyList").innerHTML='<div class="empty">O histórico aparecerá aqui.</div>';
  $("#leadDialog").showModal();
}

function renderHistory(history){
  $("#historyList").innerHTML = history && history.length
    ? history.map(h=>`<div class="history-item">${escapeHtml(h.text)}<small>${new Date(h.date).toLocaleString("pt-BR")}</small></div>`).join("")
    : '<div class="empty">Nenhuma movimentação registrada.</div>';
}

window.editLead=id=>{
  const l=leads.find(x=>x.id===id);
  if(!l)return;
  fieldIds().forEach(k=>{const el=document.getElementById(k);if(el)el.value=l[k]??""});
  $("#leadId").value=l.id;
  $("#deleteBtn").hidden=false;
  $("#modalTitle").textContent=l.company;
  renderHistory(l.history);
  $("#leadDialog").showModal();
};

$("#leadForm").addEventListener("submit",e=>{
  e.preventDefault();
  const id=$("#leadId").value||crypto.randomUUID();
  const old=leads.find(x=>x.id===id);
  const data=migrateLead({
    ...(old||{}),
    id,
    company:$("#company").value.trim(),
    segment:$("#segment").value.trim(),
    city:$("#city").value.trim(),
    decisionMaker:$("#decisionMaker").value.trim(),
    role:$("#role").value.trim(),
    phone:$("#phone").value.trim(),
    altPhone:$("#altPhone").value.trim(),
    email:$("#email").value.trim(),
    instagram:$("#instagram").value.trim(),
    linkedin:$("#linkedin").value.trim(),
    website:$("#website").value.trim(),
    maps:$("#maps").value.trim(),
    decisionPhone:$("#decisionPhone").value.trim(),
    decisionEmail:$("#decisionEmail").value.trim(),
    decisionInstagram:$("#decisionInstagram").value.trim(),
    decisionLinkedin:$("#decisionLinkedin").value.trim(),
    marketingStructure:$("#marketingStructure").value,
    currentAgency:$("#currentAgency").value.trim(),
    paidMedia:$("#paidMedia").value,
    contentFrequency:$("#contentFrequency").value,
    marketingQuality:$("#marketingQuality").value,
    benchmark:$("#benchmark").value.trim(),
    strengths:$("#strengths").value.trim(),
    weaknesses:$("#weaknesses").value.trim(),
    opportunities:$("#opportunities").value.trim(),
    value:Number($("#value").value||0),
    stage:$("#stage").value,
    priority:$("#priority").value,
    temperature:$("#temperature").value,
    source:$("#source").value,
    owner:$("#owner").value.trim(),
    lastContact:$("#lastContact").value,
    nextContact:$("#nextContact").value,
    nextAction:$("#nextAction").value.trim(),
    notes:$("#notes").value.trim(),
    updatedAt:new Date().toISOString()
  });

  if(old){
    data.history = [...(old.history||[])];
    if(old.stage!==data.stage) addHistory(data,`Etapa alterada de “${old.stage}” para “${data.stage}”`);
    if(old.nextContact!==data.nextContact && data.nextContact) addHistory(data,`Próximo contato definido para ${new Date(data.nextContact+"T12:00:00").toLocaleDateString("pt-BR")}`);
    addHistory(data,"Ficha atualizada");
    leads[leads.findIndex(x=>x.id===id)] = data;
  }else{
    data.history=[];
    addHistory(data,"Lead criado no CRM");
    leads.unshift(data);
  }

  save();
  $("#leadDialog").close();
  toast("Lead salvo com sucesso");
});

$("#deleteBtn").onclick=()=>{
  const id=$("#leadId").value;
  if(confirm("Excluir este lead definitivamente?")){
    leads=leads.filter(x=>x.id!==id);
    save();
    $("#leadDialog").close();
    toast("Lead excluído");
  }
};

$("#newLeadBtn").onclick=openNew;
$("#closeDialog").onclick=$("#cancelBtn").onclick=()=>$("#leadDialog").close();

["search","segmentFilter","priorityFilter","temperatureFilter","followFilter"].forEach(id=>{
  document.getElementById(id).addEventListener(id==="search"?"input":"change",render);
});

$("#clearFilters").onclick=()=>{
  $("#search").value="";
  $("#segmentFilter").value="";
  $("#priorityFilter").value="";
  $("#temperatureFilter").value="";
  $("#followFilter").value="";
  render();
};

$("#exportBtn").onclick=()=>{
  const blob=new Blob([JSON.stringify({version:2,exportedAt:new Date().toISOString(),leads},null,2)],{type:"application/json"});
  const a=document.createElement("a");
  a.href=URL.createObjectURL(blob);
  a.download=`levelup-crm-backup-${isoToday()}.json`;
  a.click();
  URL.revokeObjectURL(a.href);
};

let pendingImportMode = "merge";

$("#importBtn").onclick=()=>$("#importDialog").showModal();
$("#closeImportDialog").onclick=()=>$("#importDialog").close();
$("#mergeImportBtn").onclick=()=>{pendingImportMode="merge";$("#importDialog").close();$("#importFile").click();};
$("#replaceImportBtn").onclick=()=>{pendingImportMode="replace";$("#importDialog").close();$("#importFile").click();};

function leadIdentity(l){
  const company=norm(l.company).replace(/[^a-z0-9]/g,"");
  const phone=String(l.phone||l.decisionPhone||"").replace(/\D/g,"");
  const instagram=norm(l.instagram).replace(/^https?:\/\/(www\.)?instagram\.com\//,"").replace(/^@/,"").replace(/[\/?#].*$/,"");
  return {company,phone,instagram};
}
function findDuplicateIndex(candidate){
  const c=leadIdentity(candidate);
  return leads.findIndex(existing=>{const e=leadIdentity(existing);if(c.phone&&e.phone&&c.phone===e.phone)return true;if(c.instagram&&e.instagram&&c.instagram===e.instagram)return true;return Boolean(c.company&&e.company&&c.company===e.company);});
}
function mergeLead(existing,incoming){
  const merged={...existing};
  Object.keys(incoming).forEach(key=>{const value=incoming[key];const empty=value===""||value===null||value===undefined||(Array.isArray(value)&&value.length===0)||(typeof value==="number"&&value===0);if(!empty)merged[key]=value;});
  merged.id=existing.id;merged.createdAt=existing.createdAt;merged.history=[...(existing.history||[])];addHistory(merged,"Dados complementados por importação");merged.updatedAt=new Date().toISOString();return migrateLead(merged);
}

$("#importFile").onchange=async e=>{
  try{
    const parsed=JSON.parse(await e.target.files[0].text());
    const incomingRaw=Array.isArray(parsed)?parsed:parsed.leads;
    if(!Array.isArray(incomingRaw))throw new Error();
    const incoming=incomingRaw.map(migrateLead);
    if(pendingImportMode==="replace"){if(!confirm(`Substituir toda a base atual por ${incoming.length} leads?`))return;leads=incoming;save();toast("Base restaurada com sucesso");return;}
    let added=0,updated=0;
    incoming.forEach(candidate=>{const index=findDuplicateIndex(candidate);if(index>=0){leads[index]=mergeLead(leads[index],candidate);updated++;}else{addHistory(candidate,"Lead adicionado por importação");leads.unshift(candidate);added++;}});
    save();toast(`${added} novos leads e ${updated} atualizados`);
  }catch(err){alert("Arquivo inválido. Use um JSON compatível com o CRM.");}
  finally{e.target.value="";}
};

$("#assistantBtn").onclick=()=>$("#assistantDialog").showModal();
$("#closeAssistantDialog").onclick=()=>$("#assistantDialog").close();
$("#runAssistantBtn").onclick=async()=>{
  const prompt=$("#assistantPrompt").value.trim();if(!prompt){alert("Digite o que você deseja pesquisar.");return;}
  const result=$("#assistantResult");result.className="";result.innerHTML="<p>Verificando a conexão segura com o assistente...</p>";
  try{const response=await fetch("./ai.php",{method:"POST",headers:{"Content-Type":"application/json"},body:JSON.stringify({prompt})});const data=await response.json();if(!response.ok||!data.ok)throw new Error(data.error||"Não foi possível executar a pesquisa.");result.innerHTML=`<div class="notice">${escapeHtml(data.message||"Pesquisa concluída.")}</div>`;}
  catch(err){result.innerHTML=`<div class="error">${escapeHtml(err.message)}</div><p style="color:var(--muted)">A estrutura já está instalada. Falta configurar a chave da API da OpenAI no servidor.</p>`;}
};

seedStages();
enablePipelineNavigation();
save();
</script>
</body>
</html>
