<?php 
  $this->layout('master', [
    'title'=>'Edit post page', //associate array
    'desc'=>'Edit and view individual post'
  ]);  
?>

<h1>Edit Post: <?= $this->e($originalTitle)?></h1>

<form action="<?= $_SERVER['REQUEST_URI']?>" method="post" enctype="multipart/form-data">	
	<div>
		<label for="title">Title: </label>
		<input type="text" id="title" name="title" value="<?= $post['title']?>">
		<?= isset($titleError) ?  $titleError : '' ?>
	</div>
	<div>
		<label for="desc">Description: </label>
		<textarea name="description" id="desc"><?= $post['description']?></textarea>
		<?= isset($descError) ?  $descError : '' ?>
	</div>

	<img src="img/uploads/stream/<?= $post['image'] ?>" alt=="">
	<input type="file" name="image">

	<input type="submit" name="edit-post">
</form>