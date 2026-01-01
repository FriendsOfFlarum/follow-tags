<x-mail::plain.notification>
<x-slot:body>
{!! $translator->trans('fof-follow-tags.email.newPostInTag.plain.body', [
    '{actor_display_name}' => $blueprint->post->user->display_name,
    '{discussion_title}' => $blueprint->post->discussion->title,
    '{post_url}' => $url->to('forum')->route('discussion', ['id' => $blueprint->post->discussion_id, 'near' => $blueprint->post->number]),
    '{content}' => $blueprint->post->content
]) !!}
</x-slot:body>
</x-mail::plain.notification>
