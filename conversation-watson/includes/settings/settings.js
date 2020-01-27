if (typeof jQuery.fn.prop != 'function') {
  jQuery.fn.prop = jQuery.fn.attr;
}

if (typeof localStorage !== 'undefined') {
  try {
      localStorage.setItem('localStorage', 1);
      localStorage.removeItem('localStorage');
  } catch (e) {
      Storage.prototype._setItem = Storage.prototype.setItem;
      Storage.prototype.setItem = function() {};
  }
}

function luminance(hex) {
  let result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);

  if (!Array.isArray(result) || result.length !== 4) {
    return 0;
  }

  let rgb = result.slice(1, 4).map(function(val) {
    val = parseInt(val, 16) / 255;

    if (val <= 0.03928 ) {
			return val / 12.92
		} else {
			return Math.pow((val + 0.055) / 1.055, 2.4);
		}
  });

  return 0.2126 * rgb[0] + 0.7152 * rgb[1] + 0.0722 * rgb[2];
}

function switch_tab(tab_name) {
  jQuery('.tab-page').hide();
  jQuery(`.tab-page.${tab_name}_page`).show();
  jQuery('.nav-tab-wrapper .nav-tab-active').removeClass('nav-tab-active');
  jQuery(`.nav-tab-wrapper .${tab_name}_tab`).addClass('nav-tab-active');

  sessionStorage.setItem('watsonconv_active_tab_' + page_data.hook_suffix, tab_name);
}

jQuery(document).ready(function($) {

  if (sessionStorage.getItem('watsonconv_active_tab_' + page_data.hook_suffix)) {
    switch_tab(sessionStorage.getItem('watsonconv_active_tab_' + page_data.hook_suffix));
  }

  $(document).tooltip({
    show: 200,
    hide: 200
  });

  $('#error_expand')
    .on('click', function(e) {
      e.preventDefault();

      $('#error_response').toggle(200);
    })

  // ---- Main Setup Section ----
  $('input[name="watsonconv_credentials[type]"]')
  .on('change', function() {
    if (this.value == 'basic') {
      $('.basic_cred').closest('tr,p').show();
      $('.iam_cred').closest('tr,p').hide();
    } else if (this.value == 'iam') {
      $('.basic_cred').closest('tr,p').hide();
      $('.iam_cred').closest('tr,p').show();
    }
  })
  .filter('input:checked')
  .trigger('change');


  $('#watsonconv_enabled')
    .on('change', function() {
      if (this.checked) {
        $('input.watsonconv_credentials').prop('disabled', false);
      } else {
        $('input.watsonconv_credentials').prop('disabled', true);
      }
    })
    .trigger('change');

  // ---- Having Issues? Section ----

  // Button "Copy X messages to clipboard"
  $('#watsonconv_copy_log_messages').click( function () {
    // Block with all the messages
    let logContainer = $('#watsonconv_log_container');
    // Variable to store result text
    let logText = "";

    // Elements with events and total number of them
    let eventElements = $('.watsonconv_log_event');
    let eventsNumber = eventElements.length;

    // Iterating through events
    for (let i = 0; i < eventsNumber; i++) {
      // Event text with separator
      let eventFullText = "--------------------\r\n";

      // Getting event element and its element id
      let eventElement = eventElements[i];
      let eventId = eventElement.id.split("_")[3];

      // Getting event text and adding to event text
      let eventText = eventElement.textContent;
      eventFullText += eventText + "\r\n\r\n";

      // Getting messages for that event and message details
      let eventMessages = $('.watsonconv_event_message_' + eventId);
      let eventMessagesNumber = eventMessages.length;
      let eventDetails = $('.watsonconv_event_details_' + eventId);
      let eventDetailsNumber = eventDetails.length;

      // Adding message texts and message details to event text
      for (let j = 0; j < eventMessagesNumber; j++) {
          messageText = eventMessages[j].textContent;
          eventFullText += messageText + "\r\n";
          detailsText = eventDetails[j].textContent;
          eventFullText += detailsText + "\r\n\r\n";
      }

      // Adding message
      logText += eventFullText;
  }

  // Creating textarea element for text copying
  $('body').append('<textarea id="proxyTextarea"></textarea>');
  // Setting the style to hide it, filling it with text and selecting text in it
  $('#proxyTextarea')
    .css("height", "10px")
    .css("width", "10px")
    .css("position", "fixed")
    .css("top", "-100px")
    .text(logText)
    .select();
  // Copying text
  document.execCommand('copy');
  // Removing textarea
  $('#proxyTextarea').remove();

  // Notifying user about copying
  alert("Log copied to clipboard");
});

  // ---- Voice Calling Section ----

  $('input[name="watsonconv_use_twilio"]')
    .on('change', function() {
      if (this.value == 'yes') {
        $('span.twilio_settings').parent().show();
        $('p.twilio_settings').show();

        $('input[id="watsonconv_twilio_sid"]').closest('table').show();
        $('input[id="watsonconv_call_tooltip"]').closest('table').show();

        $('input[id="watsonconv_twilio_sid"]').closest('table').find(':input').prop('disabled', false);
        $('input[id="watsonconv_twilio_sid"]').closest('table').find(':input').prop('disabled', false);
      } else {
        $('span.twilio_settings').parent().css('display', 'none');
        $('p.twilio_settings').css('display', 'none');

        $('input[id="watsonconv_twilio_sid"]').closest('table').css('display', 'none');
        $('input[id="watsonconv_call_tooltip"]').closest('table').css('display', 'none');

        $('input[id="watsonconv_twilio_sid"]').closest('table').find(':input').prop('disabled', true);
        $('input[id="watsonconv_twilio_sid"]').closest('table').find(':input').prop('disabled', true);
      }
    })
    .filter('input:checked')
    .trigger('change');

  // ---- Sending Context Variables Section ----

    $('input[name="watsonconv_smtp_setting_enabled"]')
        .on('change', function () {
            if (this.value == 1) {
                $('#watsonconv_mail_vars_smtp_host').closest('table').show();
            } else {
                $('#watsonconv_mail_vars_smtp_host').closest('table').hide();
            }
        })
        .filter('input:checked')
        .trigger('change');

    $('input[name="watsonconv_mail_vars_smtp_authentication"]')
        .on('change', function () {
            if (this.value == 1) {
                $('input[id="watsonconv_mail_vars_smtp_username"]').closest('tr').show();
                $('input[id="watsonconv_mail_vars_smtp_password"]').closest('tr').show();
            } else {
                $('input[id="watsonconv_mail_vars_smtp_username"]').closest('tr').hide();
                $('input[id="watsonconv_mail_vars_smtp_password"]').closest('tr').hide();
            }
        })
        .filter('input:checked')
        .trigger('change');

    $('#watsonconv_notification_send_test')
        .on('click', function () {
            let emails = $('#watsonconv_notification_email_to').val();
            let siteUrl = window.location.origin;
            let restRoute = "/index.php?rest_route=/watsonconv/v1/test-notification";
            let fullUrl = siteUrl + restRoute;
            $.ajax({
                type: "POST",
                url: fullUrl,
                data: {emails: emails},
                beforeSend: function ( xhr ) {
                    xhr.setRequestHeader( 'X-WP-Nonce', wpApiSettings.nonce);
                },
                success: function( response ) {
                    $(".notice-info").after(response);
                },
                error: function(message) {
                    $(".notice-info").after(
                        '<div class="error settings-error notice is-dismissible"><p>Error occurred:<br>' + message.responseText + '</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Hide this notice</span></button></div>');
                }
            })
        });

  // ---- Senfing test notification ---
    $('#watsonconv_button_check_email_sending')
        .on('click', function () {
            let email = $('#watsonconv_mail_vars_email_address_to').val();
            let siteUrl = window.location.origin;
            let restRoute = "/index.php?rest_route=/watsonconv/v1/test-email";
            let fullUrl = siteUrl + restRoute;
            $.ajax({
                type: "POST",
                url: fullUrl,
                data: {email: email},
                beforeSend: function ( xhr ) {
                    xhr.setRequestHeader( 'X-WP-Nonce', wpApiSettings.nonce);
                },
                success: function( response ) {
                    $(".notice-info").after(response);
                },
                error: function(message) {
                    // console.log(message.responseText);
                    $(".notice-info").after(
                        '<div class="error settings-error notice is-dismissible"><p>Error occurred:<br>' + message.responseText + '</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Hide this notice</span></button></div>');
                }
            })
            // $.post( fullUrl, {email: email}, function( response ) {
            //     $(".notice-info").after(response);
            // });
        });

  // ---- Rate Limiting Section ----

  $('input[name="watsonconv_use_limit"]')
    .on('change', function() {
      $('#watsonconv_limit, #watsonconv_interval, #watsonconv_limit_message').prop('disabled', this.value == 'no');
    })
    .filter('input:checked')
    .trigger('change');

  $('input[name="watsonconv_use_client_limit"]')
    .on('change', function() {
      $('#watsonconv_client_limit, #watsonconv_client_interval, #watsonconv_client_limit_message').prop('disabled', this.value == 'no');
    })
    .filter('input:checked')
    .trigger('change');

  // ----- Chat history collection section -----

  // Elements of chat history collection settings
  const historyFields = {
    // history collection on/off
    main: 'input[name="watsonconv_history_enabled"]',
    // debug information collection on/off
    debug: 'input[name="watsonconv_history_debug_enabled"]',
    // history limit on/off
    limit: 'input[name="watsonconv_history_limit_enabled"]',
    // history limit number
    limitNumber: 'input[name="watsonconv_history_limit"]'
  };

  // History collection switch
  $(historyFields.main)
    .on('change', function() {
      const valueEnabled = (this.value === "yes");
      if(valueEnabled) {
        $(historyFields.debug).closest('tr').show();
        $(historyFields.limit).closest('tr').show();
        $(historyFields.limitNumber).closest('tr').show();
      }
      else {
        $(historyFields.debug).closest('tr').hide();
        $(historyFields.limit).closest('tr').hide();
        $(historyFields.limitNumber).closest('tr').hide();
      }
    })
    .filter('input:checked')
    .trigger('change');

  // History limit switch
  $(historyFields.limit)
    .on('change', function() {
      $(historyFields.limitNumber).prop('disabled', this.value === 'no');
    })
    .filter('input:checked')
    .trigger('change');


    // ------ Notification section ------
    $('input[name="watsonconv_notification_enabled"]')
        .on('change', function () {
            const valueEnabled = this.value === "yes";
            const ctrls = $(
                'input[name="watsonconv_notification_email_to"] ,' +
                'input[name="watsonconv_notification_summary_interval"] ,' +
                'input[name="watsonconv_notification_send_test"] , ' + 
                '#watsonconv_notification_send_test'
            );
            // ctrls.prop('disabled', !valueEnabled);
            if (valueEnabled) {
                ctrls.closest('tr').show();
            } else {
                ctrls.closest('tr').hide();
            }
        })
        .filter('input:checked')
        .trigger('change');

  // ------ Behaviour section ------

  $('input[name="watsonconv_show_on"]')
    .on('change', function() {
      if (this.value === 'only') {
        $('span.show_on_only').show();
        $('fieldset.show_on_only').closest('tr').show();
      } else {
        $('span.show_on_only').css('display', 'none');
        $('fieldset.show_on_only').closest('tr').css('display', 'none');
      }
    })
    .filter('input:checked')
    .trigger('change');

  $('input[id="select_all_pages"]')
    .on('change', function() {
      $('input[name="watsonconv_pages[]"]').prop('checked', this.checked);
    })

  $('input[id="select_all_posts"]')
    .on('change', function() {
      $('input[name="watsonconv_posts[]"]').prop('checked', this.checked);
    })

  $('input[id="select_all_cats"]')
    .on('change', function() {
      $('input[name="watsonconv_categories[]"]').prop('checked', this.checked);
    })

  // ------ Appearance Section ------

  $('input[name="watsonconv_full_screen[mode]"]')
    .on('change', function() {
      if (this.value == 'mobile') {
        $('#watsonconv_full_screen_query').hide();
        $('#watsonconv_full_screen_max_width').show();
      } else if (this.value == 'custom') {
        $('#watsonconv_full_screen_query').show();
        $('#watsonconv_full_screen_max_width').hide();
      } else {
        $('#watsonconv_full_screen_query').hide();
        $('#watsonconv_full_screen_max_width').hide();
      }
    })
    .filter('input:checked')
    .trigger('change');

  $('#watsonconv_color')
    .wpColorPicker({
      palettes: true,
      change: function() {
        $('#watson-box #watson-header, #message-container #messages .watson-message, #watson-fab, #message-send')
          .css({
            'background-color': this.value,
            'color': luminance(this.value) > 0.5 ? 'black' : 'white'
          });

        $('#message-send svg')
        .css({
          'fill': luminance(this.value) > 0.5 ? 'black' : 'white'
        })
      }
    });

  $('#watsonconv_font_size')
    .on('change', function() {
      let size = $('input[name="watsonconv_size"]:checked').val();

      $('#watson-box .watson-font').css('font-size', this.value + 'pt');
      $('#watson-box').css('width', (0.825 * size + 4.2 * this.value) + 'pt');
    });

  $('input[name="watsonconv_size"]')
    .on('change', function() {
      let fontSize = $('#watsonconv_font_size').val();

      $('#watson-box').css('width', (0.825 * this.value + 4.2 * fontSize) + 'pt');
      $('#message-container').css('height', this.value + 'pt');
    });

  $('input[name="watsonconv_send_btn"]')
    .on('change', function() {
      if (this.value == 'no') {
        $('#message-send').hide();
      } else {
        $('#message-send').show();
      }
    })
    .filter('input:checked')
    .trigger('change');

  $('#watsonconv_title')
    .on('input', function() {
      $('#watson-title').text(this.value)
    });

  $('#watsonconv_clear_text')
    .on('input', function() {
      $('#watson-clear-messages').text(this.value)
    });

  $('#watsonconv_message_prompt')
    .on('input', function() {
      $('#watson-message-input').attr('placeholder', this.value)
    });

  $('input[name="watsonconv_fab_icon_pos"]')
    .on('change', function() {
      if (this.value == 'left') {
        $('.fab-icon-left').show();
        $('.fab-icon-right').hide();
      } else if (this.value == 'right') {
        $('.fab-icon-left').hide();
        $('.fab-icon-right').show();
      } else {
        $('.fab-icon-left').hide();
        $('.fab-icon-right').hide();
      }
    })
    .filter('input:checked')
    .trigger('change');

  $('#watsonconv_fab_text')
    .on('input', function() {
      if (this.value) {
        $('#watson-fab-text').show().text(this.value)
      } else {
        $('#watson-fab-text').hide().text(this.value);
      }
    })
    .trigger('input');

  $('#watsonconv_fab_icon_size')
    .on('change', function() {
      $('#watson-fab-icon').css('font-size', this.value + 'pt');
    });

  $('#watsonconv_fab_text_size')
    .on('change', function() {
      $('#watson-fab-text').css('font-size', this.value + 'pt');
    });

  $('#watsonconv_credentials_link')
      .on('click', function () {
         $('#watsonconv_credentials_description').css('display', 'block');
         return false;
      });

    $('#wpbody-content')
        .on('click', '.notice-dismiss', function () {
            $(this).parent().remove();
        });

    $('#watsonconv_custom_logo')
        .on('change', function () {

            const file = this.files[0];
            const fileType = file['type'];
            const allowedImageTypes = ['image/jpg', 'image/jpeg', 'image/png', 'image/gif', 'image/ico'];
            $(this).parent().find('.logo-error').remove();

            if ($.inArray(fileType, allowedImageTypes) < 0) {
                $(this).parent().append(
                    '<span class="logo-error">' +
                    '*' + fileType + ' - wrong file type, the chatbot logo must be a type image: jpg, jpeg, png, gif, ico.' +
                    '</span>');
                return false;
            }
            const getImagePath = URL.createObjectURL(file);
            $('#watson-logo').css('background-image', 'url("' + getImagePath + '")');
        });

    $('input[name="watsonconv_logo"]')
        .on('change', function () {
            if (this.value == 'custom') {
                $('#watsonconv_custom_logo').closest('tr').show();
            } else {
                $('#watsonconv_custom_logo').closest('tr').hide();
            }

            if (this.value == 'off') {
                $('#watson-logo').css('display', 'none');
                $('#watson-title').css('margin-left', '0');
            } else {
                $('#watson-logo').css('display', 'block');
                $('#watson-title').css('margin-left', '35px');
            }

            const watsonLogoOption = this.value;
            const siteUrl = window.location.origin;
            const restRoute = "/index.php?rest_route=/watsonconv/v1/get-logo";
            const fullUrl = siteUrl + restRoute;

            $.ajax({
                type: "POST",
                url: fullUrl,
                data: {watsonLogoOption: watsonLogoOption},
                beforeSend: function ( xhr ) {
                    xhr.setRequestHeader( 'X-WP-Nonce', wpApiSettings.nonce);
                },
                success: function( response ) {
                    if (response) {
                        $('#watson-logo').css('background-image', 'url("' + response + '")');
                    }
                },
                error: function(message) {
                    console.log(message.responseText);
                    $(this).parent().append('<span class="logo-type-error">*Error occurred' + message.responseText + '</span>');
                }
            })
        })
        .filter('input:checked')
        .trigger('change');

    $('input[name="watsonconv_typing_delay_from_plugin[typing]"]')
        .on('change', function() {
            if (this.value == 'yes') {
                $('#watsonconv_typing_max_waiting_time').hide();
                $('#watsonconv_typing_delay_time').show();
            } else if (this.value == 'waiting_ibm_response') {
                $('#watsonconv_typing_max_waiting_time').show();
                $('#watsonconv_typing_delay_time').hide();
            } else {
                $('#watsonconv_typing_max_waiting_time').hide();
                $('#watsonconv_typing_delay_time').hide();
            }
        })
        .filter('input:checked')
        .trigger('change');

  // Watson Assistant Credentials section
  // Function to check V1 credentials in Assistant URL field
  let checkCredentialsV1 = function() {
    if($('#watsonconv_workspace_url').length) {
      let apiV1Regex = /\/api\/v1\/workspaces\/\w{8}-\w{4}-\w{4}-\w{4}-\w{12}\/message/;
      let apiPath = $('#watsonconv_workspace_url').val();
      
      if(apiPath.match(apiV1Regex) !== null) {
        $("#v1_url_notice").css("display", "block");
      }
      else {
        $("#v1_url_notice").css("display", "none");
      }
    }
  }
  checkCredentialsV1();
  $('#watsonconv_workspace_url').on('input', checkCredentialsV1);
});
