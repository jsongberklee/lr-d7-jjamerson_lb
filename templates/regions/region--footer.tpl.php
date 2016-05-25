<?php if ($content): ?>
  <section class="<?php print $classes; ?>" <?php print $attributes ?>>
    <div class="container">
      <nav class="footer-menus">
        <?php print $content; ?>
      </nav>
      <div class="footer-block">
          <span class="copy">Copyright &copy; <?php echo date('Y'); ?></span>
          <ul class="bar">
				    <li><a href="/about-us/services/interlibrary-loan-request-form">Interlibrary Loan Request</a></li>
				    <li><a href="http://ask.library.berklee.edu/widget_standalone.php?la_widget_id=3283" target="_blank">Ask A Librarian</a></li>
<!-- 				    <li><a href="#" onclick="if(navigator.userAgent.toLowerCase().indexOf('opera') != -1 &amp;&amp; window.event.preventDefault) window.event.preventDefault();this.newWindow = window.open('/_mibew/client.php?locale=en&amp;style=silver&amp;name=Type your name&amp;email=Type your email&amp;url='+escape(document.location.href)+'&amp;referrer='+escape(document.referrer), 'mibew', 'toolbar=0,scrollbars=0,location=0,status=1,menubar=0,width=640,height=480,resizable=1');this.newWindow.focus();this.newWindow.opener=window;return false;" style="margin: 0 .25em;">Ask A Librarian</a></li> -->
				    <li><a href="/about-us/frequently-asked-questions">FAQ</a></li>
				    <li><a href="http://www.berklee.edu/">Berklee.edu</a></li>
				    <li><a href="/about-us/contact-us">Contact Us</a></li>
					<li><a href="http://learningcenter.berklee.edu/">Learning Center</a></li>
					<li><a href="http://learningcenter.berklee.edu/Core_Music_Tutoring/">Tutoring Services</a></li>
				  </ul>
        </div>
    </div>
  </section>
<?php endif; ?>
