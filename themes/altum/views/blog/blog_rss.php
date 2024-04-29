<?php defined('ALTUMCODE') || die() ?>
<?= '<?xml version="1.0" encoding="UTF-8"?>' ?>
<rss version="2.0">
    <channel>

        <title><?= settings()->main->title ?></title>
        <link><?= SITE_URL ?></link>
        <description><?= l('index.meta_description') ?></description>
        <language><?=  \Altum\Language::$code  ?></language>

        <?php foreach($data->blog_posts as $blog_post): ?>
        <item>
            <title><?= $blog_post->title ?></title>
            <link><?= SITE_URL . ($blog_post->language ? \Altum\Language::$active_languages[$blog_post->language] . '/' : null) . 'blog/' . $blog_post->url ?></link>
            <description><?= $blog_post->description ?></description>
            <pubDate><?= \Altum\Date::get($blog_post->datetime, 'D, d M Y H:i:s O') ?></pubDate>
        </item>
        <?php endforeach ?>

    </channel>
</rss>
