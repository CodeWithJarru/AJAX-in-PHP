<?php
$db = mysqli_connect('localhost', 'root', '', 'ajax');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Using AJAX in PHP</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Courier New', Courier, monospace;
        }

        textarea {
            resize: none;
        }

        body {
            background-color: skyblue;
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
        }

        .container {
            width: 50%;
            margin: 0 auto;
        }

        .form {
            display: flex;
            flex-direction: column;
            border: 2px solid #0f2339;
            padding: 20px;
            border-radius: 4px;
            border-right: 10px solid #0f2339;
        }

        .inp {
            margin-bottom: 10px;
            background-color: #ffffff44;
            border-radius: 6px;
            border: 2px solid #0f2339;
            outline: none;
            padding: 10px;
        }

        .textarea {
            margin-bottom: 10px;
            border: 2px solid #0f2339;
            background-color: #ffffff44;
            outline: none;
            border-radius: 6px;
        }

        .btn {
            background-color: #0f2339;
            color: #ffffff;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        #post {
            border: 2px solid #0f2339;
            padding: 20px;
            border-radius: 4px;
            border-right: 10px solid #0f2339;
            margin-bottom: 20px;
        }

        .success-msg {
            color: green;
            padding: 10px;
            margin: 10px 0;
            font-weight: bold;
        }

        .error-msg {
            color: red;
            padding: 10px;
            margin: 10px 0;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Hello, User</h1>
        <p>Post Anything</p>
        <form action="" method="post" class="form">
            <?php if (isset($msg)) {
                echo $msg;
            } ?>
            <label for="title">Title</label>
            <input type="text" name="title" id="title" class="inp">
            <label for="content">Content</label>
            <textarea name="content" id="content" cols="30" rows="10" class="textarea"></textarea>
            <input type="submit" name="submit" value="send" class="btn">
        </form>
        <div id="l"></div>
        <div style="margin-top: 12px;"></div>
        <h1>Posts</h1>
    </div>
    
    <script>
        const form = document.querySelector('.form');
        const l = document.querySelector('#l');

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('Form submitted');
            
            const title = document.querySelector('#title').value.trim();
            const content = document.querySelector('#content').value.trim();
            
            console.log('Title:', title);
            console.log('Content:', content);
            
            if (!title || !content) {
                alert('Please fill in all fields');
                return;
            }
            
            l.innerHTML = 'Loading...';
            
            const data = JSON.stringify({
                title: title,
                content: content
            });
            
            console.log('Sending data:', data);
            
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'submit.php', true);
            xhr.setRequestHeader('Content-Type', 'application/json');
            
            xhr.onreadystatechange = function() {
                console.log('ReadyState:', this.readyState, 'Status:', this.status);
                
                if (this.readyState === 4) {
                    l.innerHTML = '';
                    
                    if (this.status === 200) {
                        console.log('Response:', this.responseText);
                        l.innerHTML = '<p class="success-msg">Post submitted successfully!</p>';
                        
                        setTimeout(() => {
                            l.innerHTML = '';
                        }, 2000);
                        
                        // Clear the form
                        document.querySelector('#title').value = '';
                        document.querySelector('#content').value = '';
                        
                        // Reload posts to show the new one
                        console.log('Loading posts...');
                        loadPosts();
                    } else {
                        l.innerHTML = '<p class="error-msg">Error submitting post (Status: ' + this.status + ')</p>';
                        console.error('Error:', this.status, this.responseText);
                    }
                }
            }
            
            xhr.onerror = function() {
                l.innerHTML = '<p class="error-msg">Network error occurred</p>';
                console.error('Network error');
            }
            
            xhr.send(data);
        });

        function loadPosts() {
            console.log('loadPosts() called');
            const xhr2 = new XMLHttpRequest();
            xhr2.open('GET', 'getting.php', true);

            xhr2.onreadystatechange = function() {
                if (this.readyState === 4) {
                    if (this.status === 200) {
                        console.log('Posts response:', this.responseText);
                        try {
                            const posts = JSON.parse(this.responseText);
                            console.log('Parsed posts:', posts);

                            const existingPosts = document.querySelectorAll('.post');
                            existingPosts.forEach(post => post.remove());

                            posts.forEach(post => {
                                const postDiv = document.createElement('div');
                                postDiv.className = 'post';
                                postDiv.id = 'post';
                                postDiv.innerHTML = `<h2>${post.title}</h2><p>${post.content.replace(/\n/g, '<br>')}</p>`;
                                document.querySelector('.container').appendChild(postDiv);
                            });
                        } catch (e) {
                            console.error('JSON parse error:', e);
                            console.log('Raw response:', this.responseText);
                        }
                    } else {
                        console.error('Error loading posts:', this.status);
                    }
                }
            }

            xhr2.send();
        }

        // Load posts when page loads
        console.log('Page loaded, loading posts...');
        loadPosts();
    </script>
</body>

</html>
