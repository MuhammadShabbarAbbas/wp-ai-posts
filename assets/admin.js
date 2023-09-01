/*!
 * jQuery namespaced 'Starter' plugin boilerplate
 * Author: @dougneiner
 * Further changes: @addyosmani
 * Licensed under the MIT license
 */
window.WP_AI_POSTS = window.WP_AI_POSTS || {};
(function (window, document, $, app, undefined) {

        'use strict';

        var $document;

        var defaults = {
            wrap: $('#wpaiposts-create-post'),
            prompts: $('#wpaiposts-create-post textarea'),
            create_posts_button: $('#submit_button_wpaiposts-create-post'),
        };

        app.init = function () {
            $document = $(document);

            // Setup the CMB2 object defaults.
            $.extend(app, defaults);

            // app.prompts.select2({tags: true, multiple: true});

            app.create_posts_button.on('click', app.create_posts)

            app.wrap.append("<div class='spinner'></div>");
        }

        app.create_posts = function (e) {
            e.preventDefault();
            const $self = $(this);

            const promises = [];
            app.prompts.each(function (index, prompt) {
                const $prompt = $(prompt);
                if ($.trim($prompt.val())) {
                    $('#wpaiposts-create-post .spinner').css('visibility', 'visible');
                    $self.prop("disabled", true);
                    app.wrap.append('<br>');
                    const promise = new Promise((resolve, reject) => {
                        $.ajax({
                            type: 'POST',
                            url: wpaiposts.ajax_url,
                            data: {
                                action: 'wp_ai_create_post',
                                data: {
                                    prompt: $prompt.val()
                                }
                            },
                            success: function (response) {
                                // Handle the success response
                                if (response.success) {
                                    app.wrap.append('<li style="color: darkgreen">' + $prompt.val().substring(0, 50) + '....: ' + response.message + '</li>')
                                } else {
                                    app.wrap.append('<li style="color: red">' + $prompt.val().substring(0, 50) + '....: ' + response.message + '</li>')
                                }
                                resolve(response);
                            },
                            error: function (xhr, status, error) {
                                // Handle the error response
                                app.wrap.append('<li style="color: red">' + $prompt.val().substring(0, 50) + '....: ' + error.message + '</li>')
                                reject(error);
                            },
                        });
                    });
                    promises.push(promise);
                }
            });

            Promise.all(promises)
                .then(results => {
                    $('#wpaiposts-create-post .spinner').css('visibility', 'hidden');
                    $self.prop("disabled", false);
                })
                .catch(error => {
                    // One or more promises have been rejected
                    // Handle the error here
                    $('#wpaiposts-create-post .spinner').css('visibility', 'hidden');
                    $self.prop("disabled", false);
                });

        }

        app.trigger = function (evtName) {
            var args = Array.prototype.slice.call(arguments, 1);
            args.push(app);
            $document.trigger(evtName, args);
        };

        app.triggerElement = function ($el, evtName) {
            var args = Array.prototype.slice.call(arguments, 2);
            args.push(app);
            $el.trigger(evtName, args);
        };

        $(app.init);

    }
)(window, document, jQuery, window.WP_AI_POSTS);