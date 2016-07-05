<?php 
  $this->layout('master', [
    'title'=>'Post page', //associate array
    'desc'=>'View and individual post'
  ]);  
?>

<body id="post-page">

<h1><?=$post['title'] ?></h1>

<p><?= $post['description'] ?></p>

<img src="img/uploads/original/<?= $post['image']?>" alt="">

<ul>
	<li>Post Created <?= $post['created_at']?></li>
	<li>Post Updated <?= $post['updated_at']?></li>
	<li>Posted by: <?= $post['first_name'].' '.$post['last_name'] ?></li>
</ul>

<section>

	<h1>Comments: (<?= count($allComments) ?>)</h1>

	<form action="index.php?page=post&postid=<?= $_GET['postid'] ?>" method="post">
		<label for ="comment">Write a comment: </label>
		<textarea name="comment" id="comment" cols="30" rows="10"></textarea>
		<input type="submit" name="new-comment" value="Submit">
	</form>

	<?php foreach($allComments as $comment): ?>

		<article> 
			<p><?= htmlentities($comment['comment']) ?></p>
			<small>Written by: <?=htmlentities($comment['author']) ?></small>
			<?php 
				//is the visitor logged in?
				if(isset($_SESSION['id'])) {

					//does this user own the comment
					if($_SESSION['id'] == $comment['user_id'] ) {

						//yes! this uer owns the comment
						echo 'delete';
						echo 'edit';
					}
				}

			?>

		</article>


	<?php endforeach ?>

</section>