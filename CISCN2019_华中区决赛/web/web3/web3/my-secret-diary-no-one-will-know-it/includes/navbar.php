
<div class="ui inverted menu">
    <div class="ui container">
      <a href="#" class="header item">
        Guestbook
      </a>
      <a href="?" class="item">Home</a>
      <div class="right menu">
        <a href="?action=pages/index&method=logout" class="item">Logout</a>
        <a href="?action=pages/profile" class="item"><img src="<?php echo $user['avatar'];?>" style="width:20px;height:20px;" alt="avatar"> <?php echo htmlspecialchars($user['username']); ?></a>
      </div>
    </div>
  </div>
