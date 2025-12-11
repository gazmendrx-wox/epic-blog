<?php

namespace App\Mail;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PostPublishedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Post $post;
    public string $unsubscribeToken;

    /**
     * Create a new message instance.
     */
    public function __construct(Post $post, string $unsubscribeToken)
    {
        $this->post = $post;
        $this->unsubscribeToken = $unsubscribeToken;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Post Published: ' . $this->post->title,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.post-published',
            with: [
                'post' => $this->post,
                'postUrl' => config('app.frontend_url') . '/posts/' . $this->post->slug,
                'unsubscribeUrl' => config('app.frontend_url') . '/newsletter/unsubscribe/' . $this->unsubscribeToken,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
