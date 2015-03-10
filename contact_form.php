<?php
/**
 * Created by PhpStorm.
 * User: selly
 * Date: 3/10/15
 * Time: 2:41 PM
 */
?>							<h5>Leave A Message</h5>
<div class="form-wrapper clearfix">
  <form class="form-contact submit-form">
    <input type="hidden" name="todo" value="contact">
    <div class="inputs-wrapper">
      <input class="form-name validate-required" type="text" placeholder="Your Name" name="name">
      I'm reaching out because:
      <select name="reason">
        <option value="General">I'm passionate about climate change in general</option>
        <option value="Feedback">I want to provide feedback to you about the site/service</option>
        <option value="Help">I looking for a way to help change our climate change trajectory</option>
        <option value="Volunteering">I'm interested in volunteering</option>
        <option value="Contact">I'd like to get in touch</option>
        <option value="Other">Something else (please describe below!)</option>
      </select>
      <input class="form-email validate-required validate-email" type="text" placeholder="Your Email Address" name="email">
      <textarea class="form-message validate-required" name="message" placeholder="Your Message" rows="5"></textarea>
    </div>
    <input type="submit" class="send-form contact-submit" value="Submit Message">
    <div class="form-success">
      <span class="text-white">Message sent - Thanks for your enquiry. Your message confirmation code is:</span>
    </div>
    <div class="form-error">
      <span class="text-white">Please complete the fields correctly</span>
    </div>
    <div class="form-message">
    </div>
  </form>
</div>
