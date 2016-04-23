<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="mtstyle.css">

			<title>I244 Võrgurakendused 1 Praktikum 1</title>

		<script>
		function myFunction() {
		    alert("vajuta OK");
		}
		</script>
		<script src="countdown.js"></script>
	</head>


	<body>
		<h1>Võrgurakendused 1 lehekülg</h1>

		<p>Kassi pildi näidis</p>

		<br/>
		<a href=""></a>
		<img src="https://www.petfinder.com/wp-content/uploads/2012/11/99233806-bringing-home-new-cat-632x475-253x190.jpg" alt="" />
		
		<h3>Lehe külastuste arv</h3><br/>

		<?php 
			include("counter.php")
		?>

		<h3>Counter, mis hakkab tiksuma 10 minutist alla </h3><br/>

		<script>
			var myCountdown1 = new Countdown({time:600});
		</script>

		<p>
			<?php include("UserStat.php");?>
		</p>

		<h1>Pealkiri1</h1>
		<h2>Pealkiri2</h2>
		<h3>Pealkiri3</h3>
		<h4>Pealkiri4</h4>
		<h5>Pealkiri5</h5>
		<h6>Pealkiri6</h6>

		<b>jäme text</b>

		<u>allajoonitud text</u>

		<em>kursiivis text</em>

		<ul><li>Level 0</li>
			<li>Level 0</li>
			<li>Level 0
				<ul>
					<li>level 1</li>
					<li>level 1</li>
					<li>level 1
					<ul>
						<li>level 2</li>
						<li>level 2</li>
						<li>level 2
						<ul>
							<li>level 3</li>
							<li>level 3</li>
							<li>level 3</li>
						</ul>
					</li></ul>
				</li></ul>
			</li></ul>
		

		<ol start="42">
		  <li>This</li>
		  <li>Is</li>
		  <li>Life</li>
		</ol>

		<pre> Preformaaditud text (tavaliselt Courier) </pre>

		<button type="button" onclick="myFunction()">Proovi mind!</button>

		<p>
		 <a href="http://validator.w3.org/check?uri=referer">
		  <img src="http://www.w3.org/Icons/valid-xhtml10" alt="Valid XHTML 1.0 Strict" height="31" width="88" />
		 </a>
		</p>

	</body>

</html>