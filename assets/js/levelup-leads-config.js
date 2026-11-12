// Configuracao publica — APENAS endpoint e sitekey PUBLICA do Turnstile.
// NUNCA colocar service_role, CRM_SUPABASE_SECRET_KEY ou secret do Turnstile aqui.
window.LEVELUP_LEAD_CAPTURE = Object.freeze({
  endpoint: 'https://aprovup.com.br/api/levelup-leads',
  turnstileSiteKey: '0x4AAAAAAF9UAWEA_ocnWymq' // Preencher com a chave PUBLICA antes da publicacao das LPs
});
