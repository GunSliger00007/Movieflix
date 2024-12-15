<?php
session_start();
include "./php_connection/connection.php";
if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $sql = "SELECT movie_id, title, release_date,file_path,description,genre,cover_image, duration,created_at FROM movies where movie_id=$id";
  $result = $conn->query($sql);
  $sql1="SELECT u.username, r.review_text, r.rating, COUNT(r.review_text) AS total_reviews FROM reviews r JOIN users u ON r.user_id = u.user_id WHERE r.movie_id = $id GROUP BY u.username, r.review_text, r.rating;";
  $result1=$conn->query($sql1);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Document</title>
 
  <link rel="stylesheet" href="playmovie/style.css" />
</head>

<body>
  <div class="header" id="myHeader">
    <div class="container">
      <div class="logo">
        <a href="index.php">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            width="113"
            height="60"
            viewBox="0 0 113 60"
            fill="none">
            <path
              d="M14.5809 23.4622H17.2086L21.917 28.1497L26.6253 23.4622H29.253V36.3158H26.6253V27.0724L21.917 31.5707L17.2086 27.0724V36.3158H14.5809V23.4622ZM32.0465 31.4638C32.0465 30.7675 32.1874 30.1152 32.4693 29.5066C32.7566 28.898 33.1628 28.3662 33.6878 27.9112C34.2183 27.4562 34.8566 27.0971 35.6026 26.8339C36.3542 26.5707 37.197 26.4392 38.1309 26.4392C39.0648 26.4392 39.9048 26.5707 40.6508 26.8339C41.4024 27.0971 42.0407 27.4562 42.5657 27.9112C43.0962 28.3662 43.5024 28.898 43.7842 29.5066C44.0715 30.1152 44.2153 30.7675 44.2153 31.4638C44.2153 32.1601 44.0715 32.8125 43.7842 33.4211C43.5024 34.0296 43.0962 34.5614 42.5657 35.0164C42.0407 35.4715 41.4024 35.8306 40.6508 36.0937C39.9048 36.3569 39.0648 36.4885 38.1309 36.4885C37.197 36.4885 36.3542 36.3569 35.6026 36.0937C34.8566 35.8306 34.2183 35.4715 33.6878 35.0164C33.1628 34.5614 32.7566 34.0296 32.4693 33.4211C32.1874 32.8125 32.0465 32.1601 32.0465 31.4638ZM34.6245 31.4638C34.6245 31.8531 34.7019 32.2232 34.8566 32.574C35.0169 32.9194 35.2462 33.2264 35.5446 33.495C35.8485 33.7582 36.216 33.9693 36.6471 34.1283C37.0836 34.2818 37.5782 34.3585 38.1309 34.3585C38.6835 34.3585 39.1754 34.2818 39.6063 34.1283C40.043 33.9693 40.4105 33.7582 40.7089 33.495C41.0128 33.2264 41.2421 32.9194 41.3968 32.574C41.5571 32.2232 41.6373 31.8531 41.6373 31.4638C41.6373 31.0746 41.5571 30.7045 41.3968 30.3536C41.2421 30.0027 41.0128 29.6957 40.7089 29.4326C40.4105 29.1639 40.043 28.9528 39.6063 28.7994C39.1754 28.6404 38.6835 28.5608 38.1309 28.5608C37.5782 28.5608 37.0836 28.6404 36.6471 28.7994C36.216 28.9528 35.8485 29.1639 35.5446 29.4326C35.2462 29.6957 35.0169 30.0027 34.8566 30.3536C34.7019 30.7045 34.6245 31.0746 34.6245 31.4638ZM45.3426 26.6036H48.1858L50.7306 32.4424L51.3938 34.0789L52.0569 32.4424L54.6018 26.6036H57.4449L52.8113 36.3158H49.9763L45.3426 26.6036ZM60.4539 25.2878C60.211 25.2878 59.9897 25.2549 59.7908 25.1892C59.5977 25.1179 59.4316 25.0247 59.2937 24.9095C59.1552 24.7889 59.0475 24.6518 58.9701 24.4984C58.8985 24.3394 58.8624 24.1722 58.8624 23.9967C58.8624 23.8158 58.8985 23.6486 58.9701 23.495C59.0475 23.336 59.1552 23.199 59.2937 23.0839C59.4316 22.9687 59.5977 22.8783 59.7908 22.8125C59.9897 22.7412 60.211 22.7056 60.4539 22.7056C60.7028 22.7056 60.924 22.7412 61.1171 22.8125C61.316 22.8783 61.4847 22.9687 61.6227 23.0839C61.7611 23.199 61.8662 23.336 61.9378 23.495C62.0153 23.6486 62.054 23.8158 62.054 23.9967C62.054 24.1722 62.0153 24.3394 61.9378 24.4984C61.8662 24.6518 61.7611 24.7889 61.6227 24.9095C61.4847 25.0247 61.316 25.1179 61.1171 25.1892C60.924 25.2549 60.7028 25.2878 60.4539 25.2878ZM59.2274 26.6118H61.681V36.3158H59.2274V26.6118ZM64.3665 31.4309C64.3665 30.773 64.5018 30.1453 64.7729 29.5477C65.0434 28.9446 65.4334 28.4128 65.9416 27.9523C66.4498 27.4918 67.0716 27.1245 67.8064 26.8503C68.547 26.5762 69.3873 26.4392 70.3263 26.4392C71.2606 26.4392 72.1004 26.5789 72.8463 26.8585C73.598 27.1327 74.2336 27.5109 74.753 27.9934C75.2782 28.4759 75.6787 29.0433 75.9551 29.6957C76.2368 30.3482 76.3779 31.0472 76.3779 31.7927C76.3779 31.8859 76.3753 31.9874 76.3694 32.0971C76.3694 32.2012 76.3641 32.2972 76.353 32.3848H67.1018C67.2069 32.6754 67.3756 32.9413 67.608 33.1826C67.8451 33.4238 68.1411 33.6321 68.4945 33.8076C68.8483 33.983 69.2573 34.1201 69.7215 34.2187C70.1857 34.3119 70.6998 34.3585 71.2632 34.3585C71.8272 34.3585 72.3879 34.301 72.946 34.1858C73.5041 34.0653 74.0373 33.9145 74.5461 33.7336L75.3747 35.5921C75.0543 35.7401 74.728 35.8717 74.3964 35.9868C74.0707 36.0965 73.728 36.1897 73.3688 36.2664C73.015 36.3377 72.642 36.3925 72.25 36.4309C71.8627 36.4693 71.4542 36.4885 71.0229 36.4885C69.9231 36.4885 68.9587 36.3569 68.13 36.0937C67.3008 35.8306 66.6074 35.4715 66.0493 35.0164C65.4912 34.5614 65.071 34.0268 64.7893 33.4128C64.5076 32.7988 64.3665 32.1382 64.3665 31.4309ZM73.7747 30.4852C73.6866 30.2166 73.5482 29.9671 73.3604 29.7368C73.1784 29.5011 72.9487 29.2983 72.6723 29.1283C72.3964 28.9528 72.0754 28.8158 71.711 28.7171C71.346 28.6184 70.9454 28.569 70.5088 28.569C70.0446 28.569 69.6218 28.6212 69.2409 28.7253C68.8595 28.8295 68.5252 28.9693 68.2377 29.1447C67.9501 29.3202 67.7098 29.523 67.5167 29.7533C67.3284 29.9836 67.1904 30.2275 67.1018 30.4852H73.7747ZM80.0915 28.4375H78.4167V26.6118H80.0915C80.0915 25.9539 80.1854 25.3701 80.3732 24.8602C80.5611 24.3448 80.8321 23.9117 81.1855 23.5608C81.5446 23.2045 81.9786 22.9331 82.4868 22.7467C83.0009 22.5603 83.5786 22.4671 84.2195 22.4671C84.6672 22.4671 85.1065 22.5109 85.5373 22.5987C85.9686 22.6809 86.35 22.8015 86.6816 22.9605L85.811 25.0082C85.6784 24.9315 85.4906 24.8602 85.2476 24.7944C85.01 24.7286 84.7474 24.6957 84.4598 24.6957C83.8964 24.6957 83.4402 24.8547 83.0921 25.1727C82.7494 25.4852 82.5669 25.9649 82.5452 26.6118H85.637V28.4375H82.5452V36.3158H80.0915V28.4375ZM87.593 22.6727H90.0467V36.3158H87.593V22.6727ZM94.4898 25.2878C94.2468 25.2878 94.0256 25.2549 93.8266 25.1892C93.6335 25.1179 93.4675 25.0247 93.3295 24.9095C93.191 24.7889 93.0834 24.6518 93.0059 24.4984C92.9343 24.3394 92.8982 24.1722 92.8982 23.9967C92.8982 23.8158 92.9343 23.6486 93.0059 23.495C93.0834 23.336 93.191 23.199 93.3295 23.0839C93.4675 22.9687 93.6335 22.8783 93.8266 22.8125C94.0256 22.7412 94.2468 22.7056 94.4898 22.7056C94.7386 22.7056 94.9598 22.7412 95.1529 22.8125C95.3518 22.8783 95.5205 22.9687 95.6585 23.0839C95.797 23.199 95.902 23.336 95.9736 23.495C96.0511 23.6486 96.0898 23.8158 96.0898 23.9967C96.0898 24.1722 96.0511 24.3394 95.9736 24.4984C95.902 24.6518 95.797 24.7889 95.6585 24.9095C95.5205 25.0247 95.3518 25.1179 95.1529 25.1892C94.9598 25.2549 94.7386 25.2878 94.4898 25.2878ZM93.2632 26.6118H95.7168V36.3158H93.2632V26.6118ZM101.403 31.4638L97.3583 26.6036H100.491L102.945 29.5642L105.275 26.6036H108.333L104.528 31.4556L108.573 36.3158H105.44L102.912 33.2813L100.566 36.3158H97.5073L101.403 31.4638Z"
              fill="#E8E8E8" />
            <path
              d="M61.0094 17.9863V1.05264H1.06104V58.9474H61.0094V42.0137"
              stroke="#E8E8E8" />
          </svg>
        </a>
      </div>
     
      <div class="menu-btn">
        <img src="../assets/images/menu-line.svg" alt="icon" />
      </div>
      <div class="nav nav-container">
        <ul>
          <li>
            <a href="index.php" class="active">Home</a>
          </li>
          <li>
            <a href="categories.php">Categories</a>
          </li>
          <li>
            <a href="watchlist.php" id="wishlistLink">Wishlist</a>
          </li>
        </ul>
        <div class="search-menu-wrapper">
          <div class="search-icon">
            <form action="search.php" method="get">
              <input type="text" name="search_query" id="search" placeholder="Search Something..." />
              <button>
                <img src="./assets/images/Frame.svg">
              </button>
            </form>
            <ul id="searchResults" class="search-results-list"></ul>
          </div>

        </div>
        <div class="right-btn">
        <?php
          session_start();  // Start the session

          if (isset($_SESSION['username'])) {
            // Slice the first five letters of the username
            $user_id=$_SESSION['user_id'];
            $username = $_SESSION['username'];
            $shortenedUsername = substr($username, 0, 5);

            echo '<a href="#">' . $shortenedUsername.'</a>';
            echo '<a href="logout.php" >Log out</a>';
            echo '<input type="hidden" id="signup">';
            echo '<input type="hidden" id="loginSpecial">';
          } else {
            echo '<a href="#" id="loginSpecial">Log in</a>';
            echo '<a href="#" id="signup">Sign up</a>';
          }
          ?>
        

        </div>


      </div>
    </div>
  </div>
  </div>
  <main>
    <div class="custom-container">
      <?php while ($row = $result->fetch_assoc()) { ?>
        <div class="play-sec">
          <video class="background-video" controls height="100%" width="100%" id="myVideo">
            <source src="./php_connection/<?php echo $row['file_path'] ?>" type="video/mp4" />
            Your browser does not support the video tag.
          </video>
          <div class="overlay"></div>
          <button class="play-btn" id="playButton">
            <img src="./assets/images/play.svg" alt="icon" />
          </button>
        </div>
        <div class="movie-detail-wrapper">
          <div class="movie-detail">
            <div class="thumb">

              <img src="./php_connection/<?php echo $row['cover_image'] ?>" alt="thumbnail" />
            </div>
            <div class="detail-wrapper">
              <h3><?php echo $row['title'] ?></h3>
              <div class="rating-badges">
                <p class="badge-1">HD</p>

              </div>
              <p class="descp">
              <?php echo $row['description'] ?>
              </p>
              <div class="all-detail">
                <ul>
                  <li>Released: <span><?php echo $row['release_date'] ?></span></li>
                  <li>Genre: <span><?php echo $row['genre'] ?></span></li>

                  <li>Duration: <span><?php echo $row['duration'] ?> min</span></li>
                  <li>Country: <span>United States of America</span></li>

                </ul>
              <?php } ?>
              </div>
            </div >
          </div>
          <div class="review">
            <span class="total-review">
              Movie reviews (<?php echo $result1->num_rows; ?>)
            </span>
            <?php while ($row1=$result1->fetch_assoc()){ ?>
            <div class="rating-card">
            <p><?php echo $row1['review_text']?></p>

            <div class="rate">
              <?php 
              $rating=$row1['rating'];
              for($i=1;$i<=$rating;$i++){
                echo "<label for='star5' title='text'>5 stars</label>";
              }
              ?>
              </div>

            <div class="user-name"><?php echo $row1["username"]?></div>
      </div><?php }?>
        
            <button class="review-btn" id="reviewLink">Add Review</button>
            <form method="post" action="add_wishlist.php" id="wishlistForm">
              <input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id ?>">
              <input type="hidden" name="movie_id" id="movie_id" value="<?php echo $id ?>">
            <button class="wishlist-btn" id="wishlistButton">Add to wishlist</button>
            </form>
           
          </div>
          
        </div>
       
    </div>
    
  </main>
  <div class="popup-overlay" id="popupOverlay" style="display: none;">
    <div class="popup" id="popup">
      <span class="close" id="closePopup">&times;</span>
      <div class="popup-content" id="signupForm">
        <p>Welcome to our website!</p>
        <p>login up to receive exclusive offers:</p>
        <p id="responseMessage" style="color: red;"></p>
        <form action="register.php" method="post" id="registerForm">
          <input type="email" name="email" placeholder="Your email" id="emailInput">
          <input type="username" name="username" placeholder="your username" id="userInput">
          <input type="password" name="password1" placeholder="password" id="passwordInput">
          <input type="password" placeholder="password" id="passwordInput1">

          <button id="submitFormBtn">Sign Up</button>
        </form>
        <p>Already have an account? <a href="#" id="loginLink">Please login</a></p>
      </div>
      <div class="popup-content" id="loginForm" style="display: none;">
        <p id="loginResponseMessage" style="color: red;"></p>
        <form method="POST" action="login.php" id="loginForm1">
          <input name="email1" id="loginEmailInput" placeholder="Enter your email">
          <input type="password" name="password2" id="passwordInputlogin" placeholder="Enter your password" required>
          <button id="loginFormBtn" type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="#" id="signupLink">Sign Up</a></p>
        <?php if (isset($error)): ?>
          <p style="color:red;"><?php echo $error; ?></p>
        <?php endif; ?>
        <p id="responseMessage" style="color:red;"></p>

      </div>
      <div class="popup-content" id="reviewForm" style="display: none;" >
        <p id="reviewResponseMessage" style="color: red;"></p>
        <form method="POST" action="add_review.php" onsubmit="return validateReviewForm()">
        <p id="reviewResponseMessage" style="color: red;"></p>
          <input type="hidden"  name="user_id" value="<?php echo $user_id ?>">
          <input type="hidden" name="movie_id" value="<?php echo $id ?>">
          <textarea name="review" id="reviewInput" placeholder="Write your review here" rows="4" cols="50"></textarea>
          <div class="rate">
            <input type="radio" id="star5" name="rate" value="5" />
            <label for="star5" title="5 stars">5 stars</label>

            <input type="radio" id="star4" name="rate" value="4" />
            <label for="star4" title="4 stars">4 stars</label>

            <input type="radio" id="star3" name="rate" value="3" />
            <label for="star3" title="3 stars">3 stars</label>

            <input type="radio" id="star2" name="rate" value="2" />
            <label for="star2" title="2 stars">2 stars</label>

            <input type="radio" id="star1" name="rate" value="1" />
            <label for="star1" title="1 star">1 star</label>
          </div>
          <button id="reviewFormBtn" type="submit">Submit</button>
        </form>

        <?php if (isset($error)): ?>
          <p style="color:red;"><?php echo $error; ?></p>
        <?php endif; ?>
        <p id="responseMessage" style="color:red;"></p>

      </div>

    </div>
  </div>
  <script>
    var username = "<?php echo isset($_SESSION['username']) ? $_SESSION['username'] : ''; ?>";
    if (username) {
      console.log("User is logged in as: " + username);
    } else {
      console.log("User is not logged in.");
    }
  </script>
  <script src="script.js"></script>
</body>

</html>