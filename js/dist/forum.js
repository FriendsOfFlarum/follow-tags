module.exports =
/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./forum.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./forum.js":
/*!******************!*\
  !*** ./forum.js ***!
  \******************/
/*! exports provided: components, utils */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _src_forum__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./src/forum */ "./src/forum/index.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "components", function() { return _src_forum__WEBPACK_IMPORTED_MODULE_0__["components"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "utils", function() { return _src_forum__WEBPACK_IMPORTED_MODULE_0__["utils"]; });

// exports from common are not imported on purpose as the utils namespace would be overridden
// because of that common exports are imported then re-exported from forum


/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/extends.js":
/*!************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/extends.js ***!
  \************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return _extends; });
function _extends() {
  _extends = Object.assign || function (target) {
    for (var i = 1; i < arguments.length; i++) {
      var source = arguments[i];

      for (var key in source) {
        if (Object.prototype.hasOwnProperty.call(source, key)) {
          target[key] = source[key];
        }
      }
    }

    return target;
  };

  return _extends.apply(this, arguments);
}

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/esm/inheritsLoose.js":
/*!******************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/esm/inheritsLoose.js ***!
  \******************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return _inheritsLoose; });
function _inheritsLoose(subClass, superClass) {
  subClass.prototype = Object.create(superClass.prototype);
  subClass.prototype.constructor = subClass;
  subClass.__proto__ = superClass;
}

/***/ }),

/***/ "./src/common/index.js":
/*!*****************************!*\
  !*** ./src/common/index.js ***!
  \*****************************/
/*! exports provided: utils */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "utils", function() { return utils; });
/* harmony import */ var _utils_followingPageOptions__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./utils/followingPageOptions */ "./src/common/utils/followingPageOptions.js");

var utils = {
  followingPageOptions: _utils_followingPageOptions__WEBPACK_IMPORTED_MODULE_0__["default"]
};

/***/ }),

/***/ "./src/common/utils/followingPageOptions.js":
/*!**************************************************!*\
  !*** ./src/common/utils/followingPageOptions.js ***!
  \**************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
var opts;
/* harmony default export */ __webpack_exports__["default"] = (function (section) {
  return opts || (opts = ['none', 'tags', 'all'].reduce(function (o, key) {
    o[key] = app.translator.trans("fof-follow-tags." + section + ".following_" + key + "_label");
    return o;
  }, {}));
});

/***/ }),

/***/ "./src/forum/addDiscussionBadge.js":
/*!*****************************************!*\
  !*** ./src/forum/addDiscussionBadge.js ***!
  \*****************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return addSubscriptionBadge; });
/* harmony import */ var flarum_extend__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! flarum/extend */ "flarum/extend");
/* harmony import */ var flarum_extend__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(flarum_extend__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var flarum_models_Discussion__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! flarum/models/Discussion */ "flarum/models/Discussion");
/* harmony import */ var flarum_models_Discussion__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(flarum_models_Discussion__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var flarum_components_Badge__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! flarum/components/Badge */ "flarum/components/Badge");
/* harmony import */ var flarum_components_Badge__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(flarum_components_Badge__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _utils_isFollowingPage__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./utils/isFollowingPage */ "./src/forum/utils/isFollowingPage.js");




function addSubscriptionBadge() {
  Object(flarum_extend__WEBPACK_IMPORTED_MODULE_0__["extend"])(flarum_models_Discussion__WEBPACK_IMPORTED_MODULE_1___default.a.prototype, 'badges', function (badges) {
    if (!Object(_utils_isFollowingPage__WEBPACK_IMPORTED_MODULE_3__["default"])()) {
      return;
    }

    var subscriptions = this.tags().map(function (tag) {
      return tag.subscription();
    }).filter(function (state) {
      return ['lurk', 'follow'].includes(state);
    });
    var type = subscriptions.includes('lurk') ? 'lurking' : 'following';

    if (subscriptions.length) {
      badges.add('followTags', flarum_components_Badge__WEBPACK_IMPORTED_MODULE_2___default.a.component({
        label: app.translator.trans("fof-follow-tags.forum.badge." + type + "_tag_tooltip"),
        icon: 'fas fa-user-tag',
        type: type + "-tag"
      }));
    }
  });
}

/***/ }),

/***/ "./src/forum/addFollowedTagsDiscussions.js":
/*!*************************************************!*\
  !*** ./src/forum/addFollowedTagsDiscussions.js ***!
  \*************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var flarum_extend__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! flarum/extend */ "flarum/extend");
/* harmony import */ var flarum_extend__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(flarum_extend__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var flarum_components_IndexPage__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! flarum/components/IndexPage */ "flarum/components/IndexPage");
/* harmony import */ var flarum_components_IndexPage__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(flarum_components_IndexPage__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var flarum_states_DiscussionListState__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! flarum/states/DiscussionListState */ "flarum/states/DiscussionListState");
/* harmony import */ var flarum_states_DiscussionListState__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(flarum_states_DiscussionListState__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _utils_isFollowingPage__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./utils/isFollowingPage */ "./src/forum/utils/isFollowingPage.js");
/* harmony import */ var _utils_getDefaultFollowingFiltering__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./utils/getDefaultFollowingFiltering */ "./src/forum/utils/getDefaultFollowingFiltering.js");
/* harmony import */ var _components_FollowingPageFilterDropdown__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./components/FollowingPageFilterDropdown */ "./src/forum/components/FollowingPageFilterDropdown.js");






/* harmony default export */ __webpack_exports__["default"] = (function () {
  Object(flarum_extend__WEBPACK_IMPORTED_MODULE_0__["extend"])(flarum_states_DiscussionListState__WEBPACK_IMPORTED_MODULE_2___default.a.prototype, 'requestParams', function (params) {
    if (!Object(_utils_isFollowingPage__WEBPACK_IMPORTED_MODULE_3__["default"])() || !app.session.user) return;

    if (!this.followTags) {
      this.followTags = Object(_utils_getDefaultFollowingFiltering__WEBPACK_IMPORTED_MODULE_4__["getDefaultFollowingFiltering"])();
    }

    var q = params.filter.q || '';
    var followTags = this.followTags;

    if (app.current.get('routeName') === 'following' && ['tags', 'all'].includes(followTags)) {
      if (followTags === 'tags' || followTags === 'all') {
        q += ' is:following-tag';
      }

      if (followTags === 'tags') {
        q = q.replace(' is:following', '');
      }

      params.filter.q = q;
    }
  });
  Object(flarum_extend__WEBPACK_IMPORTED_MODULE_0__["extend"])(flarum_components_IndexPage__WEBPACK_IMPORTED_MODULE_1___default.a.prototype, 'viewItems', function (items) {
    if (!Object(_utils_isFollowingPage__WEBPACK_IMPORTED_MODULE_3__["default"])() || !app.session.user) {
      return;
    }

    items.add('follow-tags', m(_components_FollowingPageFilterDropdown__WEBPACK_IMPORTED_MODULE_5__["default"], null));
  });
});

/***/ }),

/***/ "./src/forum/addPreferences.js":
/*!*************************************!*\
  !*** ./src/forum/addPreferences.js ***!
  \*************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var flarum_extend__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! flarum/extend */ "flarum/extend");
/* harmony import */ var flarum_extend__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(flarum_extend__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var flarum_components_SettingsPage__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! flarum/components/SettingsPage */ "flarum/components/SettingsPage");
/* harmony import */ var flarum_components_SettingsPage__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(flarum_components_SettingsPage__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var flarum_components_FieldSet__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! flarum/components/FieldSet */ "flarum/components/FieldSet");
/* harmony import */ var flarum_components_FieldSet__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(flarum_components_FieldSet__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var flarum_components_Select__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! flarum/components/Select */ "flarum/components/Select");
/* harmony import */ var flarum_components_Select__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(flarum_components_Select__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _utils_getDefaultFollowingFiltering__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./utils/getDefaultFollowingFiltering */ "./src/forum/utils/getDefaultFollowingFiltering.js");





/* harmony default export */ __webpack_exports__["default"] = (function () {
  Object(flarum_extend__WEBPACK_IMPORTED_MODULE_0__["extend"])(flarum_components_SettingsPage__WEBPACK_IMPORTED_MODULE_1___default.a.prototype, 'settingsItems', function (items) {
    var _this = this;

    items.add('fof-follow-tags', flarum_components_FieldSet__WEBPACK_IMPORTED_MODULE_2___default.a.component({
      label: app.translator.trans('fof-follow-tags.forum.user.settings.heading'),
      className: 'Settings-follow-tags'
    }, [m("div", {
      className: "Form-group"
    }, m("p", null, app.translator.trans('fof-follow-tags.forum.user.settings.filter_label')), flarum_components_Select__WEBPACK_IMPORTED_MODULE_3___default.a.component({
      options: Object(_utils_getDefaultFollowingFiltering__WEBPACK_IMPORTED_MODULE_4__["getOptions"])(),
      value: this.user.preferences().followTagsPageDefault || Object(_utils_getDefaultFollowingFiltering__WEBPACK_IMPORTED_MODULE_4__["getDefaultFollowingFiltering"])(),
      onchange: function onchange(key) {
        return _this.preferenceSaver('followTagsPageDefault')(key);
      }
    }))]));
  });
});

/***/ }),

/***/ "./src/forum/addSubscriptionControls.js":
/*!**********************************************!*\
  !*** ./src/forum/addSubscriptionControls.js ***!
  \**********************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var flarum_extend__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! flarum/extend */ "flarum/extend");
/* harmony import */ var flarum_extend__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(flarum_extend__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var flarum_Model__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! flarum/Model */ "flarum/Model");
/* harmony import */ var flarum_Model__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(flarum_Model__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var flarum_components_IndexPage__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! flarum/components/IndexPage */ "flarum/components/IndexPage");
/* harmony import */ var flarum_components_IndexPage__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(flarum_components_IndexPage__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _components_SubscriptionMenu__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./components/SubscriptionMenu */ "./src/forum/components/SubscriptionMenu.js");




/* harmony default export */ __webpack_exports__["default"] = (function () {
  app.store.models.tags.prototype.subscription = flarum_Model__WEBPACK_IMPORTED_MODULE_1___default.a.attribute('subscription');
  Object(flarum_extend__WEBPACK_IMPORTED_MODULE_0__["extend"])(flarum_components_IndexPage__WEBPACK_IMPORTED_MODULE_2___default.a.prototype, 'sidebarItems', function (items) {
    if (!this.currentTag() || !app.session.user) return;
    var tag = this.currentTag();
    items.replace('newDiscussion', items.get('newDiscussion'), 10);
    items.add('subscription', _components_SubscriptionMenu__WEBPACK_IMPORTED_MODULE_3__["default"].component({
      model: tag,
      itemClassName: 'App-primaryControl'
    }), 5);
  });
});

/***/ }),

/***/ "./src/forum/components/FollowingPageFilterDropdown.js":
/*!*************************************************************!*\
  !*** ./src/forum/components/FollowingPageFilterDropdown.js ***!
  \*************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return FollowingPageFilterDropdown; });
/* harmony import */ var _babel_runtime_helpers_esm_inheritsLoose__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/esm/inheritsLoose */ "./node_modules/@babel/runtime/helpers/esm/inheritsLoose.js");
/* harmony import */ var flarum_Component__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! flarum/Component */ "flarum/Component");
/* harmony import */ var flarum_Component__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(flarum_Component__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var flarum_components_Button__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! flarum/components/Button */ "flarum/components/Button");
/* harmony import */ var flarum_components_Button__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(flarum_components_Button__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var flarum_components_Dropdown__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! flarum/components/Dropdown */ "flarum/components/Dropdown");
/* harmony import */ var flarum_components_Dropdown__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(flarum_components_Dropdown__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _utils_getDefaultFollowingFiltering__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../utils/getDefaultFollowingFiltering */ "./src/forum/utils/getDefaultFollowingFiltering.js");






var FollowingPageFilterDropdown = /*#__PURE__*/function (_Component) {
  Object(_babel_runtime_helpers_esm_inheritsLoose__WEBPACK_IMPORTED_MODULE_0__["default"])(FollowingPageFilterDropdown, _Component);

  function FollowingPageFilterDropdown() {
    return _Component.apply(this, arguments) || this;
  }

  var _proto = FollowingPageFilterDropdown.prototype;

  _proto.view = function view() {
    var selected = app.discussions.followTags;
    var options = this.options();
    return flarum_components_Dropdown__WEBPACK_IMPORTED_MODULE_3___default.a.component({
      buttonClassName: 'Button',
      label: options[selected] || getDefaultFollowingFiltering()
    }, Object.keys(options).map(function (key) {
      var active = key === selected;
      return flarum_components_Button__WEBPACK_IMPORTED_MODULE_2___default.a.component({
        active: active,
        icon: active ? 'fas fa-check' : true,
        onclick: function onclick() {
          app.discussions.followTags = key;
          app.discussions.refresh();
        }
      }, options[key]);
    }));
  };

  _proto.options = function options() {
    return Object(_utils_getDefaultFollowingFiltering__WEBPACK_IMPORTED_MODULE_4__["getOptions"])();
  };

  return FollowingPageFilterDropdown;
}(flarum_Component__WEBPACK_IMPORTED_MODULE_1___default.a);



/***/ }),

/***/ "./src/forum/components/NewDiscussionNotification.js":
/*!***********************************************************!*\
  !*** ./src/forum/components/NewDiscussionNotification.js ***!
  \***********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return NewDiscussionNotification; });
/* harmony import */ var _babel_runtime_helpers_esm_inheritsLoose__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/esm/inheritsLoose */ "./node_modules/@babel/runtime/helpers/esm/inheritsLoose.js");
/* harmony import */ var flarum_components_Notification__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! flarum/components/Notification */ "flarum/components/Notification");
/* harmony import */ var flarum_components_Notification__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(flarum_components_Notification__WEBPACK_IMPORTED_MODULE_1__);



var NewDiscussionNotification = /*#__PURE__*/function (_Notification) {
  Object(_babel_runtime_helpers_esm_inheritsLoose__WEBPACK_IMPORTED_MODULE_0__["default"])(NewDiscussionNotification, _Notification);

  function NewDiscussionNotification() {
    return _Notification.apply(this, arguments) || this;
  }

  var _proto = NewDiscussionNotification.prototype;

  _proto.icon = function icon() {
    return 'fas fa-user-tag';
  };

  _proto.href = function href() {
    var notification = this.attrs.notification;
    var discussion = notification.subject();
    return app.route.discussion(discussion);
  };

  _proto.content = function content() {
    return app.translator.trans('fof-follow-tags.forum.notifications.new_discussion_text', {
      user: this.attrs.notification.fromUser(),
      title: this.attrs.notification.subject().title()
    });
  };

  return NewDiscussionNotification;
}(flarum_components_Notification__WEBPACK_IMPORTED_MODULE_1___default.a);



/***/ }),

/***/ "./src/forum/components/NewDiscussionTagNotification.js":
/*!**************************************************************!*\
  !*** ./src/forum/components/NewDiscussionTagNotification.js ***!
  \**************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return NewDiscussionTagNotification; });
/* harmony import */ var _babel_runtime_helpers_esm_inheritsLoose__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/esm/inheritsLoose */ "./node_modules/@babel/runtime/helpers/esm/inheritsLoose.js");
/* harmony import */ var flarum_components_Notification__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! flarum/components/Notification */ "flarum/components/Notification");
/* harmony import */ var flarum_components_Notification__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(flarum_components_Notification__WEBPACK_IMPORTED_MODULE_1__);



var NewDiscussionTagNotification = /*#__PURE__*/function (_Notification) {
  Object(_babel_runtime_helpers_esm_inheritsLoose__WEBPACK_IMPORTED_MODULE_0__["default"])(NewDiscussionTagNotification, _Notification);

  function NewDiscussionTagNotification() {
    return _Notification.apply(this, arguments) || this;
  }

  var _proto = NewDiscussionTagNotification.prototype;

  _proto.icon = function icon() {
    return 'fas fa-user-tag';
  };

  _proto.href = function href() {
    var notification = this.attrs.notification;
    var discussion = notification.subject();
    return app.route.discussion(discussion);
  };

  _proto.content = function content() {
    return app.translator.trans('fof-follow-tags.forum.notifications.new_discussion_tag_text', {
      user: this.attrs.notification.fromUser(),
      title: this.attrs.notification.subject().title()
    });
  };

  return NewDiscussionTagNotification;
}(flarum_components_Notification__WEBPACK_IMPORTED_MODULE_1___default.a);



/***/ }),

/***/ "./src/forum/components/NewPostNotification.js":
/*!*****************************************************!*\
  !*** ./src/forum/components/NewPostNotification.js ***!
  \*****************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return NewPostNotification; });
/* harmony import */ var _babel_runtime_helpers_esm_inheritsLoose__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/esm/inheritsLoose */ "./node_modules/@babel/runtime/helpers/esm/inheritsLoose.js");
/* harmony import */ var flarum_components_Notification__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! flarum/components/Notification */ "flarum/components/Notification");
/* harmony import */ var flarum_components_Notification__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(flarum_components_Notification__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _icons__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../icons */ "./src/forum/icons.js");




var NewPostNotification = /*#__PURE__*/function (_Notification) {
  Object(_babel_runtime_helpers_esm_inheritsLoose__WEBPACK_IMPORTED_MODULE_0__["default"])(NewPostNotification, _Notification);

  function NewPostNotification() {
    return _Notification.apply(this, arguments) || this;
  }

  var _proto = NewPostNotification.prototype;

  _proto.icon = function icon() {
    return _icons__WEBPACK_IMPORTED_MODULE_2__["default"].lurk;
  };

  _proto.href = function href() {
    var notification = this.attrs.notification;
    var discussion = notification.subject();
    var content = notification.content() || {};
    return app.route.discussion(discussion, content.postNumber);
  };

  _proto.content = function content() {
    return app.translator.trans('fof-follow-tags.forum.notifications.new_post_text', {
      user: this.attrs.notification.fromUser()
    });
  };

  return NewPostNotification;
}(flarum_components_Notification__WEBPACK_IMPORTED_MODULE_1___default.a);



/***/ }),

/***/ "./src/forum/components/SubscriptionMenu.js":
/*!**************************************************!*\
  !*** ./src/forum/components/SubscriptionMenu.js ***!
  \**************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return SubscriptionMenu; });
/* harmony import */ var _babel_runtime_helpers_esm_inheritsLoose__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/esm/inheritsLoose */ "./node_modules/@babel/runtime/helpers/esm/inheritsLoose.js");
/* harmony import */ var flarum_components_Dropdown__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! flarum/components/Dropdown */ "flarum/components/Dropdown");
/* harmony import */ var flarum_components_Dropdown__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(flarum_components_Dropdown__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var flarum_components_Button__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! flarum/components/Button */ "flarum/components/Button");
/* harmony import */ var flarum_components_Button__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(flarum_components_Button__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var flarum_helpers_icon__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! flarum/helpers/icon */ "flarum/helpers/icon");
/* harmony import */ var flarum_helpers_icon__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(flarum_helpers_icon__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var flarum_utils_extractText__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! flarum/utils/extractText */ "flarum/utils/extractText");
/* harmony import */ var flarum_utils_extractText__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(flarum_utils_extractText__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _SubscriptionMenuItem__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./SubscriptionMenuItem */ "./src/forum/components/SubscriptionMenuItem.js");
/* harmony import */ var _icons__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../icons */ "./src/forum/icons.js");








var SubscriptionMenu = /*#__PURE__*/function (_Dropdown) {
  Object(_babel_runtime_helpers_esm_inheritsLoose__WEBPACK_IMPORTED_MODULE_0__["default"])(SubscriptionMenu, _Dropdown);

  function SubscriptionMenu() {
    return _Dropdown.apply(this, arguments) || this;
  }

  var _proto = SubscriptionMenu.prototype;

  _proto.oninit = function oninit(vnode) {
    _Dropdown.prototype.oninit.call(this, vnode);

    this.loading = m.stream(false);
    this.options = [{
      subscription: false,
      icon: _icons__WEBPACK_IMPORTED_MODULE_6__["default"][false],
      label: app.translator.trans('fof-follow-tags.forum.sub_controls.not_following_button'),
      description: app.translator.trans('fof-follow-tags.forum.sub_controls.not_following_text')
    }, {
      subscription: 'follow',
      icon: _icons__WEBPACK_IMPORTED_MODULE_6__["default"].follow,
      label: app.translator.trans('fof-follow-tags.forum.sub_controls.following_button'),
      description: app.translator.trans('fof-follow-tags.forum.sub_controls.following_text')
    }, {
      subscription: 'lurk',
      icon: _icons__WEBPACK_IMPORTED_MODULE_6__["default"].lurk,
      label: app.translator.trans('fof-follow-tags.forum.sub_controls.lurking_button'),
      description: app.translator.trans('fof-follow-tags.forum.sub_controls.lurking_text')
    }, {
      subscription: 'ignore',
      icon: _icons__WEBPACK_IMPORTED_MODULE_6__["default"].ignore,
      label: app.translator.trans('fof-follow-tags.forum.sub_controls.ignoring_button'),
      description: app.translator.trans('fof-follow-tags.forum.sub_controls.ignoring_text')
    }, {
      subscription: 'hide',
      icon: _icons__WEBPACK_IMPORTED_MODULE_6__["default"].hide,
      label: app.translator.trans('fof-follow-tags.forum.sub_controls.hiding_button'),
      description: app.translator.trans('fof-follow-tags.forum.sub_controls.hiding_text')
    }];
  };

  _proto.view = function view() {
    var _this = this;

    var tag = this.attrs.model;
    var subscription = tag.subscription() || false;
    var buttonLabel = app.translator.trans('fof-follow-tags.forum.sub_controls.follow_button');
    var buttonIcon = _icons__WEBPACK_IMPORTED_MODULE_6__["default"][subscription] || 'far fa-star';
    var buttonClass = 'SubscriptionMenu-button--' + subscription;

    if (['follow', 'lurk', 'ignore', 'hide'].includes(subscription)) {
      var word = ['ignore', 'hide'].includes(subscription) ? subscription.slice(0, subscription.length - 1) : subscription;
      buttonLabel = app.translator.trans("fof-follow-tags.forum.sub_controls." + word + "ing_button");
    }

    var preferences = app.session.user.preferences();
    var notifyEmail = preferences['notify_newPostInTag_email'];
    var notifyAlert = preferences['notify_newPostInTag_alert'];
    var title = flarum_utils_extractText__WEBPACK_IMPORTED_MODULE_4___default()(app.translator.trans(notifyEmail ? 'fof-follow-tags.forum.sub_controls.notify_email_tooltip' : 'fof-follow-tags.forum.sub_controls.notify_alert_tooltip'));
    var buttonattrs = {
      className: 'Button SubscriptionMenu-button ' + buttonClass,
      icon: buttonIcon,
      onclick: this.saveSubscription.bind(this, tag, ['follow', 'lurk', 'ignore', 'hide'].includes(subscription) ? false : 'follow'),
      title: title,
      loading: this.loading()
    };

    if ((notifyEmail || notifyAlert) && subscription === false) {
      buttonattrs.oncreate = function (vnode) {
        $(vnode.dom).tooltip({
          container: '.SubscriptionMenu',
          placement: 'bottom',
          delay: 250,
          title: title
        });
      };
    } else {
      buttonattrs.oncreate = function (vnode) {
        return $(vnode.dom).tooltip('destroy');
      };

      delete buttonattrs.title;
    }

    buttonattrs.onupdate = buttonattrs.oncreate;
    return m("div", {
      className: "Dropdown ButtonGroup SubscriptionMenu App-primaryControl"
    }, flarum_components_Button__WEBPACK_IMPORTED_MODULE_2___default.a.component(buttonattrs, buttonLabel), m("button", {
      className: 'Dropdown-toggle Button Button--icon ' + buttonClass,
      "data-toggle": "dropdown"
    }, flarum_helpers_icon__WEBPACK_IMPORTED_MODULE_3___default()('fas fa-caret-down', {
      className: 'Button-icon'
    })), m("ul", {
      className: "Dropdown-menu dropdown-menu Dropdown-menu--right"
    }, this.options.map(function (attrs) {
      attrs.onclick = _this.saveSubscription.bind(_this, tag, attrs.subscription);
      attrs.active = subscription === attrs.subscription;
      attrs.disabled = attrs.subscription === 'hide' && tag.isHidden();
      return m("li", null, _SubscriptionMenuItem__WEBPACK_IMPORTED_MODULE_5__["default"].component(attrs));
    })));
  };

  _proto.saveSubscription = function saveSubscription(tag, subscription) {
    var _this2 = this;

    this.loading(true);
    app.request({
      url: app.forum.attribute('apiUrl') + "/tags/" + tag.id() + "/subscription",
      method: 'POST',
      body: {
        data: {
          subscription: subscription
        }
      }
    }).then(function (res) {
      return app.store.pushPayload(res);
    }).then(function () {
      _this2.loading(false);

      m.redraw();
    });
    this.$('.SubscriptionMenu-button').tooltip('hide');
  };

  return SubscriptionMenu;
}(flarum_components_Dropdown__WEBPACK_IMPORTED_MODULE_1___default.a);



/***/ }),

/***/ "./src/forum/components/SubscriptionMenuItem.js":
/*!******************************************************!*\
  !*** ./src/forum/components/SubscriptionMenuItem.js ***!
  \******************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return SubscriptionMenuItem; });
/* harmony import */ var _babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/esm/extends */ "./node_modules/@babel/runtime/helpers/esm/extends.js");
/* harmony import */ var _babel_runtime_helpers_esm_inheritsLoose__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/esm/inheritsLoose */ "./node_modules/@babel/runtime/helpers/esm/inheritsLoose.js");
/* harmony import */ var flarum_Component__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! flarum/Component */ "flarum/Component");
/* harmony import */ var flarum_Component__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(flarum_Component__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var flarum_helpers_icon__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! flarum/helpers/icon */ "flarum/helpers/icon");
/* harmony import */ var flarum_helpers_icon__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(flarum_helpers_icon__WEBPACK_IMPORTED_MODULE_3__);





var SubscriptionMenuItem = /*#__PURE__*/function (_Component) {
  Object(_babel_runtime_helpers_esm_inheritsLoose__WEBPACK_IMPORTED_MODULE_1__["default"])(SubscriptionMenuItem, _Component);

  function SubscriptionMenuItem() {
    return _Component.apply(this, arguments) || this;
  }

  var _proto = SubscriptionMenuItem.prototype;

  _proto.view = function view() {
    var attrs = {
      onclick: this.attrs.onclick,
      disabled: this.attrs.disabled
    };
    return m("button", Object(_babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_0__["default"])({
      className: "SubscriptionMenuItem hasIcon " + (this.attrs.disabled && 'disabled')
    }, attrs), this.attrs.active ? flarum_helpers_icon__WEBPACK_IMPORTED_MODULE_3___default()('fas fa-check', {
      className: 'Button-icon'
    }) : '', m("span", {
      className: "SubscriptionMenuItem-label"
    }, flarum_helpers_icon__WEBPACK_IMPORTED_MODULE_3___default()(this.attrs.icon, {
      className: 'Button-icon'
    }), m("strong", null, this.attrs.label), m("span", {
      className: "SubscriptionMenuItem-description"
    }, this.attrs.description)));
  };

  return SubscriptionMenuItem;
}(flarum_Component__WEBPACK_IMPORTED_MODULE_2___default.a);



/***/ }),

/***/ "./src/forum/components/index.js":
/*!***************************************!*\
  !*** ./src/forum/components/index.js ***!
  \***************************************/
/*! exports provided: components */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "components", function() { return components; });
/* harmony import */ var _FollowingPageFilterDropdown__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./FollowingPageFilterDropdown */ "./src/forum/components/FollowingPageFilterDropdown.js");
/* harmony import */ var _NewDiscussionNotification__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./NewDiscussionNotification */ "./src/forum/components/NewDiscussionNotification.js");
/* harmony import */ var _NewDiscussionTagNotification__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./NewDiscussionTagNotification */ "./src/forum/components/NewDiscussionTagNotification.js");
/* harmony import */ var _NewPostNotification__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./NewPostNotification */ "./src/forum/components/NewPostNotification.js");
/* harmony import */ var _SubscriptionMenu__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./SubscriptionMenu */ "./src/forum/components/SubscriptionMenu.js");
/* harmony import */ var _SubscriptionMenuItem__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./SubscriptionMenuItem */ "./src/forum/components/SubscriptionMenuItem.js");






var components = {
  FollowingPageFilterDropdown: _FollowingPageFilterDropdown__WEBPACK_IMPORTED_MODULE_0__["default"],
  NewDiscussionNotification: _NewDiscussionNotification__WEBPACK_IMPORTED_MODULE_1__["default"],
  NewDiscussionTagNotification: _NewDiscussionTagNotification__WEBPACK_IMPORTED_MODULE_2__["default"],
  NewPostNotification: _NewPostNotification__WEBPACK_IMPORTED_MODULE_3__["default"],
  SubscriptionMenu: _SubscriptionMenu__WEBPACK_IMPORTED_MODULE_4__["default"],
  SubscriptionMenuItem: _SubscriptionMenuItem__WEBPACK_IMPORTED_MODULE_5__["default"]
};

/***/ }),

/***/ "./src/forum/icons.js":
/*!****************************!*\
  !*** ./src/forum/icons.js ***!
  \****************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
var _false$follow$lurk$ig;

/* harmony default export */ __webpack_exports__["default"] = (_false$follow$lurk$ig = {}, _false$follow$lurk$ig[false] = 'fas fa-star', _false$follow$lurk$ig.follow = 'fas fa-star', _false$follow$lurk$ig.lurk = 'fas fa-comments', _false$follow$lurk$ig.ignore = 'fas fa-bell-slash', _false$follow$lurk$ig.hide = 'fas fa-eye-slash', _false$follow$lurk$ig);

/***/ }),

/***/ "./src/forum/index.js":
/*!****************************!*\
  !*** ./src/forum/index.js ***!
  \****************************/
/*! exports provided: components, utils */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var flarum_extend__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! flarum/extend */ "flarum/extend");
/* harmony import */ var flarum_extend__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(flarum_extend__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var flarum_components_NotificationGrid__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! flarum/components/NotificationGrid */ "flarum/components/NotificationGrid");
/* harmony import */ var flarum_components_NotificationGrid__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(flarum_components_NotificationGrid__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _addSubscriptionControls__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./addSubscriptionControls */ "./src/forum/addSubscriptionControls.js");
/* harmony import */ var _addFollowedTagsDiscussions__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./addFollowedTagsDiscussions */ "./src/forum/addFollowedTagsDiscussions.js");
/* harmony import */ var _components_NewDiscussionNotification__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./components/NewDiscussionNotification */ "./src/forum/components/NewDiscussionNotification.js");
/* harmony import */ var _components_NewPostNotification__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./components/NewPostNotification */ "./src/forum/components/NewPostNotification.js");
/* harmony import */ var _components_NewDiscussionTagNotification__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./components/NewDiscussionTagNotification */ "./src/forum/components/NewDiscussionTagNotification.js");
/* harmony import */ var _addDiscussionBadge__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./addDiscussionBadge */ "./src/forum/addDiscussionBadge.js");
/* harmony import */ var _addPreferences__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./addPreferences */ "./src/forum/addPreferences.js");
/* harmony import */ var _components__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ./components */ "./src/forum/components/index.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "components", function() { return _components__WEBPACK_IMPORTED_MODULE_9__["components"]; });

/* harmony import */ var _utils__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ./utils */ "./src/forum/utils/index.js");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "utils", function() { return _utils__WEBPACK_IMPORTED_MODULE_10__["utils"]; });












app.initializers.add('fof/follow-tags', function () {
  if (!app.initializers.has('flarum-tags')) {
    console.error('[fof/follow-tags] flarum/tags is not enabled');
    return;
  }

  Object(_addSubscriptionControls__WEBPACK_IMPORTED_MODULE_2__["default"])();

  if (app.initializers.has('subscriptions')) {
    Object(_addDiscussionBadge__WEBPACK_IMPORTED_MODULE_7__["default"])();
    Object(_addFollowedTagsDiscussions__WEBPACK_IMPORTED_MODULE_3__["default"])();
    Object(_addPreferences__WEBPACK_IMPORTED_MODULE_8__["default"])();
  }

  app.notificationComponents.newPostInTag = _components_NewPostNotification__WEBPACK_IMPORTED_MODULE_5__["default"];
  app.notificationComponents.newDiscussionInTag = _components_NewDiscussionNotification__WEBPACK_IMPORTED_MODULE_4__["default"];
  app.notificationComponents.newDiscussionTag = _components_NewDiscussionTagNotification__WEBPACK_IMPORTED_MODULE_6__["default"];
  Object(flarum_extend__WEBPACK_IMPORTED_MODULE_0__["extend"])(flarum_components_NotificationGrid__WEBPACK_IMPORTED_MODULE_1___default.a.prototype, 'notificationTypes', function (items) {
    items.add('newDiscussionInTag', {
      name: 'newDiscussionInTag',
      icon: 'fas fa-user-tag',
      label: app.translator.trans('fof-follow-tags.forum.settings.notify_new_discussion_label')
    });
    items.add('newPostInTag', {
      name: 'newPostInTag',
      icon: 'fas fa-user-tag',
      label: app.translator.trans('fof-follow-tags.forum.settings.notify_new_post_label')
    });
    items.add('newDiscussionTag', {
      name: 'newDiscussionTag',
      icon: 'fas fa-user-tag',
      label: app.translator.trans('fof-follow-tags.forum.settings.notify_new_discussion_tag_label')
    });
  });
}, -1);

/***/ }),

/***/ "./src/forum/utils/getDefaultFollowingFiltering.js":
/*!*********************************************************!*\
  !*** ./src/forum/utils/getDefaultFollowingFiltering.js ***!
  \*********************************************************/
/*! exports provided: options, getOptions, getDefaultFollowingFiltering */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "options", function() { return options; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getOptions", function() { return getOptions; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getDefaultFollowingFiltering", function() { return getDefaultFollowingFiltering; });
/* harmony import */ var _common_utils_followingPageOptions__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../common/utils/followingPageOptions */ "./src/common/utils/followingPageOptions.js");

var options;
var getOptions = function getOptions() {
  if (!options) {
    options = Object(_common_utils_followingPageOptions__WEBPACK_IMPORTED_MODULE_0__["default"])('forum.index.following');
  }

  return options;
};
var getDefaultFollowingFiltering = function getDefaultFollowingFiltering() {
  getOptions();
  var value = app.data['fof-follow-tags.following_page_default'];

  if (!options[value]) {
    value = null;
  }

  if (app.session.user) {
    var preference = app.session.user.preferences().followTagsPageDefault;

    if (options[preference]) {
      value = preference;
    }
  }

  return value || 'none';
};

/***/ }),

/***/ "./src/forum/utils/index.js":
/*!**********************************!*\
  !*** ./src/forum/utils/index.js ***!
  \**********************************/
/*! exports provided: utils */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "utils", function() { return utils; });
/* harmony import */ var _babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/esm/extends */ "./node_modules/@babel/runtime/helpers/esm/extends.js");
/* harmony import */ var _getDefaultFollowingFiltering__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./getDefaultFollowingFiltering */ "./src/forum/utils/getDefaultFollowingFiltering.js");
/* harmony import */ var _isFollowingPage__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./isFollowingPage */ "./src/forum/utils/isFollowingPage.js");
/* harmony import */ var _common__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../common */ "./src/common/index.js");




var utils = Object(_babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_0__["default"])({
  options: _getDefaultFollowingFiltering__WEBPACK_IMPORTED_MODULE_1__["options"],
  getOptions: _getDefaultFollowingFiltering__WEBPACK_IMPORTED_MODULE_1__["getOptions"],
  getDefaultFollowingFiltering: _getDefaultFollowingFiltering__WEBPACK_IMPORTED_MODULE_1__["getDefaultFollowingFiltering"],
  isFollowingPage: _isFollowingPage__WEBPACK_IMPORTED_MODULE_2__["default"]
}, _common__WEBPACK_IMPORTED_MODULE_3__["utils"]);

/***/ }),

/***/ "./src/forum/utils/isFollowingPage.js":
/*!********************************************!*\
  !*** ./src/forum/utils/isFollowingPage.js ***!
  \********************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony default export */ __webpack_exports__["default"] = (function () {
  return m.route.get().includes(app.route('following'));
});

/***/ }),

/***/ "flarum/Component":
/*!**************************************************!*\
  !*** external "flarum.core.compat['Component']" ***!
  \**************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = flarum.core.compat['Component'];

/***/ }),

/***/ "flarum/Model":
/*!**********************************************!*\
  !*** external "flarum.core.compat['Model']" ***!
  \**********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = flarum.core.compat['Model'];

/***/ }),

/***/ "flarum/components/Badge":
/*!*********************************************************!*\
  !*** external "flarum.core.compat['components/Badge']" ***!
  \*********************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = flarum.core.compat['components/Badge'];

/***/ }),

/***/ "flarum/components/Button":
/*!**********************************************************!*\
  !*** external "flarum.core.compat['components/Button']" ***!
  \**********************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = flarum.core.compat['components/Button'];

/***/ }),

/***/ "flarum/components/Dropdown":
/*!************************************************************!*\
  !*** external "flarum.core.compat['components/Dropdown']" ***!
  \************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = flarum.core.compat['components/Dropdown'];

/***/ }),

/***/ "flarum/components/FieldSet":
/*!************************************************************!*\
  !*** external "flarum.core.compat['components/FieldSet']" ***!
  \************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = flarum.core.compat['components/FieldSet'];

/***/ }),

/***/ "flarum/components/IndexPage":
/*!*************************************************************!*\
  !*** external "flarum.core.compat['components/IndexPage']" ***!
  \*************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = flarum.core.compat['components/IndexPage'];

/***/ }),

/***/ "flarum/components/Notification":
/*!****************************************************************!*\
  !*** external "flarum.core.compat['components/Notification']" ***!
  \****************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = flarum.core.compat['components/Notification'];

/***/ }),

/***/ "flarum/components/NotificationGrid":
/*!********************************************************************!*\
  !*** external "flarum.core.compat['components/NotificationGrid']" ***!
  \********************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = flarum.core.compat['components/NotificationGrid'];

/***/ }),

/***/ "flarum/components/Select":
/*!**********************************************************!*\
  !*** external "flarum.core.compat['components/Select']" ***!
  \**********************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = flarum.core.compat['components/Select'];

/***/ }),

/***/ "flarum/components/SettingsPage":
/*!****************************************************************!*\
  !*** external "flarum.core.compat['components/SettingsPage']" ***!
  \****************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = flarum.core.compat['components/SettingsPage'];

/***/ }),

/***/ "flarum/extend":
/*!***********************************************!*\
  !*** external "flarum.core.compat['extend']" ***!
  \***********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = flarum.core.compat['extend'];

/***/ }),

/***/ "flarum/helpers/icon":
/*!*****************************************************!*\
  !*** external "flarum.core.compat['helpers/icon']" ***!
  \*****************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = flarum.core.compat['helpers/icon'];

/***/ }),

/***/ "flarum/models/Discussion":
/*!**********************************************************!*\
  !*** external "flarum.core.compat['models/Discussion']" ***!
  \**********************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = flarum.core.compat['models/Discussion'];

/***/ }),

/***/ "flarum/states/DiscussionListState":
/*!*******************************************************************!*\
  !*** external "flarum.core.compat['states/DiscussionListState']" ***!
  \*******************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = flarum.core.compat['states/DiscussionListState'];

/***/ }),

/***/ "flarum/utils/extractText":
/*!**********************************************************!*\
  !*** external "flarum.core.compat['utils/extractText']" ***!
  \**********************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = flarum.core.compat['utils/extractText'];

/***/ })

/******/ });
//# sourceMappingURL=forum.js.map