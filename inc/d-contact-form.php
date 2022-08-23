<?php
/** 
 * Custom Contact Form UI
 */
?>
<div class="d_contact_wrap">
<div class="d_contact_msg"></div>
<form id="custom-contact-frm" class="styled" action="" method="post">
    <input type="hidden" name="action" value="save_custom_contact">
    <input type="hidden" name="custom_contact_nonce" value="<?php echo wp_create_nonce('differenz-contact-nonce'); ?>"/>
        <fieldset>
          <legend><?php esc_html_e('Contact Form', 'custom-contacts'); ?></legend>
          <ol>
            <li class="form-row">
              <label><?php esc_html_e('Name', 'custom-contacts'); ?>:</label>
              <input id="name" name="name" type="text" class="text-input required default dfm-field" placeholder="<?php esc_attr_e('Enter Your Name', 'custom-contacts') ?>" />
            </li>
            <li class="form-row">
              <label><?php esc_html_e('Email', 'custom-contacts'); ?>:</label>
              <input id="email" type="text" class="text-input required email default dfm-field" name="email" placeholder="<?php esc_attr_e('Email', 'custom-contacts') ?>" />
            </li>
            <li class="form-row">
              <label><?php esc_html_e('Subject', 'custom-contacts'); ?>:</label>
              <input id="subject" type="text" class="text-input dfm-field" name="subject" placeholder="<?php esc_attr_e('Subject', 'custom-contacts') ?>"/>
            </li>
            <li class="form-row">
              <label><?php esc_html_e('Message', 'custom-contacts'); ?>:</label>
              <textarea id="message" class="text-area dfm-field" name="message" cols="40" rows="8" placeholder="<?php esc_attr_e('Message', 'custom-contacts') ?>"></textarea>
            </li>
            <li class="button-row text-right">
              <input class="btn-submit dfnz_btn_save" type="submit" value="<?php esc_attr_e('Submit', 'custom-contacts') ?>" name="dfm-submit" />
            </li>
          </ol>
        </fieldset>
      </form>
  </div>
