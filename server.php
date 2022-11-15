<?php 
// mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$conn = mysqli_connect('localhost', 'root', '', 'like');
if (!$conn) {
  die("Error connecting to database: " . mysqli_connect_error($conn));
  exit();
} 

  $ip = $_SERVER['REMOTE_ADDR'];
  $ip_query = "SELECT `id` FROM uniq_user WHERE user_ip = ('$ip')";
  $res_ip_query = mysqli_query($conn, $ip_query);
  $result = mysqli_fetch_array($res_ip_query);
 if(isset($result)) {
  $user_id = $result[0];
 } else {
  $push_ip_query = "REPLACE INTO uniq_user(user_ip) VALUES ('$ip')";
  $get_ip_query = "SELECT id FROM uniq_user WHERE user_ip=('$ip')";
  $res_push = mysqli_query($conn, $push_ip_query);
  $res_get = mysqli_query($conn, $get_ip_query);
  $result_long = mysqli_fetch_array($res_get);
   $user_id = $result_long[0];
 }

if (isset($_POST['action'])) {
  $post_id = $_POST['post_id'];
  $action = $_POST['action'];
  switch ($action) {
  	case 'like':
         $sql="INSERT INTO rating_info (user_id, post_id, rating_action) 
         	   VALUES ($user_id, $post_id, 'like') 
         	   ON DUPLICATE KEY UPDATE rating_action='like'";
         break;
  	case 'dislike':
          $sql="INSERT INTO rating_info (user_id, post_id, rating_action) 
               VALUES ($user_id, $post_id, 'dislike') 
         	   ON DUPLICATE KEY UPDATE rating_action='dislike'";
         break;
  	case 'unlike':
	      $sql="DELETE FROM rating_info WHERE user_id=$user_id AND post_id=$post_id";
	      break;
  	case 'undislike':
      	  $sql="DELETE FROM rating_info WHERE user_id=$user_id AND post_id=$post_id";
      break;
  	default:
  		break;
  }
  mysqli_query($conn, $sql);
  echo getRating($post_id);
  exit(0);
}

// всего лайков
function getLikes($id)
{
  global $conn;
  $sql = "SELECT COUNT(*) FROM rating_info 
  		  WHERE post_id = $id AND rating_action='like'";
  $rs = mysqli_query($conn, $sql);
  $result = mysqli_fetch_array($rs);
  return $result[0];
}

//  всего дизлайков
function getDislikes($id)
{
  global $conn;
  $sql = "SELECT COUNT(*) FROM rating_info 
  		  WHERE post_id = $id AND rating_action='dislike'";
  $rs = mysqli_query($conn, $sql);
  $result = mysqli_fetch_array($rs);
  return $result[0];
}

// лайки и дизлайки конкретного фото
function getRating($id)
{
  global $conn;
  $rating = array();
  $likes_query = "SELECT COUNT(*) FROM rating_info WHERE post_id = $id AND rating_action='like'";
  $dislikes_query = "SELECT COUNT(*) FROM rating_info 
		  			WHERE post_id = $id AND rating_action='dislike'";
  $likes_rs = mysqli_query($conn, $likes_query);
  $dislikes_rs = mysqli_query($conn, $dislikes_query);
  $likes = mysqli_fetch_array($likes_rs);
  $dislikes = mysqli_fetch_array($dislikes_rs);
  $rating = [
  	'likes' => $likes[0],
  	'dislikes' => $dislikes[0]
  ];
  return json_encode($rating);
}

// проверка лайкал ли пост пользователь
function userLiked($post_id)
{
  global $conn;
  global $user_id;
  $sql = "SELECT * FROM rating_info WHERE user_id=$user_id 
  		  AND post_id=$post_id AND rating_action='like'";
  $result = mysqli_query($conn, $sql);
  if (mysqli_num_rows($result) > 0) {
  	return true;
  }else{
  	return false;
  }
}

// проверка дизлайкал ли пост пользователь
function userDisliked($post_id)
{
  global $conn;
  global $user_id;
  $sql = "SELECT * FROM rating_info WHERE user_id=$user_id 
  		  AND post_id=$post_id AND rating_action='dislike'";
  $result = mysqli_query($conn, $sql);
  if (mysqli_num_rows($result) > 0) {
  	return true;
  }else{
  	return false;
  }
}


$sql = "SELECT * FROM posts";
$result = mysqli_query($conn, $sql);
// вывести все посты
// выводит массив под именем $posts
$posts = mysqli_fetch_all($result, MYSQLI_ASSOC);