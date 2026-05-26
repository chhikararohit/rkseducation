    <!-- Footer -->
    <footer class="footer">
        <div class="container grid grid-3 gap-4">
            <div class="footer-col">
                <a href="#" class="footer-logo">RKS Temple Of Education</a>
                <p>Nurturing minds, building futures. We are committed to academic excellence and personal growth.</p>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
            <div class="footer-col">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="index.php#hero">Home</a></li>
                    <li><a href="index.php#about">About Us</a></li>
                    <li><a href="index.php#courses">Courses</a></li>
                    <li><a href="index.php#gallery">Gallery</a></li>
                    <li><a href="blogs.php">Blogs</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h3>Contact Info</h3>
                <ul class="footer-contact">
                    <li><i class="fas fa-map-marker-alt"></i> <span>Railway Rd, near court complex,<br>opposite Chirag
                            Garden, Ganaur 131101</span></li>
                    <li><i class="fas fa-phone-alt"></i> +91 98765 43210</li>
                    <li><i class="fas fa-envelope"></i> info@rkseducation.com</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom text-center">
            <p>&copy; <?php echo date("Y"); ?> RKS Temple Of Education. All Rights Reserved. Designed to Excel.</p>
        </div>
    </footer>

    <!-- Floating Actions -->
    <a href="https://wa.me/919876543210" class="floating-btn whatsapp-float" target="_blank" aria-label="WhatsApp Us">
        <i class="fab fa-whatsapp"></i>
    </a>
    <a href="tel:+919876543210" class="floating-btn call-float mobile-only" aria-label="Call Us">
        <i class="fas fa-phone-alt"></i>
    </a>

    <!-- Popup Modal -->
    <div id="notes-popup" class="popup-modal">
        <div class="popup-content">
            <span class="close-btn" onclick="closePopup()">&times;</span>
            <div class="popup-header">
                <h3>Get Free Handmade Notes!</h3>
                <p>Fill out the details below to download premium study materials (Class 8th to 12th).</p>
            </div>
            <form id="popup-form" onsubmit="downloadNotes(event)">
                <div class="form-group" style="text-align: left;">
                    <input type="text" id="pop-name" required placeholder="Your Name" style="width: 100%; padding: 0.75rem; margin-bottom: 1rem; border: 1px solid #cbd5e1; border-radius: var(--border-radius);">
                </div>
                <div class="form-group" style="text-align: left;">
                    <input type="tel" id="pop-mobile" required placeholder="Your Mobile Number" style="width: 100%; padding: 0.75rem; margin-bottom: 1rem; border: 1px solid #cbd5e1; border-radius: var(--border-radius);">
                </div>
                <div class="form-group" style="text-align: left;">
                    <select id="pop-class" required style="width: 100%; padding: 0.75rem; margin-bottom: 1.5rem; border: 1px solid #cbd5e1; border-radius: var(--border-radius);">
                        <option value="">Select Your Class</option>
                        <option value="8th">8th Class</option>
                        <option value="9th">9th Class</option>
                        <option value="10th">10th Class</option>
                        <option value="11th">11th Class</option>
                        <option value="12th">12th Class</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100">Download Notes <i class="fas fa-download" style="margin-left: 5px;"></i></button>
            </form>
        </div>
    </div>

    <!-- Contact Form WhatsApp JS -->
    <script>
        function closePopup() {
            document.getElementById('notes-popup').classList.remove('show');
        }

        async function downloadNotes(event) {
            event.preventDefault();
            const btn = event.target.querySelector('button');
            const originalText = btn.innerHTML;
            btn.innerHTML = 'Downloading...';

            const name = document.getElementById('pop-name').value;
            const mobile = document.getElementById('pop-mobile').value;
            const cls = document.getElementById('pop-class').value;

            const formData = new FormData();
            formData.append('pop-name', name);
            formData.append('pop-mobile', mobile);
            formData.append('pop-class', cls);

            try {
                const response = await fetch('process_download.php', { method: 'POST', body: formData });
                const result = await response.json();
                
                if (result.success) {
                    const a = document.createElement('a');
                    a.href = result.url;
                    a.download = '';
                    a.target = '_blank';
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    closePopup();
                } else {
                    const whatsappNumber = "919729615438";
                    let wmsg = `*Free Handwritten Notes Request*\n\n*Name:* ${name}\n*Mobile:* ${mobile}\n*Class:* ${cls}\n\n_Note: Please send me the file manually as it was not available directly on the site._`;
                    window.open(`https://wa.me/${whatsappNumber}?text=${encodeURIComponent(wmsg)}`, '_blank');
                    closePopup();
                }
            } catch (error) {
                alert("Something went wrong processing your download.");
            }
            btn.innerHTML = originalText;
        }

        // Show popup after 3 seconds of page load
        window.addEventListener('load', function() {
            setTimeout(function() {
                if(!sessionStorage.getItem('popupShown')) {
                    document.getElementById('notes-popup').classList.add('show');
                    sessionStorage.setItem('popupShown', 'true');
                }
            }, 3000);
        });
        function sendToWhatsApp(event) {
            event.preventDefault();
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const phone = document.getElementById('phone').value;
            const course = document.getElementById('course').options[document.getElementById('course').selectedIndex].text;
            const courseVal = document.getElementById('course').value;
            const message = document.getElementById('message').value;

            const whatsappNumber = "919729615438";

            let whatsappMessage = `*New Enquiry from Website*\n\n`;
            whatsappMessage += `*Name:* ${name}\n`;
            whatsappMessage += `*Email:* ${email}\n`;
            whatsappMessage += `*Phone:* ${phone}\n`;
            if (courseVal) whatsappMessage += `*Course:* ${course}\n`;
            if (message) whatsappMessage += `*Message:* ${message}\n`;

            window.open(`https://wa.me/${whatsappNumber}?text=${encodeURIComponent(whatsappMessage)}`, '_blank');
        }
    </script>

    <!-- Custom JS -->
    <script src="assets/js/main.js?v=<?php echo filemtime('assets/js/main.js'); ?>"></script>
</body>

</html>
