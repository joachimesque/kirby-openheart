# kirby-openheart 💗

A very basic implementation of the [Openheart protocol](https://openheart.fyi/) for [Kirby](https://getkirby.com).

## State of the plugin

This plugin was developed for my own personal use. It works on my website, so my goal is fullfilled.

It is now published under a permissive license so that you can do whatever you wish with it. Don’t hesitate to fork this repo, prepare and publish the plugin for Composer, I do not want the responsibility associated with that part.

(is there a “Make it better” license? whereby I leave the code open, with the explicit goal that you have to make it better before redistributing it? anyways, that’s the spirit under which it is shared.)

The license doesn’t apply to the vendor code that is distributed along with this plugin.
The [emoji-picker-element](https://github.com/nolanlawson/emoji-picker-element#readme) dependency is the work of Nathan Lawson, for which I am forever grateful. It is copied in this repository (in `assets/js/vendor/`) because it’s the easiest way I know to do include and distribute it.

## Using

Copy the `kirby-openheart` directory into your `site/plugins` directory.

### options

You can specify which emoji reactions you won’t allow.

```config.php
  'joachimesque.openheart.disallow' => ['🖕','🖕🏻','🖕🏼','🖕🏽','🖕🏾','🖕🏿'],
```

### blueprints

Add the Openheart tab for site-wide emoji leaderboards

```site.yml
tabs:
  site:
    …
  openhearts: tabs/openheart
```

Add the Openheart field for a page-specific list of Openheart emoji

```default.yml
sections:
  fields:
    type: fields
    fields:
      openheart: fields/openheart
```

### templates

Add a call for the scripts in your template:

```layout.php
…
<html>
  <head>
  …
  <?php snippet('openheart-scripts') ?>
  </head>
  <body>
  …
```

Add the openheart snippet in your content page:

```article.php
<main>
  <h1><?= $page->title() ?></h1>
  …
  <?php snippet('openheart') ?>
</main>
```

You can also include a list of all openheart emojis to be displayed in your pages list:

```articles_list.php
<?php foreach($page->children() as $child): ?>
  <article>
    <h2><?= $child->title() ?></h2>
    …
    <?php snippet('all-openhearts', ['page' => $child]) ?>
  </article>
<?php endforeach ?>
```

## Websites

- https://blog.professeurjoachim.com, by @joachimesque
- …yours?
