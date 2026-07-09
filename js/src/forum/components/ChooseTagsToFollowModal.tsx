import app from 'flarum/forum/app';
import Modal, { IInternalModalAttrs } from 'flarum/common/components/Modal';
import Button from 'flarum/common/components/Button';
import Model from 'flarum/common/Model';
import Tag from 'flarum/tags/models/Tag';
import sortTags from 'flarum/tags/common/utils/sortTags';
import tagIcon from 'flarum/tags/common/helpers/tagIcon';
import SubscriptionModal from './SubscriptionModal';
import SubscriptionStateButton from './SubscriptionStateButton';

interface IChooseTagsToFollowModalAttrs extends IInternalModalAttrs {
  hasNotChosenYet?: boolean;
}

export default class ChooseTagsToFollowModal extends Modal<IChooseTagsToFollowModalAttrs> {
  className() {
    return 'ChooseTagsToFollowModal';
  }

  title() {
    return app.translator.trans('fof-follow-tags.forum.prompt.modal_title');
  }

  content() {
    const tags = sortTags((Model.hasMany('fofFollowTagsPromptList').call(app.forum) as Tag[]) || []);

    return [
      <div className="Modal-body ChooseTagsToFollowModal-scroll">
        <table>
          <tbody>
            {tags.map((tag) => (
              <tr>
                <td className="TagName" style={{ color: tag.color() }} onclick={() => this.openSubscriptionModal(tag)}>
                  {tagIcon(tag)}
                  {tag.name()}
                </td>
                <td className="TagDescription">{tag.description()}</td>
                <td className="TagFollow">
                  <SubscriptionStateButton
                    className="Button"
                    /** @ts-expect-error */
                    subscription={tag.subscription()}
                    tooltipPosition="right"
                    onclick={() => this.openSubscriptionModal(tag)}
                  />
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>,
      <div className="Modal-body ChooseTagsToFollowModal-footer">
        {this.attrs.hasNotChosenYet && (
          <Button className="Button Button--link" onclick={() => app.modal.close()}>
            {app.translator.trans('fof-follow-tags.forum.prompt.later_button')}
          </Button>
        )}
        <Button className="Button Button--primary" loading={this.loading} onclick={() => this.continueToForum()}>
          {app.translator.trans('fof-follow-tags.forum.prompt.continue_button')}
        </Button>
      </div>,
    ];
  }

  openSubscriptionModal(tag: Tag) {
    app.modal.show(SubscriptionModal, { model: tag }, true);
  }

  continueToForum() {
    // If we are not in the "forced to choose" mode, there's no need to send a
    // request again since it's already marked as done
    if (!this.attrs.hasNotChosenYet) {
      app.modal.close();

      return;
    }

    this.loading = true;

    app.session
      .user!.save({
        fofFollowTagsPromptConfigured: true,
      })
      .then(() => {
        this.loading = false;
        m.redraw();
        app.modal.close();
      })
      .catch((err) => {
        this.loading = false;
        m.redraw();
        throw err;
      });
  }

  onsubmit(event: Event) {
    // Buttons without an explicit type would otherwise submit the modal form
    event.preventDefault();
  }
}
