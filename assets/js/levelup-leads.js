/* Level UP -> CRM do AprovUp | quatro landing pages | R1 */
(() => {
  'use strict';
  const config = window.LEVELUP_LEAD_CAPTURE || {};
  const source = location.pathname.split('/').filter(Boolean)[0];
  const allowed = new Set(['educacao', 'imobiliarias', 'consorcios', 'incorporadoras']);
  const form = document.querySelector('#booking-form');
  if (!form || !allowed.has(source)) return;

  const step2 = form.querySelector('[data-step="2"]');
  const step3 = form.querySelector('[data-step="3"]');
  const actions = step2?.querySelector('.booking-actions');
  if (!step2 || !step3 || !actions) return;

  const warn = document.createElement('p');
  warn.setAttribute('role', 'alert');
  warn.setAttribute('aria-live', 'polite');
  warn.style.cssText = 'color:#a12626;font-size:13px;line-height:1.45;margin:8px 0;display:none';
  actions.before(warn);
  function message(text) {
    warn.textContent = text;
    warn.style.display = text ? 'block' : 'none';
  }

  const consentLabel = document.createElement('label');
  consentLabel.style.cssText = 'display:flex;align-items:flex-start;gap:8px;font-size:13px;line-height:1.5;margin:14px 0;cursor:pointer';
  const consent = document.createElement('input');
  consent.type = 'checkbox';
  consent.required = true;
  consent.name = 'consentimento';
  consent.style.cssText = 'width:18px;height:18px;flex:none;margin-top:1px';
  const consentCopy = document.createElement('span');
  consentCopy.textContent = 'Autorizo o contato da Level UP sobre esta solicitação. Li a ';
  const privacy = document.createElement('a');
  privacy.href = '/privacidade.html';
  privacy.target = '_blank';
  privacy.rel = 'noopener noreferrer';
  privacy.textContent = 'Política de Privacidade';
  privacy.style.textDecoration = 'underline';
  consentCopy.append(privacy, '.');
  consentLabel.append(consent, consentCopy);
  actions.before(consentLabel);

  const trap = document.createElement('input');
  trap.type = 'text';
  trap.name = 'website_honeypot';
  trap.autocomplete = 'off';
  trap.tabIndex = -1;
  trap.setAttribute('aria-hidden', 'true');
  trap.style.cssText = 'position:absolute;left:-9999px;width:1px;height:1px;opacity:0';
  form.append(trap);

  const verification = document.createElement('div');
  verification.style.cssText = 'min-height:18px;margin:12px 0';
  actions.before(verification);
  let widgetId = null;
  let captchaToken = '';
  let loadingScript = null;
  function renderSecurity() {
    if (widgetId !== null || !config.turnstileSiteKey) return;
    if (window.turnstile) {
      widgetId = window.turnstile.render(verification, {
        sitekey: config.turnstileSiteKey,
        action: 'levelup_lp',
        theme: 'auto',
        callback: token => { captchaToken = token; message(''); },
        'expired-callback': () => { captchaToken = ''; },
        'error-callback': () => { captchaToken = ''; message('Não foi possível realizar a verificação. Recarregue a página.'); }
      });
      return;
    }
    if (loadingScript) return;
    const script = document.createElement('script');
    script.src = 'https://challenges.cloudflare.com/turnstile/v0/api.js?render=explicit';
    script.async = true;
    loadingScript = true;
    script.addEventListener('load', () => { loadingScript = false; renderSecurity(); });
    script.addEventListener('error', () => { loadingScript = false; message('Verificação temporariamente indisponível.'); });
    document.head.append(script);
  }
  document.querySelector('#booking-next')?.addEventListener('click', () => setTimeout(() => {
    if (!step2.hidden) renderSecurity();
  }, 0));

  document.addEventListener('submit', async event => {
    if (event.target !== form) return;
    // Captura antes dos handlers legados de demonstracao para nao exibir sucesso falso.
    event.preventDefault();
    event.stopImmediatePropagation();
    message('');
    const fields = Array.from(step2.querySelectorAll('input:not([type="checkbox"]), textarea'));
    if (!fields.every(field => field.reportValidity()) || !consent.reportValidity()) return;
    const day = form.querySelector('.booking-day[aria-pressed="true"]')?.dataset.date || '';
    const time = form.querySelector('.booking-time[aria-pressed="true"]')?.textContent.trim() || '';
    if (!day || !time) { message('Selecione um dia e um horário na etapa anterior.'); return; }
    if (!config.endpoint || !config.turnstileSiteKey) {
      message('Atendimento em configuração. Tente novamente em instantes.');
      return;
    }
    if (!captchaToken) { message('Conclua a verificação de segurança para enviar.'); renderSecurity(); return; }
    const fd = new FormData(form);
    const requestBody = {
      source,
      name: String(fd.get('nome') || fd.get('Nome completo') || '').trim(),
      company: String(fd.get('escola') || fd.get('imobiliaria') || fd.get('Empresa') || '').trim(),
      whatsapp: String(fd.get('whatsapp') || fd.get('WhatsApp') || '').trim(),
      email: String(fd.get('email') || fd.get('E-mail') || '').trim(),
      objective: String(fd.get('Objetivo') || '').trim(),
      date: day,
      time,
      consent: consent.checked,
      website_honeypot: trap.value,
      turnstileToken: captchaToken
    };
    const submit = step2.querySelector('button[type="submit"]');
    if (submit?.disabled) return;
    if (submit) submit.disabled = true;
    try {
      const response = await fetch(config.endpoint, {
        method: 'POST',
        mode: 'cors',
        credentials: 'omit',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(requestBody),
        signal: AbortSignal.timeout(15000)
      });
      const data = await response.json().catch(() => null);
      if (!response.ok || !data?.ok) throw new Error(data?.message || 'Não foi possível enviar a solicitação.');
      form.querySelectorAll('[data-step]').forEach(page => { page.hidden = page !== step3; });
      document.querySelectorAll('[data-progress]').forEach(p => p.classList.remove('is-current'));
      step3.querySelector('h3').textContent = 'Solicitação recebida!';
      const helper = step3.querySelector('.booking-help');
      if (helper) helper.textContent = 'Recebemos seus dados. Nossa equipe entrará em contato para confirmar a conversa. A data e o horário escolhidos são uma preferência, não uma reserva.';
      step3.scrollIntoView({ behavior: 'smooth', block: 'center' });
    } catch (error) {
      message(error instanceof Error ? error.message : 'Falha no envio. Tente novamente.');
      captchaToken = '';
      if (widgetId !== null && window.turnstile) window.turnstile.reset(widgetId);
    } finally {
      if (submit) submit.disabled = false;
    }
  }, true);
})();
