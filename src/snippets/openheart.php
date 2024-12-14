<ul class="emojo-listo" aria-live="polite" data-page-id="<?= $page->id() ?>">
  <?php if ($openheart = $page->openheart()): ?>
    <?php foreach($page->openheart()->toStructure() as $item): ?>
      <li>
        <button type="button" data-emojo-selecto="<?= $item->emojo() ?>">
          <i><?= $item->emojo() ?></i> <?= $item->count() ?>
        </button>
      </li>
    <?php endforeach ?>
  <?php endif ?>

  <li class="emojo-fieldo">
    <button type="button" class="emojo-clicko" popovertarget="emojo-selecto">
      emojo !
    </button>
    <div class="emojo-selecto" role="tooltip" popover id="emojo-selecto">
      <emoji-picker></emoji-picker>
    </div>
  </li>
</ul>
