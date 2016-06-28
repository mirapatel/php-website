<?php 
  $this->layout('master', [
    'title'=>'Post page', //associate array
    'desc'=>'View and individual post'
  ]);  
?>

<body id="post-page">

<h1><?=$post['title'] ?></h1>

<p><?= $post['description'] ?></p>

<img src="img/uploads/highres/<?= $post['image']?>" alt="">

<ul>
	<li>Post Created <?= $post['created_at']?></li>
	<li>Post Updated <?= $post['updated_at']?></li>
	<li>Posted by: <?= $post['first_name'].' '.$post['last_name'] ?></li>
</ul>