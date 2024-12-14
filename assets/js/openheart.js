const openheartListEl = document.querySelector('.emojo-listo');
const openheartFieldEl = document.querySelector('.emojo-fieldo');
const openheartBtnEl = openheartFieldEl.querySelector('button.emojo-clicko');
const openheartPopEl = openheartFieldEl.querySelector('.emojo-selecto');
const apiUrl = `/openheart/${openheartListEl.dataset.pageId}`;

const EMOJO_SELECT = "emojo-select";
const EMOJO_BTN = "emojo-btn";

const supportsPopover = () => {
  return HTMLElement.prototype.hasOwnProperty("popover");
}

const pleaseUpdato = (emojo) => {
  fetch(apiUrl, {
    method: "post",
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'text/plain'
    },
    body: emojo
  })
  .then(response => response.json().then(data => updateListo(data, emojo)))
  .catch(() => {
    const li = document.createElement('li');
    li.innerText = 'Oups, la requête a échoué';
    openheartListEl.append(li);
  });
}

const emoClicko = (e, source) => {
  let value = "";
  if (source == EMOJO_SELECT) {
    value = e.detail.unicode;
  } else if (source == EMOJO_BTN) {
    const btn = e.target.tagName == "BUTTON" ? e.target : e.target.closest('button');
    value = btn.dataset.emojoSelecto;
  };

  openheartBtnEl.disabled = true;
  openheartListEl.setAttribute('tabindex', '-1');
  openheartListEl.focus();

  supportsPopover() && openheartPopEl.hidePopover();
  openheartFieldEl.hidden = true;
  pleaseUpdato(value);
}

const updateListo = (data, current) => {
  const newList = [...Object.entries(data)].map(item => {
    const li = document.createElement('li');
    let special = "";
    if (current == item[0]) {
      special = ' class="is_new"'
    }
    li.innerHTML = `<span${special}><i>${item[0]}</i> ${item[1]}</span>`;
    return li;
  });

  openheartListEl.innerText = '';
  openheartListEl.append(...newList);
}

document.querySelector('emoji-picker').addEventListener('emoji-click', (e) => emoClicko(e, EMOJO_SELECT));

[...document.querySelectorAll('button[data-emojo-selecto]')].forEach(btn => {
  btn.addEventListener('click', (e) => emoClicko(e, EMOJO_BTN));
})