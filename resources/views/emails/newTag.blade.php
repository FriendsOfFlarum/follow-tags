Hey {!! $user->display_name !!}!

{!! $blueprint->actor->display_name !!} just changed the tag on a discussion by {!! $blueprint->discussion->user->display_name !!} to a tag you're following: {!! $blueprint->discussion->title !!}

To view the discussion, check out the following link:
{!! $url->to('forum')->route('discussion', ['id' => $blueprint->discussion->id]) !!}

---

{!! $blueprint->post->content !!}
