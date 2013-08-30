<?php
	
	include_once("templates/header.php");
	
?>
<div id="major_wrapper">
	<div id="edit">
		<section>
			<h1>Import Tool</h1>
		</section>
		<aside id="arrow_border"></aside>
		<aside id="arrow"></aside>
	</div>
	<section id="content_wrapper">
		<form enctype="multipart/form-data" method="POST" action="upload.php">
			<section>
				<h4>Select File</h4>
				<input name="file" type="file">
			</section>
			<section>
<!--				<h4>Category</h4>
				<select name="category" required>
					<option value =\" \">-- Select category --</option>
					<option value ="policies">Policies</option>
					<option value ="rates">Rates</option>
					<option value ="agents">Agents</option>
				</select>
</section> -->
			<section>
				<h4>Carrier</h4>
				<select name="carrier" required>
					<option value =\" \">-- Select carrier --</option>
					<option value ="1">Allianz / Allianz Preferred</option>
					<option value ="3">American Equity</option>
					<option value ="6">Athene</option>
					<option value ="10">Forethought</option>
					<option value ="13">Great American</option>
					<option value ="16">ING</option>
				</select>
			</section>
			<input type='submit' name='edit' class='edit_button' value='&#59226;'>
		</form>
	</section>
</div>
</body>
</html>