<?php

class Post extends User
{
    function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function posts($user_id, $profileId, $num)
    {

        $userdata = $this->userData($user_id);

        $stmt = $this->pdo->prepare("SELECT * FROM users LEFT JOIN profile ON users.user_id = profile.userId LEFT JOIN post ON post.userId = users.user_id WHERE post.userId = :user_id ORDER BY post.postedOn DESC LIMIT :num");

        $stmt->bindParam(":user_id", $profileId, PDO::PARAM_INT);
        $stmt->bindParam(":num", $num, PDO::PARAM_INT);
        $stmt->execute();
        $posts = $stmt->fetchAll(PDO::FETCH_OBJ);

        foreach ($posts as $post) {
?>
            <div class="profile-timeline">
                <div class="news-feed-comp">
                    <div class="news-feed-text">
                        <div class="nf-1">
                            <div class="nf-1-left">
                                <div class="nf-pro-pic">
                                    <a href="<?php echo BASE_URL . $post->userLink; ?>"></a>
                                    <img src="<?php echo BASE_URL . $post->profilePic ?>" alt="" class="pro-pic" />
                                </div>
                                <div class="nf-pro-name-time">
                                    <div class="nf-profile-name">
                                        <a href="<?php echo BASE_URL . $post->userLink; ?>" class="nf-pro-name"><?php echo '' . $post->firstName . ' ' . $post->lastName . ''; ?></a>
                                    </div>
                                    <div class="nf-profile-time-privacy">
                                        <div class="nf-pro-time">
                                            <?php echo $this->timeAgo($post->postedOn); ?>
                                        </div>
                                        <div class="nf-pro-privacy"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="nf-1-right">
                                <div class="nf-1-right-dott">
                                    <div class="post-option" data-postid="<?php echo $post->post_id; ?>" data-userid="<?php echo $post->user_id; ?>">...</div>
                                    <div class="post-option-details-container"></div>
                                </div>
                            </div>
                        </div>
                        <div class="nf-2">
                            <div class="nf-2-text" data-postid="<?php echo $post->post_id; ?>" data-userid="<?php echo $post->user_id; ?>" data-profilePic="<?php echo $post->profilePic; ?>">
                                <?php echo $post->post; ?>
                            </div>
                            <div class="nf-2-img" data-postid="<?php echo $post->post_id; ?>" data-userid="<?php echo $post->user_id; ?>">
                                <?php
                                $imgJson = json_decode($post->postImage);
                                $count = 0;

                                for ($i = 0; $i < count((array)$imgJson); $i++) {
                                    echo ' <div class="post-img-box" data-postImgID="' . $post->id . '" style="max-height:400px;overflow:hidden;"><img src="' . BASE_URL . $imgJson['' . $count++ . '']->imageName . '" class="postImage" style="width:100%;cursor:pointer;" /></div>';
                                }
                                ?>
                            </div>
                        </div>
                        <div class="nf-3">

                        </div>
                        <div class="nf-4">
                            <div class="like-action-wrap">
                                <div class="like-action ra">
                                    <div class="like-action-icon">
                                        <img src="assets/image/likeAction.JPG" alt="">
                                    </div>
                                    <div class="like-action-text"><span>Like</span></div>
                                </div>
                            </div>
                            <div class="comment-action ra">
                                <div class="comment-action-icon">
                                    <img src="assets/image/commentAction.JPG" alt="">
                                </div>
                                <div class="comment-action-text">
                                    <div class="comment-count-text-wrap">
                                        <div class="comment-wrap"></div>
                                        <div class="comment-text">Comment</div>
                                    </div>
                                </div>
                            </div>
                            <div class="share-action ra" data-postid="<?php echo $post->post_id; ?>" data-userid="<?php echo $post->user_id; ?>" data-profilePic="<?php echo $post->profilePic; ?>">
                                <div class="share-action-icon">
                                    <img src="assets/image/shareAction.JPG" alt="">
                                </div>
                                <div class="share-action-text">Share</div>
                            </div>

                        </div>
                        <div class="nf-5"></div>
                    </div>
                    <div class="news-feed-photo"></div>

                </div>
            </div>
<?php
        }
    }
}

?>