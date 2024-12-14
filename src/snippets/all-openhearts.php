<?php if($page->openheart()->isNotEmpty()): ?>
  <span class="all-emojos" data-count="<?= $page->totalEmojos() ?>">
    <?php foreach($page->openheart()->toStructure()->flip() as $emojo): ?>
      <?php foreach(range(1, min(11, $emojo->count()->toInt())) as $idx): ?>
        <span><?= $emojo->emojo() ?></span>
      <?php endforeach ?>
    <?php endforeach ?>
  </span>
<?php endif ?>