<?php

App::uses('DebugMemo', 'DebugMemo.Model');

$urlString = DebugMemo::urlString($this->request->params);

?>
<div id="debug-memo-container">
	<div id="debug-memo-handle"><?php echo $this->Html->image('/debug_memo/img/debug_memo_icon.png', array('alt' => 'DebugMemo')); ?> [ <?php echo $urlString; ?> ]</div>
<h2><?php echo $urlString; ?></h2>
<div id="debug-memo-form">
<?php echo $this->Form->create('DebugMemo', array(
	'url' => array(
		'plugin' => 'debug_memo',
		'controller' => 'debug_memos',
		'action' => 'update',
	),
	'inputDefaults' => array('label' => false, 'div' => false, 'divControls' => false),
)); ?>
<?php echo $this->Form->input('memo', array('id' => 'debug-memo-textarea', 'type' => 'textarea', 'value' => DebugMemo::readMemo($this->request->params))); ?>
<?php if (!empty($this->data['DebugMemo']['modified'])): ?>
<div id="debug-memo-modified">
Last modified: <?php echo $this->data['DebugMemo']['modified']; ?>
</div>
<?php endif; ?>
<?php echo $this->Form->hidden('plugin', 	 array('value' => $this->request->params['plugin'])); ?>
<?php echo $this->Form->hidden('controller', array('value' => $this->request->params['controller'])); ?>
<?php echo $this->Form->hidden('action', 	 array('value' => $this->request->params['action'])); ?>
<?php echo $this->Form->submit(__('Update Memo'), array('class' => 'debug-memo-btn', 'div' => false)); ?>
[ <?php echo $this->Html->link('View memo list', array('controller' => 'debug_memos', 'action' => 'index', 'plugin' => 'debug_memo'), array('target' => '_blank')); ?> ]
<?php echo $this->Form->end(); ?>
</div>
</div>
