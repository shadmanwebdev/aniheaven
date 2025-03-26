<?php
    class Db {
        public $con;
        private $servername = 'localhost';
        private $username = 'root';
        private $password = '';
        private $dbname = 'anime-site';
        
        private $servername_2 = 'sql100.epizy.com';
        private $username_2 = 'epiz_31998373';
        private $password_2 = 'yztyRSek70EPg';
        private $dbname_2 = 'epiz_31998373_anime_site';

        public function con() {
            $server = $_SERVER['SERVER_NAME'];
            if($server == 'localhost' || $server == 'http://anime.test') {
                $con = new mysqli(
                    $this->servername, 
                    $this->username, 
                    $this->password, 
                    $this->dbname
                );
            } else if($server == 'testserver4.ga') {
                $con = new mysqli(
                    $this->servername_2, 
                    $this->username_2, 
                    $this->password_2, 
                    $this->dbname_2
                );
            }
            // Check connection
            if ($con->connect_error) {
                die("Connection failed: " . $con->connect_error);
            }
            return $con;
        }
    }
?>