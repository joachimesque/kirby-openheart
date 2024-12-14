<?php

return [
  'options' => [
    'disallow' => ['🖕','🖕🏻','🖕🏼','🖕🏽','🖕🏾','🖕🏿']
  ],
  'blueprints' => [
    'fields/openheart' => [
      'label' => 'OpenHeart reactions',
      'type' => 'structure',
      'fields' => [
        'emojo' => [ 
          'label' => 'Emojo',
          'type' => 'text',
        ],
        'count' => [
          'label' => 'Count',
          'type' => 'text',
        ],
      ]
    ],
    'tabs/openheart' => [
      'label' => 'Openheart',
      'icon' => 'heart-filled',
      'sections' => [
        'latest' => [
          'type' => 'pages',
          'create' => false,
          'query' => 'site.index.filterBy("totalEmojos", ">", 0).sortBy("modified", "desc", "date", "desc")',
          'info' => '{{ page.allEmojos }}',
          'limit' => 5,
          'layout' => 'table',
          'columns' => [
            'info' => [
              'mobile' => true
            ]
          ]
        ],
        'most' => [
          'type' => 'pages',
          'create' => false,
          'query' => 'site.index.filterBy("totalEmojos", ">", 0).sortBy("totalEmojos", "desc", "date", "desc")',
          'info' => '{{ page.totalEmojos }}',
          'limit' => 5,
          'layout' => 'table',
          'columns' => [
            'info' => [
              'mobile' => true
            ]
          ]
        ]
      ]
    ]
  ],
  'pageMethods' => [
    'totalEmojos' => function () {
      return A::sum(A::pluck($this->openheart()->toStructure()->toArray(), 'count'));
    },
    'allEmojos' => function () {
      $emo = '';
      foreach($this->openheart()->toStructure()->toArray() as $emojo) {
        $emo = $emo . A::implode(A::fill(
          [],
          min(5, (int)$emojo['count']),
          (string)$emojo['emojo'],
        ));
      }
      return $emo;
    }
  ],
  'snippets' => [
    'openheart' => __DIR__ . '/snippets/openheart.php',
    'openheart-scripts' => __DIR__ . '/snippets/openheart-scripts.php',
    'all-openhearts' => __DIR__ . '/snippets/all-openhearts.php',
  ],
  'routes' => [
    [
      'pattern' => ['openheart/(:all)'],
      'action' => function ($request_path) {
        $kirby = kirby();
        $kirby->impersonate('kirby');
        
        $page = page($request_path);
        
        if (!$page) {
          Header::notfound(true);
          return '{"error": 404}';
        }

        $field = $page->openheart()->toStructure()->toArray();

        if (kirby()->request()->method() == "GET") {
          $array = [];

          foreach($field as $item) {
            $array[$item['emojo']] =  $item['count'];
          }

          return JSON::encode($array);
        }
        
        if (kirby()->request()->method() == "POST") {
          $disallowed = option('joachimesque.openheart.disallow');
          $kirby_data = kirby()->request()->body()->contents();

          $emojis = Emoji\detect_emoji($kirby_data);
          $openheart_key = $emojis[0]['emoji'];

          if (in_array($openheart_key, $disallowed)) {
            Header::error(true);
            return '{"error": 400}';
          }

          $changed = false;

          foreach ($field as &$item) {
            if ($item['emojo'] == $openheart_key) {
              $changed = true;
              $item['count']++;
            }
          }

          if (!$changed) {
            $field[] = [
              'emojo' => $openheart_key,
              'count' => 1,
            ];
          }

          $new_page = $page->update(['openheart' => $field]);

          $new_field = $new_page->openheart()->toStructure();

          return JSON::encode(array_combine(
            array_map(
              fn($item) => $item->toString(),
              $new_field->pluck('emojo')
            ),
            array_map(
              fn($item) => $item->toString(),
              $new_field->pluck('count')
            ),
          ));
        }
      },
      'method' => 'GET|POST',
    ],
  ],
];