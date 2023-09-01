(function () {
    tinymce.PluginManager.add('chatgpt', function (editor, url) {
        editor.addButton('chatgpt', {
            text: 'ChatGPT',
            icon: false,
            onclick: function () {

                editor.windowManager.open({
                    title: 'ChatGPT Prompt',
                    body: [
                        {
                            type: 'textbox',
                            name: 'prompt',
                            label: 'Prompt:',
                            multiline: true,
                            minHeight: 100
                        }
                    ],
                    onsubmit: function (e) {
                        editor.setMode('readonly');
                        jQuery.ajax({
                            "url": wpaiposts.chat_gpt_api_url + "completions",
                            "method": "POST",
                            "timeout": 0,
                            "headers": {
                                "Authorization": "Bearer " + wpaiposts.chat_gpt_api_key,
                                "Content-Type": "application/json"
                            },
                            "data": JSON.stringify({
                                "model": "gpt-3.5-turbo",
                                "messages": [{"role": "user", "content": e.data.prompt}]
                            }),
                            success: function (response) {
                                // var response = JSON.parse(xhr.responseText);
                                // Insert the generated text into the editor
                                editor.insertContent(response.choices[0].message.content.trim());
                                editor.setMode('design');
                            },
                            error: function (error) {
                                console.log(error);
                                editor.setMode('design');
                                alert('Error in generating response')
                            }
                        });
                    }
                });

            }
        });
    })

})();