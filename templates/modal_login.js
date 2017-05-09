define(['jquery', 'core/notification', 'core/custom_interaction_events', 'core/modal'],
        function($, Notification, CustomEvents, Modal) {
 
    var SELECTORS = {
        LOGIN_BUTTON: '[data-action="login"]',
        CANCEL_BUTTON: '[data-action="cancel"]',
    };
 
    /**
     * Constructor for the Modal.
     *
     * @param {object} root The root jQuery element for the modal
     */
    var ModalLogin = function(root) {
        Modal.call(this, root);
 
        if (!this.getFooter().find(SELECTORS.LOGIN_BUTTON).length) {
            Notification.exception({message: 'No login button found'});
        }
 
        if (!this.getFooter().find(SELECTORS.CANCEL_BUTTON).length) {
            Notification.exception({message: 'No cancel button found'});
        }
    };
 
    ModalLogin.prototype = Object.create(Modal.prototype);
    ModalLogin.prototype.constructor = ModalLogin;
 
    /**
     * Set up all of the event handling for the modal.
     *
     * @method registerEventListeners
     */
    ModalLogin.prototype.registerEventListeners = function() {
        // Apply parent event listeners.
        Modal.prototype.registerEventListeners.call(this);
 
        this.getModal().on(CustomEvents.events.activate, SELECTORS.LOGIN_BUTTON, function(e, data) {
            // Add your logic for when the login button is clicked. This could include the form validation,
            // loading animations, error handling etc.
        }.bind(this));
 
        this.getModal().on(CustomEvents.events.activate, SELECTORS.CANCEL_BUTTON, function(e, data) {
            // Add your logic for when the cancel button is clicked.
        }.bind(this));
    };
 
    return ModalLogin;
});
