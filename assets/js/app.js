document.addEventListener('DOMContentLoaded',()=>{
 const navToggle=document.querySelector('[data-nav-toggle]'),nav=document.querySelector('[data-nav]');navToggle?.addEventListener('click',()=>nav?.classList.toggle('open'));
 document.querySelectorAll('.flash').forEach(el=>setTimeout(()=>el.classList.add('hide'),4200));
 const currencyInputs=document.querySelectorAll('[data-currency]');
 const format=n=>new Intl.NumberFormat('id-ID').format(String(n).replace(/\D/g,''));
 currencyInputs.forEach(input=>{if(input.value)input.value=format(input.value);input.addEventListener('input',()=>{const p=input.selectionStart;input.value=format(input.value);input.setSelectionRange(input.value.length,input.value.length);});});
 document.querySelectorAll('[data-amount]').forEach(btn=>btn.addEventListener('click',()=>{const input=document.querySelector('input[name="amount"]');if(input){input.value=format(btn.dataset.amount);input.dispatchEvent(new Event('input'));}}));
 document.querySelectorAll('[data-donation-form]').forEach(form=>form.addEventListener('submit',()=>{const b=form.querySelector('button[type="submit"]');if(b){b.disabled=true;b.textContent='Mengirim...';}}));
});
