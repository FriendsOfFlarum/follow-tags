<x-mail::html.notification :preview="$blueprint->post->formatContent()">
    {!! $formatter->convert($translator->trans('fof-follow-tags.email.newPostInTag.html.body', [
        '{actor_display_name}' => $blueprint->post->user->display_name,
        '{discussion_title}' => $blueprint->post->discussion->title,
        '{post_url}' => $url->to('forum')->route('discussion', ['id' => $blueprint->post->discussion_id, 'near' => $blueprint->post->number]),
    ])) !!}
</x-mail::html.notification>
