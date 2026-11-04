// Número no formato DDI + DDD + número, sem espaços. Manter vazio até configurar o atendimento.
window.LEVELUP_WHATSAPP_NUMBER = '';
document.querySelector('.mobile-menu')?.addEventListener('click',()=>{const m=document.querySelector('.navlinks');const o=m.classList.toggle('open');document.querySelector('.mobile-menu').setAttribute('aria-expanded',String(o));});
document.querySelectorAll('.navlinks a').forEach(a=>a.addEventListener('click',()=>{document.querySelector('.navlinks').classList.remove('open');document.querySelector('.mobile-menu').setAttribute('aria-expanded','false')}));

(()=>{
 const form=document.querySelector('#booking-form');if(!form)return;
 const days=document.querySelector('#booking-days'),times=document.querySelector('#booking-times');
 const pages=[...form.querySelectorAll('[data-step]')],progress=[...document.querySelectorAll('[data-progress]')];
 const state={date:null,time:null};const weekdays=['DOM','SEG','TER','QUA','QUI','SEX','SÁB'];
 const months=['JAN','FEV','MAR','ABR','MAI','JUN','JUL','AGO','SET','OUT','NOV','DEZ'];
 const fmt=d=>`${String(d.getDate()).padStart(2,'0')}/${String(d.getMonth()+1).padStart(2,'0')}/${d.getFullYear()}`;
 const show=step=>{pages.forEach(p=>p.hidden=Number(p.dataset.step)!==step);progress.forEach(p=>p.classList.toggle('is-current',Number(p.dataset.progress)===step));};
 let found=0,offset=1;const today=new Date();today.setHours(12,0,0,0);
 while(found<10&&offset<45){const d=new Date(today);d.setDate(d.getDate()+offset++);if(d.getDay()===0||d.getDay()===6)continue;
  const label=fmt(d),b=document.createElement('button');b.type='button';b.className='booking-day';b.dataset.date=label;b.setAttribute('aria-pressed','false');b.setAttribute('aria-label',`${weekdays[d.getDay()]} ${label}`);
  b.innerHTML=`<small>${weekdays[d.getDay()]}</small><strong>${d.getDate()}</strong><small>${months[d.getMonth()]}</small>`;
  b.addEventListener('click',()=>{state.date=label;days.querySelectorAll('button').forEach(x=>x.setAttribute('aria-pressed',String(x===b)));document.querySelector('#booking-step-error').hidden=true});days.append(b);found++}
 ['09:00','10:30','14:00','15:30','17:00'].forEach(t=>{const b=document.createElement('button');b.type='button';b.className='booking-time';b.textContent=t;b.setAttribute('aria-pressed','false');b.addEventListener('click',()=>{state.time=t;times.querySelectorAll('button').forEach(x=>x.setAttribute('aria-pressed',String(x===b)));document.querySelector('#booking-step-error').hidden=true});times.append(b)});
 document.querySelector('#booking-next').addEventListener('click',()=>{if(!state.date||!state.time){document.querySelector('#booking-step-error').hidden=false;return}document.querySelector('#booking-choice-summary').textContent=`Preferência: ${state.date}, às ${state.time} (Brasília)`;show(2);form.scrollIntoView({behavior:'smooth',block:'center'})});
 document.querySelector('#booking-back').addEventListener('click',()=>show(1));
 form.addEventListener('submit',e=>{e.preventDefault();const inputs=[...form.querySelectorAll('[data-step="2"] input')];if(!inputs.every(i=>i.reportValidity()))return;
   const values=Object.fromEntries(new FormData(form).entries());
   const n=(window.LEVELUP_WHATSAPP_NUMBER||'').replace(/\D/g,'');
   if(n){const lines=['Olá, Level UP! Gostaria de conversar sobre '+(form.dataset.sector||'minha empresa')+'.','',`Data preferida: ${state.date}`,`Horário preferido: ${state.time} (Brasília)`, ...Object.entries(values).map(([k,v])=>`${k}: ${v}`),'','Data e horário sujeitos à confirmação.'];
       const url='https://wa.me/'+n+'?text='+encodeURIComponent(lines.join('\n'));window.open(url,'_blank','noopener');
       document.querySelector('#booking-finish-title').textContent='Solicitação preparada no WhatsApp.';
       document.querySelector('#booking-finish-message').textContent='Confira os dados no WhatsApp e envie a mensagem. A reunião só estará marcada depois da confirmação da equipe.';
   }else{
       document.querySelector('#booking-finish-title').textContent='Dados preparados.';
       document.querySelector('#booking-finish-message').textContent='Esta é uma demonstração. Nenhuma solicitação foi enviada. Antes de publicar, conecte este formulário ao WhatsApp ou CRM da Level UP.';
   }
   show(3);
 });
 document.querySelector('#booking-restart').addEventListener('click',()=>{form.reset();state.date=null;state.time=null;form.querySelectorAll('[aria-pressed]').forEach(x=>x.setAttribute('aria-pressed','false'));show(1)});
})();
