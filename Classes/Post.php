<?php
/*
    update()
    delete()
    posts
    load_more
    show_posts()
    admin_posts
    createPost()
*/
class Post extends Db {
    public function __construct() {
        $this->con = $this->con();
    }
    private function startSession() {
        if(!isset($_SESSION)) { 
            ob_start();
            session_start(); 
        }
    }
    private function endSession() {
        if(isset($_SESSION)) { 
            session_unset();
            session_destroy();
        }
    }
    public function create() {  
        $this->startSession();      
        $author = $_POST['author'];
        $title = $_POST['title'];
        $content = $_POST['content'];
        $created_at = datetime_now() ;
        $post_status = 'public';
        $post_category = $_POST['post_category'];
        
        var_dump($author, $title, $content, $created_at, $post_status, $post_category);
        $thumbnail = $this->addPostImg('image');

        $stmt = $this->con->prepare("INSERT INTO posts(author, title, content, thumbnail, created_at, post_status, post_category) VALUES (?,?,?,?,?,?,?)");
        $stmt->bind_param('sssssss', $author, $title, $content, $thumbnail, $created_at, $post_status, $post_category);
        $stmt->execute();
        $stmt->close();
        // echo $status;
        header("location: ../admin/posts.php");
    }
    public function get_posts($orderBy, $direction, $limit=null, $post_status='public') {
        $posts_array = array();
        if($limit == null) {
            $stmt = $this->con->prepare("SELECT * FROM posts WHERE post_status=? ORDER BY $orderBy $direction");
            $stmt->bind_param('s', $post_status); 
        } else {
            $stmt = $this->con->prepare("SELECT * FROM posts WHERE post_status=? ORDER BY $orderBy $direction LIMIT $limit");
            $stmt->bind_param('s', $post_status); 
        } 
        
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        if(isset($data)) {
            if(count($data) > 0) {
                foreach($data as $row): 
                    $post_array = array(
                        'id' => $row['id'],
                        'author' => $row['author'],
                        'title' => $row['title'],
                        'summary' => segment($row['content']),
                        'content' => nl2br($row['content']),
                        'thumbnail' => $row['thumbnail'],
                        'created_at' => $row['created_at'],
                        'mjy' => convert_date($row['created_at'], 'M j Y'),
                        'length' => read_time($row['content']),
                        'elapsed' => elapsed($row['created_at']),
                        'post_category' => $row['post_category'],
                        'comments' => $row['comments'],
                        'views' => $row['views'],
                        'likes' => $row['likes'],
                    );
                    array_push($posts_array, $post_array);          
                endforeach;
            } 
        }
        $stmt->close();
        return $posts_array;
    }
    public function like($pid) {
        $this->startSession();
        $pid = intval($pid);
        /*
            1. Never liked
            2. Liked:               $_SESSION['like'] == '1'
            3. Liked and Unliked:   $_SESSION['like'] == '0'
        */
        if(!isset($_SESSION['like'.$pid])) {
            $stmt = $this->con->prepare("UPDATE posts SET likes=likes+1 WHERE id=?");
            $stmt->bind_param('i', $pid);
            if($stmt->execute()) {
                $_SESSION['like'.$pid] = '1';
            }
            $stmt->close();
        } else {
            if($_SESSION['like'.$pid] == '1') {
                $stmt = $this->con->prepare("UPDATE posts SET likes=likes-1 WHERE id=?");
                $stmt->bind_param('i', $pid);
                if($stmt->execute()) {
                    $_SESSION['like'.$pid] = '0';
                }
                $stmt->close();     
            } else {
                $stmt = $this->con->prepare("UPDATE posts SET likes=likes+1 WHERE id=?");
                $stmt->bind_param('i', $pid);
                if($stmt->execute()) {
                    $_SESSION['like'.$pid] = '1';
                }
                $stmt->close();
            }
        }
        $post_array = $this->get_single_post($pid);
        $likes = $post_array['likes']; 

        if($_SESSION['like'.$pid] == '1') {
            echo "<span id='like-{$post_array['id']}' class='like-{$post_array['id']}'>
                {$post_array['likes']}
            </span>
            <i class='fas fa-heart' onclick='like({$post_array['id']})'></i>";
            return;
        } else {
            echo "<span id='like-{$post_array['id']}' class='like-{$post_array['id']}'>
                {$post_array['likes']}
            </span>
            <i class='far fa-heart' onclick='like({$post_array['id']})'></i>";
            return;
        }
    }
    public function posts($orderBy, $direction, $limit=null, $post_status='public') {
        $posts_array = $this->get_posts($orderBy, $direction, $limit, $post_status);

        if(count($posts_array) > 0) {
            $posts = "";
            foreach($posts_array as $post_array):
                $posts .= "
                <div class='post post-summary'>
                    <div class='thumbnail'>
                        <a href=''>
                            <img src='./img/{$post_array['thumbnail']}' alt=''>
                        </a>
                        <div class='post-category'>
                            {$post_array['post_category']}
                        </div> 
                    </div>
                    <div class='post-content'>
                        <div class='post-header'>
                            <div class='author-photo'>
                                <img src='./img/original.jpg' alt=''>
                            </div>
                            <div class='post-details'>
                                <div class='post-author'>
                                    <span>{$post_array['author']}</span>
                                </div>
                                <div class='post-info'>
                                    <div class='post-date'></span>
                                        <span>{$post_array['mjy']}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a href=''>
                            <h3>
                                {$post_array['title']}
                            </h3>
                        </a>
                        <div class='post-main-content'>
                            <div id='post-brief-{$post_array['id']}' class='post-brief show'>{$post_array['summary']}</div>
                            <div id='post-full-{$post_array['id']}' class='post-full'>{$post_array['content']}</div>
                        </div>
                        <div class='post-footer'>
                            <span> <i class='far fa-eye'></i> {$post_array['views']}</span>
                            <span> <i class='far fa-comments'></i> {$post_array['comments']}</span>     
                            <span id='like-{$post_array['id']}' class='like'>
                                <i class='far fa-heart'></i> {$post_array['likes']}
                            </span>  
                        </div>
                        <div class='read-more-btn' id='{$post_array['id']}' onclick='readmore(this.id)'>
                            <span>Read More</span>
                            <img src='./img/arrow-right-long-to-line.svg'>
                        </div>
                    </div>
                </div>";
            endforeach;
        }

        return $posts;
    }
    public function admin_posts($orderBy, $direction, $limit=null, $post_status='public') {
        $posts_array = $this->get_posts($orderBy, $direction, $limit, $post_status);
        $posts = "";
        if(count($posts_array) > 0) {
            $posts .= "<div class='admin_posts_wrapper'>";
            foreach($posts_array as $post_array):
                $posts .= "<div class='post post_admin'>
                <div class='post_row'>
                    <div class='img_wrapper'>
                        <img src='../img/{$post_array['thumbnail']}' alt=''>
                    </div>
                    <div class='admin_post_title'>
                        <a href='../post-single?id={$post_array['id']}'>{$post_array['title']}</a>
                    </div>
                    <div class='post-short'>
                    </div>
                    <div class='upload_date'>{$post_array['elapsed']}</div>
                    <span class='admin_post-links'>
                        <a title='edit' class='edit-link' href='post-edit?id={$post_array['id']}'>Edit</a>
                        <a title='delete' onclick='return pop(this)' class='del-link' href='../controllers/post-handler?delete_post={$post_array['id']}'>Delete</a>
                    </span>
                </div>
            </div>";
            endforeach;
            $posts .= "</div>";
        }

        return $posts;
    }
    public function get_single_post($id) {   
        $id = intval($id);    
        $stmt = $this->con->prepare("SELECT * FROM posts WHERE id=? LIMIT 1");    
        $stmt->bind_param('i', $id);   
        $stmt->execute();    
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        if(isset($data)) {
            if(count($data) > 0) {
                foreach($data as $row):
                    $post_array = array(
                        'id' => $row['id'],
                        'author' => $row['author'],
                        'title' => $row['title'],
                        'content' => nl2br($row['content']),
                        'thumbnail' => $row['thumbnail'],
                        'created_at' => $row['created_at'],
                        'mjy' => convert_date($row['created_at'], 'M j Y'),
                        'length' => read_time($row['content']),
                        'elapsed' => elapsed($row['created_at']),
                        'post_category' => $row['post_category'],
                        'comments' => $row['comments'],
                        'views' => $row['views'],
                        'likes' => $row['likes']
                    );          
                endforeach;
            } 
        } 
        $stmt->close();
        return $post_array;
    }
    public function single_post($pid) { 
        $post_array = $this->get_single_post($pid);

        $slug = "post-single?id=$pid";
        $title = "{$post_array['title']}";
        $content = strip_tags($post_array['content']);
        $content = substr($content, 0, 250);

        var_dump($slug);

        if(isset($_SESSION['like'.$pid])) {
            if($_SESSION['like'.$pid] == '1') {
                $likes = "<span id='like-{$post_array['id']}' class='like-{$post_array['id']}'>
                    {$post_array['likes']}
                </span>
                <i class='fas fa-heart' onclick='like({$post_array['id']})'></i>";
            } else {
                $likes = "<span id='like-{$post_array['id']}' class='like-{$post_array['id']}'>
                    {$post_array['likes']}
                </span>
                <i class='far fa-heart' onclick='like({$post_array['id']})'></i>";
            }
        } else {
            $likes = "<span id='like-{$post_array['id']}' class='like-{$post_array['id']}'>
                {$post_array['likes']}
            </span>
            <i class='far fa-heart' onclick='like({$post_array['id']})'></i>";
        }
        $popupId = 'link_popup';

        // Url and Slug for social media sharing
        if($_SERVER['SERVER_NAME'] == 'smartmoneymovement.test') {
            $baseurl = 'https://smartmoneymovement.test/';
        } else {
            $baseurl = 'https://smartmoneymovement.us/';
        }
        

        $post = "<div class='post post-single'>
            <div class='post-content'>
                <div class='post-header'>
                    <div class='author-photo'>
                        <img src='./img/{$post_array['author_img']}' alt=''>
                    </div>
                    <div class='post-details'>
                        <div class='post-author'>
                            <span>{$post_array['author']}</span>
                        </div>
                        <div class='post-info'>
                            <div class='post-date'></span>
                                <span>{$post_array['mjy']}</span>
                            </div>
                            <div class='post-length'>
                                <span>{$post_array['length']} min</span>
                            </div>
                        </div>
                    </div>
                </div>
                <h3>
                    {$post_array['title']}
                </h3>
                <div class='post-main-content'>
                    {$post_array['content']}
                </div>

                
                <div class='share'>

                    <div class='share-btn facebook'>
                        <a target='_blank' class='icon-facebook-share' title='Share this on Facebook' href='http://facebook.com/share.php?u=$baseurl$slug'>
                            
                        </a>
                    </div>
                    <div class='share-btn twitter'>
                        <a target='_blank' class='icon-twitter-share' title='Share this on Twitter' href='http://twitter.com/share?text=$title&url=$baseurl$slug'>
                            
                        </a>
                    </div>

                    
                    <div class='share-btn link'>
                        <a class='icon-link-share' title='Copy this link' onclick=popup(`$popupId`)>
                            
                        </a>
                    </div>
                </div>
                <div class='post-footer'>
                    <span class='views_comments'>
                        <span>{$post_array['views']} views</span>
                        <span>{$post_array['comments']} comments</span>
                    </span>

                    <span class='likes single-post-likes likes-{$post_array['id']}'> 
                        $likes
                    </span>

                </div>
            </div>
        </div>";
        return $post;

        // <div class='share-btn linkedin'>
        //     <a target='_blank' class='icon-linkedin-share' href='http://www.linkedin.com/shareArticle?mini=true&url=$baseurl$slug&title=$title&summary=$content&source=smartmoneymovement.us'>
                    
        //     </a>
        // </div>

    }
    private function addPostImg($n) {
        // $img = $_FILES['image']['name'];
        $image = $_FILES[$n]['name'];
        // CHECK IF INPUT IS EMPTY
        if(!empty($image)) {
            $allowed = array('png', 'jpg', 'jpeg', 'webp');
            $ext = pathinfo($image, PATHINFO_EXTENSION);
            // CHECK IF FILE TYPE IS ALLOWED
            if (!in_array($ext, $allowed)) {
                $staus = '2';
                // echo '<span class=errorMsg>Incorrect File Type</span>';
                exit();
            } else {
                $imagePath = '../img/';
                $uniquesavename = intval(time().uniqid(rand(10, 20)));
                $destFile = $imagePath . $uniquesavename . '.'.$ext;
                $tempname = $_FILES[$n]['tmp_name'];
                move_uploaded_file($tempname,  $destFile);
                $img = $uniquesavename . '.'.$ext;
            }
        } else {
            $img = '';
        }
        return $img;
    }
    public function delete($id) {
        // Get old filename
        $stmt = $this->con->prepare("SELECT * FROM posts WHERE id=?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->execute();    
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        if(isset($data)) {
            if(count($data) > 0) {
                foreach($data as $row): 
                    $old_thumbnail = $row['thumbnail'];
                endforeach;
            }
        }
        $stmt->close();

        // Delete old image
        $this->del_thumbnail($old_thumbnail, $id);

        $stmt = $this->con->prepare("DELETE FROM posts WHERE id=?");
        $stmt->bind_param('i', $id);
        if($stmt->execute()) {
            $status = '1';
        } else {
            $status = '0';
        }
        $stmt->close();
        // return $status;
        header('location: ../admin/posts');
    }
    public function update($id) {
        $this->startSession(); 
        $id = intval($id);
        $title = $_POST['title'];
        $post_category = $_POST['post_category'];
        $content = $_POST['content'];
        $thumbnail = $this->addPostImg('image');
        if(empty($thumbnail)) {
            $stmt = $this->con->prepare("UPDATE posts SET title=?, content=?, post_category=? WHERE id=?");
            $stmt->bind_param('sssi', $title, $content, $post_category, $id);
        } else {
            $stmt = $this->con->prepare("UPDATE posts SET title=?, content=?, thumbnail=?, post_category=? WHERE id=?");
            $stmt->bind_param('ssssi', $title, $content, $thumbnail, $post_category, $id);
        }

        if($stmt->execute()) {
            $status = '1';
        } else {
            $status = '0';
        }    
        $stmt->close();
        // echo $status;
        header("location: ../admin/posts.php");
    }
    public function increase_view_count($id) {
        $id = intval($id);
        $stmt = $this->con->prepare("UPDATE posts SET views=views+1 WHERE id=?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->close();
    }
    public function trash($id) {
        $post_status = 'deleted';
        $stmt = $this->con->prepare("UPDATE posts SET post_status=? WHERE id=?");
        $stmt->bind_param('si', $post_status, $id);        
        if($stmt->execute()) {
            $status = '1';
        } else {
            $status = '0';
        }    
        $stmt->close();
        echo $status;
    }
    public function del_thumbnail($thumbnail, $pid) {
        unlink("../img/$thumbnail");
        $stmt = $this->con->prepare("UPDATE posts SET thumbnail=NULL WHERE id=?");
        $stmt->bind_param('i', $pid);
        $stmt->execute();
        $stmt->close();
        header("location: ../admin/post-edit?id=$pid");
    }
    public function createForm() {
        // onsubmit='return addPost(event)'
        return "<form autocomplete='off' action='../controllers/post-handler' method='POST' class='post-form add-post-form' id='add-post-form'  enctype='multipart/form-data'>
            <input type='hidden' name='create_post' value='true'>
            <input type='hidden' name='author' value='Author Name'>
            <div class='input-col input-col-2'>
                <label for='title'>Title: </label>
                <input type='text' name='title' id='title'>
            </div>
            <div class='input-col input-col-2'>
                <label for='post_category'>Post Category: </label>
                <input type='text' name='post_category' id='post_category'>
            </div>
            <div class='form-group'>
                <textarea name='content' id='content' class='content'></textarea>
            </div> 
            <input class='input' id='image' type='file' name='image' value='' style='display: none;'>
            <span style='cursor:pointer;' onclick='return fireButton(event);'>
                <i class='fas fa-paperclip'></i>
                <span>Thumbnail</span>
                <span id='image-name-1'></span>
            </span> 
            <div class='btns-wrapper admin-form-btns'>
                <span class='cancel'>
                    <a class='cancel' href='./posts'>Cancel</a>
                </span>
                <button type=submit class='btn'>Create</button>
            </div>
            <div id='msg-response'></div>
        </form>";
    }
    public function editForm($id) {
        // onsubmit='return updatePost(event)'
        $post_array = $this->get_single_post($id);
        
        $title = $post_array['title'];
        $content = $post_array['content'];
        $thumbnail = $post_array['thumbnail'];
        $post_category = $post_array['post_category'];

        if(!empty($thumbnail)) {
            $img_str = "<div style='width:300px;height:200px;'>
                    <img style='width:100%;height:100%;object-fit:cover;' src='../img/$thumbnail' alt=''>
                </div>
                <div>
                    <a title='delete' class='del-link' href='../controllers/post-handler?pid=$id&del=$thumbnail'>Delete</a>
                </div>";
        } else {
            $img_str = "";
        }
        return "<form autocomplete='off' action='../controllers/post-handler' method='POST' class='post-form edit-post-form' id='edit-post-form' enctype='multipart/form-data'>
            <input type='hidden' name='update_post' value='true'>
            <input type='hidden' name='post_id' value='$id'>
            <div class='input-col input-col-2'>
                <label for='title'>Title: </label>
                <input type='text' name='title' id='title' value='$title' placeholder='Title for this post'>
            </div>
            <div class='input-col input-col-2'>
                <label for='post_category'>Post Category: </label>
                <input type='text' name='post_category' id='post_category' value='$post_category'>
            </div>
            <div class='form-group'>
                <textarea name='content' id='content' class='content'>$content</textarea>
            </div>
            $img_str
            <input class='input' id='image' type='file' name='image' value='' style='display: none;'>
            <span style='cursor:pointer;' onclick='return fireButton(event);'>
                <i class='fas fa-paperclip'></i>
                <span>Thumbnail</span>
                <span id='image-name-1'></span>
            </span>
            <div class='btns-wrapper admin-form-btns'>
                <span class='cancel'>
                    <a class='cancel' href='./posts'>Cancel</a>
                </span>
                <button type=submit class='btn'>Update</button>
            </div>
            <div id='msg-response'></div>
        </form>";
    }
    public function load_more($orderBy, $direction, $limit=null, $post_status='public') {
        $posts_array = $this->get_posts($orderBy, $direction, $limit, $post_status);
        // echo count($posts_array);
        $posts = "";
        if(count($posts_array) > 0) {
            

            foreach($posts_array as $post_array):
                $posts .= "
                <div class='post post-summary'>
                    <div class='thumbnail'>
                        <a href=''>
                            <img src='./img/{$post_array['thumbnail']}' alt=''>
                        </a>
                        <div class='post-category'>
                            {$post_array['post_category']}
                        </div> 
                    </div>
                    <div class='post-content'>
                        <div class='post-header'>
                            <div class='author-photo'>
                                <img src='./img/original.jpg' alt=''>
                            </div>
                            <div class='post-details'>
                                <div class='post-author'>
                                    <span>{$post_array['author']}</span>
                                </div>
                                <div class='post-info'>
                                    <div class='post-date'></span>
                                        <span>{$post_array['mjy']}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a href=''>
                            <h3>
                                {$post_array['title']}
                            </h3>
                        </a>
                        <div class='post-main-content'>
                            <div id='post-brief-{$post_array['id']}' class='post-brief show'>{$post_array['summary']}</div>
                            <div id='post-full-{$post_array['id']}' class='post-full'>{$post_array['content']}</div>
                        </div>
                        <div class='post-footer'>
                            <span> <i class='far fa-eye'></i> {$post_array['views']}</span>
                            <span> <i class='far fa-comments'></i> {$post_array['comments']}</span>     
                            <span id='like-{$post_array['id']}' class='like'>
                                <i class='far fa-heart'></i> {$post_array['likes']}
                            </span>  
                        </div>
                        <div class='read-more-btn' id='{$post_array['id']}' onclick='readmore(this.id)'>
                            <span>Read More</span>
                            <img src='./img/arrow-right-long-to-line.svg'>
                        </div>
                    </div>
                </div>";
            endforeach;
        }

        return $posts;
        // June 7, 2022
        // Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore.
    }
    public function load_btn() {
        return "<span class='btn load-more'>
            Load More
        </span>";
    }
}

?>