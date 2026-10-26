<?php $cur = basename($_SERVER['PHP_SELF']); ?>
<aside class="admin-side" id="adminSide">
  <div class="brand"><span class="brand-mark">AM</span><span>Ariyan<small>Admin Panel</small></span></div>
  <ul class="admin-nav">
    <li><a href="<?= url('admin/dashboard.php') ?>" class="<?= $cur==='dashboard.php'?'active':'' ?>"><i class="fa-solid fa-gauge"></i> Dashboard</a></li>
    <li><a href="<?= url('admin/albums.php') ?>" class="<?= in_array($cur,['albums.php','album-edit.php','photos.php'])?'active':'' ?>"><i class="fa-solid fa-images"></i> Albums</a></li>
    <li><a href="<?= url('admin/album-create.php') ?>" class="<?= $cur==='album-create.php'?'active':'' ?>"><i class="fa-solid fa-plus"></i> Create Album</a></li>
    <li><a href="<?= url('admin/services.php') ?>" class="<?= $cur==='services.php'?'active':'' ?>"><i class="fa-solid fa-list-check"></i> Services</a></li>
    <li><a href="<?= url('admin/settings.php') ?>" class="<?= $cur==='settings.php'?'active':'' ?>"><i class="fa-solid fa-gear"></i> Settings</a></li>
    <li style="margin-top:20px; border-top:1px solid var(--border); padding-top:20px;"><a href="<?= url('') ?>" target="_blank"><i class="fa-solid fa-arrow-up-right-from-square"></i> View Site</a></li>
    <li><a href="<?= url('admin/logout.php') ?>"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
  </ul>
</aside>
<button class="mobile-admin-toggle" onclick="document.getElementById('adminSide').classList.toggle('open')"><i class="fa-solid fa-bars"></i></button>