<?php
    /*
        create()
        getContacts()
        showContacts()
    */
    class Contact extends Db {
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
        public function create() {
            $fname = $_POST['fname'];
            $lname = $_POST['lname'];
            $email = $_POST['email'];
            $msg = $_POST['msg'];            
            $created_at = datetime_now();
            
            $stmt = $this->con->prepare("INSERT INTO contacts (fname, lname, email, msg, created_at) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $fname, $lname, $email, $msg, $created_at);
            if($stmt->execute()) {
                $status = "1";
            } else {
                $status = "0";
            }
            $stmt->close();
            echo $status;
        }
        private function getContacts() {
            $contacts_array = array();
            $stmt = $this->con->prepare("SELECT * FROM contacts ORDER BY id DESC");
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_all(MYSQLI_ASSOC);

            if(count($data) > 0) {
                foreach($data as $row):
                    $contact_array = array(
                        "id" => $row['id'],
                        "fname" => $row['fname'],
                        "lname" => $row['lname'],
                        "email" => $row['email'],
                        "msg" => $row['msg'],
                        "created_at" => $row['created_at']
                    );
                    array_push($contacts_array, $contact_array);
                endforeach;
            }
            $stmt->close();
            return $contacts_array;
        }
        public function showContacts() {
            $contacts_array = $this->getContacts();
            $contactsStr = "";

            $num_of_rows = count($contacts_array);
            $results_per_page = 50;
            // Number of total pages available
            $num_of_pages = ceil($num_of_rows/$results_per_page);
            // var_dump($num_of_pages);
            // Determine which page user is currently on
            if(!isset($_GET['page'])) {
                $page = 1;
            } else {
                $page = intval($_GET['page']);
            }
            $starting_limit_number = ($page-1)*$results_per_page;
            /*
            $contactsStr = "<div class='contacts'>
                <div class='contacts-inner'>
                    <div class='searchWrapper'>
                        <form action='./contacts-search?page=1' method='post'>
                            <div class='search'>
                                <div class='form-group'>
                                    <input name='search-content' id='search-content' class='search-content' type='text'>
                                    <button name='submit' class='search-btn' id='search-btn' type='submit'></button>
                                </div>
                            </div>
                        </form>
                    </div>";
                    */
            // var_dump($starting_limit_number, $results_per_page , $page);
            for($x=$starting_limit_number; $x<$starting_limit_number+$results_per_page; $x++) {
                if($x < $num_of_rows) {
                    $contact_array = $contacts_array[$x];
                    $msg = nl2br($contact_array['msg']);
    
                    // Date and time
                    $date = new DateTime($contact_array['created_at'], new DateTimeZone('Europe/Dublin') );
                    $MjYDate = $date->format("M j, Y");
                    $time = $date->format("H:i:s");
    
                    $contactsStr .= "
                    <div class='contact'>
                        <div class='contact-header'>
                            <div class='contact-col-1'>
                                <div>
                                    Name: {$contact_array['fname']} {$contact_array['lname']}
                                </div>
                                <div>Email: {$contact_array['email']}</div>
                            </div>
                            <div class='contact-col-2'>
                                <div>$MjYDate</div>
                                <div>$time</div>
                            </div>
    
                        </div>
                        <div class='contact-body'>
                            <div>$msg</div>
                            <a style='display: grid; margin-top: 20px;' title='delete' onclick='return pop(this)' class='del-link' href='../controllers/contact-handler?delete_contact={$contact_array['id']}'>Delete</a>
                        </div>
                    </div>";
                }
            }
            $contactFooter = "<div style='display:flex;justify-content:center;'>
                <div></div>
            <div style='margin:20px 0;display:flex;flex-flow:row nowrap;column-gap:5px;'>";
            for($p=1;$p<=$num_of_pages;$p++) {
                if($page == $p) {
                    $contactFooter .= "<a style='border:1px solid #b2b2b2;color:gray; font-size:14px;padding:10px 15px;background-color:rgb(240, 240, 240);' href='./contacts?page=".$p."'>".$p."</a> ";
                } else {
                    if($p == $page-1) {
                        $contactFooter .= "<a style='color:#000; font-size:14px;padding:10px 15px;' href='./contacts?page=".$p."'>Previous.</a> ";
                    } elseif($p == $page+1) {
                        $contactFooter .= "<a style='color:#000; font-size:14px;padding:10px 15px;' href='./contacts?page=".$p."'>Next</a> ";
                    } else {
                        $contactFooter .= "<a style='border:1px solid #000;color:#000; font-size:14px;padding:10px 15px;background-color:rgb(240, 240, 240);' href='./contacts?page=".$p."'>".$p."</a> ";
                    }
                }
            }
            $contactFooter .= "</div></div>";

            $contactsStr .= $contactFooter;
            $contactsStr .= "</div></div>";



            return $contactsStr;
        }
        public function delete($id) {
            $stmt = $this->con->prepare("DELETE FROM contacts WHERE id=?");
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $stmt->close();
            header('location: ../admin/contacts'); 
        }
    }
?>