<?php

$get = function($param, $default = '') {
	return htmlspecialchars($_GET[$param] ?? $default);
};
$post = function($param, $default = '') {
	return htmlspecialchars($_POST[$param] ?? $default);
};

$prompt = function($message, $type = 'info', $onReturn = 'history.back()') use (&$conn) {
	require_once __DIR__ . '/html_header.php';

	$types = [
		'success' => [ 'color' => 'green', 'icon' => 'check_circle' ],
		'info'    => [ 'color' => 'blue', 'icon' => 'info' ],
		'error'   => [ 'color' => 'red', 'icon' => 'error' ]
	];

	echo '
	<main class="p-24">
		<button onclick="' . $onReturn . '" class="hover:bg-gray-300 p-1 rounded-md text-xl">
			<span class="material-icons-round">arrow_back</span>
			Return to page
		</button>
		<div class="bg-' . $types[$type]['color'] . '-100 mt-12 p-2 rounded-xl text-2xl flex items-center gap-6">
			<span class="material-icons-round text-6xl text-' . $types[$type]['color'] . '-600">' . $types[$type]['icon'] . '</span>
			<div>' . $message . '</div>
		</div>
	</main>';

	if (isset($conn)) mysqli_close($conn);
	require_once __DIR__ . '/html_footer.php';
	exit;
};

$roleFromPath = function() use ($prompt) {
	$path = trim($_SERVER['REQUEST_URI'], '/');
	$role = strtok($path, '/');
	if (!in_array($role, ['admin', 'teacher', 'student'])) {
		return false;
	}

	return $role;
};

$roleFromGET = function($default = '') use ($prompt, $get) {
	$role = $get('role', $default);
	if (empty($role)) $prompt('Invalid query: Missing role parameter', 'error');
	if (!in_array($role, ['admin', 'teacher', 'student'])) {
		$prompt('Invalid role: ' . $role, 'error');
	};

	return $role;
};

$roleFromPOST = function() use ($prompt, $post) {
	$role = $post('role');
	if (empty($role)) $prompt('Invalid query: Missing role parameter', 'error');
	if (!in_array($role, ['admin', 'teacher', 'student'])) {
		$prompt('Invalid role: ' . $role, 'error');
	};

	return $role;
};

$roleFromSession = function() {
	$role = $_SESSION['role'] ?? '';

	return $role;
};

$where = function($filters) {
	$conditions = [];

	foreach ($filters as $column => $value) {
		if ($value == '') continue;
		$conditions[] = "$column = '$value'";
	}

	if (!$conditions) return '';
	return ' where ' . implode(' and ', $conditions);
};

$mailLink = function($type, $options) {
	$to = $options['to'];
	$subject = rawurlencode($options['subject']);
	$body = rawurlencode(trim($options['body']));
	switch ($type) {
		case 'mailto':
			return "mailto:{$to}?subject={$subject}&body={$body}";
		case 'gmail':
			return "https://mail.google.com/mail/u/0/?tf=cm&to={$to}&su={$subject}&body={$body}";
	}
};
