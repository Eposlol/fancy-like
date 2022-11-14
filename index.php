<?php include('server.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
  <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->
 
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.css" rel="stylesheet"/>
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/> -->
    <script src="/js/jquery-3.3.1.min.js.js"></script>
    <script src="/js/jquery.fancybox.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/fancybox.css">
    <link rel="stylesheet" href="css/artisan.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<main>
<div class="f-manege medipad gallery-container">
	<div class="gallery-box">
  <?php foreach ($posts as $post): ?>
    <a 
    href="<?php echo $post['img'] ?>" 
    data-fancybox="gallery" 
    data-id="<?php echo $post['id'] ?>"
    data-caption='
      <div><?php echo $post['text'] ?></div>
      <div class="raiting"><i <?php if (userLiked($post['id'])): ?>
          class="fa fa-thumbs-up like-btn"
        <?php else: ?>
        class="fa fa-thumbs-o-up like-btn"
        <?php endif ?>
        data-id="<?php echo $post['id'] ?>"></i>
        <span class="likes"><?php echo getLikes($post['id']); ?></span>
        <i <?php if (userDisliked($post['id'])): ?>
        class="fa fa-thumbs-down dislike-btn"
        <?php else: ?>
        class="fa fa-thumbs-o-down dislike-btn"
         <?php endif ?>
        data-id="<?php echo $post['id'] ?>"></i>
        <span class="dislikes"><?php echo getDislikes($post['id']); ?></span>
        </div>'
    ><img 
    src="<?php echo $post['img'] ?>"> 
    <?php if (!empty($post['gallery_title'])): ?>
		<div class="gallery-title">
       <?php echo $post['gallery_title']; ?>
		</div>
    <?php else: ?>
      
    <?php endif ?>
		</a>
    <?php endforeach ?>
</div>
</div>
    </main>
  <script src="js/scripts.js"></script>
</body>
</html>