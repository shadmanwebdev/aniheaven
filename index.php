<?php
    include './header.php';
?>
<div id="bgOverlay"></div>

<?php

// $options = [
//     'cost' => 12,
// ];
// echo password_hash("admin", PASSWORD_BCRYPT, $options);
?>


<?php include './includes/navigation.php'; ?>
    
    <!-- TOP SECTION -->
    <div class="top-section" id="top-section">
        <div class="shadow"></div>
        
        <img src="./img/anime-55-compressed.jpg" alt="">
        
        <div class="top-content">
            <div>
                <div class="title"  data-rateY="0" data-rateX="-20" data-direction="horizontal">
                    <h1>Lorem Ipsum Dolor</h1>
                </div>
                <div class="subtitle "  data-rateY="0" data-rateX="-20"  data-direction="horizontal">
                    Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium unde omnis iste natus
                </div>
                <div>
                    <a class="cta" href="#" onclick="scroll_to_element('blog-section', event)">Discover More!</a>
                </div>
            </div>
            <div class="video-wrapper">
                <iframe src="https://www.youtube.com/embed/c2r3sF9vAGs" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
        </div>
    </div>


    <!-- ABOUT -->
    <div class="about-section" id="about-section">
        <div class="txt-col">
            <div class="title">
                <h1>About Us</h1>
            </div>
            <div class="subtitle">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
            </div>
        </div>
        <div class="about-img scroll" data-rateY="0" data-rateX="1" data-direction="horizontal">
            <img src="./img/14.jpg" alt="">
        </div>
    </div>

    <!-- BLOG -->
    <div class="blog-section" id="blog-section">
        <div class="title">
            <h1>Blog</h1>
        </div>
        <div class='posts blog-posts'>
            <div class='posts-inner'>
                <?php
                    // echo $post->posts('id', 'DESC', 1);
                ?>
                <?php
                    $rowperpage = 1;
                    $posts_array = $post->get_posts('id', 'DESC');
                    $allcount = count($posts_array);
                    echo $post->posts('id', 'DESC', $rowperpage); 
                ?>
                <input type="hidden" id="row" value="0">
                <input type="hidden" id="all" value="<?php echo $allcount; ?>">
            </div>
            <?php
                echo $post->load_btn();
            ?>
        </div>
    </div>


    <!-- PRODUCT -->
    <!-- <div class='products-section' id="products-section">
        <div class="title">
            <h1>Products</h1>
        </div>
        <div class='products-wrapper'>
            <div class='products'>
                <div class='products-inner-div'>
                    <div class='product'>
                        <div class='product-photo'>
                            <a href='#'>
                                <img src='./img/pdt-1.jpg'>
                            </a>
                        </div>
                        <div class='product-details'>
                            <div class='product-title'>
                                <a href='#'>
                                    <h1>Lorem ipsum dolor</h1>
                                </a>
                            </div>
                            <div class='product-price'>
                                <p>
                                    <span class='regular'>$25.00</span>
                                    $16.99
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class='product'>
                        <div class='product-photo'>
                            <a href='#'>
                                <img src='./img/pdt-2.jpg'>
                            </a>
                        </div>
                        <div class='product-details'>
                            <div class='product-title'>
                                <a href='#'>
                                    <h1>Lorem ipsum dolor</h1>
                                </a>
                            </div>
                            <div class='product-price'>
                                <p>
                                    <span class='regular'>$25.00</span>
                                    $16.99
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class='product'>
                        <div class='product-photo'>
                            <a href='#'>
                                <img src='./img/pdt-1.jpg'>
                            </a>
                        </div>
                        <div class='product-details'>
                            <div class='product-title'>
                                <a href='#'>
                                    <h1>Lorem ipsum dolor</h1>
                                </a>
                            </div>
                            <div class='product-price'>
                                <p>
                                    <span class='regular'>$25.00</span>
                                    $16.99
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class='product'>
                        <div class='product-photo'>
                            <a href='#'>
                                <img src='./img/pdt-2.jpg'>
                            </a>
                        </div>
                        <div class='product-details'>
                            <div class='product-title'>
                                <a href='#'>
                                    <h1>Lorem ipsum dolor</h1>
                                </a>
                            </div>
                            <div class='product-price'>
                                <p>
                                    <span class='regular'>$25.00</span>
                                    $16.99
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->







    <!-- CONTACT -->
    <div class="contact-section" id="contact-section">
        <div id='contact'>
            <div class='form-header'>
                <div class='form-heading'>
                    <h3>Contact</h3>
                </div>
                <!-- <div class='form-subheading'>
                    <p>Sed do eiusmod tempor incididunt ut labore et dolore</p>
                </div> -->
            </div>
        
            <form onsubmit='return validateContact(event)' autocomplete='off' action='./controllers/contact-handler.php' id='contactForm' class='contact contact-form' method='POST'>
                <div class='name-inputs'>
                    <div class='input-group'>
                        <input type='text' class='fname' name='fname' id='fname' placeholder='FIRST NAME'>
                        <div class='error' id='fnameError'></div>
                    </div>
                    <div class='input-group'>
                        <input type='text' class='lname' name='lname' id='lname' placeholder='LAST NAME'>
                        <div class='error' id='lnameError'></div>
                    </div>
                </div>
                <div class='input-group'>
                    <input type='text' class='email' name='email' id='email' placeholder='EMAIL'>
                    <div class='error' id='emailError'></div>
                </div>
                <div class='input-group'>
                    <textarea name='msg' id='msg' cols='30' rows='12' placeholder='MESSAGE'></textarea>
                    <div class='error' id='msgError'></div>
                </div>
                <input type='submit' class='send' name='send' value='Send Message'>
                
                <div id='msg-response'>

                </div>
            </form>
        </div>
    </div>
    







    <!-- FOOTER -->
    <footer class='footer-main'>
        <div class='footer-inner-div'>
            <div class='footer-row-wrapper'>
                <div class='footer-row' id='footer-row-1'>
                    <div class='footer-col' id='footer-col-1'>
                        <div class="logo">                
                            <div class='logo-text'>
                                <a href="./">
                                    AniHeaven
                                </a>
                            </div>
                        </div>
                        <div class='socials'>
                            <div title='facebook' class='social' id='social-1'>
                                <a href="https://www.facebook.com/"></a>
                            </div>
                            <!-- <div class='social' id='social-2'>
                                <a href="https:/twitter.com"></a>
                            </div> -->
                            <div title='instagram' class='social' id='social-3'>
                                <a href="https://www.instagram.com/"></a>
                            </div>
                            <div title='pinterest' class='social' id='social-4'>
                                <a href="https://www.pinterest.com//"></a>
                            </div>
                            <div title='reddit' class='social' id='social-5'>
                                <a href="https://www.reddit.com/user/"></a>
                            </div>
                        </div>
                    </div>
                    <div class='footer-col' id='footer-col-2'>
                        <!-- <div class='col-title'>
                            Get Started
                        </div> -->
                        <div class='col-items'>
                            <a href='./disclaimer'>Disclaimer</a>
                            <a href='./terms-of-service'>Terms of Service</a>
                            <a href='./cprivacy-policy'>Privacy Policy</a>
                            <a href='./faq'>FAQ</a>
                        </div>
                    </div>
                    <div class='footer-col' id='footer-col-3'>
                        <div class='col-items'>
                            <a href="#" onclick="scroll_to_element('top-section', event)">Home</a>
                            <a href="#" onclick="scroll_to_element('about-section', event)">About</a>
                            <a href="#" onclick="scroll_to_element('blog-section', event)">Blog</a>
                            <a href="#" onclick="scroll_to_element('products-section', event)">Products</a>
                        </div>
                    </div>
                    <div class='footer-col' id='footer-col-4'>
                        <div class='col-items'>

                            <div class='searchWrapper'>
                                <form action='#' method='get'>
                                    <input type="hidden" name='status' value=''>
                                    <input type="hidden" name='page' value='1'>
                                    <div class='search'>
                                        <div class='form-group'>
                                            <input name='s' id='search-content' class='search-content' type='text' placeholder='Search Posts'>
                                            <button name='submit' class='search-btn' id='search-btn' type='submit' value='1'><i class="fas fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class='footer-row-wrapper'>
                <div class='footer-row' id='footer-row-2'>
                    <div class='footer-col' id='footer-col-1'>
                        Coypright © AniHeaven
                    </div>                    
                    <div class='footer-col' id='footer-col-2'>
                        contact@contact.com
                    </div>
                </div>
            </div>
        </div>
    </footer>





<?php
    include './footer.php';
?>