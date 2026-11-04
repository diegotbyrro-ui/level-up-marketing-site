(()=>{const b=document.querySelector('.menu-toggle'),n=document.querySelector('.nav-links');b?.addEventListener('click',()=>{let open=n.classList.toggle('open');b.setAttribute('aria-expanded',open);b.textContent=open?'×':'☰'});document.querySelectorAll('.nav-links a').forEach(a=>a.addEventListener('click',()=>n.classList.remove('open')));const d=document.querySelector('input[name=data]');if(d){const x=new Date();d.min=new Date(x.getTime()-x.getTimezoneOffset()*60000).toISOString().slice(0,10)}document.querySelector('#lead-form')?.addEventListener('submit',e=>{e.preventDefault();const f=e.currentTarget; if(!f.reportValidity())return; const t=f.querySelector('.form-feedback');t.textContent='Formulário demonstrativo. Antes da publicação, conecte um destino de atendimento (WhatsApp, CRM ou e-mail) para receber as solicitações.';t.style.color='#a35d04'})})();

(()=>{
 const form=document.querySelector('#booking-form'); if(!form)return;
 const daysEl=document.querySelector('#booking-days'),timesEl=document.querySelector('#booking-times');
 const pages=[...form.querySelectorAll('[data-step]')], progress=[...document.querySelectorAll('[data-progress]')];
 const choices={date:null,time:null};
 const weekday=['DOM','SEG','TER','QUA','QUI','SEX','SÁB'];
 const fmt=(d)=>`${String(d.getDate()).padStart(2,'0')}/${String(d.getMonth()+1).padStart(2,'0')}/${d.getFullYear()}`;
 const show=(step)=>{pages.forEach(x=>x.hidden=Number(x.dataset.step)!==step);progress.forEach(x=>x.classList.toggle('is-current',Number(x.dataset.progress)===step));};
 const today=new Date();today.setHours(12,0,0,0);let found=0,offset=1;
 while(found<10&&offset<40){const d=new Date(today);d.setDate(d.getDate()+offset++);if(d.getDay()===0||d.getDay()===6)continue;
  const label=fmt(d),btn=document.createElement('button');btn.type='button';btn.className='booking-day';btn.setAttribute('aria-pressed','false');btn.dataset.date=label;btn.setAttribute('aria-label',`${weekday[d.getDay()]}, ${label}`);
  btn.innerHTML=`<small>${weekday[d.getDay()]}</small><strong>${d.getDate()}</strong><small>${['JAN','FEV','MAR','ABR','MAI','JUN','JUL','AGO','SET','OUT','NOV','DEZ'][d.getMonth()]}</small>`;
  btn.addEventListener('click',()=>{choices.date=label;daysEl.querySelectorAll('button').forEach(x=>x.setAttribute('aria-pressed',String(x===btn)));document.querySelector('#booking-step-error').hidden=true;});daysEl.append(btn);found++;
 }
 ['09:00','10:30','14:00','15:30','17:00'].forEach(t=>{let btn=document.createElement('button');btn.type='button';btn.className='booking-time';btn.textContent=t;btn.setAttribute('aria-pressed','false');btn.addEventListener('click',()=>{choices.time=t;timesEl.querySelectorAll('button').forEach(x=>x.setAttribute('aria-pressed',String(x===btn)));document.querySelector('#booking-step-error').hidden=true});timesEl.append(btn)});
 document.querySelector('#booking-next').addEventListener('click',()=>{if(!choices.date||!choices.time){document.querySelector('#booking-step-error').hidden=false;return}document.querySelector('#booking-choice-summary').textContent=`Preferência selecionada: ${choices.date}, às ${choices.time} (Brasília)`;show(2);form.scrollIntoView({behavior:'smooth',block:'center'})});
 document.querySelector('#booking-back').addEventListener('click',()=>show(1));
 form.addEventListener('submit',e=>{e.preventDefault();const inputs=[...form.querySelectorAll('[data-step="2"] input')];if(!inputs.every(i=>i.reportValidity()))return;show(3)});
 document.querySelector('#booking-restart').addEventListener('click',()=>{form.reset();choices.date=null;choices.time=null;form.querySelectorAll('[aria-pressed]').forEach(x=>x.setAttribute('aria-pressed','false'));show(1)});
})();
