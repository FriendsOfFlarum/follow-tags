<x-mail::plain.notification>
{!! $translator->trans('fof-follow-tags.email.newDiscussionInTag.plain.body', [
    '{actor_display_name}' => $blueprint->discussion->user->display_name,
    '{discussion_title}' => $blueprint->discussion->title,
    '{discussion_url}' => $url->to('forum')->route('discussion', ['id' => $blueprint->discussion->id]),
    '{content}' => $blueprint->post->content
]) !!}
</x-mail::plain.notification>
