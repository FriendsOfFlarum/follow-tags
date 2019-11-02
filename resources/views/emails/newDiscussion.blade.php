Hey {!! $user->display_name !!}!

{!! $blueprint->discussion->user->display_name !!} made a discussion in a tag you're following: {!! $blueprint->discussion->title !!}

To view the new discussion, check out the following link:
{!! app()->url() !!}/d/{!! $blueprint->discussion->id !!}

---

{!! $blueprint->post->content !!}
