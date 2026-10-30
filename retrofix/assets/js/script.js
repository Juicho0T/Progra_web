document.addEventListener('DOMContentLoaded', () => {
  const botonMenu = document.querySelector('.menu-movil');
  const enlaces = document.querySelector('.nav-enlaces');
  if (botonMenu && enlaces) botonMenu.addEventListener('click', () => enlaces.classList.toggle('abierto'));
  document.querySelectorAll('[data-confirmar]').forEach((elemento) => {
    elemento.addEventListener('click', (evento) => {
      if (!window.confirm(elemento.dataset.confirmar)) evento.preventDefault();
    });
  });
  document.querySelectorAll('[data-costo]').forEach((formulario) => {
    const actualizar = () => {
      const reparacion = Number(formulario.querySelector('[name="costo_reparacion"]')?.value || 0);
      const refacciones = Number(formulario.querySelector('[name="costo_refacciones"]')?.value || 0);
      const total = formulario.querySelector('[data-total]');
      if (total) total.textContent = (reparacion + refacciones).toLocaleString('es-MX', { style: 'currency', currency: 'MXN' });
    };
    formulario.addEventListener('input', actualizar); actualizar();
  });
});

