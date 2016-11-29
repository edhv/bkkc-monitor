//var base_url = 'http://www.cultureleatlasbrabant.nl/monitor/site';
//var base_url = 'http://localhost/Cab_Monitor/site';
var base_url = wordpress_vars['wpurl'];

jQuery(document).ready(function($){




 


  jQuery("#get_organisations").click(function() {
    jQuery(".recipients_list").empty();
    jQuery.ajax({
          type:'GET',
          data:{action:'get_organisations',query_category:jQuery("#select_organisations").val()},
          url: base_url+"/wp-admin/admin-ajax.php",
          success: function(value) {


            var organisations_list = jQuery.parseJSON(value);
            jQuery.each(organisations_list, function(index, value) {
              //console.log(organisation);
              jQuery(".recipients_list").append("<li><input type='checkbox' name='mail_recipient[]' value='"+value['id']+"' checked/><span class='label'>"+value['name']+'</a></li>');
             });
          

            //var recipients_list = jQuery(".recipients_list");
          }
      });


  });


    // when test mail is clicked
    jQuery("#test_mail").click(function() {

        jQuery("#mailer-metabox_test-box .loader").show();

        jQuery.ajax({
            type:'GET',
            data:{action:'get_organisations',query_category:'vragenlijst_organisations'},
            url: base_url+"/wp-admin/admin-ajax.php",
            success: function(value) {

                var recipients = JSON.parse(value);
                var randomRecipient = recipients[ (Math.floor((Math.random()*(recipients.length-1)))+1) ]

                //
                send_mail(true, [String(randomRecipient.id)]);

            }
        });


        //    send_mail(true, jQuery("#test_mail_recipient").val());

    });
    

    // when send mail is clicked
    jQuery("#send_mail").click(function() {

        var recipients = [];
        list = jQuery(".recipients_list").find('input:checkbox:checked');

        list.each(function(index) {
            recipients.push( jQuery(this).val() );
        });
        send_mail(false, recipients);
    });

});


function send_mail(test_mail, recipients) {

    var log;

    //console.log(jQuery(".recipients_list").find('input'));
    if (!test_mail) {
        jQuery("#mailer-metabox_send-box .loader").show();
    }

    if (!test_mail) {
        log = jQuery("#mailer-metabox_send-box .send_mail_log");
    } else {
        log = jQuery("#mailer-metabox_test-box .send_mail_log");
    }


    var recipients_object = '';


    var from_address = jQuery("#acf-field-mail_from").val();
    var bcc_address = jQuery("#acf-field-mail_bcc").val();
    var email_body = tinyMCE.activeEditor.getContent();


    // if (test_mail) {

    //   recipients_object = jQuery(".recipients_list").find('input:checkbox:checked');
    //   random_recipient = Math.floor((Math.random()*(recipients_object.length-1))+1);
    //   recipients_object = recipients_object.eq(random_recipient);

    // } else {
    //   recipients_object = jQuery(".recipients_list").find('input:checkbox:checked');
    // }

    var recipients_list = new Array();

    var mails_sent = 0;

    if (recipients.length == 0 ) {
      alert('U dient ten minste één organisatie te selecteren');
    }

    // Walk through each recipient in the recipients list
    for (var i = 0; i < recipients.length; i++) {
        

        // Show send status
        if (test_mail) {
            jQuery(".test_send_status").show();
        } else {
            jQuery(".send_status").show();
        }
        


        var organisation_id = recipients[i];
        // recipients_list.push(organisation_id);
        log.empty();


        var type = 'POST';
        var data = {action:'prepare_mail',subject:jQuery("#acf-field-mail_subject").val(),body:email_body,organisations:organisation_id,from:from_address,bcc:bcc_address,mail_id:post_id};
        if (test_mail) {
             data = {action:'prepare_mail',subject:jQuery("#acf-field-mail_subject").val(),body:email_body,organisations:organisation_id,from:from_address,bcc:bcc_address,mail_id:post_id,test_mail:jQuery("#test_mail_recipient").val()};
        }


        // Send mail to this organisation
        jQuery.ajax({
            type:type,
            data:data,
            url: base_url+"/wp-admin/admin-ajax.php",
            success: function(value) {
                    var mail_status = jQuery.parseJSON(value);
       
                    if (mail_status['status'] == 'success') {
                        log.prepend("<li><span class='status send'></span><span class='name'>"+mail_status['organisation']['name']+"</span></li>");
                    } else {
                        log.prepend("<li><span class='status failed'></span><span class='name'>"+mail_status['organisation']['name']+"</span></li>");
                    }
                
            }
        }).done(function() {
            mails_sent = mails_sent+1;

            if (mails_sent == recipients.length) {

                if (!test_mail) {
                    jQuery("#mailer-metabox_send-box .loader").hide();
                } else {
                    jQuery("#mailer-metabox_test-box .loader").hide();

                }

            }
        });

    };
    //recipients.each(function( index ) {
        
        // // Show spinner
        // spinner.show();

        // // Show send status
        // jQuery(".send_status").show();


        // var organisation_id = jQuery(this).val();
        // recipients_list.push(organisation_id);
        // jQuery(".send_mail_log").empty();


        // var type = 'POST';
        // var data = {action:'prepare_mail',subject:jQuery("#acf-field-mail_subject").val(),body:email_body,organisations:organisation_id,from:from_address,bcc:bcc_address,mail_id:post_id};
        // if (test_mail) {
        //     data = {action:'prepare_mail',subject:jQuery("#acf-field-mail_subject").val(),body:email_body,organisations:organisation_id,from:from_address,bcc:bcc_address,mail_id:post_id,test_mail:recipient};

        // }

        // // Send mail to this organisation
        // jQuery.ajax({
        //       type:type,
        //       data:data,
        //       url: base_url+"/wp-admin/admin-ajax.php",
        //       success: function(value) {
        //         console.log(value);
        //         if (!test_mail) {
        //         var mail_status = jQuery.parseJSON(value);


        //         if (mail_status['status'] == 'success') {
        //           jQuery(".send_mail_log").prepend("<li><span class='status send'></span><span class='name'>"+mail_status['organisation']['name']+"</span></li>");
        //         } else {
        //           jQuery(".send_mail_log").prepend("<li><span class='status failed'></span><span class='name'>"+mail_status['organisation']['name']+"</span></li>");
        //         }
        //         }
        //         //console.log(mail_status);
        //         //jQuery(this).html(value);
        //       }
        //   }).done(function() {
        //     mails_sent = mails_sent+1;

        //     if (mails_sent == recipients_object.length) {
        //         spinner.hide();
        //     }
        //   });

    //});

}


