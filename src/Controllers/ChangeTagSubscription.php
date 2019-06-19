<?php


namespace FoF\FollowTags\Controllers;


use Flarum\Api\Controller\AbstractShowController;
use Flarum\Tags\Api\Serializer\TagSerializer;
use Flarum\Tags\Tag;
use Flarum\User\AssertPermissionTrait;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ChangeTagSubscription extends AbstractShowController
{
    use AssertPermissionTrait;

    /**
     * {@inheritdoc}
     */
    public $serializer = TagSerializer::class;

    /**
     * {@inheritdoc}
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');
        $id = array_get($request->getQueryParams(), 'id');
        $subscription = array_get($request->getParsedBody(), 'data.subscription');

        $this->assertRegistered($actor);

        $tag = Tag::whereVisibleTo($actor)->findOrFail($id);
        $state = $tag->stateFor($actor);

        if (! in_array($subscription, ['follow', 'lurk', 'ignore'])) {
            $subscription = null;
        }

        $state->subscription = $subscription;

        $state->save();

        return $tag;
    }
}