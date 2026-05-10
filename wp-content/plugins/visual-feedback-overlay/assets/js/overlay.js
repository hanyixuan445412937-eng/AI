(function () {
  if (!window.vfoSettings) {
    return;
  }

  const state = { active: false, annotations: [] };
  const toolbar = document.createElement('div');
  toolbar.className = 'vfo-toolbar';
  toolbar.innerHTML = '<button type="button" class="vfo-toggle" aria-pressed="false">标注模式</button><button type="button" class="vfo-export">导出</button><button type="button" class="vfo-clear">清空本页</button>';
  document.body.appendChild(toolbar);

  const toggle = toolbar.querySelector('.vfo-toggle');
  const exportButton = toolbar.querySelector('.vfo-export');
  const clearButton = toolbar.querySelector('.vfo-clear');

  function api(method, body) {
    const url = new URL(window.vfoSettings.restUrl);
    url.searchParams.set('path', window.vfoSettings.path);
    return fetch(url.toString(), {
      method,
      headers: {
        'Content-Type': 'application/json',
        'X-WP-Nonce': window.vfoSettings.nonce
      },
      body: body ? JSON.stringify(body) : undefined
    }).then((response) => response.json());
  }

  function render() {
    document.querySelectorAll('.vfo-marker').forEach((marker) => marker.remove());
    state.annotations.forEach((item, index) => {
      const marker = document.createElement('button');
      marker.type = 'button';
      marker.className = 'vfo-marker';
      marker.style.left = item.x + '%';
      marker.style.top = item.y + '%';
      marker.textContent = String(index + 1);
      marker.dataset.note = '[' + item.createdAt + '] ' + item.note;
      marker.setAttribute('aria-label', item.note);
      document.body.appendChild(marker);
    });
  }

  function load() {
    api('GET').then((items) => {
      state.annotations = Array.isArray(items) ? items : [];
      render();
    });
  }

  toggle.addEventListener('click', () => {
    state.active = !state.active;
    toggle.setAttribute('aria-pressed', String(state.active));
    document.documentElement.classList.toggle('vfo-crosshair', state.active);
  });

  document.addEventListener('click', (event) => {
    if (!state.active || toolbar.contains(event.target) || event.target.closest('.vfo-marker')) {
      return;
    }
    const note = window.prompt('请输入这个位置需要修改的内容：');
    if (!note) {
      return;
    }
    const x = (event.pageX / Math.max(document.documentElement.scrollWidth, document.body.scrollWidth)) * 100;
    const y = (event.pageY / Math.max(document.documentElement.scrollHeight, document.body.scrollHeight)) * 100;
    api('POST', { x, y, note, viewport: window.innerWidth + 'x' + window.innerHeight }).then((item) => {
      if (item && item.id) {
        state.annotations.push(item);
        render();
      }
    });
  }, true);

  exportButton.addEventListener('click', () => {
    const blob = new Blob([JSON.stringify(state.annotations, null, 2)], { type: 'application/json' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'visual-feedback-' + window.location.pathname.replace(/[^a-z0-9]+/gi, '-').replace(/^-|-$/g, '') + '.json';
    link.click();
    URL.revokeObjectURL(link.href);
  });

  clearButton.addEventListener('click', () => {
    if (!window.confirm('确定清空当前页面的所有标注吗？')) {
      return;
    }
    api('DELETE').then(() => {
      state.annotations = [];
      render();
    });
  });

  load();
}());
