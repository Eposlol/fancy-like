<?php 
// connect to database
$conn = mysqli_connect('localhost', 'root', '', 'like');

// get ip function
// function getClientIP():string
// {
//     $keys=array('HTTP_CLIENT_IP','HTTP_X_FORWARDED_FOR','HTTP_X_FORWARDED','HTTP_FORWARDED_FOR','HTTP_FORWARDED','REMOTE_ADDR');
//     foreach($keys as $k)
//     {
//         if (!empty($_SERVER[$k]) && filter_var($_SERVER[$k], FILTER_VALIDATE_IP))
//         {
//             return $_SERVER[$k];
//         }
//     }
//     return "UNKNOWN";
// }

// lets assume a user is logged in with id $user_id


if (!$conn) {
  die("Error connecting to database: " . mysqli_connect_error($conn));
  exit();
} 
//else { 

//   $ip = filter_var(
//     array_map("trim", 
//         explode("," ,
//             $_SERVER['HTTP_CLIENT_IP']??
//             $_SERVER['HTTP_X_FORWARDED_FOR']??
//             $_SERVER['HTTP_X_FORWARDED']??
//             $_SERVER['HTTP_FORWARDED_FOR']??
//             $_SERVER['HTTP_FORWARDED']??
//             $_SERVER['REMOTE_ADDR']
//         )
//     )[0],
//   FILTER_VALIDATE_IP);
// $ip = $ip!=""?$ip:"Invalid IP";
// echo $ip;

  // global $conn;
  //  $sql = "INSERT INTO unique_user VALUES (id, INET6_ATON('".$_SERVER['REMOTE_ADDR']."'))"; 
  // $result = mysqli_query($conn, $sql);
  // mysql_query($conn, $sql);
    // $sql = "INSERT INTO unique_user (id, user_ip) 
    //         VALUES (id, INET6_ATON('".$_SERVER['REMOTE_ADDR']."'))";
    // $result = mysqli_query($conn, $sql);
    
//}




// function getUser() {
//   global $conn;
//   $sql="SELECT id FROM `unique_user` where user_ip = $ip";
//   $rs = mysqli_query($conn, $sql);
//   $result = mysqli_fetch_array($rs);
//   return $user_id;
// }


$ip = $_SERVER['REMOTE_ADDR'];
$query = "insert into IP(IP) values ('$ip')";
$res = mysql_query($query);
if($res)
{
echo "Ваш IP адрес успешно добавлен в БД!";
}
else
{
echo "IP не добавлен!";
}
$user_id = 4;
// клик лайк или дизлайк
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

  // execute query to effect changes in the database ...
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