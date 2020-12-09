{!! $translator->trans('fof-follow-tags.email.body.newDiscussionInTag', [
    '{recipient_display_name}' => $user->display_name,
    '{actor_display_name}' => $blueprint->discussion->user->display_name,
    '{discussion_title}' => $blueprint->discussion->title,
    '{discussion_url}' => $url->to('forum')->route('discussion', ['id' => $blueprint->discussion->id]),
    '{post_content}' => $blueprint->post->content,
]) !!}
