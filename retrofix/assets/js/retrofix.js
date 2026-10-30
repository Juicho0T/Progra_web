const toggle = document.querySelector('.menu-toggle');
toggle?.addEventListener('click', () => {
  const open = toggle.getAttribute('aria-expanded') !== 'true';
  toggle.setAttribute('aria-expanded', String(open));
  document.querySelector('#navegacion').classList.toggle('open', open);
});
document.addEventListener('keydown', e => {
  if (e.key === 'Escape') { toggle?.setAttribute('aria-expanded', 'false'); document.querySelector('#navegacion')?.classList.remove('open'); }
});
document.querySelectorAll('[data-print]').forEach(button => button.addEventListener('click', () => window.print()));
document.querySelectorAll('form').forEach(form => {
  form.addEventListener('input', () => {
    const preview = form.querySelector('.cost-preview');
    if (preview) preview.textContent = 'Total: ' + new Intl.NumberFormat('es-MX', {style:'currency',currency:'MXN'}).format([...form.querySelectorAll('[data-cost]')].reduce((sum,field) => sum + Number(field.value || 0), 0));
  });
});
