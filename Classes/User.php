<?php
/*
    show_user_profile()
*/
class User extends Db {
    public function __construct() {
        $this->con = $this->con();
    }
    public function startSession() {
        ob_start();
        session_start();
    }
    public function endSession() {
        session_unset();
        session_destroy();
    }
    public function get_uid() {
        if(isset($_SESSION['user'])) {
            $userdata = json_decode($_SESSION['user'], true);
            $uid = $userdata['uid'];
            return $uid;
        }
    }     
    public function is_logged_in() {
        if(isset($_SESSION['user'])) {
            $userdata = json_decode($_SESSION['user'], true);
            $logged = $userdata['logged'];
            return $logged;
        }
    }
    public function get_user_status() {
        if(isset($_SESSION['user'])) {
            $userdata = json_decode($_SESSION['user'], true);
            $user_status = $userdata['user_status'];
            return $user_status;
        }
    }
    public function get_account_status() {
        if(isset($_SESSION['user'])) {
            $userdata = json_decode($_SESSION['user'], true);
            $account_status = $userdata['account_status'];
            return $account_status;
        }
    }   
    public function get_user_email() {
        if(isset($_SESSION['user'])) {
            $userdata = json_decode($_SESSION['user'], true);
            $email = $userdata['email'];
            return $email;
        }
    }  
    public function get_user_img() {
        if(isset($_SESSION['user'])) {
            $userdata = json_decode($_SESSION['user'], true);
            $user_img = $userdata['user_img'];
            if(empty($user_img)) {
                $user_img = 'avi.png';
            }
            return $user_img ;
        }
    }
    public function login() {
        $this->startSession();

        $email = $_POST['email'];
        $password = $_POST['password'];     

        $stmt = $this->con->prepare("SELECT * FROM users WHERE email=? LIMIT 1");
        $stmt->bind_param('s', $email);
        $stmt->execute();        
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        if(isset($data)) {
            if(count($data) > 0) {
                foreach($data as $row):   
                    $hash = trim($row['pwd']);
                    if(password_verify($password, $hash)) {
                    // if($password === trim($row['pwd'])) {
                        if($row['account_status'] == 'active') {
                            $userdata = array(
                                'logged' => '1',
                                'uid' => $row['id'],
                                'email' => $row['email'],
                                'user_status' => $row['user_status'],
                                'account_status' => $row['account_status']
                            );
                            $_SESSION['user'] = json_encode($userdata, true);
                            setcookie("uid", $row['id'], time() + (10 * 365 * 24 * 60 * 60));
                            if($row['user_status'] == 'admin') {
                                $status = '1';
                            } else if ($row['user_status'] == 'member') {
                                $status = '2';
                            } else {
                                $status = '5';
                            }
                        } else {
                            $status = '6';
                        }
                    } else {
                        $status = '3';
                    }
                endforeach;
            } else {
                $status = '4';
            }  
        } else {
            $status = '4';
        }       
        $stmt->close();
        echo $status;
    }
    public function logout() {
        $this->startSession();
        $this->endSession();
        header('location: ../'); 
    }
    public function check_user_session() {
        $uriArray = explode('/', $_SERVER['REQUEST_URI']);
        $folder = $uriArray[1];
        /*
            1. Folder is admin but user not logged in
            2. User is logged in but not admin
        */
        if($folder == 'admin') {
            if(!isset($_SESSION['user'])) {
                header('location: ../');
                exit();
            } else {
                $userdata = json_decode($_SESSION['user'], true);
                if($userdata['user_status'] != 'admin') {
                    header('location: ../');
                    exit();
                }
            }
        }    
        
    }
    public function create() {
        $this->startSession();
  
        $email = $_POST['email'];  
        
        $duplicate = $this->duplicate_email($email);
        if($duplicate == '1') {
            $status = '2';
        } else {
            $password = $_POST['password'];      
            $user_status = 'member';
            $account_status = 'pending';
            $created_at = convert_date('now', 'Y-m-d H:i:s');
            $updated_at = $created_at;

            // var_dump($email, $password, $user_status, $created_at, $updated_at);
    
            $stmt = $this->con->prepare("INSERT INTO users(email, pwd, user_status, account_status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $email, $password, $user_status, $account_status, $created_at, $updated_at);
            if($stmt->execute()) {
                $status = '1';
            } else {
                $status = '0';
            }
            $stmt->close();
        }
        echo $status;
    }
    public function get_user($id) {
        $user_array = array();

        $stmt = $this->con->prepare("SELECT * FROM users WHERE id=? LIMIT 1");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        if(isset($data)) {
            if(count($data) > 0) {
                foreach($data as $row): 
                    $user_array = array(
                        'id' => $row['id'],
                        'email' => $row['email'],
                        'photo' => $row['photo'],
                        'user_status' => $row['user_status'],
                        'pwd' => $row['pwd'],
                        'created_at' => $row['created_at'],
                        'updated_at' => $row['updated_at']
                    );
                endforeach;
            }    
        }
        $stmt->close();
        return $user_array;
    }
}