<?php require_once 'core/dbConfig.php'; ?>
<?php require_once 'core/models.php'; ?>
<?php 
// Redirect to login if user is not logged in
if (!isset($_SESSION['username'])) {
	header("Location: login.php");
	exit();
}

// Log that the user viewed the activity logs page
logAction($pdo, 'READ', 'ActivityLogs', null, $_SESSION['username'], "Viewed Activity Logs");
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Biblioteca — Activity Logs</title>
	<link rel="stylesheet" href="styles.css">
	<style>
		.logs-table {
			width: 100%;
			border-collapse: collapse;
			margin-top: 20px;
			background: var(--cream);
			border-radius: 8px;
			overflow: hidden;
			box-shadow: var(--shadow-soft);
		}
		.logs-table th, .logs-table td {
			padding: 12px 15px;
			text-align: left;
			border-bottom: 1px solid var(--parchment-dark);
		}
		.logs-table th {
			background: linear-gradient(135deg, var(--wood-dark), var(--wood-medium));
			color: var(--parchment);
			font-weight: 600;
			font-family: 'Playfair Display', Georgia, serif;
			letter-spacing: 0.5px;
		}
		.logs-table tr {
			transition: var(--transition);
		}
		.logs-table tr:hover {
			background: var(--parchment);
		}
		.op-CREATE { color: #27ae60; font-weight: bold; }
		.op-READ { color: #2980b9; font-weight: bold; }
		.op-UPDATE { color: #d35400; font-weight: bold; }
		.op-DELETE { color: #c0392b; font-weight: bold; }
	</style>
</head>
<body>

	<!-- Library Header -->
	<div class="library-header">
		<h1>Biblioteca</h1>
		<p>Activity Logs</p>
		<div class="header-nav">
			<span class="user-greeting">Logged in as: <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></span>
			<a href="index.php">← Return to Library</a>
			<a href="core/handleForms.php?logoutAUser=1">Logout</a>
		</div>
	</div>

	<div class="main-container">
		<h2 class="section-title">System Activity Logs</h2>
		
		<!-- ===================== ACTIVITY LOGS TABLE ===================== -->
		<?php $logs = getAllActivityLogs($pdo); ?>

		<?php if (count($logs) > 0) { ?>
		<table class="logs-table">
			<thead>
				<tr>
					<th>Log ID</th>
					<th>Timestamp</th>
					<th>Operation</th>
					<th>Entity</th>
					<th>Entity ID</th>
					<th>User</th>
					<th>Description</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($logs as $log) { ?>
				<tr>
					<td><?php echo $log['log_id']; ?></td>
					<td><?php echo $log['timestamp']; ?></td>
					<td class="op-<?php echo $log['operation']; ?>"><?php echo htmlspecialchars($log['operation']); ?></td>
					<td><?php echo htmlspecialchars($log['entity']); ?></td>
					<td><?php echo $log['entity_id'] ? $log['entity_id'] : 'N/A'; ?></td>
					<td><strong><?php echo htmlspecialchars($log['username']); ?></strong></td>
					<td><?php echo htmlspecialchars($log['description']); ?></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
		<?php } else { ?>
		<div class="empty-state">
			No activity logs found.
		</div>
		<?php } ?>

	</div>

</body>
</html>
