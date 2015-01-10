<h1>Development Blog</h1>
<?php foreach( $this->posts as $this->post ): ?>
	<div>
		<h3><?php echo htmlspecialchars( $this->post['title'] ); ?></h3>
		<p><?php echo htmlspecialchars( $this->post['title'] ); ?></p>
	</div>
<?php endforeach;?>