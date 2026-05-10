(function () {
  const button = document.querySelector('.menu-toggle');
  const nav = document.querySelector('.primary-navigation');

  if (!button || !nav) {
    return;
  }

  button.addEventListener('click', function () {
    const expanded = button.getAttribute('aria-expanded') === 'true';
    button.setAttribute('aria-expanded', String(!expanded));
    nav.classList.toggle('is-open', !expanded);
  });
}());
