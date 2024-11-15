<x-mail::html.notification>
    <x-slot:body>
        {!! $translator->trans('fof-follow-tags.email.body.newDiscussionTag', [
    'recipient_display_name' => $user->display_name,
    'actor_display_name' => $blueprint->actor->display_name,
    'author_display_name' => $blueprint->discussion->user->display_name,
    'discussion_title' => $blueprint->discussion->title,
    'discussion_url' => $url->to('forum')->route('discussion', ['id' => $blueprint->discussion->id]),
]) !!}
    </x-slot:body>

    <x-slot:preview><!-- Optional content --></x-slot:preview>
</x-mail::html.notification>
