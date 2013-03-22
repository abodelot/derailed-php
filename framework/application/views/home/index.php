<h1>Hello, <?php echo $name ?>!</h1>
<p>I'm a view file!</p>
<?php $this->render_view('home/_partial_view.php'); ?>
<p>
	Back to main view<br />
	Intern link:
	<?php echo Html::a('home/page2', 'Page 2') ?>
	<br />
	Intern link with options array:
	<?php echo Html::a('home/page2', 'Page 2', array('class' => 'link', 'title' => 'Hey!')) ?>
	<br />
	Intern link with options string:
	<?php echo Html::a('home/page2', 'Page 2', 'id="foobar" style="color:red;"') ?>
	<br />
	Extern link:
	<?php echo Html::a('http://google.com', 'Google', 'title="is not evil"') ?>
	<br />
	Image:
	<?php echo Html::img('http://static.php.net/www.php.net/images/php.gif') ?>
	<br />
	Image in a link:
	<?= Html::a('http://php.net', Html::img('http://static.php.net/www.php.net/images/php.gif', 'alt="php"')) ?>
</p>


