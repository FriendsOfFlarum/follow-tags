<x-mail::plain.notification>
<x-slot:body>
{!! $translator->trans('fof-follow-tags.email.newDiscussionTag.plain.body', [
    '{actor_display_name}' => $blueprint->actor->display_name,
    '{author_display_name}' => $blueprint->discussion->user->display_name,
    '{discussion_title}' => $blueprint->discussion->title,
    '{discussion_url}' => $url->to('forum')->route('discussion', ['id' => $blueprint->discussion->id]),
    '{content}' => $blueprint->post->content
]) !!}
</x-slot:body>
</x-mail::plain.notification>
