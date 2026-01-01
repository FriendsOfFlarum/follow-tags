<x-mail::html.notification>
    <x-slot:body>
        {!! $formatter->convert($translator->trans('fof-follow-tags.email.newPostInTag.html.body', [
            '{actor_display_name}' => $blueprint->post->user->display_name,
            '{discussion_title}' => $blueprint->post->discussion->title,
            '{post_url}' => $url->to('forum')->route('discussion', ['id' => $blueprint->post->discussion_id, 'near' => $blueprint->post->number]),
        ])) !!}
    </x-slot:body>

    <x-slot:preview>
        {!! $blueprint->post->formatContent() !!}
    </x-slot:preview>
</x-mail::html.notification>
