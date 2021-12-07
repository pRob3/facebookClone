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

            $main_react = $this->main_react($user_id, $post->post_id);
            $react_max_show = $this->react_max_show($post->post_id);
            $main_react_count = $this->main_react_count($post->post_id);

            $commentDetails = $this->commentFetch($post->post_id);
            $totalCommentCount = $this->totalCommentCount($post->post_id);
            // $totalShareCount = $this->totalShareCount($post->post_id);
            // if(empty($post->shareId)){}else{
            //     $shareDetails = $this->shareFetch($post->shareId, $post->postBy);
            // }            

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
                                    echo ' <div class="post-img-box" data-postImgID="' . $post->id . '" style="max-height:400px;overflow:hidden;"><img src="' . BASE_URL . $imgJson['' . $count++ . '']->imageName . '" class="postImage" style="width:100%;cursor:pointer;" data-postid="' . $post->post_id . '"  data-profileid="' . $profileId . '" data-userid="' . $user_id . '" /></div>';
                                }
                                ?>
                            </div>
                        </div>
                        <div class="nf-3">
                            <div class="react-comment-count-wrap" style="width: 100%;display: flex;justify-content: space-between; align-items: center;">
                                <div class="react-count-wrap align-middle">
                                    <div class="nf-3-react-icon">
                                        <div class="react-inst-img align-middle">
                                            <?php
                                            foreach ($react_max_show as $react_max) {
                                                echo '<img class = "' . $react_max->reactType . '-max-show" src="assets/image/react/' . $react_max->reactType . '.png" alt="" style="height:15px; width:15px; margin-right:2px; cursor:pointer;">';
                                            }

                                            ?>
                                        </div>
                                    </div>
                                    <div class="nf-3-react-username">
                                        <?php
                                        if ($main_react_count->maxreact == '0') {
                                        } else {
                                            echo $main_react_count->maxreact;
                                        }

                                        ?>
                                    </div>
                                </div>
                                <div class="comment-share-count-wrap align-middle" style="font-size: 13px;color: grey;">
                                    <div class="comment-count-wrap">
                                        <?php if (empty($totalCommentCount->totalComment)) {
                                        } else {
                                            echo '' . $totalCommentCount->totalComment . ' comments';
                                        } ?>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="nf-4">
                            <div class="like-action-wrap" data-postid="<?php echo $post->post_id; ?>" data-userid="<?php echo $user_id; ?>" style="position: relative;">
                                <div class="react-bundle-wrap">

                                </div>
                                <div class="like-action ra">
                                    <?php if (empty($main_react)) {
                                    ?>
                                        <div class="like-action-icon">
                                            <img src="assets/image/likeAction.JPG" alt="">
                                        </div>
                                        <div class="like-action-text"><span>Like</span></div>

                                    <?php
                                    } else { ?>

                                        <div class="like-action-icon" style="display: flex;align-items: center;">
                                            <img src="assets/image/react/<?php echo $main_react->reactType; ?>.png" alt="" class="reactIconSize" style="  margin-top: 0;">
                                            <div class="like-action-text"><span class="<?php echo $main_react->reactType; ?>-color"><?php echo $main_react->reactType; ?></span></div>
                                        </div>

                                    <?php
                                    } ?>

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
                        <div class="nf-5">
                            <div class="comment-list">
                                <ul class="add-comment">
                                    <?php
                                    // echo $post->post_id;
                                    // echo $user_id;

                                    if (!empty($commentDetails)) {
                                        foreach ($commentDetails as $comment) {
                                            $com_react_max_show = $this->com_react_max_show($comment->commentOn, $comment->commentID);
                                            $com_main_react_count = $this->com_main_react_count($comment->commentOn, $comment->commentID);
                                            $commentReactCheck = $this->commentReactCheck($user_id, $comment->commentOn, $comment->commentID);

                                                ?>
                                            <li class="new-comment">
                                                <div class="com-details">
                                                    <div class="com-pro-pic">
                                                        <a href="#">
                                                            <span class="top-pic"><img src="<?php echo $comment->profilePic; ?>" alt=""></span>
                                                        </a>
                                                    </div>
                                                    <div class="com-pro-wrap">
                                                        <div class="com-text-react-wrap">
                                                            <div class="com-text-option-wrap align-middle">
                                                                <div class="com-pro-text align-middle">
                                                                    <div class="com-react-placeholder-wrap align-middle">
                                                                        <div>
                                                                            <span class="nf-pro-name">
                                                                                <a href="" class="nf-pro-name"><?php echo '' . $comment->firstName . ' ' . $comment->lastName . '' ?></a>
                                                                            </span>
                                                                            <span class="com-text" style="margin-left: 5px;" data-postid="<?php echo $comment->commentOn; ?>" data-userid="<?php echo $user_id; ?>" data-commentid="<?php echo $comment->commentID; ?>" data-profilepic="<?php echo $userdata->profilePic; ?>"><?php echo $comment->comment; ?></span>
                                                                        </div>
                                                                        <div class="com-nf-3-wrap">
                                                                            <?php

                                                                            if ($com_main_react_count->maxreact == '0') {
                                                                            } else {
                                                                            ?>
                                                                                <div class="com-nf-3 align-middle">
                                                                                    <div class="nf-3-react-icon">
                                                                                        <div class="react-inst-img align-middle">
                                                                                            <?php
                                                                                            foreach ($com_react_max_show as $react_max) {
                                                                                                echo '<img class="' . $react_max->reactType . '-max.show" src="assets/image/react/' . $react_max->reactType . '.png" alt="" style="height:12px;width:12px;margin-right:2px;cursor:pointer;" />';
                                                                                            }

                                                                                            ?>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="nf-3-react-username">
                                                                                        <?php
                                                                                        if ($com_main_react_count->maxreact == '0') {
                                                                                        } else {
                                                                                            echo $main_react_count->maxreact;
                                                                                        }
                                                                                        ?>
                                                                                    </div>
                                                                                </div>

                                                                            <?php
                                                                            }

                                                                            ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <?php
                                                                if ($user_id == $comment->commentBy) {
                                                                ?>
                                                                    <div class="com-dot-option-wrap">
                                                                        <div class="com-dot" style="color:gray; margin-left:5px; cursor:pointer;font-weight:600;" data-postid="<?php echo $comment->commentOn; ?>" data-userid="<?php echo $user_id; ?>" data-commentid="<?php echo $comment->commentID; ?>">...</div>
                                                                        <div class="com-option-details-container">

                                                                        </div>
                                                                    </div>

                                                                <?php
                                                                } else {
                                                                }
                                                                ?>
                                                            </div>
                                                            <div class="com-react">
                                                                <div class="com-like-react" data-postid="<?php echo $comment->commentOn; ?>" data-userid="<?php echo $user_id; ?>" data-commentid="<?php echo $comment->commentID; ?>">
                                                                    <div class="com-react-bundle-wrap" data-commentid="<?php echo $comment->commentID; ?>"></div>
                                                                    <?php
                                                                    if (empty($commentReactCheck)) {
                                                                        echo '<div class="com-like-action-text"><span>Like</span></div>';
                                                                    } else {
                                                                        echo '<div class="com-like-action-text"><span class="' . $commentReactCheck->reactType . '-color">' . $commentReactCheck->reactType . '</span></div>';
                                                                    }
                                                                    ?>
                                                                </div>
                                                                <div class="com-reply-action" data-postid="<?php echo $comment->commentOn; ?>" data-userid="<?php echo $user_id; ?>" data-commentid="<?php echo $comment->commentID; ?>" data-profilepic="<?php echo $userdata->profilePic; ?>">
                                                                    Reply
                                                                </div>
                                                                <div class="com-time">
                                                                    <?php echo $this->timeAgo($comment->commentAt); ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>

                                    <?php
                                        }
                                    } ?>

                                </ul>
                            </div>
                            <div class="comment-write">
                                <div class="com-pro-pic" style="margin-top: 4px;">
                                    <a href="#">
                                        <div class="top-pic"><img src="<?php echo $userdata->profilePic; ?>" alt=""></div>
                                    </a>
                                </div>
                                <div class="com-input">
                                    <div class="comment-input" style="flex-basis: 90%;">
                                        <input type="text" name="" id="" class="comment-input-style comment-submit" placeholder="Write a comment..." data-postid="<?php echo $post->post_id; ?>" data-userid="<?php echo $user_id; ?>">
                                    </div>
                                </div>
                            </div>



                        </div>
                    </div>
                    <div class="news-feed-photo"></div>

                </div>
            </div>
<?php
        }
    }

    public function postUpd($user_id, $post_id, $editText)
    {
        $stmt = $this->pdo->prepare('UPDATE post SET post = :editText WHERE post_id=:post_id AND userId = :user_id');
        $stmt->bindParam(":post_id", $post_id, PDO::PARAM_INT);
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        $stmt->bindParam(":editText", $editText, PDO::PARAM_STR);
        $stmt->execute();
    }

    public function main_react($userid, $postid)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `react` WHERE `reactBy` = :user_id AND `reactOn` = :postid AND `reactCommentOn` = '0' AND `reactReplyOn` = '0' ");
        $stmt->bindParam(":user_id", $userid, PDO::PARAM_INT);
        $stmt->bindParam(":postid", $postid, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function react_max_show($postid)
    {
        $stmt = $this->pdo->prepare("SELECT reactType, count(*) as maxreact from react WHERE reactOn = :postid AND reactCommentOn = '0' AND reactReplyOn = '0' GROUP BY reactType LIMIT 3");
        $stmt->bindParam(":postid", $postid, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function main_react_count($postid)
    {
        $stmt = $this->pdo->prepare("SELECT count(*) as maxreact from react WHERE reactOn = :postid AND reactCommentOn = '0' AND reactReplyOn = '0'");
        $stmt->bindParam(":postid", $postid, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function commentFetch($postid)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM comments INNER JOIN profile ON comments.commentBy = profile.userId WHERE comments.commentOn = :postid AND comments.commentReplyID = '0' LIMIT 10");
        $stmt->bindParam(":postid", $postid, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function totalCommentCount($postid)
    {
        $stmt = $this->pdo->prepare("SELECT count(*) as totalComment FROM comments WHERE comments.commentOn =:postid");
        $stmt->bindParam(":postid", $postid, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function com_react_max_show($postid, $commentid)
    {
        $stmt = $this->pdo->prepare("SELECT reactType, count(*) as maxreact FROM react WHERE reactOn = :postid AND reactCommentOn = :commentID AND reactReplyOn = '0' GROUP BY reactType LIMIT 3");
        $stmt->bindParam(":postid", $postid, PDO::PARAM_INT);
        $stmt->bindParam(":commentID", $commentid, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function com_main_react_count($postid, $commentid)
    {
        $stmt = $this->pdo->prepare("SELECT count(*) as maxreact FROM react WHERE reactOn = :postid AND reactCommentOn = :commentID AND reactReplyOn = '0' ");
        $stmt->bindParam(":postid", $postid, PDO::PARAM_INT);
        $stmt->bindParam(":commentID", $commentid, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function lastCommentFetch($commentid){
        $stmt = $this->pdo->prepare("SELECT * FROM comments INNER JOIN profile ON comments.commentBy = profile.userId WHERE comments.commentID = :commentid");
        $stmt->bindParam(":commentid", $commentid, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    public function commentReactCheck($userid, $postid, $commentid)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM react WHERE reactBy = :userid AND reactOn = :postid AND reactCommentOn = :commentid and reactReplyOn = '0' ");
        $stmt->bindParam(":userid", $userid, PDO::PARAM_INT);
        $stmt->bindParam(":postid", $postid, PDO::PARAM_INT);
        $stmt->bindParam(":commentid", $commentid, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
}

?>