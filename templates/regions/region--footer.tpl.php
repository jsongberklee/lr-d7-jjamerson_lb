<?php if ($content): ?>
  <section class="<?php print $classes; ?>" <?php print $attributes ?>>
    <div class="container">
      <nav class="footer-menus">
        <?php print $content; ?>
      </nav>
      <div class="footer-block">
          <ul class="bar">
				    <li><a href="/about-us/services/interlibrary-loan-request-form">Interlibrary Loan Request</a></li>
				    <li><a href="http://ask.library.berklee.edu/widget_standalone.php?la_widget_id=3283">Ask A Librarian</a></li>
				    <li><a href="http://ask.library.berklee.edu/">FAQ</a></li>
				    <li><a href="http://www.berklee.edu/">Berklee.edu</a></li>
				    <li><a href="/about-us/contact-us">Contact Us</a></li>
					<li><a href="http://learningcenter.berklee.edu/">Learning Center</a></li>
					<li><a href="http://learningcenter.berklee.edu/Core_Music_Tutoring/">Tutoring Services</a></li>
				  </ul>
					<div class="copy">Copyright &copy; <?php echo date('Y'); ?></div>
      </div>
    </div>
  </section>
<?php endif; ?>
