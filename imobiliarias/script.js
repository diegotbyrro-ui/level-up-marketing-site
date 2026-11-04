(()=>{
 const m=document.querySelector('.menu-toggle'),nav=document.querySelector('.menu');
 m?.addEventListener('click',()=>{const open=nav.classList.toggle('open');m.setAttribute('aria-expanded',String(open))});
 document.querySelectorAll('.menu a').forEach(a=>a.addEventListener('click',()=>{nav.classList.remove('open');m.setAttribute('aria-expanded','false')}));
 const slides=["assets/sala-skyline.png", "assets/sala-luxo.png", "assets/lounge-horizonte.png"];const alts=['Sala moderna com vista para o skyline','Sala de luxo com vista para o entardecer','Lounge luxuoso com vista para o horizonte'];let index=0;const img=document.querySelector('.case-photo img');const dots=document.querySelector('.dots');
 document.querySelectorAll('.slider-arrows button').forEach((b,n)=>b.addEventListener('click',()=>{index=(index+(n===0?-1:1)+slides.length)%slides.length;img.src=slides[index];img.onerror=()=>{img.onerror=null;img.src='assets/sala-skyline.png'};img.alt=alts[index];dots.textContent=slides.map((_,i)=>i===index?'●':'○').join('  ')}));
 const form=document.querySelector('#booking-form');if(!form)return;
 const days=document.querySelector('#booking-days'),times=document.querySelector('#booking-times');const pages=[...form.querySelectorAll('[data-step]')],progress=[...document.querySelectorAll('[data-progress]')];
 const state={date:null,time:null};const weekdays=['DOM','SEG','TER','QUA','QUI','SEX','SÁB'];const months=['JAN','FEV','MAR','ABR','MAI','JUN','JUL','AGO','SET','OUT','NOV','DEZ'];
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
 form.addEventListener('submit',e=>{e.preventDefault();const inputs=[...form.querySelectorAll('[data-step="2"] input')];if(!inputs.every(i=>i.reportValidity()))return;show(3)});
 document.querySelector('#booking-restart').addEventListener('click',()=>{form.reset();state.date=null;state.time=null;form.querySelectorAll('[aria-pressed]').forEach(x=>x.setAttribute('aria-pressed','false'));show(1)});
})();