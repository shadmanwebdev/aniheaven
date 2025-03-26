<?php
    include './header.php';
?>
<?php
    include './navigation-admin.php';
?>




<div class="admin_page-wrapper">
    <?php include './admin-sidebar.php'; ?>
    <div class="admin-content">

        <div class='add-post-wrapper'>
            <?php
                $post = new Post();
                echo $post->createForm();
            ?>
        </div>

    </div>
</div>
<script src="https://cdn.tiny.cloud/1/2atacsrz7z885ln68dgpxx516lydt7lzs7pryimrgpg5d4ix/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script>
tinymce.init({
    selector: 'textarea#content',
    statusbar: false,
    plugins: 'image code print preview searchreplace autolink directionality  visualblocks visualchars fullscreen image link    table charmap hr pagebreak nonbreaking  toc insertdatetime advlist lists textcolor wordcount   imagetools    contextmenu colorpicker textpattern media ',
    toolbar: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat |undo redo | image code| link fontsizeselect  | ',
    imagetools_cors_hosts: ['picsum.photos'],
    menubar: 'file edit view insert format tools table help',
    toolbar_sticky: true,
    autosave_ask_before_unload: true,
    autosave_interval: "30s",
    autosave_prefix: "{path}{query}-{id}-",
    autosave_restore_when_empty: false,
    autosave_retention: "2m",
    image_advtab: true,
    invalid_elements : 'br',
    forced_root_block : 'p',
    // mobile: {
    //     menubar: true,
    //     toolbar_mode: 'scrolling',
    //     toolbar_drawer: 'floating'
    // },
    link_list: [
        { title: 'My page 1', value: 'https://www.codexworld.com' },
        { title: 'My page 2', value: 'https://www.xwebtools.com' }
    ],
    image_list: [
        { title: 'My page 1', value: 'https://www.codexworld.com' },
        { title: 'My page 2', value: 'https://www.xwebtools.com' }
    ],
    image_class_list: [
        { title: 'None', value: '' },
        { title: 'Some class', value: 'class-name' }
    ],
    importcss_append: true,
    templates: [
        { title: 'New Table', description: 'creates a new table', content: '<div class="mceTmpl"><table width="98%%"  border="0" cellspacing="0" cellpadding="0"><tr><th scope="col"> </th><th scope="col"> </th></tr><tr><td> </td><td> </td></tr></table></div>' },
        { title: 'Starting my story', description: 'A cure for writers block', content: 'Once upon a time...' },
        { title: 'New list with dates', description: 'New List with dates', content: '<div class="mceTmpl"><span class="cdate">cdate</span><br /><span class="mdate">mdate</span><h2>My List</h2><ul><li></li><li></li></ul></div>' }
    ],
    template_cdate_format: '[Date Created (CDATE): %m/%d/%Y : %H:%M:%S]',
    template_mdate_format: '[Date Modified (MDATE): %m/%d/%Y : %H:%M:%S]',
    height: 600,
    image_caption: true,
    quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
    noneditable_noneditable_class: "mceNonEditable",
    toolbar_mode: 'sliding',
    contextmenu: "link image imagetools table",
      force_br_newlines : false,
      force_p_newlines : true,


    image_title: true,
    automatic_uploads: true,
    images_upload_url: 'upload.php',
    file_picker_types: 'image',
    convert_urls: false,

    
    // override default upload handler to simulate successful upload
    images_upload_handler: function (blobInfo, success, failure) {
        var xhr, formData;
      
        xhr = new XMLHttpRequest();
        xhr.withCredentials = false;
        xhr.open('POST', 'upload.php');
      
        xhr.onload = function() {
            var json;
        
            if (xhr.status != 200) {
                failure('HTTP Error: ' + xhr.status);
                return;
            }
        
            json = JSON.parse(xhr.responseText);
        
            if (!json || typeof json.location != 'string') {
                failure('Invalid JSON: ' + xhr.responseText);
                return;
            }
        
            success(json.location);
        };
      
        formData = new FormData();
        formData.append('file', blobInfo.blob(), blobInfo.filename());
      
        xhr.send(formData);
    },
});
</script>

<script>
    function fireButton(event) {
        event.preventDefault();
        document.getElementById('image').click()
    }
    $("#image").change(function(){
        $imageSrc = document.getElementById('image').value;
        $imageSrcArr = $imageSrc.split('\\');
        $imgName = $imageSrcArr.at(-1);
        $imgNameArr = $imgName.split('.');
        $imgType = $imgName.at(-1);
        document.getElementById('image-name-1').style.display = 'block';
        document.getElementById('image-name-1').textContent = $imgName;
    });
</script>



</script><meta name="csrf-token" content="{{ csrf_token() }}" />

<?php
    include './footer.php';
?>
