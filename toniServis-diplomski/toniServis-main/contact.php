<?php
print '
	<h1>Kontakt</h1>
	<div id="contact">
	<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d11145.039595861264!2d16.030453639736393!3d45.70582266567099!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47667e36f23379af%3A0x2600ad5153371002!2zR3JhZGnEh2k!5e0!3m2!1shr!2shr!4v1610191701194!5m2!1shr!2shr" width= 900 height= 400 style="border:0" allowfullscreen></iframe>
		<form id="contact_form" name="contact_form" method="POST">
			<label for="fname">Ime *</label>
			<input type="text" id="fname" name="firstname" placeholder="Vaše ime..." required>

			<label for="lname">Prezime *</label>
			<input type="text" id="lname" name="lastname" placeholder="Vaše prezime..." required>
			
			<label for="lname">E-mail adresa *</label>
			<input type="email" id="email" name="email" placeholder="Vaša e-mail adresa..." required>

			<label for="country">Države</label>
			<select id="country" name="country">
				<option value="">Odaberi...</option>
				<option value="Germany">Germany</option>
				<option value="Croatia" selected>Croatia</option>
				<option value="Austria">Austria</option>
				<option value="USA">USA</option>
			</select>

			<label for="subject">Poruka</label>
			<textarea id="subject" name="subject" placeholder="Napišite nešto..." style="height:200px"></textarea>

			<input type="submit" value="Pošalji">
		</form>
	</div>';
?>