<?php

Layout()->append('breadcrumbs', new \ApCode\Html\Element\A('Текст', ShortUrl(__FILE__)));

Layout()->append('head.css.links', 'https://cdnjs.cloudflare.com/ajax/libs/highlight.js/10.5.0/styles/mono-blue.min.css');
Layout()->append('body.js.links', 'https://cdnjs.cloudflare.com/ajax/libs/highlight.js/10.5.0/highlight.min.js');
Layout()->append('body.js.code', 'hljs.initHighlightingOnLoad();');

$text = file_get_contents(ExpandPath('@root/../TASK.md'));

if (class_exists('Parsedown')) {
    $parser = new Parsedown();
    $html = $parser->text($text);
} else {
    $html = (string) new \ApCode\Html\Element\Pre(new \ApCode\Html\Element\Code($text));
    Alert(<<<HTML
Для более правильного отображения текста установите Parsedown: <br />
<ol>
  <li>Перейдите в папку с репозиторием проекта</li>
  <li>Выполните команду <pre><code>curl https://raw.githubusercontent.com/erusev/parsedown/master/Parsedown.php > src/classes/Parsedown.php</code></pre></li>
</ol>
HTML
      , 'warning');
}

echo $html;
