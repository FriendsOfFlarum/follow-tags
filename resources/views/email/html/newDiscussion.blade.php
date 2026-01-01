<x-mail::html.notification>
    <x-slot:body>
        {!! $formatter->convert($translator->trans('fof-follow-tags.email.newDiscussionInTag.html.body', [
            '{actor_display_name}' => $blueprint->discussion->user->display_name,
            '{discussion_title}' => $blueprint->discussion->title,
            '{discussion_url}' => $url->to('forum')->route('discussion', ['id' => $blueprint->discussion->id]),
        ])) !!}
    </x-slot:body>

    <x-slot:preview>
        {!! $blueprint->post->formatContent() !!}
    </x-slot:preview>
</x-mail::html.notification>
