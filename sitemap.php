<?php
require_once __DIR__ . '/includes/init.php';
header('Content-Type: application/xml; charset=utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php foreach (['', 'about.php', 'services.php', 'work.php', 'contact.php'] as $p): ?>
  <url><loc><?= e(url($p)) ?></loc><changefreq>weekly</changefreq><priority><?= $p===''?'1.0':'0.8' ?></priority></url>
<?php endforeach;
$albums = $pdo->query("SELECT slug, updated_at FROM albums WHERE published=1")->fetchAll();
foreach ($albums as $a): ?>
  <url><loc><?= e(url('album.php?slug=' . urlencode($a['slug']))) ?></loc><lastmod><?= date('Y-m-d', strtotime($a['updated_at'])) ?></lastmod></url>
<?php endforeach; ?>
</urlset>