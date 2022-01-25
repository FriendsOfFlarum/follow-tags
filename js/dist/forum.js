(()=>{var o={n:t=>{var n=t&&t.__esModule?()=>t.default:()=>t;return o.d(n,{a:n}),n},d:(t,n)=>{for(var s in n)o.o(n,s)&&!o.o(t,s)&&Object.defineProperty(t,s,{enumerable:!0,get:n[s]})},o:(o,t)=>Object.prototype.hasOwnProperty.call(o,t),r:o=>{"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(o,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(o,"__esModule",{value:!0})}},t={};(()=>{"use strict";o.r(t),o.d(t,{components:()=>eo,utils:()=>lo});const n=flarum.core.compat["forum/app"];var s=o.n(n);const r=flarum.core.compat["common/extend"],i=flarum.core.compat["forum/components/NotificationGrid"];var e=o.n(i);const a=flarum.core.compat["common/Model"];var l=o.n(a);const c=flarum.core.compat["forum/components/IndexPage"];var u=o.n(c);function f(o,t){return f=Object.setPrototypeOf||function(o,t){return o.__proto__=t,o},f(o,t)}function p(o,t){o.prototype=Object.create(t.prototype),o.prototype.constructor=o,f(o,t)}const g=flarum.core.compat["common/components/Dropdown"];var d=o.n(g);const b=flarum.core.compat["common/components/Button"];var w=o.n(b);const h=flarum.core.compat["common/components/Tooltip"];var _=o.n(h);const v=flarum.core.compat["common/helpers/icon"];var y=o.n(v);const T=flarum.core.compat["common/utils/classList"];var S=o.n(T);const P=flarum.core.compat["common/utils/extractText"];var N=o.n(P);const D=flarum.core.compat["common/utils/Stream"];var x=o.n(D);const k=flarum.core.compat["common/Component"];var I,O=o.n(k),j=function(o){function t(){return o.apply(this,arguments)||this}return p(t,o),t.prototype.view=function(){var o={onclick:this.attrs.onclick,disabled:this.attrs.disabled};return m("button",Object.assign({className:S()("SubscriptionMenuItem","hasIcon",this.attrs.disabled&&"disabled")},o),this.attrs.active?y()("fas fa-check",{className:"Button-icon"}):"",m("span",{className:"SubscriptionMenuItem-label"},y()(this.attrs.icon,{className:"Button-icon"}),m("strong",null,this.attrs.label),m("span",{className:"SubscriptionMenuItem-description"},this.attrs.description)))},t}(O());const M=((I={})[!1]="fas fa-star",I.follow="fas fa-star",I.lurk="fas fa-comments",I.ignore="fas fa-bell-slash",I.hide="fas fa-eye-slash",I);var B=function(o){function t(){return o.apply(this,arguments)||this}p(t,o);var n=t.prototype;return n.oninit=function(t){o.prototype.oninit.call(this,t),this.loading=x()(!1),this.canShowTooltip=x()(!1),this.options=[{subscription:!1,icon:M.false,label:s().translator.trans("fof-follow-tags.forum.sub_controls.not_following_button"),description:s().translator.trans("fof-follow-tags.forum.sub_controls.not_following_text")},{subscription:"follow",icon:M.follow,label:s().translator.trans("fof-follow-tags.forum.sub_controls.following_button"),description:s().translator.trans("fof-follow-tags.forum.sub_controls.following_text")},{subscription:"lurk",icon:M.lurk,label:s().translator.trans("fof-follow-tags.forum.sub_controls.lurking_button"),description:s().translator.trans("fof-follow-tags.forum.sub_controls.lurking_text")},{subscription:"ignore",icon:M.ignore,label:s().translator.trans("fof-follow-tags.forum.sub_controls.ignoring_button"),description:s().translator.trans("fof-follow-tags.forum.sub_controls.ignoring_text")},{subscription:"hide",icon:M.hide,label:s().translator.trans("fof-follow-tags.forum.sub_controls.hiding_button"),description:s().translator.trans("fof-follow-tags.forum.sub_controls.hiding_text")}]},n.onbeforeupdate=function(t){o.prototype.onbeforeupdate.call(this,t);var n=this.attrs.model.subscription()||!1,r=s().session.user.preferences(),i=r.notify_newPostInTag_email,e=r.notify_newPostInTag_alert;(i||e)&&!1===n?this.canShowTooltip(void 0):this.canShowTooltip(!1)},n.view=function(){var o=this,t=this.attrs.model,n=t.subscription()||!1,r=s().translator.trans("fof-follow-tags.forum.sub_controls.follow_button"),i=M[n]||"far fa-star",e="SubscriptionMenu-button--"+n;if(["follow","lurk","ignore","hide"].includes(n)){var a=["ignore","hide"].includes(n)?n.slice(0,n.length-1):n;r=s().translator.trans("fof-follow-tags.forum.sub_controls."+a+"ing_button")}var l=s().session.user.preferences().notify_newPostInTag_email,c=N()(s().translator.trans(l?"fof-follow-tags.forum.sub_controls.notify_email_tooltip":"fof-follow-tags.forum.sub_controls.notify_alert_tooltip"));return m("div",{className:"Dropdown ButtonGroup SubscriptionMenu App-primaryControl"},m(_(),{text:"boolean"==typeof this.canShowTooltip()?"":c,tooltipVisible:this.canShowTooltip(),position:"bottom",delay:250},w().component({className:"Button SubscriptionMenu-button "+e,icon:i,onclick:this.saveSubscription.bind(this,t,!["follow","lurk","ignore","hide"].includes(n)&&"follow"),loading:this.loading()},r)),m("button",{className:S()("Dropdown-toggle","Button","Button--icon",e),"data-toggle":"dropdown"},y()("fas fa-caret-down",{className:"Button-icon"})),m("ul",{className:"Dropdown-menu dropdown-menu Dropdown-menu--right"},this.options.map((function(s){return s.onclick=o.saveSubscription.bind(o,t,s.subscription),s.active=n===s.subscription,s.disabled="hide"===s.subscription&&t.isHidden(),m("li",null,j.component(s))}))))},n.saveSubscription=function(o,t){var n=this;this.loading(!0),s().request({url:s().forum.attribute("apiUrl")+"/tags/"+o.id()+"/subscription",method:"POST",body:{data:{subscription:t}}}).then((function(o){return s().store.pushPayload(o)})).then((function(){n.loading(!1),m.redraw()})),this.canShowTooltip(!1)},t}(d());const C=flarum.core.compat["forum/states/DiscussionListState"];var F=o.n(C);const U=function(){return m.route.get().includes(s().route("following"))},z=flarum.core.compat["common/app"];var q,A=o.n(z);const G=function(o){return q||(q=["none","tags"].reduce((function(t,n){return t[n]=A().translator.trans("fof-follow-tags."+o+".following_"+n+"_label"),t}),{}))};var L,H=function(){return L||(L=G("forum.index.following")),L},V=function(){H();var o=s().data["fof-follow-tags.following_page_default"];if(L[o]||(o=null),s().session.user){var t=s().session.user.preferences().followTagsPageDefault;L[t]&&(o=t)}return o||"none"},E=function(o){function t(){return o.apply(this,arguments)||this}p(t,o);var n=t.prototype;return n.view=function(){var o=s().discussions.followTags,t=this.options();return d().component({buttonClassName:"Button",label:t[o]||V()},Object.keys(t).map((function(n){var r=n===o;return w().component({active:r,icon:!r||"fas fa-check",onclick:function(){s().discussions.followTags=n,s().discussions.refresh()}},t[n])})))},n.options=function(){return H()},t}(O());const J=flarum.core.compat["forum/components/Notification"];var K=o.n(J),Q=function(o){function t(){return o.apply(this,arguments)||this}p(t,o);var n=t.prototype;return n.icon=function(){return"fas fa-user-tag"},n.href=function(){var o=this.attrs.notification.subject();return s().route.discussion(o)},n.content=function(){return s().translator.trans("fof-follow-tags.forum.notifications.new_discussion_text",{user:this.attrs.notification.fromUser(),title:this.attrs.notification.subject().title()})},t}(K()),R=function(o){function t(){return o.apply(this,arguments)||this}p(t,o);var n=t.prototype;return n.icon=function(){return M.lurk},n.href=function(){var o=this.attrs.notification,t=o.subject(),n=o.content()||{};return s().route.discussion(t,n.postNumber)},n.content=function(){return s().translator.trans("fof-follow-tags.forum.notifications.new_post_text",{user:this.attrs.notification.fromUser()})},t}(K()),W=function(o){function t(){return o.apply(this,arguments)||this}p(t,o);var n=t.prototype;return n.icon=function(){return"fas fa-user-tag"},n.href=function(){var o=this.attrs.notification.subject();return s().route.discussion(o)},n.content=function(){return s().translator.trans("fof-follow-tags.forum.notifications.new_discussion_tag_text",{user:this.attrs.notification.fromUser(),title:this.attrs.notification.subject().title()})},t}(K());const X=flarum.core.compat["common/models/Discussion"];var Y=o.n(X);const Z=flarum.core.compat["common/components/Badge"];var $=o.n(Z);const oo=flarum.core.compat["forum/components/SettingsPage"];var to=o.n(oo);const no=flarum.core.compat["common/components/FieldSet"];var so=o.n(no);const ro=flarum.core.compat["common/components/Select"];var io=o.n(ro),eo={FollowingPageFilterDropdown:E,NewDiscussionNotification:Q,NewDiscussionTagNotification:W,NewPostNotification:R,SubscriptionMenu:B,SubscriptionMenuItem:j};function ao(){return ao=Object.assign||function(o){for(var t=1;t<arguments.length;t++){var n=arguments[t];for(var s in n)Object.prototype.hasOwnProperty.call(n,s)&&(o[s]=n[s])}return o},ao.apply(this,arguments)}var lo=ao({options:L,getOptions:H,getDefaultFollowingFiltering:V,isFollowingPage:U},{followingPageOptions:G});s().initializers.add("fof/follow-tags",(function(){s().initializers.has("flarum-tags")?(s().store.models.tags.prototype.subscription=l().attribute("subscription"),(0,r.extend)(u().prototype,"sidebarItems",(function(o){if(this.currentTag()&&s().session.user){var t=this.currentTag();o.has("newDiscussion")&&o.replace("newDiscussion",null,10),o.add("subscription",B.component({model:t,itemClassName:"App-primaryControl"}),5)}})),s().initializers.has("subscriptions")&&((0,r.extend)(Y().prototype,"badges",(function(o){if(U()&&this.tags()){var t=this.tags().map((function(o){return o.subscription()})).filter((function(o){return["lurk","follow"].includes(o)})),n=t.includes("lurk")?"lurking":"following";t.length&&o.add("followTags",$().component({label:s().translator.trans("fof-follow-tags.forum.badge."+n+"_tag_tooltip"),icon:"fas fa-user-tag",type:n+"-tag"}))}})),(0,r.extend)(F().prototype,"requestParams",(function(o){if(U()&&s().session.user){this.followTags||(this.followTags=V());var t=this.followTags;"following"===s().current.get("routeName")&&"tags"===t&&(o.filter["following-tag"]=!0,delete o.filter.subscription)}})),(0,r.extend)(u().prototype,"viewItems",(function(o){U()&&s().session.user&&o.add("follow-tags",m(E,null))})),(0,r.extend)(to().prototype,"settingsItems",(function(o){var t=this;o.add("fof-follow-tags",so().component({label:s().translator.trans("fof-follow-tags.forum.user.settings.heading"),className:"Settings-follow-tags"},[m("div",{className:"Form-group"},m("p",null,s().translator.trans("fof-follow-tags.forum.user.settings.filter_label")),io().component({options:H(),value:this.user.preferences().followTagsPageDefault||V(),onchange:function(o){t.user.savePreferences({followTagsPageDefault:o}).then((function(){m.redraw()}))}}))]))}))),s().notificationComponents.newPostInTag=R,s().notificationComponents.newDiscussionInTag=Q,s().notificationComponents.newDiscussionTag=W,(0,r.extend)(e().prototype,"notificationTypes",(function(o){o.add("newDiscussionInTag",{name:"newDiscussionInTag",icon:"fas fa-user-tag",label:s().translator.trans("fof-follow-tags.forum.settings.notify_new_discussion_label")}),o.add("newPostInTag",{name:"newPostInTag",icon:"fas fa-user-tag",label:s().translator.trans("fof-follow-tags.forum.settings.notify_new_post_label")}),o.add("newDiscussionTag",{name:"newDiscussionTag",icon:"fas fa-user-tag",label:s().translator.trans("fof-follow-tags.forum.settings.notify_new_discussion_tag_label")})}))):console.error("[fof/follow-tags] flarum/tags is not enabled")}),-1)})(),module.exports=t})();
//# sourceMappingURL=forum.js.map