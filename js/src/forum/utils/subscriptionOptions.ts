type SubscriptionOption = {
  subscription: string;
  icon: string;
  labelKey: string;
  descriptionKey: string;
};

const subscriptionOptions: SubscriptionOption[] = [
  {
    subscription: 'not_follow',
    icon: 'far fa-star',
    labelKey: 'fof-follow-tags.forum.sub_controls.not_following_button',
    descriptionKey: 'fof-follow-tags.forum.sub_controls.not_following_text',
  },
  {
    subscription: 'follow',
    icon: 'fas fa-star',
    labelKey: 'fof-follow-tags.forum.sub_controls.following_button',
    descriptionKey: 'fof-follow-tags.forum.sub_controls.following_text',
  },
  {
    subscription: 'lurk',
    icon: 'fas fa-eye',
    labelKey: 'fof-follow-tags.forum.sub_controls.lurking_button',
    descriptionKey: 'fof-follow-tags.forum.sub_controls.lurking_text',
  },
  {
    subscription: 'ignore',
    icon: 'fas fa-bell-slash',
    labelKey: 'fof-follow-tags.forum.sub_controls.ignoring_button',
    descriptionKey: 'fof-follow-tags.forum.sub_controls.ignoring_text',
  },
  {
    subscription: 'hide',
    icon: 'fas fa-eye-slash',
    labelKey: 'fof-follow-tags.forum.sub_controls.hiding_button',
    descriptionKey: 'fof-follow-tags.forum.sub_controls.hiding_text',
  },
];

export default subscriptionOptions;
