<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Post Published</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 8px 8px;
        }
        .post-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #667eea;
        }
        .post-excerpt {
            color: #666;
            margin-bottom: 20px;
        }
        .button {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #999;
            font-size: 12px;
        }
        .unsubscribe {
            color: #999;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📝 New Post Published!</h1>
    </div>
    
    <div class="content">
        <div class="post-title">{{ $post->title }}</div>
        
        <div class="post-excerpt">
            {{ $post->excerpt ?? Str::limit(strip_tags($post->content), 200) }}
        </div>
        
        <p>A new post has been published by <strong>{{ $post->user->name }}</strong>.</p>
        
        <p style="text-align: center;">
            <a href="{{ $postUrl }}" class="button">Read Full Post</a>
        </p>
        
        <p style="color: #666; font-size: 14px;">
            Don't miss out on the latest updates and insights from our blog!
        </p>
    </div>
    
    <div class="footer">
        <p>You're receiving this email because you subscribed to our newsletter.</p>
        <p>
            <a href="{{ $unsubscribeUrl }}" class="unsubscribe">Unsubscribe from this list</a>
        </p>
    </div>
</body>
</html>
